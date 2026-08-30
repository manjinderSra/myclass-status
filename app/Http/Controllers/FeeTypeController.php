<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\FeeGroup;
use App\Models\FeeMaster;
use App\Models\FeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FeeTypeController extends Controller
{
    /**
     * Display a listing of the fee types.
     */
    public function index()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all fee groups to populate dropdown
            $feeGroups = FeeGroup::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
            
            // Get all fee types
            $feeTypes = FeeType::where('school_id', $schoolId)
                ->with('feeGroup')
                ->orderBy('name')
                ->get();
                
            return view('client.schoolPanel.finance.feeType', compact('feeGroups', 'feeTypes'));
        } catch (\Exception $e) {
            Log::error('Error showing fee types page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to show fee types page: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created fee type in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'fees_group_id' => 'required|exists:fee_groups,id',
        'fees_type_id' => 'required|exists:fee_types,id',
        'due_date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'fine_type' => 'required|in:None,Fixed,Percentage',
        'fine_amount' => 'nullable|numeric|min:0',
        'status' => 'nullable',
    ]);

    try {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json(['error' => 'School not found for current user.'], 404);
        }

        $feeMaster = \App\Models\FeeMaster::create([
            'school_id'    => $schoolId,
            'fee_group_id' => $request->fees_group_id,
            'fee_type_id'  => $request->fees_type_id,
            'due_date'     => $request->due_date,
            'amount'       => $request->amount,
            'fine_type'    => $request->fine_type,
            'fine_amount'  => $request->fine_type !== 'None' ? ($request->fine_amount ?? 0) : 0,
'status' => $request->boolean('status'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fee Master added successfully.',
            'data' => $feeMaster
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create Fee Master: ' . $e->getMessage()
        ], 500);
    }
}


