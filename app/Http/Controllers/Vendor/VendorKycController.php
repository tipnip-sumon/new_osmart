<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorKycVerification;
use App\Models\User;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class VendorKycController extends Controller
{
    use HandlesImageUploads;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display KYC dashboard/status
     */
    public function index()
    {
        $user = Auth::user();
        $kyc = VendorKycVerification::forVendor($user->id)->first();
        
        if (!$kyc) {
            $kyc = VendorKycVerification::create([
                'vendor_id' => $user->id,
                'owner_full_name' => $user->name,
                'business_email' => $user->email,
                'business_phone' => $user->phone,
                'business_present_address' => $user->address,
                'business_present_country' => $user->country ?? 'Bangladesh',
                'business_present_district' => $user->district,
                'business_present_upazila' => $user->upazila,
                'business_present_union_ward' => $user->union_ward,
                'business_present_postal_code' => $user->postal_code,
            ]);
        }

        $steps = [
            1 => [
                'title' => 'Business Information',
                'description' => 'Basic business and registration details',
                'icon' => 'fe-briefcase',
                'completed' => $kyc->isStepCompleted(1),
            ],
            2 => [
                'title' => 'Owner Information',
                'description' => 'Business owner personal information',
                'icon' => 'fe-user',
                'completed' => $kyc->isStepCompleted(2),
            ],
            3 => [
                'title' => 'Document Information',
                'description' => 'Identity document and business license details',
                'icon' => 'fe-credit-card',
                'completed' => $kyc->isStepCompleted(3),
            ],
            4 => [
                'title' => 'Address Information',
                'description' => 'Business and owner address details',
                'icon' => 'fe-map-pin',
                'completed' => $kyc->isStepCompleted(4),
            ],
            5 => [
                'title' => 'Document Upload',
                'description' => 'Upload required documents, photos and certificates',
                'icon' => 'fe-upload',
                'completed' => $kyc->isStepCompleted(5),
            ],
            6 => [
                'title' => 'Review & Submit',
                'description' => 'Review all information and submit for verification',
                'icon' => 'fe-check-circle',
                'completed' => $kyc->isStepCompleted(6),
            ],
        ];

        return view('vendor.kyc.index', compact('kyc', 'steps'));
    }

    /**
     * Show specific step form
     */
    public function step(Request $request, int $step)
    {
        $user = Auth::user();
        $kyc = VendorKycVerification::forVendor($user->id)->firstOrFail();
        
        if ($step < 1 || $step > $kyc->total_steps) {
            return redirect()->route('vendor.kyc.index')->with('error', 'Invalid step');
        }

        // Load location data for address steps
        $locationData = null;
        if ($step === 4) {
            $locationDataPath = public_path('data/bangladesh-locations.json');
            if (file_exists($locationDataPath)) {
                $locationData = json_decode(file_get_contents($locationDataPath), true);
            }
        }

        return view("vendor.kyc.step{$step}", compact('kyc', 'step', 'locationData'));
    }

    /**
     * Save step data
     */
    public function saveStep(Request $request, int $step)
    {
        $user = Auth::user();
        $kyc = VendorKycVerification::forVendor($user->id)->firstOrFail();

        try {
            DB::beginTransaction();

            switch ($step) {
                case 1:
                    $this->saveStep1($request, $kyc);
                    break;
                case 2:
                    $this->saveStep2($request, $kyc);
                    break;
                case 3:
                    $this->saveStep3($request, $kyc);
                    break;
                case 4:
                    $this->saveStep4($request, $kyc);
                    break;
                case 5:
                    $this->saveStep5($request, $kyc);
                    break;
                case 6:
                    $this->saveStep6($request, $kyc);
                    break;
                default:
                    throw new \Exception('Invalid step');
            }

            // Mark step as completed
            $kyc->completeStep($step);
            $kyc->save();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step saved successfully',
                    'next_step' => $step < $kyc->total_steps ? $step + 1 : null,
                    'completion_percentage' => $kyc->completion_percentage,
                ]);
            }

            $message = 'Step ' . $step . ' completed successfully!';
            
            if ($step < $kyc->total_steps) {
                return redirect()->route('vendor.kyc.step', $step + 1)->with('success', $message);
            } else {
                return redirect()->route('vendor.kyc.index')->with('success', 'All steps completed! You can now review and submit.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor KYC Step Save Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saving step: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error saving step: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Save step 1 - Business Information
     */
    private function saveStep1(Request $request, VendorKycVerification $kyc)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'business_registration_number' => 'nullable|string|max:50',
            'tax_identification_number' => 'nullable|string|max:50',
            'business_license_number' => 'nullable|string|max:50',
            'establishment_date' => 'nullable|date|before:today',
            'business_description' => 'required|string',
            'website_url' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $kyc->fill($request->only([
            'business_name', 'business_type', 'business_registration_number',
            'tax_identification_number', 'business_license_number', 'establishment_date',
            'business_description', 'website_url'
        ]));
    }

    /**
     * Save step 2 - Owner Information
     */
    private function saveStep2(Request $request, VendorKycVerification $kyc)
    {
        $validator = Validator::make($request->all(), [
            'owner_full_name' => 'required|string|max:255',
            'owner_father_name' => 'required|string|max:255',
            'owner_mother_name' => 'required|string|max:255',
            'owner_date_of_birth' => 'required|date|before:today',
            'owner_gender' => 'required|in:male,female,other',
            'owner_marital_status' => 'required|in:single,married,divorced,widowed',
            'owner_nationality' => 'required|string|max:50',
            'owner_religion' => 'nullable|string|max:50',
            'owner_profession' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $kyc->fill($request->only([
            'owner_full_name', 'owner_father_name', 'owner_mother_name', 'owner_date_of_birth',
            'owner_gender', 'owner_marital_status', 'owner_nationality', 'owner_religion',
            'owner_profession'
        ]));
    }

    /**
     * Save step 3 - Document Information
     */
    private function saveStep3(Request $request, VendorKycVerification $kyc)
    {
        $rules = [
            'document_type' => 'required|in:nid,passport,driving_license,birth_certificate',
            'document_number' => 'required|string|max:50|unique:vendor_kyc_verifications,document_number,' . $kyc->id,
        ];

        if ($request->document_type === 'nid') {
            $rules['nid_type'] = 'required|in:smart,old';
            if ($request->nid_type === 'old') {
                $rules['voter_id'] = 'nullable|string|max:50';
            }
        } elseif ($request->document_type === 'passport') {
            $rules['document_issue_date'] = 'required|date';
            $rules['document_expiry_date'] = 'required|date|after:today';
            $rules['document_issuer'] = 'required|string|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $kyc->fill($request->only([
            'document_type', 'document_number', 'document_issue_date',
            'document_expiry_date', 'document_issuer', 'nid_type', 'voter_id'
        ]));
    }

    /**
     * Save step 4 - Address Information
     */
    private function saveStep4(Request $request, VendorKycVerification $kyc)
    {
        // Debug: Log what we're receiving
        Log::info('Step 4 Form Data:', $request->all());
        Log::info('Emergency Contact Name: ' . ($request->has('emergency_contact_name') ? $request->emergency_contact_name : 'NOT PRESENT'));
        
        $validator = Validator::make($request->all(), [
            // Business Address
            'business_present_address' => 'required|string',
            'business_present_country' => 'required|string|max:100',
            'business_present_district' => 'required|string|max:100',
            'business_present_upazila' => 'required|string|max:100',
            'business_present_union_ward' => 'required|string|max:100',
            'business_present_post_office' => 'nullable|string|max:100',
            'business_present_postal_code' => 'nullable|string|max:10',
            'same_as_business_present_address' => 'boolean',
            'business_permanent_address' => 'required_if:same_as_business_present_address,false|string',
            'business_permanent_country' => 'required_if:same_as_business_present_address,false|string|max:100',
            'business_permanent_district' => 'required_if:same_as_business_present_address,false|string|max:100',
            'business_permanent_upazila' => 'required_if:same_as_business_present_address,false|string|max:100',
            'business_permanent_union_ward' => 'required_if:same_as_business_present_address,false|string|max:100',
            'business_permanent_post_office' => 'nullable|string|max:100',
            'business_permanent_postal_code' => 'nullable|string|max:10',
            
            // Owner Address
            'owner_present_address' => 'required|string',
            'owner_present_country' => 'required|string|max:100',
            'owner_present_district' => 'required|string|max:100',
            'owner_present_upazila' => 'required|string|max:100',
            'owner_present_union_ward' => 'required|string|max:100',
            'owner_present_post_office' => 'nullable|string|max:100',
            'owner_present_postal_code' => 'nullable|string|max:10',
            'same_as_owner_present_address' => 'boolean',
            'owner_permanent_address' => 'required_if:same_as_owner_present_address,false|string',
            'owner_permanent_country' => 'required_if:same_as_owner_present_address,false|string|max:100',
            'owner_permanent_district' => 'required_if:same_as_owner_present_address,false|string|max:100',
            'owner_permanent_upazila' => 'required_if:same_as_owner_present_address,false|string|max:100',
            'owner_permanent_union_ward' => 'required_if:same_as_owner_present_address,false|string|max:100',
            'owner_permanent_post_office' => 'nullable|string|max:100',
            'owner_permanent_postal_code' => 'nullable|string|max:10',
            
            // Contact Information
            'phone_number' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_address' => 'nullable|string',
            
            // Bank Information
            'bank_account_holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:100',
            'bank_branch' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_routing_number' => 'nullable|string|max:20',
            'bank_account_type' => 'required|in:savings,current,business',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $data = $request->only([
            'business_present_address', 'business_present_country', 'business_present_district',
            'business_present_upazila', 'business_present_union_ward', 'business_present_post_office',
            'business_present_postal_code', 'same_as_business_present_address',
            'owner_present_address', 'owner_present_country', 'owner_present_district',
            'owner_present_upazila', 'owner_present_union_ward', 'owner_present_post_office',
            'owner_present_postal_code', 'same_as_owner_present_address',
            'phone_number', 'alternative_phone', 'email_address', 'business_phone', 'business_email',
            'emergency_contact_name', 'emergency_contact_relationship',
            'emergency_contact_phone', 'emergency_contact_address',
            'bank_account_holder_name', 'bank_name', 'bank_branch',
            'bank_account_number', 'bank_routing_number', 'bank_account_type'
        ]);

        // Handle business address same as present
        if ($request->boolean('same_as_business_present_address')) {
            $data['business_permanent_address'] = $data['business_present_address'];
            $data['business_permanent_country'] = $data['business_present_country'];
            $data['business_permanent_district'] = $data['business_present_district'];
            $data['business_permanent_upazila'] = $data['business_present_upazila'];
            $data['business_permanent_union_ward'] = $data['business_present_union_ward'];
            $data['business_permanent_post_office'] = $data['business_present_post_office'];
            $data['business_permanent_postal_code'] = $data['business_present_postal_code'];
        } else {
            $data = array_merge($data, $request->only([
                'business_permanent_address', 'business_permanent_country', 'business_permanent_district',
                'business_permanent_upazila', 'business_permanent_union_ward', 'business_permanent_post_office',
                'business_permanent_postal_code'
            ]));
        }

        // Handle owner address same as present
        if ($request->boolean('same_as_owner_present_address')) {
            $data['owner_permanent_address'] = $data['owner_present_address'];
            $data['owner_permanent_country'] = $data['owner_present_country'];
            $data['owner_permanent_district'] = $data['owner_present_district'];
            $data['owner_permanent_upazila'] = $data['owner_present_upazila'];
            $data['owner_permanent_union_ward'] = $data['owner_present_union_ward'];
            $data['owner_permanent_post_office'] = $data['owner_present_post_office'];
            $data['owner_permanent_postal_code'] = $data['owner_present_postal_code'];
        } else {
            $data = array_merge($data, $request->only([
                'owner_permanent_address', 'owner_permanent_country', 'owner_permanent_district',
                'owner_permanent_upazila', 'owner_permanent_union_ward', 'owner_permanent_post_office',
                'owner_permanent_postal_code'
            ]));
        }

        $kyc->fill($data);
    }

    /**
     * Save step 5 - Document Upload
     */
    private function saveStep5(Request $request, VendorKycVerification $kyc)
    {
        // Build validation rules based on existing documents
        $rules = [
            'document_front_image' => $kyc->document_front_image ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'owner_photo' => $kyc->owner_photo ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'owner_signature' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'utility_bill' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
            'business_license' => $kyc->business_license ? 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072' : 'required|file|mimes:jpeg,png,jpg,pdf|max:3072',
            'tax_certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
            'bank_statement' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
        ];

        // Handle document back image requirement
        if ($kyc->document_type === 'nid' && $kyc->nid_type === 'smart') {
            $rules['document_back_image'] = $kyc->document_back_image ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048';
        } elseif ($kyc->document_type === 'driving_license') {
            $rules['document_back_image'] = $kyc->document_back_image ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Upload documents using HandlesImageUploads trait
        $uploadedFiles = [];

        foreach (['document_front_image', 'document_back_image', 'owner_photo', 'owner_signature', 'utility_bill', 'business_license', 'tax_certificate', 'bank_statement'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $customSizes = [
                    'original' => ['width' => 800, 'height' => 600],
                    'medium' => ['width' => 400, 'height' => 300],
                    'thumbnail' => ['width' => 200, 'height' => 150]
                ];
                $uploadResult = $this->uploadSingleImage($file, 'vendor-kyc/' . $kyc->vendor_id . '/' . $field, $customSizes);
                $uploadedFiles[$field] = $uploadResult['sizes']['medium']['path'] ?? $uploadResult['sizes']['original']['path'];
            }
        }

        $kyc->fill($uploadedFiles);
    }

    /**
     * Save step 6 - Review & Submit
     */
    private function saveStep6(Request $request, VendorKycVerification $kyc)
    {
        $validator = Validator::make($request->all(), [
            'terms_accepted' => 'accepted',
            'declaration_accepted' => 'accepted',
        ]);

        if ($validator->fails()) {
            throw new \Exception('You must accept the terms and declaration to proceed');
        }

        // Compare with profile
        $kyc->compareWithProfile();

        // Submit for review
        if (!$kyc->submitForReview()) {
            throw new \Exception('Cannot submit - missing required documents');
        }
    }

    /**
     * Upload document file
     */
    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:3072',
            'document_type' => 'required|string|in:document_front_image,document_back_image,owner_photo,owner_signature,utility_bill,business_license,tax_certificate,bank_statement',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $kyc = VendorKycVerification::forVendor($user->id)->firstOrFail();
            
            $file = $request->file('document');
            $documentType = $request->input('document_type');
            
            $customSizes = [
                'original' => ['width' => 800, 'height' => 600],
                'medium' => ['width' => 400, 'height' => 300],
                'thumbnail' => ['width' => 200, 'height' => 150]
            ];

            $uploadResult = $this->uploadSingleImage($file, 'vendor-kyc/' . $user->id . '/' . $documentType, $customSizes);
            $path = $uploadResult['sizes']['medium']['path'] ?? $uploadResult['sizes']['original']['path'];

            $kyc->$documentType = $path;
            $kyc->save();

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'file_path' => $path,
                'file_url' => asset('storage/' . $path),
            ]);

        } catch (\Exception $e) {
            Log::error('Vendor KYC Document Upload Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|in:document_front_image,document_back_image,owner_photo,owner_signature,utility_bill,business_license,tax_certificate,bank_statement',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $kyc = VendorKycVerification::forVendor($user->id)->firstOrFail();
            
            $documentType = $request->input('document_type');
            
            if ($kyc->$documentType) {
                Storage::delete($kyc->$documentType);
                $kyc->$documentType = null;
                $kyc->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Vendor KYC Document Delete Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get KYC status for API
     */
    public function status()
    {
        $user = Auth::user();
        $kyc = VendorKycVerification::forVendor($user->id)->first();

        if (!$kyc) {
            return response()->json([
                'status' => 'not_started',
                'message' => 'KYC not started',
                'completion_percentage' => 0,
            ]);
        }

        return response()->json([
            'status' => $kyc->status,
            'message' => $kyc->status_label,
            'completion_percentage' => $kyc->completion_percentage,
            'current_step' => $kyc->current_step,
            'total_steps' => $kyc->total_steps,
            'submitted_at' => $kyc->submitted_at,
            'approved_at' => $kyc->approved_at,
            'rejected_at' => $kyc->rejected_at,
            'rejection_reason' => $kyc->rejection_reason,
            'profile_mismatches' => $kyc->profile_mismatches,
        ]);
    }

    /**
     * Update profile from KYC data
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $kyc = VendorKycVerification::forVendor($user->id)->firstOrFail();

            if (!$kyc->isApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC must be approved before updating profile',
                ], 422);
            }

            $updated = $kyc->updateProfileFromKyc();

            return response()->json([
                'success' => true,
                'message' => $updated ? 'Profile updated from KYC data' : 'No changes needed',
                'updated' => $updated,
            ]);

        } catch (\Exception $e) {
            Log::error('Vendor KYC Profile Update Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate and download KYC certificate
     */
    public function certificate()
    {
        $user = Auth::user();
        $kyc = VendorKycVerification::forVendor($user->id)->first();
        
        if (!$kyc || $kyc->status !== 'approved') {
            return redirect()->route('vendor.kyc.index')
                ->with('error', 'KYC verification required to download certificate.');
        }

        // Generate PDF certificate
        $pdf = Pdf::loadView('vendor.kyc.certificate', [
            'user' => $user,
            'kyc' => $kyc
        ]);

        return $pdf->download('vendor-kyc-certificate-' . $user->id . '.pdf');
    }

    /**
     * Resubmit rejected KYC
     */
    public function resubmit(Request $request)
    {
        $user = Auth::user();
        $kyc = VendorKycVerification::forVendor($user->id)->first();
        
        if (!$kyc || $kyc->status !== 'rejected') {
            return back()->with('error', 'You can only resubmit rejected KYC applications.');
        }

        // Reset KYC status to pending for resubmission
        $kyc->update([
            'status' => 'draft',
            'rejection_reason' => null,
            'admin_notes' => null,
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        return redirect()->route('vendor.kyc.step', 1)
            ->with('success', 'KYC resubmitted successfully. Please review and update your information.');
    }
}