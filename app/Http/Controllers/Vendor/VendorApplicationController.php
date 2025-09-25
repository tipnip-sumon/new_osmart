<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorApplication;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VendorApplicationController extends Controller
{
    /**
     * Submit vendor application.
     */
    public function submitApplication(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('register')->with('info', 'Please register as a customer first, then apply to become a vendor.');
        }
        
        // Check if user already has a pending or approved application
        $existingApplication = VendorApplication::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existingApplication) {
            $message = $existingApplication->status === 'pending' 
                ? 'You already have a pending vendor application. We will contact you within 24-48 hours.'
                : 'You already have an approved vendor application.';
            return back()->with('info', $message);
        }
        
        // Validate vendor application data
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'business_description' => 'required|string|max:1000',
            'website' => 'nullable|url|max:255',
        ]);
        
        // Create vendor application
        $application = VendorApplication::create([
            'user_id' => Auth::id(),
            'business_name' => $request->business_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'business_description' => $request->business_description,
            'website' => $request->website,
            'status' => 'pending',
        ]);

        // Send confirmation email to vendor
        $this->sendVendorApplicationConfirmationEmail($application);

        // Send notification email to admin
        $this->sendAdminVendorApplicationNotification($application);

        // Create admin notification
        AdminNotification::create([
            'type' => 'vendor_application',
            'title' => 'New Vendor Application',
            'message' => "New vendor application received from {$application->business_name} ({$application->contact_person}).",
            'data' => json_encode([
                'application_id' => $application->id,
                'business_name' => $application->business_name,
                'contact_person' => $application->contact_person,
                'email' => $application->email,
                'user_id' => $application->user_id,
            ]),
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Thank you for your vendor application! Our team will review your submission and contact you within 24-48 hours at ' . $request->email . '. We appreciate your interest in becoming a vendor partner.');
    }

    /**
     * Approve vendor application.
     */
    public function approveApplication(Request $request, $id)
    {
        $application = VendorApplication::findOrFail($id);
        
        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Update user role to vendor
        $application->user->update(['role' => 'vendor']);

        // Send approval email to vendor
        $this->sendVendorApplicationApprovalEmail($application);

        Log::info("Vendor application approved: {$application->id} by admin: " . Auth::user()->email);

        return back()->with('success', 'Vendor application approved successfully! Approval email sent to the vendor.');
    }

    /**
     * Reject vendor application.
     */
    public function rejectApplication(Request $request, $id)
    {
        $application = VendorApplication::findOrFail($id);
        
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Send rejection email to vendor
        $this->sendVendorApplicationRejectionEmail($application);

        Log::info("Vendor application rejected: {$application->id} by admin: " . Auth::user()->email);

        return back()->with('success', 'Vendor application rejected. Rejection email sent to the vendor.');
    }

    /**
     * Send confirmation email to vendor after application submission.
     */
    private function sendVendorApplicationConfirmationEmail($application)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            
            $emailData = [
                'application' => $application,
                'user' => $application->user,
                'site_name' => $siteName,
                'vendor_login_url' => route('vendor.login'),
                'admin_email' => $settings->email_from ?? 'admin@osmartbd.com',
            ];

            Mail::send('emails.vendor-application-confirmation', $emailData, function ($message) use ($application, $siteName) {
                $message->to($application->email, $application->contact_person)
                        ->subject("Vendor Application Received - {$siteName}");
            });

            Log::info("Vendor application confirmation email sent to: {$application->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send vendor application confirmation email to {$application->email}: " . $e->getMessage());
        }
    }

    /**
     * Send notification email to admin about new vendor application.
     */
    private function sendAdminVendorApplicationNotification($application)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            $adminEmail = $settings->email_from ?? 'admin@osmartbd.com';
            
            $emailData = [
                'application' => $application,
                'user' => $application->user,
                'site_name' => $siteName,
                'admin_dashboard_url' => route('admin.dashboard'),
                'application_details_url' => route('admin.vendors.applications.show', $application->id),
            ];

            Mail::send('emails.admin-vendor-application-notification', $emailData, function ($message) use ($application, $siteName, $adminEmail) {
                $message->to($adminEmail)
                        ->subject("New Vendor Application - {$application->business_name} at {$siteName}");
            });

            Log::info("Admin vendor application notification sent for: {$application->business_name}");
        } catch (\Exception $e) {
            Log::error("Failed to send admin vendor application notification for {$application->business_name}: " . $e->getMessage());
        }
    }

    /**
     * Send approval email to vendor.
     */
    private function sendVendorApplicationApprovalEmail($application)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            
            $emailData = [
                'application' => $application,
                'user' => $application->user,
                'reviewer' => $application->reviewer,
                'site_name' => $siteName,
                'vendor_login_url' => route('vendor.login'),
                'vendor_dashboard_url' => route('vendor.dashboard'),
                'admin_email' => $settings->email_from ?? 'admin@osmartbd.com',
            ];

            Mail::send('emails.vendor-application-approval', $emailData, function ($message) use ($application, $siteName) {
                $message->to($application->email, $application->contact_person)
                        ->subject("🎉 Vendor Application Approved - Welcome to {$siteName}!");
            });

            Log::info("Vendor application approval email sent to: {$application->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send vendor application approval email to {$application->email}: " . $e->getMessage());
        }
    }

    /**
     * Send rejection email to vendor.
     */
    private function sendVendorApplicationRejectionEmail($application)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            
            $emailData = [
                'application' => $application,
                'user' => $application->user,
                'reviewer' => $application->reviewer,
                'site_name' => $siteName,
                'vendor_register_url' => route('vendor.register'),
                'admin_email' => $settings->email_from ?? 'admin@osmartbd.com',
            ];

            Mail::send('emails.vendor-application-rejection', $emailData, function ($message) use ($application, $siteName) {
                $message->to($application->email, $application->contact_person)
                        ->subject("Vendor Application Update - {$siteName}");
            });

            Log::info("Vendor application rejection email sent to: {$application->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send vendor application rejection email to {$application->email}: " . $e->getMessage());
        }
    }

    /**
     * Test mail configuration for vendor applications.
     */
    public function testMailConfiguration(Request $request)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $testEmail = $request->input('email', 'test@example.com');
            
            Mail::raw('This is a test email from the osmartbd Vendor Application System. The MailConfigServiceProvider is working correctly!', function ($message) use ($testEmail, $settings) {
                $message->to($testEmail)
                        ->subject('Vendor Application Mail Test - ' . ($settings->site_name ?? 'osmartbd'));
            });

            Log::info("Vendor application test email sent successfully to: {$testEmail}");
            
            return response()->json([
                'success' => true,
                'message' => "Test email sent successfully to {$testEmail}",
                'mail_config' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Vendor application mail test failed: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Mail test failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
