<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $settings = GeneralSetting::first();
            $adminEmail = $settings->contact_email ?? $settings->admin_email ?? 'admin@example.com';
            
            // Send email to admin
            $emailData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'submitted_at' => now()->format('M d, Y h:i A'),
            ];

            Mail::send('emails.contact-form', $emailData, function ($message) use ($request, $adminEmail) {
                $message->to($adminEmail)
                        ->replyTo($request->email, $request->name)
                        ->subject('New Contact Form Submission - ' . ucfirst($request->subject) . ' Inquiry');
            });

            return back()->with('success', 'Thank you for your message! We will get back to you soon.');
            
        } catch (Exception $e) {
            return back()->with('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }
}
