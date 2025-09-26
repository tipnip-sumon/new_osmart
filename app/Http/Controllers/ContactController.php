<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\GeneralSetting;
use Exception;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $settings = GeneralSetting::first();
        
        if (!$settings) {
            // Create default settings if none exist
            $settings = new GeneralSetting();
            $settings->contact_email = 'info@example.com';
            $settings->contact_phone = '+1 (555) 123-4567';
            $settings->contact_address = 'Address not available';
            $settings->company_name = config('app.name', 'Company Name');
        }
        
        $contactInfo = [
            'email' => $settings->contact_email ?? $settings->company_email ?? 'info@example.com',
            'phone' => $settings->contact_phone ?? $settings->company_phone ?? '+1 (555) 123-4567',
            'address' => $settings->contact_address ?? $settings->company_address ?? 'Address not available',
            'company_name' => $settings->company_name ?? config('app.name', 'Company Name')
        ];
        
        $businessHours = GeneralSetting::getFormattedBusinessHours();
        
        return view('contact', compact('contactInfo', 'businessHours'));
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:general,business,support,partnership,other',
            'message' => 'required|string|max:2000',
            'subscribe_newsletter' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form inputs and try again.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $settings = GeneralSetting::first();
            $adminEmail = $settings->contact_email ?? $settings->admin_email ?? 'info@osmart.com.bd';
            
            // Convert newsletter subscription to boolean
            $subscribeNewsletter = $request->has('subscribe_newsletter') && $request->subscribe_newsletter;
            
            // Send email to admin
            $emailData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?? 'Not provided',
                'subject' => $request->subject,
                'message' => $request->message,
                'subscribe_newsletter' => $subscribeNewsletter,
                'subscribe_newsletter_text' => $subscribeNewsletter ? 'Yes' : 'No',
                'submitted_at' => now()->format('M d, Y h:i A'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ];

            // Send notification email to admin
            Mail::send('emails.contact-form', $emailData, function ($message) use ($request, $adminEmail) {
                $message->to($adminEmail)
                        ->replyTo($request->email, $request->name)
                        ->subject('New Contact Form Submission - ' . ucfirst($request->subject) . ' Inquiry');
            });

            // Send auto-reply to user
            Mail::send('emails.contact-auto-reply', $emailData, function ($message) use ($request) {
                $message->to($request->email, $request->name)
                        ->subject('Thank you for contacting OSmart - We\'ll get back to you soon!');
            });

            // Handle newsletter subscription if requested
            if ($subscribeNewsletter) {
                // Add newsletter subscription logic here if you have a newsletter system
                // Newsletter::subscribe($request->email, $request->name);
                Log::info('Newsletter subscription requested', [
                    'email' => $request->email,
                    'name' => $request->name,
                    'timestamp' => now()
                ]);
            }

            $successMessage = 'Thank you for your message, ' . $request->name . '! We have received your inquiry and will get back to you within 24 hours.';
            if ($subscribeNewsletter) {
                $successMessage .= ' You\'ve also been subscribed to our newsletter for updates and offers.';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'newsletter_subscribed' => $subscribeNewsletter,
                    'data' => [
                        'submitted_at' => now()->format('M d, Y h:i A'),
                        'reference_id' => 'CNT-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)
                    ]
                ]);
            }

            return back()->with('success', $successMessage);
            
        } catch (Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage(), [
                'email' => $request->email,
                'name' => $request->name,
                'subject' => $request->subject
            ]);

            $errorMessage = 'Sorry, there was an error sending your message. Please try again or contact us directly.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => 'EMAIL_SEND_FAILED'
                ], 500);
            }

            return back()->with('error', $errorMessage)->withInput();
        }
    }
}