public function storeFeeType(Request $request)
{
    $validated = $request->validate([
        'fee_group_id' => 'required|exists:fee_groups,id',
        'name'         => 'required|string|max:255',
        'description'  => 'nullable|string',
        'status'       => 'nullable',
    ]);

    $schoolId = $this->getSchoolId();

    if (!$schoolId) {
        return redirect()->back()->with('error', 'School not found for the current user.');
    }

    // Generate unique ID and fee code
    $uniqueId = 'FTY-' . strtoupper(substr(uniqid(), -6)); 
    $feesCode = 'FT-' . strtoupper(substr($request->name, 0, 3)) . '-' . rand(1000, 9999);

    // Create fee type
    \App\Models\FeeType::create([
        'unique_id'    => $uniqueId,
        'school_id'    => $schoolId,
        'fee_group_id' => $request->fee_group_id,
        'name'         => $request->name,
        'fees_code'    => $feesCode,
        'description'  => $request->description,
        'status'       => $request->has('status') ? 1 : 0,
    ]);

    return redirect()->route('fee-types.index')
                     ->with('success', 'Fee Type added successfully.');
}





    /**
     * Update the specified fee type in storage.
     */
 public function updateFeeType(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'edit_name' => 'required|string|max:255',
        'edit_fees_group_id' => 'required|exists:fee_groups,id',
        'edit_description' => 'nullable|string|max:1000',
        'edit_status' => 'nullable|boolean',
    ]);

    // Find the FeeType or fail
    $feeType = FeeType::findOrFail($id);

    // Optional: check if this fee type belongs to the same school
    if ($feeType->school_id != $this->getSchoolId()) {
        return redirect()->back()->with('error', 'Unauthorized access to this fee type');
    }

    // Check for duplicate name within same school
    $existingFeeType = FeeType::where('school_id', $this->getSchoolId())
        ->where('name', $request->edit_name)
        ->where('id', '!=', $id)
        ->first();

    if ($existingFeeType) {
        return redirect()->back()->with('error', 'Another fee type with this name already exists')->withInput();
    }

    // Update the FeeType record
    $feeType->update([
        'name' => $request->edit_name,
        'fees_code' => Str::slug($request->edit_name),
        'fee_group_id' => $request->edit_fees_group_id,
        'description' => $request->edit_description,
        'status' => $request->edit_status ?? false,
    ]);
        return response()->json([
        'success' => true,
        'message' => 'Fee type updated successfully!',
    ]);
}


    /**
     * Remove the specified fee type from storage.
     */
    public function destroy($id)
    {
        // dd($id);
        try {
            Log::info('FeeType delete request received', [
                'fee_type_id' => $id,
                'content_type' => request()->header('Content-Type')
            ]);
            
            $feeType = FeeType::findOrFail($id);
            // dd($feeType);
            // Check if fee type belongs to current school
            if ($feeType->school_id != $this->getSchoolId()) {
                Log::warning('Unauthorized access to fee type for deletion', [
                    'fee_type_id' => $id,
                    'school_id' => $feeType->school_id,
                    'user_school_id' => $this->getSchoolId()
                ]);
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access to fee type'
                    ], 403);
                }
                
                return redirect()->back()->with('error', 'Unauthorized access to fee type');
            }
            
            // Check if fee type is being used
            // TODO: AddLogic to check if fee type is being used in any fee structure or other related tables
            
            // Delete fee type
            $deleted = $feeType->delete();
            
            if ($deleted) {
                Log::info('Fee type deleted successfully', ['fee_type_id' => $id]);
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Fee type deleted successfully'
                    ]);
                }
                
                return redirect()->route('school.feeType')->with('success', 'Fee type deleted successfully');
            } else {
                Log::warning('Failed to delete fee type', ['fee_type_id' => $id]);
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to delete fee type'
                    ], 500);
                }
                
                return redirect()->back()->with('error', 'Failed to delete fee type');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting fee type: ' . $e->getMessage(), [
                'fee_type_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete fee type: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to delete fee type: ' . $e->getMessage());
        }
    }

    /**
     * Get all fee types for the current school (API endpoint).
     */
    public function getAllFeeTypes()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            $feeTypes = FeeType::where('school_id', $schoolId)
                ->with('feeGroup')
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'feeTypes' => $feeTypes
            ]);
        } catch (\Exception $e) {
        Log::error('Error fetching fee types: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fee types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get current school ID.
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        $schoolId = null;
        
        if ($user->role === 'school') {
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        }
        
        return $schoolId;
    }

    /**
     * Simple diagnostic endpoint to check if the controller is working and database is accessible.
     */
    public function diagnostic()
    {
        try {
            $feeGroupsCount = FeeGroup::count();
            $feeTypesCount = FeeType::count();
            $schoolId = $this->getSchoolId();
            
            return response()->json([
                'success' => true,
                'message' => 'Diagnostic successful',
                'data' => [
                    'fee_groups_count' => $feeGroupsCount,
                    'fee_types_count' => $feeTypesCount,
                    'school_id' => $schoolId,
                    'user_id' => auth()->id(),
                    'php_version' => phpversion(),
                    'laravel_version' => app()->version(),
                    'server_time' => now()->format('Y-m-d H:i:s'),
                    'memory_usage' => memory_get_usage(true),
                    'is_database_connected' => DB::connection()->getPdo() ? true : false
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Diagnostic failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * Get fee types by group ID.
     * 
     * @param int $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeeTypesByGroup($groupId)
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Verify fee group belongs to this school
            $feeGroup = FeeGroup::where('id', $groupId)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$feeGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid fee group selected'
                ], 400);
            }
            
            $feeTypes = FeeType::where('school_id', $schoolId)
                ->where('fee_group_id', $groupId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'feeTypes' => $feeTypes
            ]);
        } catch (\Exception $e) {
        Log::error('Error fetching fee types by group: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fee types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active fee types for the current school.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveFeeTypes()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            $feeTypes = FeeType::where('school_id', $schoolId)
                ->where('status', true)
                ->with('feeGroup')
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'feeTypes' => $feeTypes
            ]);
        } catch (\Exception $e) {
        Log::error('Error fetching active fee types: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fee types: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update status of multiple fee types.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'fee_type_ids' => 'required|array',
                'fee_type_ids.*' => 'required|exists:fee_types,id',
                'status' => 'required|boolean',
            ]);
            
            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()->toArray()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', $validator->errors()->first());
            }
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'School not found'
                    ], 404);
                }
                
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Update the status of all selected fee types
            $updateCount = FeeType::whereIn('id', $data['fee_type_ids'])
                ->where('school_id', $schoolId)
                ->update(['status' => $data['status']]);
            
            Log::info('Bulk updated fee types status', [
                'school_id' => $schoolId,
                'count' => $updateCount,
                'status' => $data['status'],
                'fee_type_ids' => $data['fee_type_ids']
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$updateCount} fee types " . ($data['status'] ? 'activated' : 'deactivated') . " successfully",
                    'count' => $updateCount
                ]);
            }
            
            return redirect()->back()->with('success', "{$updateCount} fee types " . ($data['status'] ? 'activated' : 'deactivated') . " successfully");
        } catch (\Exception $e) {
            Log::error('Error bulk updating fee types: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update fee types: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update fee types: ' . $e->getMessage());
        }
    }
    
    /**
     * Export fee types to CSV.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCsv(Request $request)
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all fee types for this school
            $feeTypes = FeeType::where('school_id', $schoolId)
                ->with('feeGroup')
                ->orderBy('name')
                ->get();
            
            if ($feeTypes->isEmpty()) {
                return redirect()->back()->with('error', 'No fee types found to export');
            }
            
            // Create a temporary file
            $filename = 'fee_types_export_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/' . $filename);
            
            // Create the CSV file
            $handle = fopen($filepath, 'w');
            
            // Add headers
            fputcsv($handle, [
                'ID',
                'Unique ID',
                'Name',
                'Fee Code',
                'Fee Group',
                'Description',
                'Status',
                'Created At'
            ]);
            
            // Add data
            foreach ($feeTypes as $feeType) {
                fputcsv($handle, [
                    $feeType->id,
                    $feeType->unique_id,
                    $feeType->name,
                    $feeType->fees_code,
                    $feeType->feeGroup->name ?? 'Unknown',
                    $feeType->description,
                    $feeType->status ? 'Active' : 'Inactive',
                    $feeType->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($handle);
            
            //Log the export
            Log::info('Fee types exported to CSV', [
                'school_id' => $schoolId,
                'count' => $feeTypes->count(),
                'filename' => $filename
            ]);
            
            // Return the file as a download
            return response()->download($filepath, $filename, [
                'Content-Type' => 'text/csv',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting fee types: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to export fee types: ' . $e->getMessage());
        }
    }
    
    /**
     * Import fee types from CSV.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function importCsv(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', $validator->errors()->first());
            }
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all fee groups for this school to validate against
            $feeGroups = FeeGroup::where('school_id', $schoolId)->pluck('id', 'name')->toArray();
            
            if (empty($feeGroups)) {
                return redirect()->back()->with('error', 'No fee groups found. Please create fee groups first.');
            }
            
            // Process the CSV file
            $file = $request->file('csv_file');
            $filepath = $file->getRealPath();
            
            $handle = fopen($filepath, 'r');
            $headers = fgetcsv($handle); // Get headers from first row
            
            // Validate headers
            $requiredHeaders = ['name', 'fee_group', 'description', 'status'];
            $headerIndexes = [];
            
            foreach ($requiredHeaders as $requiredHeader) {
                $index = array_search(strtolower($requiredHeader), array_map('strtolower', $headers));
                if ($index === false) {
                    fclose($handle);
                    return redirect()->back()->with('error', "Required header '{$requiredHeader}' not found in CSV");
                }
                $headerIndexes[$requiredHeader] = $index;
            }
            
            // Start transaction
            DB::beginTransaction();
            
            $importCount = 0;
            $skipCount = 0;
            $row = 2; // Start at row 2 (after headers)
            $errors = [];
            
            while (($data = fgetcsv($handle)) !== false) {
                $name = $data[$headerIndexes['name']] ?? null;
                $feeGroupName = $data[$headerIndexes['fee_group']] ?? null;
                $description = $data[$headerIndexes['description']] ?? null;
                $status = $data[$headerIndexes['status']] ?? null;
                
                // Validate row data
                if (empty($name) || empty($feeGroupName)) {
                    $errors[] = "Row {$row}: Name and Fee Group are required";
                    $row++;
                    $skipCount++;
                    continue;
                }
                
                // Check if fee group exists
                if (!isset($feeGroups[$feeGroupName])) {
                    $errors[] = "Row {$row}: Fee Group '{$feeGroupName}' not found";
                    $row++;
                    $skipCount++;
                    continue;
                }
                
                $feeGroupId = $feeGroups[$feeGroupName];
                
                // Check if fee type already exists
                $exists = FeeType::where('school_id', $schoolId)
                    ->where('name', $name)
                    ->exists();
                
                if ($exists) {
                    $errors[] = "Row {$row}: Fee Type '{$name}' already exists";
                    $row++;
                    $skipCount++;
                    continue;
                }
                
                // Determine status value
                if (is_string($status)) {
                    $status = strtolower($status);
                    $isActive = in_array($status, ['active', 'yes', '1', 'true']);
                } else {
                    $isActive = (bool)$status;
                }
                
                // Generate unique ID
                $uniqueId = 'FT' . rand(10000, 99999);
                while (FeeType::where('unique_id', $uniqueId)->exists()) {
                    $uniqueId = 'FT' . rand(10000, 99999);
                }
                
                // Create fee type
                FeeType::create([
                    'unique_id' => $uniqueId,
                    'school_id' => $schoolId,
                    'fee_group_id' => $feeGroupId,
                    'name' => $name,
                    'fees_code' => Str::slug($name),
                    'description' => $description,
                    'status' => $isActive,
                ]);
                
                $importCount++;
                $row++;
            }
            
            fclose($handle);
            
            // Commit transaction if we imported at least one fee type
            if ($importCount > 0) {
                DB::commit();
                
                Log::info('Fee types imported from CSV', [
                    'school_id' => $schoolId,
                    'imported' => $importCount,
                    'skipped' => $skipCount,
                    'errors' => $errors
                ]);
                
                return redirect()->route('school.feeType')
                    ->with('success', "{$importCount} fee types imported successfully" . ($skipCount > 0 ? ", {$skipCount} skipped" : ""))
                    ->with('import_errors', $errors);
            } else {
                DB::rollBack();
                
                return redirect()->back()
                    ->with('error', 'No fee types imported. Please check the errors.')
                    ->with('import_errors', $errors);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error importing fee types: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to import fee types: ' . $e->getMessage());
        }
    }
    
    
    
    
    public function indexFeeMaster()
{

    $schoolId = $this->getSchoolId();
// dd($schoolId);
    if (!$schoolId) {
        return redirect()->back()->with('error', 'School not found for current user.');
    }
    $result['fees_group'] = DB::table('fee_groups')
        ->where('school_id', $schoolId)
        ->get();

    $result['fees_type'] = DB::table('fee_types')
        ->where('school_id', $schoolId)
        ->get();

    return view('client.schoolPanel.finance.feeMaster', $result);
}

    public function getFeeTypes($groupId)
    {
        $schoolId = $this->getSchoolId();

        $feeTypes = DB::table('fee_types')
            ->where('school_id', $schoolId)
            ->where('fee_group_id', $groupId)
            ->whereNull('deleted_at')
            ->select('id', 'name')
            ->get();

        return response()->json($feeTypes);
    }


     // Return JSON list for DataTable
    public function list(Request $request)
    {
        // dd($request->all());
        // Optionally scope by school_id if your auth provides it:
        $schoolId = $request->user()->school_id ?? null;

        $query = FeeMaster::query();

        if ($schoolId) $query->where('school_id', $schoolId);

        // Eager load related names if relationships exist
        $items = $query->with(['feeGroup','feesType'])->orderBy('id','desc')->get();

        // Transform for frontend
        $data = $items->map(function ($fm) {
            return [
                'id' => $fm->id,
                'uid' => 'FM' . str_pad($fm->id, 5, '0', STR_PAD_LEFT),
                'fees_group_id' => $fm->fees_group_id,
                'fees_group_name' => $fm->feesGroup->name ?? 'Group ' . $fm->fees_group_id,
                'fees_type_id' => $fm->fees_type_id,
                'fees_type_name' => $fm->feesType->name ?? 'Type ' . $fm->fees_type_id,
                'due_date' => $fm->due_date ? $fm->due_date->toDateString() : null,
                'amount' => (float)$fm->amount,
                'fine_type' => $fm->fine_type,
                'fine_amount' => (float)$fm->fine_amount,
                'status' => $fm->status,
                'created_at' => $fm->created_at,
                'updated_at' => $fm->updated_at,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function feeMasterStore(Request $request)
    {
        $schoolId = $request->user()->school_id ?? null;

        $validated = $request->validate([
            'fees_group_id' => 'required|integer',
            'fees_type_id' => 'required|integer',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'fine_type' => ['required', Rule::in(['None','Percentage','Fixed'])],
            'fine_amount' => 'nullable|numeric|min:0',
            'status' => 'required|boolean', 
        ]);

        $validated['school_id'] = $schoolId;

        // If fine_type is None, ensure fine_amount = 0
        if ($validated['fine_type'] === 'None') {
            $validated['fine_amount'] = 0;
        }

        $fm = FeeMaster::create($validated);

        // return created resource
        return response()->json([
            'success' => true,
            'message' => 'Fees master created',
            'data' => [
                'id' => $fm->id,
                'uid' => 'FM' . str_pad($fm->id, 5, '0', STR_PAD_LEFT),
                'fees_group_id' => $fm->fees_group_id,
                'fees_group_name' => $fm->feesGroup->name ?? null,
                'fees_type_id' => $fm->fees_type_id,
                'fees_type_name' => $fm->feesType->name ?? null,
                'due_date' => $fm->due_date->toDateString(),
                'amount' => (float)$fm->amount,
                'fine_type' => $fm->fine_type,
                'fine_amount' => (float)$fm->fine_amount,
                'status' => (int) $fm->status, 
            ],
        ], 201);
    }

   


public function show(FeeMaster $feeMaster)
{
    $feeMaster->load([
        'feeGroup:id,name',
        'feeType:id,name'
    ]);

    // Log::info('FeeMaster show data:', $feeMaster->toArray());

    // echo($feeMaster->fees_group_id);
    // die();
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $feeMaster->id,
            'fees_group_id' => $feeMaster->fees_group_id,
            'fees_type_id' => $feeMaster->fees_type_id,
            'due_date' => $feeMaster->due_date,
            'amount' => $feeMaster->amount,
            'fine_type' => $feeMaster->fine_type,
            'fine_amount' => $feeMaster->fine_amount,
            'status' => $feeMaster->status,
            // 'fee_group' => $feeMaster->feeGroup ? $feeMaster->feeGroup->name : null,
            // 'fee_type' => $feeMaster->feeType ? $feeMaster->feeType->name : null,
        ]
    ]);
}



    public function feeMasterUpdate(Request $request, FeeMaster $feeMaster)
{
    // dd($request->all());
    $validated = $request->validate([
        'fees_group_id' => 'required|integer',
        'fees_type_id' => 'required|integer',
        'due_date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'fine_type' => ['required', Rule::in(['None','Percentage','Fixed'])],
        'fine_amount' => 'nullable|numeric|min:0',
        'status' => 'required|boolean', 
    ]);

    if ($validated['fine_type'] === 'None') {
        $validated['fine_amount'] = 0;
    }

    $feeMaster->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully',
        'data' => [
            'id' => $feeMaster->id,
            'fees_group_id' => $feeMaster->fees_group_id,
            'fees_type_id' => $feeMaster->fees_type_id,
            'due_date' => $feeMaster->due_date->toDateString(),
            'amount' => (float) $feeMaster->amount,
            'fine_type' => $feeMaster->fine_type,
            'fine_amount' => (float) $feeMaster->fine_amount,
            'status' => (int) $feeMaster->status,
        ]
    ]);
}


   public function feeMasterDestroy($id)
{
    $feeMaster = FeeMaster::find($id);

    if (!$feeMaster) {
        return response()->json(['success' => false, 'message' => 'Fee master not found'], 404);
    }

    $feeMaster->delete();

    return response()->json(['success' => true, 'message' => 'Deleted successfully']);
}

} 