<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\VendorApplication;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;

class SettingsController extends Controller
{
    /**
     * Instantly upgrade customer to affiliate role
     */
    public function becomeAffiliate(Request $request)
    {
        try {
            $authUser = Auth::user();
            $user = User::find($authUser->id);
            
            // Check if user is already an affiliate or higher role
            if ($user->role !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already a ' . ucfirst($user->role) . '. Role upgrade not available.'
                ]);
            }
            
            DB::beginTransaction();
            
            // Update user role to affiliate
            $user->update([
                'role' => 'affiliate',
                'updated_at' => now()
            ]);
            
            // Create admin notification
            AdminNotification::create([
                'type' => 'role_upgrade',
                'title' => 'New Affiliate Registration',
                'message' => "Customer {$user->name} ({$user->username}) has upgraded to Affiliate role.",
                'data' => json_encode([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'previous_role' => 'customer',
                    'new_role' => 'affiliate',
                    'upgrade_type' => 'instant',
                ]),
                'is_read' => false,
            ]);
            
            DB::commit();
            
            // Log the role upgrade
            Log::info("User {$user->id} ({$user->username}) upgraded from customer to affiliate");
            
            // Send notification emails
            try {
                $this->sendAffiliateWelcomeEmail($user);
                $this->sendAdminAffiliateNotificationEmail($user);
            } catch (\Exception $emailException) {
                Log::warning('Email sending failed during affiliate upgrade for user ' . $user->id . ': ' . $emailException->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Congratulations! You are now an affiliate. You can start earning commissions by referring customers.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Affiliate role upgrade failed for user ' . Auth::id() . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Role upgrade failed. Please try again later.'
            ]);
        }
    }
    
    /**
     * Submit vendor application for admin approval
     */
    public function submitVendorApplication(Request $request)
    {
        try {
            $authUser = Auth::user();
            $user = User::find($authUser->id);
            
            // Check if user is a customer
            if ($user->role !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor application is only available for customers.'
                ]);
            }
            
            // Check if user already has a pending or approved application
            $existingApplication = VendorApplication::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();
                
            if ($existingApplication) {
                $status = ucfirst($existingApplication->status);
                return response()->json([
                    'success' => false,
                    'message' => "You already have a {$status} vendor application. Please wait for admin review."
                ]);
            }
            
            // Validate the request
            $request->validate([
                'business_name' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'business_description' => 'required|string|max:1000',
                'website' => 'nullable|url|max:255',
            ]);
            
            DB::beginTransaction();
            
            // Create vendor application
            $application = VendorApplication::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'business_description' => $request->business_description,
                'website' => $request->website,
                'status' => 'pending',
            ]);
            
            // Create admin notification
            AdminNotification::create([
                'type' => 'vendor_application',
                'title' => 'New Vendor Application',
                'message' => "Customer {$user->name} ({$user->username}) has submitted a vendor application for '{$request->business_name}'.",
                'data' => json_encode([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'application_id' => $application->id,
                    'business_name' => $request->business_name,
                    'contact_person' => $request->contact_person,
                ]),
                'is_read' => false,
            ]);
            
            DB::commit();
            
            // Log the application
            Log::info("User {$user->id} ({$user->username}) submitted vendor application for business: {$request->business_name}");
            
            // Send notification emails
            try {
                $this->sendVendorApplicationConfirmationEmail($user, $application);
                $this->sendAdminVendorApplicationNotificationEmail($user, $application);
            } catch (\Exception $emailException) {
                Log::warning('Email sending failed during vendor application for user ' . $user->id . ': ' . $emailException->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Your vendor application has been submitted successfully! Our admin team will review it and contact you within 2-3 business days.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Vendor application submission failed for user ' . Auth::id() . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Application submission failed. Please check your information and try again.'
            ]);
        }
    }
    
    /**
     * Send welcome email to new affiliate
     */
    private function sendAffiliateWelcomeEmail($user)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            
            $emailData = [
                'user' => $user,
                'site_name' => $siteName,
                'affiliate_dashboard_url' => route('user.dashboard'),
                'referral_link' => route('register') . '?ref=' . $user->username,
            ];

            Mail::send('emails.affiliate-welcome', $emailData, function ($message) use ($user, $siteName) {
                $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject("Welcome to {$siteName} Affiliate Program!");
            });

            Log::info("Affiliate welcome email sent to: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send affiliate welcome email to {$user->email}: " . $e->getMessage());
        }
    }
    
    /**
     * Send admin notification email about new affiliate
     */
    private function sendAdminAffiliateNotificationEmail($user)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            $adminEmail = $settings->email_from ?? 'admin@osmartbd.com';
            
            $emailData = [
                'user' => $user,
                'site_name' => $siteName,
                'admin_dashboard_url' => route('admin.dashboard'),
                'user_details_url' => route('admin.users.show', $user->id),
            ];

            Mail::send('emails.admin-affiliate-notification', $emailData, function ($message) use ($user, $siteName, $adminEmail) {
                $message->to($adminEmail)
                        ->subject("New Affiliate Registration - {$user->username} at {$siteName}");
            });

            Log::info("Admin affiliate notification email sent for user: {$user->username}");
        } catch (\Exception $e) {
            Log::error("Failed to send admin affiliate notification email for user {$user->username}: " . $e->getMessage());
        }
    }
    
    /**
     * Send vendor application confirmation email to user
     */
    private function sendVendorApplicationConfirmationEmail($user, $application)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            $adminEmail = $settings->email_from ?? 'admin@osmartbd.com';
            
            $emailData = [
                'user' => $user,
                'application' => $application,
                'site_name' => $siteName,
                'dashboard_url' => route('user.dashboard'),
                'vendor_login_url' => route('login'),
                'admin_email' => $adminEmail,
            ];

            Mail::send('emails.vendor-application-confirmation', $emailData, function ($message) use ($user, $siteName, $application) {
                $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject("Vendor Application Received - {$application->business_name} at {$siteName}");
            });

            Log::info("Vendor application confirmation email sent to: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send vendor application confirmation email to {$user->email}: " . $e->getMessage());
        }
    }
    
    /**
     * Send admin notification email about vendor application
     */
    private function sendAdminVendorApplicationNotificationEmail($user, $application)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            $adminEmail = $settings->email_from ?? 'admin@osmartbd.com';
            
            $emailData = [
                'user' => $user,
                'application' => $application,
                'site_name' => $siteName,
                'admin_dashboard_url' => route('admin.dashboard'),
                'application_review_url' => '#', // Add actual route when admin interface is created
            ];

            Mail::send('emails.admin-vendor-application-notification', $emailData, function ($message) use ($user, $siteName, $adminEmail, $application) {
                $message->to($adminEmail)
                        ->subject("New Vendor Application - {$application->business_name} by {$user->username} at {$siteName}");
            });

            Log::info("Admin vendor application notification email sent for application: {$application->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send admin vendor application notification email for application {$application->id}: " . $e->getMessage());
        }
    }
}