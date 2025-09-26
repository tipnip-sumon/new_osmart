<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentReceiptController extends Controller
{
    use HandlesImageUploads;

    /**
     * Upload mobile payment receipt
     */
    public function uploadPaymentReceipt(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'payment_receipt' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
                'payment_provider' => 'required|string|in:bkash,nagad,rocket',
                'transaction_id' => 'required|string|max:255',
                'sender_phone' => 'required|string|max:20',
                'payment_datetime' => 'required|date',
                'payment_notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('payment_receipt');
            
            // Upload the receipt using the trait
            $uploadResult = $this->uploadPaymentReceiptImage($file, 'payment-receipts');
            
            // Log the upload for audit trail
            Log::info('Payment receipt uploaded', [
                'provider' => $request->payment_provider,
                'transaction_id' => $request->transaction_id,
                'file_name' => $uploadResult['filename'],
                'file_size' => $uploadResult['file_size'],
                'upload_time' => now()->toDateTimeString(),
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Receipt uploaded successfully',
                'path' => $uploadResult['sizes']['original']['path'] ?? $uploadResult['folder'] . '/' . $uploadResult['filename'],
                'url' => $uploadResult['sizes']['original']['url'] ?? $uploadResult['sizes']['original']['storage_url'] ?? asset('storage/' . $uploadResult['folder'] . '/' . $uploadResult['filename']),
                'filename' => $uploadResult['filename'],
                'file_size' => $uploadResult['file_size'],
                'upload_time' => $uploadResult['uploaded_at']
            ]);

        } catch (\Exception $e) {
            Log::error('Payment receipt upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'provider' => $request->payment_provider ?? 'unknown',
                'transaction_id' => $request->transaction_id ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload receipt. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Upload bank transfer receipt
     */
    public function uploadBankReceipt(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'bank_receipt' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
                'bank_transaction_ref' => 'required|string|max:255',
                'transfer_datetime' => 'required|date',
                'transfer_notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('bank_receipt');
            
            // Upload the receipt using the trait
            $uploadResult = $this->uploadPaymentReceiptImage($file, 'bank-receipts');
            
            // Log the upload for audit trail
            Log::info('Bank receipt uploaded', [
                'transaction_ref' => $request->bank_transaction_ref,
                'file_name' => $uploadResult['filename'],
                'file_size' => $uploadResult['file_size'],
                'upload_time' => now()->toDateTimeString(),
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank receipt uploaded successfully',
                'path' => $uploadResult['sizes']['original']['path'] ?? $uploadResult['folder'] . '/' . $uploadResult['filename'],
                'url' => $uploadResult['sizes']['original']['url'] ?? $uploadResult['sizes']['original']['storage_url'] ?? asset('storage/' . $uploadResult['folder'] . '/' . $uploadResult['filename']),
                'filename' => $uploadResult['filename'],
                'file_size' => $uploadResult['file_size'],
                'upload_time' => $uploadResult['uploaded_at']
            ]);

        } catch (\Exception $e) {
            Log::error('Bank receipt upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'transaction_ref' => $request->bank_transaction_ref ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload bank receipt. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Upload payment receipt image with specific dimensions
     */
    protected function uploadPaymentReceiptImage($file, string $folder): array
    {
        // Define sizes appropriate for receipt images
        $sizes = [
            'original' => ['width' => 1200, 'height' => 1600], // For high-quality viewing
            'large' => ['width' => 800, 'height' => 1000],     // For modal display
            'medium' => ['width' => 400, 'height' => 500],     // For thumbnails
            'small' => ['width' => 200, 'height' => 250]       // For list view
        ];
        
        return $this->processImageUpload($file, $folder, $sizes, 90); // Higher quality for receipts
    }
}