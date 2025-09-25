<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class WithdrawMethodController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of withdrawal methods.
     */
    public function index(Request $request)
    {
        try {
            $methods = $this->getWithdrawMethodsQuery();
            
            // Apply filters
            if ($request->filled('type')) {
                $methods = $methods->where('type', $request->type);
            }
            
            if ($request->filled('status')) {
                $status = $request->status === 'active' ? 1 : 0;
                $methods = $methods->where('is_active', $status);
            }
            
            if ($request->filled('gateway')) {
                $methods = $methods->where('gateway', $request->gateway);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $methods = $methods->filter(function($method) use ($search) {
                    return stripos($method['name'], $search) !== false ||
                           stripos($method['description'], $search) !== false ||
                           stripos($method['gateway'], $search) !== false;
                });
            }
            
            // Get withdrawal statistics
            $stats = $this->getWithdrawMethodStatistics();
            
            // Get method types and gateways
            $types = $this->getMethodTypes();
            $gateways = $this->getAvailableGateways();
            
            return view('admin.withdraw-methods.index', compact('methods', 'stats', 'types', 'gateways'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching withdrawal methods: ' . $e->getMessage());
            return back()->with('error', 'Failed to load withdrawal methods.');
        }
    }

    /**
     * Show the form for creating a new withdrawal method.
     */
    public function create()
    {
        try {
            $types = $this->getMethodTypes();
            $gateways = $this->getAvailableGateways();
            $currencies = $this->getSupportedCurrencies();
            $countries = $this->getSupportedCountries();
            
            return view('admin.withdraw-methods.create', compact('types', 'gateways', 'currencies', 'countries'));
            
        } catch (\Exception $e) {
            Log::error('Error loading withdrawal method create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load withdrawal method form.');
        }
    }

    /**
     * Store a newly created withdrawal method in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:withdraw_methods,name',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:bank_transfer,paypal,stripe,skrill,payoneer,crypto,mobile_money,check',
            'gateway' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'fixed_charge' => 'nullable|numeric|min:0',
            'percent_charge' => 'nullable|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'processing_time' => 'required|string|max:100',
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'auto_approval' => 'boolean',
            'require_kyc' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'supported_countries' => 'nullable|array',
            'supported_countries.*' => 'string|size:2',
            'required_fields' => 'nullable|array',
            'required_fields.*' => 'string|max:100',
            'instructions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'gateway_config' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $this->uploadImage($request->file('logo'), 'withdraw-methods/logos');
            }
            
            // Handle icon upload
            $iconPath = null;
            if ($request->hasFile('icon')) {
                $iconPath = $this->uploadImage($request->file('icon'), 'withdraw-methods/icons');
            }
            
            $methodData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'type' => $request->type,
                'gateway' => $request->gateway,
                'logo' => $logoPath,
                'icon' => $iconPath,
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'fixed_charge' => $request->fixed_charge ?: 0,
                'percent_charge' => $request->percent_charge ?: 0,
                'currency' => strtoupper($request->currency),
                'processing_time' => $request->processing_time,
                'is_active' => $request->boolean('is_active', true),
                'is_global' => $request->boolean('is_global', true),
                'auto_approval' => $request->boolean('auto_approval', false),
                'require_kyc' => $request->boolean('require_kyc', false),
                'sort_order' => $request->sort_order ?: 0,
                'supported_countries' => $request->supported_countries ? json_encode($request->supported_countries) : null,
                'required_fields' => $request->required_fields ? json_encode($request->required_fields) : null,
                'instructions' => $request->instructions,
                'terms_conditions' => $request->terms_conditions,
                'gateway_config' => $request->gateway_config ? json_encode($request->gateway_config) : null,
                'total_withdrawals' => 0,
                'total_amount' => 0
            ];
            
            $method = $this->createWithdrawMethod($methodData);
            
            DB::commit();
            
            Log::info('Withdrawal method created successfully', ['method_id' => $method['id']]);
            
            return redirect()->route('admin.withdraw-methods.index')
                           ->with('success', 'Withdrawal method created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating withdrawal method: ' . $e->getMessage());
            return back()->with('error', 'Failed to create withdrawal method.')->withInput();
        }
    }

    /**
     * Display the specified withdrawal method.
     */
    public function show($id)
    {
        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return back()->with('error', 'Withdrawal method not found.');
            }
            
            // Get method analytics
            $analytics = $this->getMethodAnalytics($id);
            
            // Get recent withdrawals using this method
            $recentWithdrawals = $this->getRecentWithdrawals($id);
            
            return view('admin.withdraw-methods.show', compact('method', 'analytics', 'recentWithdrawals'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching withdrawal method details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load withdrawal method details.');
        }
    }

    /**
     * Show the form for editing the specified withdrawal method.
     */
    public function edit($id)
    {
        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return back()->with('error', 'Withdrawal method not found.');
            }
            
            $types = $this->getMethodTypes();
            $gateways = $this->getAvailableGateways();
            $currencies = $this->getSupportedCurrencies();
            $countries = $this->getSupportedCountries();
            
            return view('admin.withdraw-methods.edit', compact('method', 'types', 'gateways', 'currencies', 'countries'));
            
        } catch (\Exception $e) {
            Log::error('Error loading withdrawal method edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load withdrawal method form.');
        }
    }

    /**
     * Update the specified withdrawal method in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('withdraw_methods')->ignore($id)],
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:bank_transfer,paypal,stripe,skrill,payoneer,crypto,mobile_money,check',
            'gateway' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'fixed_charge' => 'nullable|numeric|min:0',
            'percent_charge' => 'nullable|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'processing_time' => 'required|string|max:100',
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'auto_approval' => 'boolean',
            'require_kyc' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'supported_countries' => 'nullable|array',
            'supported_countries.*' => 'string|size:2',
            'required_fields' => 'nullable|array',
            'required_fields.*' => 'string|max:100',
            'instructions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'gateway_config' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return back()->with('error', 'Withdrawal method not found.');
            }
            
            DB::beginTransaction();
            
            // Handle logo upload
            $logoPath = $method['logo'];
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($logoPath) {
                    $this->deleteImage($logoPath);
                }
                $logoPath = $this->uploadImage($request->file('logo'), 'withdraw-methods/logos');
            }
            
            // Handle icon upload
            $iconPath = $method['icon'];
            if ($request->hasFile('icon')) {
                // Delete old icon
                if ($iconPath) {
                    $this->deleteImage($iconPath);
                }
                $iconPath = $this->uploadImage($request->file('icon'), 'withdraw-methods/icons');
            }
            
            $methodData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'type' => $request->type,
                'gateway' => $request->gateway,
                'logo' => $logoPath,
                'icon' => $iconPath,
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'fixed_charge' => $request->fixed_charge ?: 0,
                'percent_charge' => $request->percent_charge ?: 0,
                'currency' => strtoupper($request->currency),
                'processing_time' => $request->processing_time,
                'is_active' => $request->boolean('is_active', true),
                'is_global' => $request->boolean('is_global', true),
                'auto_approval' => $request->boolean('auto_approval', false),
                'require_kyc' => $request->boolean('require_kyc', false),
                'sort_order' => $request->sort_order ?: 0,
                'supported_countries' => $request->supported_countries ? json_encode($request->supported_countries) : null,
                'required_fields' => $request->required_fields ? json_encode($request->required_fields) : null,
                'instructions' => $request->instructions,
                'terms_conditions' => $request->terms_conditions,
                'gateway_config' => $request->gateway_config ? json_encode($request->gateway_config) : null
            ];
            
            $this->updateWithdrawMethod($id, $methodData);
            
            DB::commit();
            
            Log::info('Withdrawal method updated successfully', ['method_id' => $id]);
            
            return redirect()->route('admin.withdraw-methods.show', $id)
                           ->with('success', 'Withdrawal method updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating withdrawal method: ' . $e->getMessage());
            return back()->with('error', 'Failed to update withdrawal method.')->withInput();
        }
    }

    /**
     * Remove the specified withdrawal method from storage.
     */
    public function destroy($id)
    {
        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return back()->with('error', 'Withdrawal method not found.');
            }
            
            // Check if method has pending withdrawals
            if ($this->hasPendingWithdrawals($id)) {
                return back()->with('error', 'Cannot delete withdrawal method with pending withdrawals.');
            }
            
            DB::beginTransaction();
            
            // Delete images
            if ($method['logo']) {
                $this->deleteImage($method['logo']);
            }
            if ($method['icon']) {
                $this->deleteImage($method['icon']);
            }
            
            // Delete the method
            $this->deleteWithdrawMethod($id);
            
            DB::commit();
            
            Log::info('Withdrawal method deleted successfully', ['method_id' => $id]);
            
            return redirect()->route('admin.withdraw-methods.index')
                           ->with('success', 'Withdrawal method deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting withdrawal method: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete withdrawal method.');
        }
    }

    /**
     * Toggle withdrawal method status.
     */
    public function toggleStatus($id)
    {
        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return response()->json(['error' => 'Withdrawal method not found.'], 404);
            }
            
            $newStatus = !$method['is_active'];
            $this->updateWithdrawMethod($id, ['is_active' => $newStatus]);
            
            Log::info('Withdrawal method status toggled', ['method_id' => $id, 'status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Withdrawal method status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling withdrawal method status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Duplicate a withdrawal method.
     */
    public function duplicate($id)
    {
        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return back()->with('error', 'Withdrawal method not found.');
            }
            
            DB::beginTransaction();
            
            // Prepare data for duplication
            $newMethodData = $method;
            unset($newMethodData['id'], $newMethodData['created_at'], $newMethodData['updated_at']);
            $newMethodData['name'] = $method['name'] . ' (Copy)';
            $newMethodData['slug'] = Str::slug($newMethodData['name']);
            $newMethodData['is_active'] = false;
            $newMethodData['total_withdrawals'] = 0;
            $newMethodData['total_amount'] = 0;
            
            // Copy images if they exist
            if ($method['logo']) {
                $newMethodData['logo'] = $this->copyImage($method['logo']);
            }
            if ($method['icon']) {
                $newMethodData['icon'] = $this->copyImage($method['icon']);
            }
            
            $newMethod = $this->createWithdrawMethod($newMethodData);
            
            DB::commit();
            
            Log::info('Withdrawal method duplicated successfully', ['original_id' => $id, 'new_id' => $newMethod['id']]);
            
            return redirect()->route('admin.withdraw-methods.edit', $newMethod['id'])
                           ->with('success', 'Withdrawal method duplicated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating withdrawal method: ' . $e->getMessage());
            return back()->with('error', 'Failed to duplicate withdrawal method.');
        }
    }

    /**
     * Bulk actions for withdrawal methods.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'method_ids' => 'required|array|min:1',
            'method_ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $methodIds = $request->method_ids;
            $action = $request->action;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($methodIds as $methodId) {
                $method = $this->findWithdrawMethod($methodId);
                if (!$method) continue;
                
                switch ($action) {
                    case 'activate':
                        $this->updateWithdrawMethod($methodId, ['is_active' => true]);
                        $processedCount++;
                        break;
                        
                    case 'deactivate':
                        $this->updateWithdrawMethod($methodId, ['is_active' => false]);
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        // Check for pending withdrawals
                        if ($this->hasPendingWithdrawals($methodId)) {
                            continue 2; // Skip if has pending withdrawals
                        }
                        
                        // Delete images
                        if ($method['logo']) {
                            $this->deleteImage($method['logo']);
                        }
                        if ($method['icon']) {
                            $this->deleteImage($method['icon']);
                        }
                        $this->deleteWithdrawMethod($methodId);
                        $processedCount++;
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on withdrawal methods', [
                'action' => $action,
                'processed_count' => $processedCount,
                'method_ids' => $methodIds
            ]);
            
            return back()->with('success', "Successfully processed {$processedCount} withdrawal method(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Update sort order (AJAX).
     */
    public function updateSortOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'method_id' => 'required|integer',
                'sort_order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid data.'], 400);
            }

            $this->updateWithdrawMethod($request->method_id, ['sort_order' => $request->sort_order]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating sort order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update sort order.'], 500);
        }
    }

    /**
     * Test withdrawal method gateway connection.
     */
    public function testGateway($id)
    {
        try {
            $method = $this->findWithdrawMethod($id);
            
            if (!$method) {
                return response()->json(['error' => 'Withdrawal method not found.'], 404);
            }
            
            // Test gateway connection
            $testResult = $this->testGatewayConnection($method);
            
            Log::info('Gateway test performed', ['method_id' => $id, 'result' => $testResult['success']]);
            
            return response()->json($testResult);
            
        } catch (\Exception $e) {
            Log::error('Error testing gateway: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to test gateway connection.'], 500);
        }
    }

    /**
     * Get available withdrawal methods for frontend.
     */
    public function getAvailableMethods(Request $request)
    {
        try {
            $country = $request->get('country');
            $amount = $request->get('amount', 0);
            
            $methods = $this->getAvailableMethodsQuery($country, $amount);
            
            return response()->json([
                'success' => true,
                'methods' => $methods
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching available withdrawal methods: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch withdrawal methods.'], 500);
        }
    }

    /**
     * Calculate withdrawal charges.
     */
    public function calculateCharges(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'method_id' => 'required|integer',
                'amount' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid data.'], 400);
            }

            $method = $this->findWithdrawMethod($request->method_id);
            
            if (!$method || !$method['is_active']) {
                return response()->json(['error' => 'Withdrawal method not available.'], 404);
            }
            
            $charges = $this->calculateWithdrawCharges($method, $request->amount);
            
            return response()->json([
                'success' => true,
                'charges' => $charges
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error calculating withdrawal charges: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to calculate charges.'], 500);
        }
    }

    /**
     * Export withdrawal methods to CSV.
     */
    public function export()
    {
        try {
            $methods = $this->getWithdrawMethodsQuery();
            $filename = 'withdraw_methods_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($methods, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting withdrawal methods: ' . $e->getMessage());
            return back()->with('error', 'Failed to export withdrawal methods.');
        }
    }

    // Private helper methods

    private function getWithdrawMethodsQuery()
    {
        // Mock query for demonstration - replace with actual database query
        return collect([
            [
                'id' => 1,
                'name' => 'PayPal',
                'slug' => 'paypal',
                'description' => 'Withdraw funds directly to your PayPal account',
                'type' => 'paypal',
                'gateway' => 'paypal_api',
                'logo' => 'withdraw-methods/logos/paypal.png',
                'icon' => 'withdraw-methods/icons/paypal.svg',
                'min_amount' => 10.00,
                'max_amount' => 10000.00,
                'fixed_charge' => 2.50,
                'percent_charge' => 2.9,
                'currency' => 'USD',
                'processing_time' => '1-3 business days',
                'is_active' => true,
                'is_global' => true,
                'auto_approval' => false,
                'require_kyc' => false,
                'sort_order' => 1,
                'supported_countries' => json_encode(['US', 'GB', 'CA', 'AU', 'DE', 'FR']),
                'required_fields' => json_encode(['email']),
                'instructions' => 'Enter your PayPal email address to receive payments.',
                'terms_conditions' => 'PayPal withdrawal terms and conditions...',
                'gateway_config' => json_encode(['client_id' => 'xxx', 'client_secret' => 'xxx']),
                'total_withdrawals' => 1250,
                'total_amount' => 125000.00,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(5)
            ],
            [
                'id' => 2,
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'description' => 'Direct bank transfer to your account',
                'type' => 'bank_transfer',
                'gateway' => 'bank_wire',
                'logo' => 'withdraw-methods/logos/bank.png',
                'icon' => 'withdraw-methods/icons/bank.svg',
                'min_amount' => 50.00,
                'max_amount' => 50000.00,
                'fixed_charge' => 5.00,
                'percent_charge' => 0.0,
                'currency' => 'USD',
                'processing_time' => '3-5 business days',
                'is_active' => true,
                'is_global' => false,
                'auto_approval' => false,
                'require_kyc' => true,
                'sort_order' => 2,
                'supported_countries' => json_encode(['US', 'GB', 'CA']),
                'required_fields' => json_encode(['account_number', 'routing_number', 'bank_name', 'account_holder_name']),
                'instructions' => 'Provide your complete bank account details for wire transfer.',
                'terms_conditions' => 'Bank transfer terms and conditions...',
                'gateway_config' => null,
                'total_withdrawals' => 890,
                'total_amount' => 245000.00,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(3)
            ],
            [
                'id' => 3,
                'name' => 'Stripe Express',
                'slug' => 'stripe-express',
                'description' => 'Fast payout via Stripe Express',
                'type' => 'stripe',
                'gateway' => 'stripe_express',
                'logo' => 'withdraw-methods/logos/stripe.png',
                'icon' => 'withdraw-methods/icons/stripe.svg',
                'min_amount' => 5.00,
                'max_amount' => 25000.00,
                'fixed_charge' => 0.25,
                'percent_charge' => 0.25,
                'currency' => 'USD',
                'processing_time' => 'Instant to 1 business day',
                'is_active' => true,
                'is_global' => true,
                'auto_approval' => true,
                'require_kyc' => false,
                'sort_order' => 3,
                'supported_countries' => json_encode(['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'NL', 'ES', 'IT']),
                'required_fields' => json_encode(['account_id']),
                'instructions' => 'Connect your Stripe Express account for instant payouts.',
                'terms_conditions' => 'Stripe Express terms and conditions...',
                'gateway_config' => json_encode(['secret_key' => 'xxx', 'webhook_secret' => 'xxx']),
                'total_withdrawals' => 2100,
                'total_amount' => 89500.00,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDay()
            ],
            [
                'id' => 4,
                'name' => 'Skrill',
                'slug' => 'skrill',
                'description' => 'Withdraw to your Skrill e-wallet',
                'type' => 'skrill',
                'gateway' => 'skrill_mqi',
                'logo' => 'withdraw-methods/logos/skrill.png',
                'icon' => 'withdraw-methods/icons/skrill.svg',
                'min_amount' => 20.00,
                'max_amount' => 15000.00,
                'fixed_charge' => 1.45,
                'percent_charge' => 1.45,
                'currency' => 'USD',
                'processing_time' => '15 minutes to 2 hours',
                'is_active' => false,
                'is_global' => true,
                'auto_approval' => false,
                'require_kyc' => false,
                'sort_order' => 4,
                'supported_countries' => json_encode(['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'IT', 'ES', 'NL', 'BE']),
                'required_fields' => json_encode(['email']),
                'instructions' => 'Enter your Skrill email address for quick transfers.',
                'terms_conditions' => 'Skrill withdrawal terms and conditions...',
                'gateway_config' => json_encode(['merchant_id' => 'xxx', 'secret_word' => 'xxx']),
                'total_withdrawals' => 0,
                'total_amount' => 0.00,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15)
            ]
        ]);
    }

    private function getWithdrawMethodStatistics()
    {
        return [
            'total_methods' => 8,
            'active_methods' => 6,
            'inactive_methods' => 2,
            'total_withdrawals' => 4240,
            'total_amount' => 459500.00,
            'average_withdrawal' => 108.37,
            'type_distribution' => [
                'paypal' => 2,
                'bank_transfer' => 2,
                'stripe' => 1,
                'skrill' => 1,
                'crypto' => 1,
                'mobile_money' => 1
            ],
            'country_distribution' => [
                'US' => 1850,
                'GB' => 890,
                'CA' => 650,
                'AU' => 450,
                'DE' => 400
            ]
        ];
    }

    private function getMethodTypes()
    {
        return [
            'bank_transfer' => 'Bank Transfer',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'skrill' => 'Skrill',
            'payoneer' => 'Payoneer',
            'crypto' => 'Cryptocurrency',
            'mobile_money' => 'Mobile Money',
            'check' => 'Check/Cheque'
        ];
    }

    private function getAvailableGateways()
    {
        return [
            'paypal_api' => 'PayPal API',
            'bank_wire' => 'Bank Wire Transfer',
            'stripe_express' => 'Stripe Express',
            'stripe_standard' => 'Stripe Standard',
            'skrill_mqi' => 'Skrill MQI',
            'payoneer_api' => 'Payoneer API',
            'bitcoin_core' => 'Bitcoin Core',
            'mpesa' => 'M-Pesa',
            'manual_check' => 'Manual Check Processing'
        ];
    }

    private function getSupportedCurrencies()
    {
        return [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar',
            'JPY' => 'Japanese Yen',
            'CHF' => 'Swiss Franc',
            'SEK' => 'Swedish Krona',
            'NOK' => 'Norwegian Krone',
            'DKK' => 'Danish Krone'
        ];
    }

    private function getSupportedCountries()
    {
        return [
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark'
        ];
    }

    private function createWithdrawMethod($data)
    {
        // Mock creation - replace with actual database insert
        return array_merge(['id' => rand(1000, 9999)], $data, ['created_at' => now(), 'updated_at' => now()]);
    }

    private function findWithdrawMethod($id)
    {
        // Mock data - replace with actual database query
        $methods = $this->getWithdrawMethodsQuery();
        return $methods->firstWhere('id', $id);
    }

    private function updateWithdrawMethod($id, $data)
    {
        // Mock update - replace with actual database update
        Log::info('Withdrawal method updated', ['id' => $id, 'data' => $data]);
    }

    private function deleteWithdrawMethod($id)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Withdrawal method deleted', ['id' => $id]);
    }

    private function uploadImage($file, $directory)
    {
        try {
            $imageData = $this->uploadSingleImage($file, $directory);
            return $imageData['filename'];
        } catch (\Exception $e) {
            Log::error('Withdrawal method image upload failed: ' . $e->getMessage());
            throw new \Exception('Failed to upload withdrawal method image: ' . $e->getMessage());
        }
    }

    private function deleteImage($imagePath)
    {
        try {
            if ($imagePath) {
                // For withdrawal methods, we might have legacy paths or new JSON data
                // Handle both cases gracefully
                Log::info('Withdrawal method image deletion attempted', ['path' => $imagePath]);
                return true;
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Withdrawal method image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    private function copyImage($originalPath)
    {
        try {
            if ($originalPath) {
                // For duplication, we can reuse the same image path
                Log::info('Withdrawal method image copied/reused', ['original' => $originalPath]);
                return $originalPath;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Withdrawal method image copy failed: ' . $e->getMessage());
            return null;
        }
    }

    private function hasPendingWithdrawals($methodId)
    {
        // Mock check - replace with actual database query
        return false; // Assume no pending withdrawals for demo
    }

    private function getMethodAnalytics($methodId)
    {
        // Mock analytics data - replace with actual analytics query
        return [
            'total_withdrawals' => 1250,
            'total_amount' => 125000.00,
            'average_amount' => 100.00,
            'success_rate' => 98.5,
            'monthly_withdrawals' => [
                '2025-01' => 95,
                '2025-02' => 110,
                '2025-03' => 125,
                '2025-04' => 140,
                '2025-05' => 135,
                '2025-06' => 155,
                '2025-07' => 165
            ],
            'monthly_amounts' => [
                '2025-01' => 9500.00,
                '2025-02' => 11000.00,
                '2025-03' => 12500.00,
                '2025-04' => 14000.00,
                '2025-05' => 13500.00,
                '2025-06' => 15500.00,
                '2025-07' => 16500.00
            ]
        ];
    }

    private function getRecentWithdrawals($methodId)
    {
        // Mock recent withdrawals - replace with actual database query
        return collect([
            [
                'id' => 1,
                'vendor_name' => 'Tech Store',
                'amount' => 250.00,
                'status' => 'completed',
                'created_at' => now()->subHours(2)
            ],
            [
                'id' => 2,
                'vendor_name' => 'Fashion Hub',
                'amount' => 150.00,
                'status' => 'pending',
                'created_at' => now()->subHours(6)
            ],
            [
                'id' => 3,
                'vendor_name' => 'Book World',
                'amount' => 75.00,
                'status' => 'processing',
                'created_at' => now()->subDay()
            ]
        ]);
    }

    private function testGatewayConnection($method)
    {
        // Mock gateway test - replace with actual gateway testing
        $success = rand(0, 1); // Random success/failure for demo
        
        return [
            'success' => $success,
            'message' => $success ? 'Gateway connection successful' : 'Gateway connection failed',
            'response_time' => rand(100, 500) . 'ms',
            'test_timestamp' => now()->toISOString()
        ];
    }

    private function getAvailableMethodsQuery($country, $amount)
    {
        $methods = $this->getWithdrawMethodsQuery()->where('is_active', true);
        
        // Filter by amount limits
        $methods = $methods->filter(function($method) use ($amount) {
            return $amount >= $method['min_amount'] && $amount <= $method['max_amount'];
        });
        
        // Filter by country support
        if ($country) {
            $methods = $methods->filter(function($method) use ($country) {
                if ($method['is_global']) {
                    return true;
                }
                $supportedCountries = json_decode($method['supported_countries'], true);
                return $supportedCountries && in_array($country, $supportedCountries);
            });
        }
        
        return $methods->sortBy('sort_order')->values();
    }

    private function calculateWithdrawCharges($method, $amount)
    {
        $fixedCharge = $method['fixed_charge'];
        $percentCharge = ($amount * $method['percent_charge']) / 100;
        $totalCharge = $fixedCharge + $percentCharge;
        $netAmount = $amount - $totalCharge;
        
        return [
            'gross_amount' => $amount,
            'fixed_charge' => $fixedCharge,
            'percent_charge' => $percentCharge,
            'total_charge' => $totalCharge,
            'net_amount' => $netAmount,
            'currency' => $method['currency']
        ];
    }

    private function generateCsvExport($methods, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($methods) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Type',
                'Gateway',
                'Min Amount',
                'Max Amount',
                'Fixed Charge',
                'Percent Charge',
                'Currency',
                'Processing Time',
                'Status',
                'Global',
                'Auto Approval',
                'Require KYC',
                'Total Withdrawals',
                'Total Amount',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($methods as $method) {
                fputcsv($file, [
                    $method['id'],
                    $method['name'],
                    ucfirst($method['type']),
                    $method['gateway'],
                    $method['min_amount'],
                    $method['max_amount'],
                    $method['fixed_charge'],
                    $method['percent_charge'] . '%',
                    $method['currency'],
                    $method['processing_time'],
                    $method['is_active'] ? 'Active' : 'Inactive',
                    $method['is_global'] ? 'Yes' : 'No',
                    $method['auto_approval'] ? 'Yes' : 'No',
                    $method['require_kyc'] ? 'Yes' : 'No',
                    $method['total_withdrawals'],
                    $method['total_amount'],
                    $method['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
