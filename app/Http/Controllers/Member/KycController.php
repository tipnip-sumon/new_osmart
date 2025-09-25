<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberKycVerification;
use App\Models\User;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class KycController extends Controller
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
        $kyc = MemberKycVerification::forUser($user->id)->first();
        
        if (!$kyc) {
            $kyc = MemberKycVerification::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email_address' => $user->email,
                'phone_number' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'present_address' => $user->address,
                'present_country' => $user->country ?? 'Bangladesh',
                'present_district' => $user->district,
                'present_upazila' => $user->upazila,
                'present_union_ward' => $user->union_ward,
                'present_postal_code' => $user->postal_code,
            ]);
        }

        $steps = [
            1 => [
                'title' => 'Personal Information',
                'description' => 'Basic personal and family information',
                'icon' => 'fe-user',
                'completed' => $kyc->isStepCompleted(1),
            ],
            2 => [
                'title' => 'Document Information',
                'description' => 'Identity document details',
                'icon' => 'fe-credit-card',
                'completed' => $kyc->isStepCompleted(2),
            ],
            3 => [
                'title' => 'Address Information',
                'description' => 'Present and permanent address details',
                'icon' => 'fe-map-pin',
                'completed' => $kyc->isStepCompleted(3),
            ],
            4 => [
                'title' => 'Document Upload',
                'description' => 'Upload required documents and photos',
                'icon' => 'fe-upload',
                'completed' => $kyc->isStepCompleted(4),
            ],
            5 => [
                'title' => 'Review & Submit',
                'description' => 'Review all information and submit',
                'icon' => 'fe-check-circle',
                'completed' => $kyc->isStepCompleted(5),
            ],
        ];

        return view('member.kyc.index', compact('kyc', 'steps'));
    }

    /**
     * Show specific step form
     */
    public function step(Request $request, int $step)
    {
        $user = Auth::user();
        $kyc = MemberKycVerification::forUser($user->id)->firstOrFail();
        
        // Check if KYC is verified - prevent editing
        if ($kyc->status === 'verified') {
            return redirect()->route('member.kyc.index')
                ->with('info', 'Your KYC is already verified. You cannot edit the information.');
        }
        
        if ($step < 1 || $step > $kyc->total_steps) {
            return redirect()->route('member.kyc.index')->with('error', 'Invalid step');
        }

        // Load location data for address steps
        $locationData = null;
        if ($step === 3) {
            $locationDataPath = public_path('data/bangladesh-locations.json');
            if (file_exists($locationDataPath)) {
                $locationData = json_decode(file_get_contents($locationDataPath), true);
            }
        }

        return view("member.kyc.step{$step}", compact('kyc', 'step', 'locationData'));
    }

    /**
     * Save step data
     */
    public function saveStep(Request $request, int $step)
    {
        $user = Auth::user();
        $kyc = MemberKycVerification::forUser($user->id)->firstOrFail();

        // Prevent editing if KYC is already verified
        if ($kyc->status === 'verified') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your KYC is already verified. No changes allowed.',
                ], 403);
            }
            return back()->with('error', 'Your KYC is already verified. You cannot make changes.');
        }

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
                return redirect()->route('member.kyc.step', $step + 1)->with('success', $message);
            } else {
                return redirect()->route('member.kyc.index')->with('success', 'All steps completed! You can now review and submit.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KYC Step Save Error: ' . $e->getMessage());

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
     * Save step 1 - Personal Information
     */
    private function saveStep1(Request $request, MemberKycVerification $kyc)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'nationality' => 'required|string|max:50',
            'religion' => 'nullable|string|max:50',
            'profession' => 'required|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $kyc->fill($request->only([
            'full_name', 'father_name', 'mother_name', 'date_of_birth',
            'gender', 'marital_status', 'nationality', 'religion',
            'profession', 'monthly_income'
        ]));
    }

    /**
     * Save step 2 - Document Information
     */
    private function saveStep2(Request $request, MemberKycVerification $kyc)
    {
        $rules = [
            'document_type' => 'required|in:nid,passport,driving_license,birth_certificate',
            'document_number' => 'required|string|max:50|unique:member_kyc_verifications,document_number,' . $kyc->id,
        ];

        if ($request->document_type === 'nid') {
            $rules['nid_type'] = 'required|in:smart_card,old_nid';
            if ($request->nid_type === 'old_nid') {
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
     * Save step 3 - Address Information
     */
    private function saveStep3(Request $request, MemberKycVerification $kyc)
    {
        $validator = Validator::make($request->all(), [
            'present_address' => 'required|string',
            'present_country' => 'required|string|max:100',
            'present_district' => 'required|string|max:100',
            'present_upazila' => 'required|string|max:100',
            'present_union_ward' => 'required|string|max:100',
            'present_post_office' => 'nullable|string|max:100',
            'present_postal_code' => 'nullable|string|max:10',
            'same_as_present_address' => 'boolean',
            'permanent_address' => 'required_if:same_as_present_address,false|string',
            'permanent_country' => 'required_if:same_as_present_address,false|string|max:100',
            'permanent_district' => 'required_if:same_as_present_address,false|string|max:100',
            'permanent_upazila' => 'required_if:same_as_present_address,false|string|max:100',
            'permanent_union_ward' => 'required_if:same_as_present_address,false|string|max:100',
            'permanent_post_office' => 'nullable|string|max:100',
            'permanent_postal_code' => 'nullable|string|max:10',
            'phone_number' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:100',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_address' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $data = $request->only([
            'present_address', 'present_country', 'present_district',
            'present_upazila', 'present_union_ward', 'present_post_office',
            'present_postal_code', 'same_as_present_address',
            'phone_number', 'alternative_phone', 'email_address',
            'emergency_contact_name', 'emergency_contact_relationship',
            'emergency_contact_phone', 'emergency_contact_address'
        ]);

        if ($request->boolean('same_as_present_address')) {
            $data['permanent_address'] = $data['present_address'];
            $data['permanent_country'] = $data['present_country'];
            $data['permanent_district'] = $data['present_district'];
            $data['permanent_upazila'] = $data['present_upazila'];
            $data['permanent_union_ward'] = $data['present_union_ward'];
            $data['permanent_post_office'] = $data['present_post_office'];
            $data['permanent_postal_code'] = $data['present_postal_code'];
        } else {
            $data = array_merge($data, $request->only([
                'permanent_address', 'permanent_country', 'permanent_district',
                'permanent_upazila', 'permanent_union_ward', 'permanent_post_office',
                'permanent_postal_code'
            ]));
        }

        $kyc->fill($data);
    }

    /**
     * Save step 4 - Document Upload
     */
    private function saveStep4(Request $request, MemberKycVerification $kyc)
    {
        // Build validation rules based on existing documents
        $rules = [
            'document_front_image' => $kyc->document_front_image ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'user_photo' => $kyc->user_photo ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'user_signature' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'utility_bill' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
        ];

        // Handle document back image requirement
        if ($kyc->document_type === 'nid' && $kyc->nid_type === 'smart_card') {
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

        foreach (['document_front_image', 'document_back_image', 'user_photo', 'user_signature', 'utility_bill'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $customSizes = [
                    'original' => ['width' => 800, 'height' => 600],
                    'medium' => ['width' => 400, 'height' => 300],
                    'thumbnail' => ['width' => 200, 'height' => 150]
                ];
                $uploadResult = $this->uploadSingleImage($file, 'kyc/' . $kyc->user_id . '/' . $field, $customSizes);
                $uploadedFiles[$field] = $uploadResult['sizes']['medium']['path'] ?? $uploadResult['sizes']['original']['path'];
            }
        }

        $kyc->fill($uploadedFiles);
    }

    /**
     * Save step 5 - Review & Submit
     */
    private function saveStep5(Request $request, MemberKycVerification $kyc)
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
            'document_type' => 'required|string|in:document_front_image,document_back_image,user_photo,user_signature,utility_bill',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $kyc = MemberKycVerification::forUser($user->id)->firstOrFail();
            
            // Prevent document upload if KYC is verified
            if ($kyc->status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your KYC is already verified. Document uploads are not allowed.',
                ], 403);
            }
            
            $file = $request->file('document');
            $documentType = $request->input('document_type');
            
            $customSizes = [
                'original' => ['width' => 800, 'height' => 600],
                'medium' => ['width' => 400, 'height' => 300],
                'thumbnail' => ['width' => 200, 'height' => 150]
            ];
            $uploadResult = $this->uploadSingleImage($file, 'kyc/' . $user->id . '/' . $documentType, $customSizes);
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
            Log::error('KYC Document Upload Error: ' . $e->getMessage());
            
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
            'document_type' => 'required|string|in:document_front_image,document_back_image,user_photo,user_signature,utility_bill',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $kyc = MemberKycVerification::forUser($user->id)->firstOrFail();
            
            // Prevent document deletion if KYC is verified
            if ($kyc->status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your KYC is already verified. Document changes are not allowed.',
                ], 403);
            }
            
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
            Log::error('KYC Document Delete Error: ' . $e->getMessage());
            
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
        $kyc = MemberKycVerification::forUser($user->id)->first();

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
            'verified_at' => $kyc->verified_at,
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
            $kyc = MemberKycVerification::forUser($user->id)->firstOrFail();

            if (!$kyc->isVerified()) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC must be verified before updating profile',
                ], 422);
            }

            $updated = $kyc->updateProfileFromKyc();

            return response()->json([
                'success' => true,
                'message' => $updated ? 'Profile updated from KYC data' : 'No changes needed',
                'updated' => $updated,
            ]);

        } catch (\Exception $e) {
            Log::error('KYC Profile Update Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Allow limited updates for verified KYC (only contact information)
     */
    public function updateContactInfo(Request $request)
    {
        try {
            $user = Auth::user();
            $kyc = MemberKycVerification::forUser($user->id)->firstOrFail();

            // Only allow contact info updates for verified KYCs
            if ($kyc->status !== 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'This feature is only available for verified KYCs',
                ], 403);
            }

            // Validate only contact information
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|max:20',
                'alternative_phone' => 'nullable|string|max:20',
                'email_address' => 'required|email|max:255',
                'present_address' => 'required|string',
                'emergency_contact_phone' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Update only allowed fields
            $allowedFields = [
                'phone_number',
                'alternative_phone', 
                'email_address',
                'present_address',
                'emergency_contact_phone'
            ];

            $updateData = $request->only($allowedFields);
            
            // Add admin note about the update
            $updateData['admin_notes'] = ($kyc->admin_notes ? $kyc->admin_notes . "\n\n" : '') . 
                                        "Contact information updated by user on " . now()->format('Y-m-d H:i:s');

            $kyc->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Contact information updated successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('KYC Contact Update Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function certificate()
    {
        $user = Auth::user();
        
        if (!$user->memberKyc || $user->memberKyc->status !== 'verified') {
            return redirect()->route('member.kyc.index')
                ->with('error', 'KYC verification required to download certificate.');
        }

        // Generate PDF certificate
        $pdf = Pdf::loadView('member.kyc.certificate', [
            'user' => $user,
            'kyc' => $user->memberKyc
        ]);

        return $pdf->download('kyc-certificate-' . $user->id . '.pdf');
    }

    public function resubmit(Request $request)
    {
        $user = Auth::user();
        
        // Prevent resubmission if KYC is verified
        if ($user->memberKyc && $user->memberKyc->status === 'verified') {
            return back()->with('error', 'Your KYC is already verified. Resubmission is not allowed.');
        }
        
        if (!$user->memberKyc || $user->memberKyc->status !== 'rejected') {
            return back()->with('error', 'You can only resubmit rejected KYC applications.');
        }

        // Reset KYC status to pending for resubmission
        $user->memberKyc->update([
            'status' => 'pending',
            'admin_comment' => null,
            'verified_at' => null,
        ]);

        return redirect()->route('member.kyc.step', 1)
            ->with('success', 'KYC resubmitted successfully. Please review and update your information.');
    }
}
