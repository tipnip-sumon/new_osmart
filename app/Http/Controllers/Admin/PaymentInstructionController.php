<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentInstructionController extends Controller
{
    /**
     * Display payment method instructions management page
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.settings.payment-instructions', compact('paymentMethods'));
    }

    /**
     * Update payment method instructions
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'instructions' => 'array',
                'instructions.*' => 'nullable|string|max:2000'
            ]);

            $instructions = $request->input('instructions', []);
            $updated = 0;

            foreach ($instructions as $paymentMethodId => $instruction) {
                $paymentMethod = PaymentMethod::find($paymentMethodId);
                if ($paymentMethod) {
                    $paymentMethod->update([
                        'instructions' => $instruction
                    ]);
                    $updated++;
                }
            }

            Log::info('Payment instructions updated', [
                'admin_id' => auth()->guard('admin')->id(),
                'updated_count' => $updated
            ]);

            return back()->with('success', "Payment instructions updated successfully! ($updated methods updated)");

        } catch (\Exception $e) {
            Log::error('Failed to update payment instructions', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->guard('admin')->id()
            ]);

            return back()->with('error', 'Failed to update payment instructions: ' . $e->getMessage());
        }
    }

    /**
     * Get payment method instructions for specific method
     */
    public function getInstructions($code)
    {
        $paymentMethod = PaymentMethod::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$paymentMethod) {
            return response()->json(['error' => 'Payment method not found'], 404);
        }

        return response()->json([
            'name' => $paymentMethod->name,
            'code' => $paymentMethod->code,
            'instructions' => $paymentMethod->instructions,
            'account_number' => $paymentMethod->account_number,
            'account_name' => $paymentMethod->account_name
        ]);
    }

    /**
     * Preview instructions with sample data
     */
    public function preview(Request $request)
    {
        $instructions = $request->input('instructions', '');
        $sampleAmount = 1000;
        $sampleFee = 18.50;
        $sampleTotal = $sampleAmount + $sampleFee;

        // Replace placeholders with sample data
        $previewInstructions = str_replace(
            ['{{amount}}', '{{total}}', '{{fee}}', '{{account_number}}'],
            [
                '৳' . number_format($sampleAmount, 2),
                '৳' . number_format($sampleTotal, 2),
                '৳' . number_format($sampleFee, 2),
                '01XXXXXXXXX'
            ],
            $instructions
        );

        return response()->json([
            'preview' => $previewInstructions
        ]);
    }
}
