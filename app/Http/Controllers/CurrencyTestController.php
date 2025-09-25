<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class CurrencyTestController extends Controller
{
    /**
     * Display currency test page with plans
     */
    public function index()
    {
        // Get all plans for testing
        $plans = Plan::active()->get();
        
        return view('currency-test', compact('plans'));
    }

    /**
     * Test currency formatting methods
     */
    public function testFormatting()
    {
        $testAmounts = [
            500,
            5000,
            50000,
            250000,
            1500000,
            25000000,
            100000000
        ];

        $results = [];
        
        foreach ($testAmounts as $amount) {
            $results[] = [
                'amount' => $amount,
                'standard' => formatCurrency($amount),
                'bangladeshi' => formatCurrencyBangladeshi($amount),
                'input_format' => formatCurrencyInput($amount),
            ];
        }

        return response()->json([
            'currency_config' => [
                'symbol' => getCurrencySymbol(),
                'name' => getCurrencyName(),
                'code' => config('currency.default_currency'),
                'position' => config('currency.symbol_position'),
            ],
            'test_results' => $results
        ]);
    }

    /**
     * Test currency conversion
     */
    public function testConversion(Request $request)
    {
        $amount = $request->get('amount', 100000);
        $fromCurrency = $request->get('from', 'BDT');
        $toCurrency = $request->get('to', 'USD');

        $converted = convertCurrency($amount, $fromCurrency, $toCurrency);

        return response()->json([
            'original' => [
                'amount' => $amount,
                'currency' => $fromCurrency,
                'formatted' => formatCurrency($amount),
            ],
            'converted' => [
                'amount' => $converted,
                'currency' => $toCurrency,
                'formatted' => getCurrencySymbol($toCurrency) . ' ' . number_format($converted, 2),
            ],
            'exchange_rate' => config("currency.exchange_rates.{$toCurrency}", 1),
        ]);
    }

    /**
     * Test input parsing
     */
    public function testInputParsing(Request $request)
    {
        $inputs = $request->get('inputs', [
            '৳ 50,000',
            '২৫ L',
            '১.৫ Cr',
            '৳ 1,00,000',
            '5 L',
            '2.5 Cr',
            '৳ 15K'
        ]);

        $results = [];
        
        foreach ($inputs as $input) {
            $parsed = parseCurrencyInput($input);
            $results[] = [
                'input' => $input,
                'parsed' => $parsed,
                'formatted' => formatCurrency($parsed),
                'bangladeshi' => formatCurrencyBangladeshi($parsed),
            ];
        }

        return response()->json($results);
    }
}
