<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Models\FeeMaster;
use App\Models\Student;
use App\Models\Section;
use App\Models\AssignFee;
use App\Models\CollectFee;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CollectFeeController extends Controller
{
    /**
     * Get school ID from authenticated user
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
     * Display a listing of the resource (Collect Fees overview).
     */
    public function index(Request $request)
    {
        $school = $this->getSchoolId();

        $feeGroups = FeeGroup::where('school_id', $school)->get();
        $feeTypes = FeeType::where('school_id', $school)->get();
        $feeMasters = FeeMaster::with(['feeGroup', 'feeType'])
            ->where('school_id', $school)
            ->get();
        $students = Student::with(['class', 'section'])
            ->where('school_id', $school)
            ->get();
        $classes = SchoolClass::where('school_id', $school)->get();
        $sections = Section::where('school_id', $school)->get();

        // Build the query with filters
        $query = AssignFee::with([
            'student.class',
            'student.section',
            'feeGroup',
            'feeType',
            'feeMaster',
            'collectFee'
        ])
            ->whereHas('student', function ($q) use ($school) {
                $q->where('school_id', $school);
            });

        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                    ->orWhere('roll_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Apply class filter
        if ($request->has('class_id') && $request->class_id != '') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        // Apply section filter
        if ($request->has('section_id') && $request->section_id != '') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        // Apply date range filter
        if ($request->has('date_range') && $request->date_range != '') {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                    $query->whereHas('collectFee', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('collection_date', [$startDate, $endDate]);
                    });
                } catch (\Exception $e) {
                    // If date parsing fails, ignore the filter
                    \Log::warning('Date range parsing failed: ' . $e->getMessage());
                }
            }
        }

        $assignFees = $query->latest()->paginate(10);

        $assignFees->getCollection()->transform(function ($fee) {
            $total = (float) ($fee->feeMaster->amount ?? 0);
            $paid = (float) \App\Models\CollectFee::where('assign_fee_id', $fee->id)->sum('paid_amount');

            $balance = $total - $paid;
            if ($balance < 0) $balance = 0;

            if ($paid <= 0) {
                $status = 'unpaid';
            } elseif ($paid < $total) {
                $status = 'pending';
            } else {
                $status = 'paid';
            }
            $fee->status = $status;

            $lastCollect = \App\Models\CollectFee::where('assign_fee_id', $fee->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $fee->collectFee = (object) [
                'paid_amount' => $lastCollect->paid_amount ?? $paid,
                'total_paid' => $paid,
                'balance' => $balance,
                'collection_date' => $lastCollect->collection_date ?? null,
                'payment_type' => $lastCollect->payment_type ?? null,
                'payment_reference_no' => $lastCollect->payment_reference_no ?? null,
                'note' => $lastCollect->note ?? null,
                'status' => $status,
            ];
            return $fee;
        });

        return view('client.schoolPanel.finance.collectFee', compact(
            'feeGroups',
            'feeTypes',
            'feeMasters',
            'students',
            'classes',
            'sections',
            'assignFees'
        ));
    }


    /**
     * Process fee payment (NEW METHOD)
     */
    public function payFee(Request $request, $feeId)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }

        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'collection_date' => 'nullable|date',
            'payment_type' => 'nullable|in:cash,upi,cheque,bank_transfer',
            'payment_reference_no' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Find the assigned fee
            $assignFee = AssignFee::with(['student', 'feeMaster'])
                ->whereHas('student', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->findOrFail($feeId);

            $paidAmount = $request->amount;
            $totalFeeAmount = $assignFee->feeMaster->amount ?? 0;

            // Get previously paid amount from collect_fees table
            $previouslyPaid = CollectFee::where('assign_fee_id', $assignFee->id)
                ->sum('paid_amount');

            $totalPaid = $previouslyPaid + $paidAmount;

            // Validate amount doesn't exceed total
            if ($totalPaid > $totalFeeAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds total fee amount'
                ], 422);
            }

            $balance = $totalFeeAmount - $totalPaid;

            // Determine status
            $status = 'unpaid';
            if ($totalPaid >= $totalFeeAmount) {
                $status = 'paid';
            } elseif ($totalPaid > 0) {
                $status = 'pending';
            }

            // Create collect fee record with ALL fields
            $collectFee = new CollectFee();
            $collectFee->assign_fee_id = $assignFee->id;
            $collectFee->paid_amount = $paidAmount;
            $collectFee->balance = $balance;
            $collectFee->collection_date = $request->collection_date;
            $collectFee->payment_type = $request->payment_type;
            $collectFee->payment_reference_no = $request->payment_reference_no;
            $collectFee->note = $request->note;
            $collectFee->status = $status;
            $collectFee->save();

            // Update assign fee status
            $assignFee->status = $status;
            $assignFee->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment collected successfully',
                'data' => [
                    'collect_fee_id' => $collectFee->id,
                    'total_paid' => $totalPaid,
                    'balance' => $balance,
                    'status' => $status
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error collecting fee: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to collect fee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter Fee Masters based on Group and Type (for the "Assign New Fees" modal).
     */
    public function filterFeeMasters(Request $request)
    {
        $schoolId = $this->getSchoolId();
        $query = FeeMaster::with(['feeGroup', 'feeType'])
            ->where('school_id', $schoolId);

        if ($request->fee_group_id) {
            $query->where('fee_group_id', $request->fee_group_id);
        }

        if ($request->fee_type_id) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        return response()->json($query->get());
    }

    /**
     * Filter Students based on Class, Section, Gender, and Category (for the "Assign New Fees" modal).
     */
    public function filterStudents(Request $request)
    {
        $schoolId = $this->getSchoolId();
        $query = Student::with(['class', 'section'])
            ->where('school_id', $schoolId);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->gender && $request->gender !== 'Select') {
            $query->where('gender', $request->gender);
        }
        if ($request->category && $request->category !== 'Select') {
            $query->where('category', $request->category);
        }

        // Return a collection of students as JSON
        $students = $query->get()->map(function ($student) {
            $student->name = $student->first_name . ' ' . $student->last_name;
            return $student;
        });

        return response()->json($students);
    }

    /**
     * Store a newly created assigned fee in storage.
     */
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId();
        $feesData = $request->json('fees_data');

        if (empty($feesData)) {
            return response()->json(['success' => false, 'message' => 'No data provided for assignment.'], 400);
        }

        // Use a transaction to ensure all assignments succeed or none do
        DB::beginTransaction();
        $assignmentsCreated = 0;

        try {
            foreach ($feesData as $data) {
                // Ensure fee master and student belong to the school (basic validation)
                $feeMaster = FeeMaster::where('id', $data['fee_master_id'])->where('school_id', $schoolId)->first();
                $student = Student::where('id', $data['student_id'])->where('school_id', $schoolId)->first();

                if ($feeMaster && $student) {
                    // Prevent duplicates: Check if the fee is already assigned to the student
                    $existingAssignment = AssignFee::where('student_id', $data['student_id'])
                        ->where('fee_master_id', $data['fee_master_id'])
                        ->first();

                    if (!$existingAssignment) {
                        AssignFee::create([
                            'school_id' => $schoolId,
                            'student_id' => $data['student_id'],
                            'fee_group_id' => $data['fee_group_id'],
                            'fee_type_id' => $data['fee_type_id'],
                            'fee_master_id' => $data['fee_master_id'],
                            'amount' => $feeMaster->amount,
                            'status' => 'unpaid', // Set initial status
                        ]);
                        $assignmentsCreated++;
                    }
                }
            }

            DB::commit();

            if ($assignmentsCreated > 0) {
                return response()->json(['success' => true, 'message' => "$assignmentsCreated new fee assignments created successfully."], 201);
            } else {
                return response()->json(['success' => false, 'message' => 'All selected fees were already assigned.'], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fee Assignment Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to assign fees due to a server error.'], 500);
        }
    }

    /**
     * Show the form for editing the specified assigned fee.
     */
    public function edit($id)
    {
        $schoolId = $this->getSchoolId();

        // Find the assigned fee and ensure it belongs to the school
        $assignFee = AssignFee::with(['student', 'feeGroup', 'feeType', 'feeMaster'])
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();

        if (!$assignFee) {
            return response()->json(['error' => 'Assignment not found.'], 404);
        }

        return response()->json($assignFee);
    }

    /**
     * Update the specified assigned fee in storage.
     */
    public function update(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $assignFee = AssignFee::where('id', $id)
            ->where('school_id', $schoolId)
            ->first();

        if (!$assignFee) {
            return response()->json(['success' => false, 'message' => 'Assigned fee not found.'], 404);
        }

        $assignFee->amount = $request->amount;
        $assignFee->save();

        return response()->json(['success' => true, 'message' => 'Assigned fee updated successfully.']);
    }

    /**
     * Remove the specified assigned fee from storage.
     */
    public function destroy($id)
    {
        $schoolId = $this->getSchoolId();

        $deletedCount = AssignFee::where('id', $id)
            ->where('school_id', $schoolId)
            ->delete();

        if ($deletedCount) {
            return response()->json(['success' => true, 'message' => 'Fee assignment deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Fee assignment not found or already deleted.'], 404);
        }
    }
}
