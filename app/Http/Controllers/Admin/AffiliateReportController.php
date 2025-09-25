<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;
use App\Models\Commission;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateReportController extends Controller
{
    /**
     * Display affiliate performance reports
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);
        $endDate = now();

        if ($request->filled('date_from')) {
            $startDate = $request->date_from;
        }
        if ($request->filled('date_to')) {
            $endDate = $request->date_to;
        }

        // Overall statistics
        $overallStats = [
            'total_affiliates' => User::whereHas('affiliateClicks')->count(),
            'active_affiliates' => User::whereHas('affiliateClicks', function($q) use ($startDate, $endDate) {
                                      $q->whereBetween('clicked_at', [$startDate, $endDate]);
                                  })->count(),
            'total_clicks' => AffiliateClick::whereBetween('clicked_at', [$startDate, $endDate])->count(),
            'total_conversions' => Commission::where('commission_type', 'affiliate')
                                            ->whereBetween('earned_at', [$startDate, $endDate])
                                            ->count(),
            'total_revenue' => Commission::where('commission_type', 'affiliate')
                                        ->where('status', 'approved')
                                        ->whereBetween('earned_at', [$startDate, $endDate])
                                        ->sum('order_amount'),
            'total_commissions' => Commission::where('commission_type', 'affiliate')
                                            ->where('status', 'approved')
                                            ->whereBetween('earned_at', [$startDate, $endDate])
                                            ->sum('commission_amount'),
        ];

        // Calculate conversion rate
        $overallStats['conversion_rate'] = $overallStats['total_clicks'] > 0 
            ? round(($overallStats['total_conversions'] / $overallStats['total_clicks']) * 100, 2)
            : 0;

        // Calculate average commission per conversion
        $overallStats['avg_commission'] = $overallStats['total_conversions'] > 0
            ? round($overallStats['total_commissions'] / $overallStats['total_conversions'], 2)
            : 0;

        // Top performing affiliates
        $topAffiliates = User::withCount(['affiliateClicks as clicks_count' => function($q) use ($startDate, $endDate) {
                                 $q->whereBetween('clicked_at', [$startDate, $endDate]);
                             }])
                            ->withCount(['commissions as commissions_count' => function($q) use ($startDate, $endDate) {
                                $q->where('commission_type', 'affiliate')
                                  ->whereBetween('earned_at', [$startDate, $endDate]);
                            }])
                            ->withSum(['commissions as total_earned' => function($q) use ($startDate, $endDate) {
                                $q->where('commission_type', 'affiliate')
                                  ->where('status', 'approved')
                                  ->whereBetween('earned_at', [$startDate, $endDate]);
                            }], 'commission_amount')
                            ->having('clicks_count', '>', 0)
                            ->orderBy('total_earned', 'desc')
                            ->limit(10)
                            ->get();

        // Top performing products
        $topProducts = Product::withCount(['affiliateClicks as clicks_count' => function($q) use ($startDate, $endDate) {
                                   $q->whereBetween('clicked_at', [$startDate, $endDate]);
                               }])
                              ->withCount(['commissions as commissions_count' => function($q) use ($startDate, $endDate) {
                                  $q->where('commission_type', 'affiliate')
                                    ->whereBetween('earned_at', [$startDate, $endDate]);
                              }])
                              ->withSum(['commissions as total_revenue' => function($q) use ($startDate, $endDate) {
                                  $q->where('commission_type', 'affiliate')
                                    ->where('status', 'approved')
                                    ->whereBetween('earned_at', [$startDate, $endDate]);
                              }], 'order_amount')
                              ->having('clicks_count', '>', 0)
                              ->orderBy('total_revenue', 'desc')
                              ->limit(10)
                              ->get();

        // Performance over time (daily)
        $performanceOverTime = AffiliateClick::selectRaw('
                                   DATE(clicked_at) as date,
                                   COUNT(*) as clicks,
                                   COUNT(DISTINCT user_id) as unique_affiliates
                               ')
                               ->whereBetween('clicked_at', [$startDate, $endDate])
                               ->groupBy('date')
                               ->orderBy('date')
                               ->get();

        // Add commission data to performance over time
        $commissionsByDate = Commission::selectRaw('
                                 DATE(earned_at) as date,
                                 COUNT(*) as conversions,
                                 SUM(commission_amount) as commission_amount,
                                 SUM(order_amount) as revenue
                             ')
                             ->where('commission_type', 'affiliate')
                             ->whereBetween('earned_at', [$startDate, $endDate])
                             ->groupBy('date')
                             ->get()
                             ->keyBy('date');

        foreach ($performanceOverTime as $day) {
            $commissionData = $commissionsByDate->get($day->date);
            $day->conversions = $commissionData->conversions ?? 0;
            $day->commission_amount = $commissionData->commission_amount ?? 0;
            $day->revenue = $commissionData->revenue ?? 0;
            $day->conversion_rate = $day->clicks > 0 ? round(($day->conversions / $day->clicks) * 100, 2) : 0;
        }

        return view('admin.affiliate-reports.index', compact(
            'overallStats', 'topAffiliates', 'topProducts', 'performanceOverTime', 'startDate', 'endDate'
        ));
    }

    /**
     * Generate detailed affiliate performance report
     */
    public function detailed(Request $request)
    {
        $affiliateId = $request->get('affiliate_id');
        $startDate = $request->get('date_from', now()->subMonth());
        $endDate = $request->get('date_to', now());

        if (!$affiliateId) {
            return redirect()->route('admin.affiliate-reports.index')
                           ->with('error', 'Please select an affiliate to view detailed report.');
        }

        $affiliate = User::findOrFail($affiliateId);

        // Affiliate statistics
        $stats = [
            'total_clicks' => $affiliate->affiliateClicks()
                                      ->whereBetween('clicked_at', [$startDate, $endDate])
                                      ->count(),
            'unique_products' => $affiliate->affiliateClicks()
                                          ->whereBetween('clicked_at', [$startDate, $endDate])
                                          ->distinct('product_id')
                                          ->count(),
            'total_conversions' => $affiliate->commissions()
                                           ->where('commission_type', 'affiliate')
                                           ->whereBetween('earned_at', [$startDate, $endDate])
                                           ->count(),
            'total_earned' => $affiliate->commissions()
                                      ->where('commission_type', 'affiliate')
                                      ->where('status', 'approved')
                                      ->whereBetween('earned_at', [$startDate, $endDate])
                                      ->sum('commission_amount'),
            'pending_earnings' => $affiliate->commissions()
                                          ->where('commission_type', 'affiliate')
                                          ->where('status', 'pending')
                                          ->whereBetween('earned_at', [$startDate, $endDate])
                                          ->sum('commission_amount'),
        ];

        $stats['conversion_rate'] = $stats['total_clicks'] > 0 
            ? round(($stats['total_conversions'] / $stats['total_clicks']) * 100, 2)
            : 0;

        // Product performance for this affiliate
        $productPerformance = Product::withCount(['affiliateClicks as clicks_count' => function($q) use ($affiliate, $startDate, $endDate) {
                                         $q->where('user_id', $affiliate->id)
                                           ->whereBetween('clicked_at', [$startDate, $endDate]);
                                     }])
                                    ->withCount(['commissions as conversions_count' => function($q) use ($affiliate, $startDate, $endDate) {
                                        $q->where('commission_type', 'affiliate')
                                          ->where('user_id', $affiliate->id)
                                          ->whereBetween('earned_at', [$startDate, $endDate]);
                                    }])
                                    ->withSum(['commissions as earnings' => function($q) use ($affiliate, $startDate, $endDate) {
                                        $q->where('commission_type', 'affiliate')
                                          ->where('user_id', $affiliate->id)
                                          ->where('status', 'approved')
                                          ->whereBetween('earned_at', [$startDate, $endDate]);
                                    }], 'commission_amount')
                                    ->having('clicks_count', '>', 0)
                                    ->orderBy('earnings', 'desc')
                                    ->get();

        // Recent activity
        $recentClicks = $affiliate->affiliateClicks()
                                 ->with(['product'])
                                 ->whereBetween('clicked_at', [$startDate, $endDate])
                                 ->latest('clicked_at')
                                 ->limit(20)
                                 ->get();

        $recentCommissions = $affiliate->commissions()
                                     ->where('commission_type', 'affiliate')
                                     ->with(['product', 'order'])
                                     ->whereBetween('earned_at', [$startDate, $endDate])
                                     ->latest('earned_at')
                                     ->limit(10)
                                     ->get();

        return view('admin.affiliate-reports.detailed', compact(
            'affiliate', 'stats', 'productPerformance', 'recentClicks', 'recentCommissions', 'startDate', 'endDate'
        ));
    }

    /**
     * Export affiliate performance data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('date_from', now()->subMonth());
        $endDate = $request->get('date_to', now());

        $affiliates = User::withCount(['affiliateClicks as clicks_count' => function($q) use ($startDate, $endDate) {
                              $q->whereBetween('clicked_at', [$startDate, $endDate]);
                          }])
                         ->withCount(['commissions as commissions_count' => function($q) use ($startDate, $endDate) {
                             $q->where('commission_type', 'affiliate')
                               ->whereBetween('earned_at', [$startDate, $endDate]);
                         }])
                         ->withSum(['commissions as total_earned' => function($q) use ($startDate, $endDate) {
                             $q->where('commission_type', 'affiliate')
                               ->where('status', 'approved')
                               ->whereBetween('earned_at', [$startDate, $endDate]);
                         }], 'commission_amount')
                         ->having('clicks_count', '>', 0)
                         ->orderBy('total_earned', 'desc')
                         ->get();

        $filename = 'affiliate_performance_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        return response()->stream(function () use ($affiliates) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Affiliate ID',
                'Name',
                'Username',
                'Email',
                'Total Clicks',
                'Total Conversions',
                'Conversion Rate (%)',
                'Total Earned',
                'Status'
            ]);

            // CSV data
            foreach ($affiliates as $affiliate) {
                $conversionRate = $affiliate->clicks_count > 0 
                    ? round(($affiliate->commissions_count / $affiliate->clicks_count) * 100, 2)
                    : 0;

                fputcsv($file, [
                    $affiliate->id,
                    $affiliate->name,
                    $affiliate->username,
                    $affiliate->email,
                    $affiliate->clicks_count,
                    $affiliate->commissions_count,
                    $conversionRate,
                    number_format($affiliate->total_earned ?? 0, 2),
                    ucfirst($affiliate->status)
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }
}
