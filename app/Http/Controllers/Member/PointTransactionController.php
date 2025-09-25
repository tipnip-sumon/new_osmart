<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointTransactionController extends Controller
{
    /**
     * Display point transactions history
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build query
        $query = PointTransaction::where('user_id', $user->id);
        
        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Get transactions with pagination
        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());
        
        return view('member.point-transactions.index', compact(
            'user',
            'transactions'
        ));
    }
    
    /**
     * Show transaction details
     */
    public function show($id)
    {
        $user = Auth::user();
        $transaction = PointTransaction::where('user_id', $user->id)
            ->findOrFail($id);
            
        return view('member.point-transactions.show', compact(
            'user',
            'transaction'
        ));
    }
    
    /**
     * Export transactions to CSV/PDF
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $format = $request->get('format', 'csv');
        
        // Build query with same filters as index
        $query = PointTransaction::where('user_id', $user->id);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        if ($format === 'csv') {
            return $this->exportCsv($transactions, $user);
        } elseif ($format === 'pdf') {
            return $this->exportPdf($transactions, $user);
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }
    
    /**
     * Export transactions to CSV
     */
    private function exportCsv($transactions, $user)
    {
        $filename = 'point_transactions_' . $user->id . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Time',
                'Type',
                'Amount',
                'Description',
                'Category',
                'Status',
                'Reference ID'
            ]);
            
            // CSV Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('Y-m-d'),
                    $transaction->created_at->format('H:i:s'),
                    ucfirst($transaction->type),
                    ($transaction->type === 'credit' ? '+' : '-') . number_format($transaction->amount, 2),
                    $transaction->description,
                    ucfirst(str_replace('_', ' ', $transaction->reference_type ?? 'N/A')),
                    ucfirst($transaction->status),
                    $transaction->reference_id ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export transactions to PDF
     */
    private function exportPdf($transactions, $user)
    {
        $pdf = app('dompdf.wrapper');
        
        $html = view('member.point-transactions.export-pdf', compact('transactions', 'user'))->render();
        
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'point_transactions_' . $user->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Get transaction statistics
     */
    public function stats(Request $request)
    {
        $user = Auth::user();
        
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);
        
        $stats = [
            'total_credits' => PointTransaction::where('user_id', $user->id)
                ->where('type', 'credit')
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
            
            'total_debits' => PointTransaction::where('user_id', $user->id)
                ->where('type', 'debit')
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
                
            'transactions_count' => PointTransaction::where('user_id', $user->id)
                ->where('created_at', '>=', $startDate)
                ->count(),
                
            'by_category' => PointTransaction::where('user_id', $user->id)
                ->where('created_at', '>=', $startDate)
                ->selectRaw('reference_type, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('reference_type')
                ->get(),
                
            'daily_activity' => PointTransaction::where('user_id', $user->id)
                ->where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credits, SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debits')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get()
        ];
        
        return response()->json($stats);
    }
}
