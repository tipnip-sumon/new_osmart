<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Exception;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts
     */
    public function index(Request $request)
    {
        $query = Contact::query();
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('reference_id', 'like', "%{$search}%");
            });
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $contacts = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics for dashboard
        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::where('status', 'new')->count(),
            'replied' => Contact::where('status', 'replied')->count(),
            'newsletter' => Contact::where('subscribe_newsletter', true)->count(),
        ];
        
        return view('admin.contacts.index', compact('contacts', 'stats'));
    }
    
    /**
     * Display the specified contact
     */
    public function show(Contact $contact)
    {
        // Mark as read when viewed
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }
        
        // Get customer statistics
        $customerStats = [
            'total_contacts' => Contact::where('email', $contact->email)->count(),
            'replied_contacts' => Contact::where('email', $contact->email)
                                       ->where('status', 'replied')
                                       ->count(),
            'first_contact_date' => Contact::where('email', $contact->email)
                                          ->oldest()
                                          ->first()
                                          ?->created_at
                                          ?->format('M d, Y') ?? 'N/A'
        ];
        
        return view('admin.contacts.show', compact('contact', 'customerStats'));
    }
    
    /**
     * Show reply form
     */
    public function reply(Contact $contact)
    {
        return view('admin.contacts.reply', compact('contact'));
    }
    
    /**
     * Send reply to contact
     */
    public function sendReply(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'reply_subject' => 'required|string|max:255',
            'reply_message' => 'required|string|max:5000',
            'admin_notes' => 'nullable|string|max:1000',
            'mark_as_closed' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            // Prepare email data
            $emailData = [
                'customer_name' => $contact->name,
                'customer_email' => $contact->email,
                'original_subject' => $contact->subject_display,
                'original_message' => $contact->message,
                'original_date' => $contact->formatted_created_at,
                'reference_id' => $contact->reference_id,
                'reply_subject' => $request->reply_subject,
                'reply_message' => $request->reply_message,
                'admin_name' => Auth::user()->name ?? 'OSmart Team',
                'company_name' => config('app.name', 'OSmart')
            ];
            
            // Send reply email
            Mail::send('emails.admin-reply', $emailData, function ($message) use ($request, $contact) {
                $message->to($contact->email, $contact->name)
                        ->subject($request->reply_subject)
                        ->replyTo(config('mail.from.address'), config('mail.from.name'));
            });
            
            // Update contact status and notes
            $status = $request->has('mark_as_closed') && $request->mark_as_closed ? 'closed' : 'replied';
            
            $contact->update([
                'status' => $status,
                'replied_at' => now(),
                'admin_notes' => $request->admin_notes
            ]);
            
            // Log the reply
            Log::info('Admin replied to contact', [
                'contact_id' => $contact->id,
                'reference_id' => $contact->reference_id,
                'admin_user' => Auth::user()->email ?? 'unknown',
                'reply_subject' => $request->reply_subject,
                'status_updated_to' => $status
            ]);
            
            return redirect()
                ->route('admin.contacts.show', $contact)
                ->with('success', 'Reply sent successfully! Contact status updated to ' . ucfirst($status) . '.');
            
        } catch (Exception $e) {
            Log::error('Failed to send admin reply', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
                'admin_user' => Auth::user()->email ?? 'unknown'
            ]);
            
            return back()
                ->with('error', 'Failed to send reply. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Update contact status
     */
    public function updateStatus(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,read,replied,closed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status update request'
            ], 422);
        }
        
        try {
            $contact->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Contact status updated successfully',
                'new_status' => $request->status
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
    
    /**
     * Bulk actions for contacts
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:mark_read,mark_replied,mark_closed,delete',
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id'
        ]);
        
        if ($validator->fails()) {
            return back()->with('error', 'Invalid bulk action request');
        }
        
        try {
            $contacts = Contact::whereIn('id', $request->contact_ids);
            
            switch ($request->action) {
                case 'mark_read':
                    $contacts->update(['status' => 'read']);
                    $message = 'Selected contacts marked as read';
                    break;
                case 'mark_replied':
                    $contacts->update(['status' => 'replied', 'replied_at' => now()]);
                    $message = 'Selected contacts marked as replied';
                    break;
                case 'mark_closed':
                    $contacts->update(['status' => 'closed']);
                    $message = 'Selected contacts marked as closed';
                    break;
                case 'delete':
                    $contacts->delete();
                    $message = 'Selected contacts deleted';
                    break;
            }
            
            return back()->with('success', $message);
            
        } catch (Exception $e) {
            Log::error('Bulk action failed', [
                'action' => $request->action,
                'contact_ids' => $request->contact_ids,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Bulk action failed. Please try again.');
        }
    }
    
    /**
     * Export contacts to CSV
     */
    public function export(Request $request)
    {
        $query = Contact::query();
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        $contacts = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'contacts_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Reference ID',
                'Name',
                'Email',
                'Phone',
                'Subject',
                'Message',
                'Newsletter Subscription',
                'Status',
                'IP Address',
                'Submitted At',
                'Replied At'
            ]);
            
            // CSV data
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->reference_id,
                    $contact->name,
                    $contact->email,
                    $contact->phone ?? 'N/A',
                    $contact->subject_display,
                    $contact->message,
                    $contact->subscribe_newsletter ? 'Yes' : 'No',
                    ucfirst($contact->status),
                    $contact->ip_address ?? 'N/A',
                    $contact->formatted_created_at,
                    $contact->replied_at ? $contact->replied_at->format('M d, Y h:i A') : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
