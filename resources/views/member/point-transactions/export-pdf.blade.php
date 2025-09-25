<!DOCTYPE html>
<html>
<head>
    <title>Point Transactions Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .user-info {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .user-info table {
            width: 100%;
        }
        .user-info td {
            padding: 3px 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .credit {
            color: #28a745;
        }
        .debit {
            color: #dc3545;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Point Transactions Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>

    <div class="user-info">
        <table>
            <tr>
                <td><strong>User Name:</strong></td>
                <td>{{ $user->name }}</td>
                <td><strong>User ID:</strong></td>
                <td>{{ $user->id }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $user->email }}</td>
                <td><strong>Phone:</strong></td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <h3>Point Balance Summary</h3>
        <div class="summary-item">
            <strong>Active Points:</strong> {{ number_format($user->active_points) }}
        </div>
        <div class="summary-item">
            <strong>Reserve Points:</strong> {{ number_format($user->reserve_points) }}
        </div>
        <div class="summary-item">
            <strong>Total Earned:</strong> {{ number_format($user->total_points_earned) }}
        </div>
    </div>

    @if($transactions->count() > 0)
        <div class="summary">
            <h3>Transaction Summary</h3>
            @php
                $totalCredits = $transactions->where('type', 'credit')->sum('amount');
                $totalDebits = $transactions->where('type', 'debit')->sum('amount');
                $netPoints = $totalCredits - $totalDebits;
            @endphp
            <div class="summary-item">
                <strong>Total Credits:</strong> <span class="credit">+{{ number_format($totalCredits) }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Debits:</strong> <span class="debit">-{{ number_format($totalDebits) }}</span>
            </div>
            <div class="summary-item">
                <strong>Net Points:</strong> <span class="{{ $netPoints >= 0 ? 'credit' : 'debit' }}">{{ $netPoints >= 0 ? '+' : '' }}{{ number_format($netPoints) }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Transactions:</strong> {{ $transactions->count() }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('M d, Y H:i A') }}</td>
                    <td class="text-center">
                        {{ ucfirst($transaction->type) }}
                    </td>
                    <td class="text-center {{ $transaction->type === 'credit' ? 'credit' : 'debit' }}">
                        {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }}
                    </td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->reference_type ?? 'N/A')) }}</td>
                    <td class="text-center">{{ ucfirst($transaction->status) }}</td>
                    <td class="text-center">{{ $transaction->reference_id ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center" style="margin-top: 50px;">
            <h3>No Transactions Found</h3>
            <p>There are no point transactions for the selected criteria.</p>
        </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the system. For any questions, please contact support.</p>
        <p>Report generated for {{ $user->name }} (ID: {{ $user->id }}) on {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>
</body>
</html>
