<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SupportController extends Controller
{
    /**
     * Display a listing of support tickets.
     */
    public function index(Request $request)
    {
        try {
            $tickets = $this->getTicketsQuery();
            
            // Apply filters
            if ($request->filled('status')) {
                $tickets = $tickets->where('status', $request->status);
            }
            
            if ($request->filled('priority')) {
                $tickets = $tickets->where('priority', $request->priority);
            }
            
            if ($request->filled('category')) {
                $tickets = $tickets->where('category', $request->category);
            }
            
            if ($request->filled('assigned_to')) {
                $tickets = $tickets->where('assigned_to', $request->assigned_to);
            }
            
            if ($request->filled('date_from')) {
                $tickets = $tickets->filter(function($ticket) use ($request) {
                    return Carbon::parse($ticket['created_at'])->gte($request->date_from);
                });
            }
            
            if ($request->filled('date_to')) {
                $tickets = $tickets->filter(function($ticket) use ($request) {
                    return Carbon::parse($ticket['created_at'])->lte($request->date_to);
                });
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $tickets = $tickets->filter(function($ticket) use ($search) {
                    return stripos($ticket['subject'], $search) !== false ||
                           stripos($ticket['ticket_number'], $search) !== false ||
                           stripos($ticket['customer_name'], $search) !== false ||
                           stripos($ticket['customer_email'], $search) !== false;
                });
            }
            
            // Get support statistics
            $stats = $this->getSupportStatistics();
            
            // Get filter options
            $statuses = $this->getTicketStatuses();
            $priorities = $this->getTicketPriorities();
            $categories = $this->getTicketCategories();
            $agents = $this->getSupportAgents();
            
            return view('admin.support.index', compact('tickets', 'stats', 'statuses', 'priorities', 'categories', 'agents'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching support tickets: ' . $e->getMessage());
            return back()->with('error', 'Failed to load support tickets.');
        }
    }

    /**
     * Get tickets data for AJAX requests (DataTables)
     */
    public function tickets(Request $request)
    {
        try {
            $tickets = $this->getTicketsQuery();
            
            // Apply filters from DataTables
            if ($request->filled('search.value')) {
                $search = $request->input('search.value');
                $tickets = $tickets->filter(function($ticket) use ($search) {
                    return stripos($ticket['subject'], $search) !== false ||
                           stripos($ticket['ticket_number'], $search) !== false ||
                           stripos($ticket['customer_name'], $search) !== false ||
                           stripos($ticket['customer_email'], $search) !== false;
                });
            }
            
            // Handle ordering
            if ($request->filled('order')) {
                $orderColumn = $request->input('order.0.column');
                $orderDir = $request->input('order.0.dir');
                
                $columns = ['id', 'ticket_number', 'subject', 'customer_name', 'status', 'created_at'];
                if (isset($columns[$orderColumn])) {
                    $tickets = $tickets->sortBy($columns[$orderColumn]);
                    if ($orderDir === 'desc') {
                        $tickets = $tickets->sortByDesc($columns[$orderColumn]);
                    }
                }
            }
            
            // Handle pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            
            $totalRecords = $tickets->count();
            $tickets = $tickets->slice($start, $length)->values();
            
            // Format the data for DataTables
            $formattedTickets = $tickets->map(function($ticket) {
                return [
                    'id' => $ticket['id'],
                    'ticket_number' => $ticket['ticket_number'],
                    'subject' => $ticket['subject'],
                    'user' => $ticket['customer_name'],
                    'status' => $ticket['status'],
                    'status_badge' => '<span class="badge bg-' . $this->getStatusColor($ticket['status']) . '">' . ucfirst($ticket['status']) . '</span>',
                    'priority' => $ticket['priority'],
                    'created_at' => $ticket['created_at'],
                ];
            });
            
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $formattedTickets
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching tickets data: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load tickets data'
            ]);
        }
    }

    /**
     * Show the form for creating a new support ticket.
     */
    public function create()
    {
        try {
            $categories = $this->getTicketCategories();
            $priorities = $this->getTicketPriorities();
            $customers = $this->getCustomers();
            $agents = $this->getSupportAgents();
            
            return view('admin.support.create', compact('categories', 'priorities', 'customers', 'agents'));
            
        } catch (\Exception $e) {
            Log::error('Error loading support ticket create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load support ticket form.');
        }
    }

    /**
     * Store a newly created support ticket in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|integer',
            'customer_name' => 'required_if:customer_id,null|string|max:255',
            'customer_email' => 'required_if:customer_id,null|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:general,technical,billing,product,shipping,returns,account,vendor',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120',
            'assigned_to' => 'nullable|integer',
            'tags' => 'nullable|string|max:500',
            'internal_notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Generate unique ticket number
            $ticketNumber = $this->generateTicketNumber();
            
            // Handle file attachments
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachments[] = $this->uploadAttachment($file, 'support/attachments');
                }
            }
            
            $ticketData = [
                'ticket_number' => $ticketNumber,
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'subject' => $request->subject,
                'category' => $request->category,
                'priority' => $request->priority,
                'status' => 'open',
                'description' => $request->description,
                'attachments' => !empty($attachments) ? json_encode($attachments) : null,
                'assigned_to' => $request->assigned_to,
                'created_by' => Auth::id(),
                'tags' => $request->tags ? explode(',', $request->tags) : null,
                'internal_notes' => $request->internal_notes,
                'last_reply_at' => now(),
                'last_reply_by' => Auth::id(),
                'response_time' => null,
                'resolution_time' => null
            ];
            
            $ticket = $this->createTicket($ticketData);
            
            // Send notification email to customer
            $this->sendTicketCreatedEmail($ticket);
            
            // Notify assigned agent if any
            if ($request->assigned_to) {
                $this->notifyAssignedAgent($ticket);
            }
            
            DB::commit();
            
            Log::info('Support ticket created successfully', ['ticket_id' => $ticket['id'], 'ticket_number' => $ticketNumber]);
            
            return redirect()->route('admin.support.show', $ticket['id'])
                           ->with('success', 'Support ticket created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating support ticket: ' . $e->getMessage());
            return back()->with('error', 'Failed to create support ticket.')->withInput();
        }
    }

    /**
     * Display the specified support ticket.
     */
    public function show($id)
    {
        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return back()->with('error', 'Support ticket not found.');
            }
            
            // Get ticket replies/messages
            $replies = $this->getTicketReplies($id);
            
            // Get ticket history
            $history = $this->getTicketHistory($id);
            
            // Get related tickets
            $relatedTickets = $this->getRelatedTickets($ticket);
            
            // Get available agents for assignment
            $agents = $this->getSupportAgents();
            
            // Mark ticket as viewed
            $this->markTicketAsViewed($id);
            
            return view('admin.support.show', compact('ticket', 'replies', 'history', 'relatedTickets', 'agents'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching support ticket details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load support ticket details.');
        }
    }

    /**
     * Show the form for editing the specified support ticket.
     */
    public function edit($id)
    {
        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return back()->with('error', 'Support ticket not found.');
            }
            
            $categories = $this->getTicketCategories();
            $priorities = $this->getTicketPriorities();
            $statuses = $this->getTicketStatuses();
            $agents = $this->getSupportAgents();
            
            return view('admin.support.edit', compact('ticket', 'categories', 'priorities', 'statuses', 'agents'));
            
        } catch (\Exception $e) {
            Log::error('Error loading support ticket edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load support ticket form.');
        }
    }

    /**
     * Update the specified support ticket in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'category' => 'required|in:general,technical,billing,product,shipping,returns,account,vendor',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,pending,resolved,closed',
            'assigned_to' => 'nullable|integer',
            'tags' => 'nullable|string|max:500',
            'internal_notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return back()->with('error', 'Support ticket not found.');
            }
            
            DB::beginTransaction();
            
            $oldData = $ticket;
            $newData = [
                'subject' => $request->subject,
                'category' => $request->category,
                'priority' => $request->priority,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
                'tags' => $request->tags ? explode(',', $request->tags) : null,
                'internal_notes' => $request->internal_notes,
                'updated_by' => Auth::id()
            ];
            
            // Set resolution time if status changed to resolved/closed
            if (in_array($request->status, ['resolved', 'closed']) && !in_array($oldData['status'], ['resolved', 'closed'])) {
                $newData['resolution_time'] = now();
            }
            
            $this->updateTicket($id, $newData);
            
            // Log changes in ticket history
            $this->logTicketChanges($id, $oldData, $newData);
            
            // Send notifications for status/assignment changes
            if ($oldData['status'] !== $request->status) {
                $this->sendStatusChangeEmail($ticket, $oldData['status'], $request->status);
            }
            
            if ($oldData['assigned_to'] !== $request->assigned_to) {
                $this->notifyAssignmentChange($ticket, $oldData['assigned_to'], $request->assigned_to);
            }
            
            DB::commit();
            
            Log::info('Support ticket updated successfully', ['ticket_id' => $id]);
            
            return redirect()->route('admin.support.show', $id)
                           ->with('success', 'Support ticket updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating support ticket: ' . $e->getMessage());
            return back()->with('error', 'Failed to update support ticket.')->withInput();
        }
    }

    /**
     * Remove the specified support ticket from storage.
     */
    public function destroy($id)
    {
        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return back()->with('error', 'Support ticket not found.');
            }
            
            // Check if ticket can be deleted (only closed tickets)
            if (!in_array($ticket['status'], ['closed', 'resolved'])) {
                return back()->with('error', 'Only closed or resolved tickets can be deleted.');
            }
            
            DB::beginTransaction();
            
            // Delete attachments
            if ($ticket['attachments']) {
                $attachments = json_decode($ticket['attachments'], true);
                foreach ($attachments as $attachment) {
                    $this->deleteAttachment($attachment['path']);
                }
            }
            
            // Delete ticket replies and history
            $this->deleteTicketReplies($id);
            $this->deleteTicketHistory($id);
            
            // Delete the ticket
            $this->deleteTicket($id);
            
            DB::commit();
            
            Log::info('Support ticket deleted successfully', ['ticket_id' => $id]);
            
            return redirect()->route('admin.support.index')
                           ->with('success', 'Support ticket deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting support ticket: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete support ticket.');
        }
    }

    /**
     * Reply to a support ticket.
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120',
            'is_internal' => 'boolean',
            'close_ticket' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return back()->with('error', 'Support ticket not found.');
            }
            
            DB::beginTransaction();
            
            // Handle file attachments
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachments[] = $this->uploadAttachment($file, 'support/replies');
                }
            }
            
            $replyData = [
                'ticket_id' => $id,
                'message' => $request->message,
                'attachments' => !empty($attachments) ? json_encode($attachments) : null,
                'is_internal' => $request->boolean('is_internal', false),
                'created_by' => Auth::id(),
                'created_at' => now()
            ];
            
            $reply = $this->createTicketReply($replyData);
            
            // Update ticket last reply info
            $ticketUpdates = [
                'last_reply_at' => now(),
                'last_reply_by' => Auth::id(),
                'status' => $request->boolean('close_ticket') ? 'closed' : 'pending'
            ];
            
            // Set response time if this is the first admin reply
            if (!$ticket['response_time']) {
                $ticketUpdates['response_time'] = now();
            }
            
            $this->updateTicket($id, $ticketUpdates);
            
            // Send email notification to customer (if not internal)
            if (!$request->boolean('is_internal')) {
                $this->sendReplyEmail($ticket, $reply);
            }
            
            DB::commit();
            
            Log::info('Support ticket reply created', ['ticket_id' => $id, 'reply_id' => $reply['id']]);
            
            return back()->with('success', 'Reply sent successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating ticket reply: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reply.');
        }
    }

    /**
     * Assign ticket to agent.
     */
    public function assign(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid agent selected.'], 400);
        }

        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return response()->json(['error' => 'Support ticket not found.'], 404);
            }
            
            $oldAgentId = $ticket['assigned_to'];
            $newAgentId = $request->agent_id;
            
            $this->updateTicket($id, ['assigned_to' => $newAgentId, 'updated_by' => Auth::id()]);
            
            // Log assignment change
            $this->logTicketAssignment($id, $oldAgentId, $newAgentId);
            
            // Notify agents
            $this->notifyAssignmentChange($ticket, $oldAgentId, $newAgentId);
            
            Log::info('Support ticket assigned', ['ticket_id' => $id, 'agent_id' => $newAgentId]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ticket assigned successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error assigning ticket: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to assign ticket.'], 500);
        }
    }

    /**
     * Change ticket status.
     */
    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,pending,resolved,closed'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid status.'], 400);
        }

        try {
            $ticket = $this->findTicket($id);
            
            if (!$ticket) {
                return response()->json(['error' => 'Support ticket not found.'], 404);
            }
            
            $oldStatus = $ticket['status'];
            $newStatus = $request->status;
            
            $updates = [
                'status' => $newStatus,
                'updated_by' => Auth::id()
            ];
            
            // Set resolution time if resolving/closing
            if (in_array($newStatus, ['resolved', 'closed']) && !in_array($oldStatus, ['resolved', 'closed'])) {
                $updates['resolution_time'] = now();
            }
            
            $this->updateTicket($id, $updates);
            
            // Log status change
            $this->logTicketStatusChange($id, $oldStatus, $newStatus);
            
            // Send notification email
            $this->sendStatusChangeEmail($ticket, $oldStatus, $newStatus);
            
            Log::info('Support ticket status changed', ['ticket_id' => $id, 'old_status' => $oldStatus, 'new_status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error changing ticket status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Bulk actions for support tickets.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:assign,status_change,delete,priority_change',
            'ticket_ids' => 'required|array|min:1',
            'ticket_ids.*' => 'integer',
            'value' => 'required_unless:action,delete'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $ticketIds = $request->ticket_ids;
            $action = $request->action;
            $value = $request->value;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($ticketIds as $ticketId) {
                $ticket = $this->findTicket($ticketId);
                if (!$ticket) continue;
                
                switch ($action) {
                    case 'assign':
                        $this->updateTicket($ticketId, ['assigned_to' => $value, 'updated_by' => Auth::id()]);
                        $this->logTicketAssignment($ticketId, $ticket['assigned_to'], $value);
                        $processedCount++;
                        break;
                        
                    case 'status_change':
                        $updates = ['status' => $value, 'updated_by' => Auth::id()];
                        if (in_array($value, ['resolved', 'closed']) && !in_array($ticket['status'], ['resolved', 'closed'])) {
                            $updates['resolution_time'] = now();
                        }
                        $this->updateTicket($ticketId, $updates);
                        $this->logTicketStatusChange($ticketId, $ticket['status'], $value);
                        $processedCount++;
                        break;
                        
                    case 'priority_change':
                        $this->updateTicket($ticketId, ['priority' => $value, 'updated_by' => Auth::id()]);
                        $this->logTicketPriorityChange($ticketId, $ticket['priority'], $value);
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        if (in_array($ticket['status'], ['closed', 'resolved'])) {
                            // Delete attachments
                            if ($ticket['attachments']) {
                                $attachments = json_decode($ticket['attachments'], true);
                                foreach ($attachments as $attachment) {
                                    $this->deleteAttachment($attachment['path']);
                                }
                            }
                            $this->deleteTicketReplies($ticketId);
                            $this->deleteTicketHistory($ticketId);
                            $this->deleteTicket($ticketId);
                            $processedCount++;
                        }
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on support tickets', [
                'action' => $action,
                'processed_count' => $processedCount,
                'ticket_ids' => $ticketIds
            ]);
            
            return back()->with('success', "Successfully processed {$processedCount} ticket(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Export support tickets to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tickets = $this->getTicketsQuery();
            
            // Apply same filters as index
            if ($request->filled('status')) {
                $tickets = $tickets->where('status', $request->status);
            }
            if ($request->filled('priority')) {
                $tickets = $tickets->where('priority', $request->priority);
            }
            if ($request->filled('category')) {
                $tickets = $tickets->where('category', $request->category);
            }
            
            $filename = 'support_tickets_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($tickets, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting support tickets: ' . $e->getMessage());
            return back()->with('error', 'Failed to export support tickets.');
        }
    }

    /**
     * Support dashboard with analytics.
     */
    public function dashboard()
    {
        try {
            $stats = $this->getSupportDashboardStats();
            $recentTickets = $this->getRecentTickets();
            $agentPerformance = $this->getAgentPerformance();
            $categoryStats = $this->getCategoryStatistics();
            
            return view('admin.support.dashboard', compact('stats', 'recentTickets', 'agentPerformance', 'categoryStats'));
            
        } catch (\Exception $e) {
            Log::error('Error loading support dashboard: ' . $e->getMessage());
            return back()->with('error', 'Failed to load support dashboard.');
        }
    }

    // Private helper methods

    private function getTicketsQuery()
    {
        // Mock query for demonstration - replace with actual database query
        return collect([
            [
                'id' => 1,
                'ticket_number' => 'SUP-2025-001',
                'customer_id' => 1,
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '+1234567890',
                'subject' => 'Order not received',
                'category' => 'shipping',
                'priority' => 'high',
                'status' => 'open',
                'description' => 'I placed an order 2 weeks ago but have not received it yet.',
                'attachments' => json_encode([['name' => 'receipt.pdf', 'path' => 'support/attachments/receipt.pdf']]),
                'assigned_to' => 1,
                'created_by' => 1,
                'tags' => ['shipping', 'delay'],
                'internal_notes' => 'Customer called twice about this issue.',
                'last_reply_at' => now()->subHours(2),
                'last_reply_by' => 1,
                'response_time' => now()->subDays(1),
                'resolution_time' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subHours(2)
            ],
            [
                'id' => 2,
                'ticket_number' => 'SUP-2025-002',
                'customer_id' => 2,
                'customer_name' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'customer_phone' => null,
                'subject' => 'Payment issue with credit card',
                'category' => 'billing',
                'priority' => 'medium',
                'status' => 'pending',
                'description' => 'My credit card was charged but the order was not processed.',
                'attachments' => null,
                'assigned_to' => 2,
                'created_by' => 1,
                'tags' => ['payment', 'credit-card'],
                'internal_notes' => null,
                'last_reply_at' => now()->subHours(6),
                'last_reply_by' => 2,
                'response_time' => now()->subHours(8),
                'resolution_time' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(6)
            ],
            [
                'id' => 3,
                'ticket_number' => 'SUP-2025-003',
                'customer_id' => null,
                'customer_name' => 'Anonymous User',
                'customer_email' => 'guest@example.com',
                'customer_phone' => '+9876543210',
                'subject' => 'Website not loading properly',
                'category' => 'technical',
                'priority' => 'low',
                'status' => 'resolved',
                'description' => 'The website is very slow and some pages are not loading.',
                'attachments' => json_encode([['name' => 'screenshot.png', 'path' => 'support/attachments/screenshot.png']]),
                'assigned_to' => 3,
                'created_by' => 1,
                'tags' => ['performance', 'technical'],
                'internal_notes' => 'Fixed server configuration issue.',
                'last_reply_at' => now()->subDays(1),
                'last_reply_by' => 3,
                'response_time' => now()->subDays(4),
                'resolution_time' => now()->subDays(1),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1)
            ]
        ]);
    }

    private function getSupportStatistics()
    {
        return [
            'total_tickets' => 156,
            'open_tickets' => 23,
            'pending_tickets' => 15,
            'resolved_tickets' => 89,
            'closed_tickets' => 29,
            'today_tickets' => 12,
            'avg_response_time' => '2.5 hours',
            'avg_resolution_time' => '1.2 days',
            'satisfaction_rating' => 4.3,
            'category_breakdown' => [
                'general' => 25,
                'technical' => 18,
                'billing' => 22,
                'shipping' => 31,
                'product' => 19,
                'returns' => 16,
                'account' => 12,
                'vendor' => 13
            ],
            'priority_breakdown' => [
                'low' => 45,
                'medium' => 67,
                'high' => 32,
                'urgent' => 12
            ]
        ];
    }

    private function getTicketStatuses()
    {
        return [
            'open' => 'Open',
            'pending' => 'Pending Customer',
            'resolved' => 'Resolved',
            'closed' => 'Closed'
        ];
    }

    private function getTicketPriorities()
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];
    }

    private function getTicketCategories()
    {
        return [
            'general' => 'General Inquiry',
            'technical' => 'Technical Support',
            'billing' => 'Billing & Payments',
            'product' => 'Product Issues',
            'shipping' => 'Shipping & Delivery',
            'returns' => 'Returns & Refunds',
            'account' => 'Account Issues',
            'vendor' => 'Vendor Support'
        ];
    }

    private function getSupportAgents()
    {
        return collect([
            ['id' => 1, 'name' => 'Agent John', 'email' => 'john@support.com'],
            ['id' => 2, 'name' => 'Agent Sarah', 'email' => 'sarah@support.com'],
            ['id' => 3, 'name' => 'Agent Mike', 'email' => 'mike@support.com']
        ]);
    }

    private function getCustomers()
    {
        return collect([
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['id' => 3, 'name' => 'Bob Wilson', 'email' => 'bob@example.com']
        ]);
    }

    private function generateTicketNumber()
    {
        return 'SUP-' . date('Y') . '-' . str_pad(rand(1, 9999), 3, '0', STR_PAD_LEFT);
    }

    private function createTicket($data)
    {
        // Mock creation - replace with actual database insert
        return array_merge(['id' => rand(1000, 9999)], $data, ['created_at' => now(), 'updated_at' => now()]);
    }

    private function findTicket($id)
    {
        // Mock data - replace with actual database query
        $tickets = $this->getTicketsQuery();
        return $tickets->firstWhere('id', $id);
    }

    private function updateTicket($id, $data)
    {
        // Mock update - replace with actual database update
        Log::info('Support ticket updated', ['id' => $id, 'data' => $data]);
    }

    private function deleteTicket($id)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Support ticket deleted', ['id' => $id]);
    }

    private function uploadAttachment($file, $directory)
    {
        // Mock upload - replace with actual file upload logic
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $directory . '/' . $filename;
        
        Log::info('Support attachment uploaded', ['path' => $path, 'size' => $file->getSize()]);
        
        return [
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }

    private function deleteAttachment($path)
    {
        // Mock deletion - replace with actual file deletion
        Log::info('Support attachment deleted', ['path' => $path]);
    }

    private function getTicketReplies($ticketId)
    {
        // Mock replies - replace with actual database query
        return collect([
            [
                'id' => 1,
                'ticket_id' => $ticketId,
                'message' => 'Thank you for contacting us. We are looking into your issue.',
                'attachments' => null,
                'is_internal' => false,
                'created_by' => 1,
                'created_by_name' => 'Agent John',
                'created_at' => now()->subHours(2)
            ]
        ]);
    }

    private function getTicketHistory($ticketId)
    {
        // Mock history - replace with actual database query
        return collect([
            [
                'id' => 1,
                'ticket_id' => $ticketId,
                'action' => 'status_changed',
                'description' => 'Status changed from open to pending',
                'created_by' => 1,
                'created_by_name' => 'Agent John',
                'created_at' => now()->subHours(1)
            ]
        ]);
    }

    private function getRelatedTickets($ticket)
    {
        // Mock related tickets - replace with actual database query
        return collect([]);
    }

    private function markTicketAsViewed($id)
    {
        // Mock mark as viewed - replace with actual implementation
        Log::info('Ticket marked as viewed', ['ticket_id' => $id]);
    }

    private function createTicketReply($data)
    {
        // Mock creation - replace with actual database insert
        return array_merge(['id' => rand(1000, 9999)], $data);
    }

    private function deleteTicketReplies($ticketId)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Ticket replies deleted', ['ticket_id' => $ticketId]);
    }

    private function deleteTicketHistory($ticketId)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Ticket history deleted', ['ticket_id' => $ticketId]);
    }

    private function logTicketChanges($ticketId, $oldData, $newData)
    {
        // Mock logging - replace with actual history logging
        Log::info('Ticket changes logged', ['ticket_id' => $ticketId]);
    }

    private function logTicketAssignment($ticketId, $oldAgentId, $newAgentId)
    {
        // Mock logging - replace with actual history logging
        Log::info('Ticket assignment logged', ['ticket_id' => $ticketId, 'old_agent' => $oldAgentId, 'new_agent' => $newAgentId]);
    }

    private function logTicketStatusChange($ticketId, $oldStatus, $newStatus)
    {
        // Mock logging - replace with actual history logging
        Log::info('Ticket status change logged', ['ticket_id' => $ticketId, 'old_status' => $oldStatus, 'new_status' => $newStatus]);
    }

    private function logTicketPriorityChange($ticketId, $oldPriority, $newPriority)
    {
        // Mock logging - replace with actual history logging
        Log::info('Ticket priority change logged', ['ticket_id' => $ticketId, 'old_priority' => $oldPriority, 'new_priority' => $newPriority]);
    }

    private function sendTicketCreatedEmail($ticket)
    {
        // Mock email - replace with actual email sending
        Log::info('Ticket creation email sent', ['ticket_id' => $ticket['id']]);
    }

    private function sendReplyEmail($ticket, $reply)
    {
        // Mock email - replace with actual email sending
        Log::info('Ticket reply email sent', ['ticket_id' => $ticket['id'], 'reply_id' => $reply['id']]);
    }

    private function sendStatusChangeEmail($ticket, $oldStatus, $newStatus)
    {
        // Mock email - replace with actual email sending
        Log::info('Status change email sent', ['ticket_id' => $ticket['id'], 'old_status' => $oldStatus, 'new_status' => $newStatus]);
    }

    private function notifyAssignedAgent($ticket)
    {
        // Mock notification - replace with actual notification
        Log::info('Agent assignment notification sent', ['ticket_id' => $ticket['id'], 'agent_id' => $ticket['assigned_to']]);
    }

    private function notifyAssignmentChange($ticket, $oldAgentId, $newAgentId)
    {
        // Mock notification - replace with actual notification
        Log::info('Assignment change notification sent', ['ticket_id' => $ticket['id'], 'old_agent' => $oldAgentId, 'new_agent' => $newAgentId]);
    }

    private function getSupportDashboardStats()
    {
        return [
            'today_tickets' => 12,
            'yesterday_tickets' => 8,
            'this_week_tickets' => 45,
            'this_month_tickets' => 156,
            'avg_response_time_today' => '1.8 hours',
            'avg_resolution_time_today' => '4.2 hours',
            'satisfaction_score_today' => 4.5,
            'ticket_trends' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                'open' => [5, 8, 12, 6, 9, 4, 7],
                'resolved' => [12, 15, 18, 14, 16, 8, 11]
            ]
        ];
    }

    private function getRecentTickets()
    {
        return $this->getTicketsQuery()->take(5);
    }

    private function getAgentPerformance()
    {
        return collect([
            ['name' => 'Agent John', 'tickets_handled' => 45, 'avg_rating' => 4.2, 'avg_response_time' => '2.1 hours'],
            ['name' => 'Agent Sarah', 'tickets_handled' => 38, 'avg_rating' => 4.6, 'avg_response_time' => '1.8 hours'],
            ['name' => 'Agent Mike', 'tickets_handled' => 42, 'avg_rating' => 4.3, 'avg_response_time' => '2.5 hours']
        ]);
    }

    private function getCategoryStatistics()
    {
        return [
            'general' => ['count' => 25, 'avg_resolution' => '1.2 days'],
            'technical' => ['count' => 18, 'avg_resolution' => '2.1 days'],
            'billing' => ['count' => 22, 'avg_resolution' => '0.8 days'],
            'shipping' => ['count' => 31, 'avg_resolution' => '1.5 days']
        ];
    }

    private function generateCsvExport($tickets, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Ticket Number',
                'Customer Name',
                'Customer Email',
                'Subject',
                'Category',
                'Priority',
                'Status',
                'Assigned To',
                'Response Time',
                'Resolution Time',
                'Created At',
                'Updated At'
            ]);
            
            // CSV Data
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket['ticket_number'],
                    $ticket['customer_name'],
                    $ticket['customer_email'],
                    $ticket['subject'],
                    ucfirst($ticket['category']),
                    ucfirst($ticket['priority']),
                    ucfirst($ticket['status']),
                    $ticket['assigned_to'] ?? 'Unassigned',
                    $ticket['response_time'] ?? 'Not responded',
                    $ticket['resolution_time'] ?? 'Not resolved',
                    $ticket['created_at'],
                    $ticket['updated_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get status color for badges
     */
    private function getStatusColor($status)
    {
        switch (strtolower($status)) {
            case 'open':
                return 'primary';
            case 'pending':
                return 'warning';
            case 'in_progress':
            case 'in-progress':
                return 'info';
            case 'resolved':
            case 'closed':
                return 'success';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
