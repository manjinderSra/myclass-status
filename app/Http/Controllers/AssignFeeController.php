<?php

namespace App\Http\Controllers;

use App\Models\AssignFee;
use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Models\FeeMaster;
use App\Models\Student;
use App\Models\ClassModel; // Assuming you have a Class model
use App\Models\SchoolClass;
use App\Models\Section; // Assuming you have a Section model
use Illuminate\Http\Request;

class AssignFeeController extends Controller
{
    private function getSchoolId()
    {
        $user = auth()->user();

        // If user is a school
        if ($user->role === 'school') {
            $school = \App\Models\School::where('admin_id', $user->id)->first();
            return $school ? $school->id : null;
        }

        return null;
    }

    public function index()
    {
        $schoolId = $this->getSchoolId(); // Make sure this returns logged in school ID

        // Assigned Fees
        $assignFees = AssignFee::with(['feeGroup', 'feeType', 'feeMaster', 'student.class', 'student.section'])
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Fee Groups (school based)
        $feeGroups = FeeGroup::where('school_id', $schoolId)->get();

        // Fee Types (school based)
        $feeTypes = FeeType::where('school_id', $schoolId)
            ->whereNull('deleted_at')
            ->get();

        // Fee Masters (school based)
        $feeMasters = FeeMaster::with(['feeGroup', 'feeType'])
            ->where('school_id', $schoolId)
            ->get();

        // Students (school based)
        $students = Student::with(['class', 'section'])
            ->where('school_id', $schoolId)
            ->get();

        // Classes (school based)
        $classes = SchoolClass::where('school_id', $schoolId)->get();

        // Sections (school based)
        $sections = Section::where('school_id', $schoolId)->get();

        return view('client.schoolPanel.finance.assignFee', compact(
            'assignFees',
            'feeGroups',
            'feeTypes',
            'feeMasters',
            'students',
            'classes',
            'sections'
        ));
    }


    // Store new fee assignment (supports bulk assignment)
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'fees_data' => 'required|array',
            'fees_data.*.fee_group_id' => 'required|exists:fee_groups,id',
            'fees_data.*.fee_type_id' => 'required|exists:fee_types,id',
            'fees_data.*.fee_master_id' => 'required|exists:fee_masters,id',
            'fees_data.*.student_id' => 'required|exists:students,id',
        ]);

        try {
            foreach ($request->fees_data as $feeData) {
                $exists = AssignFee::where('fee_group_id', $feeData['fee_group_id'])
                    ->where('fee_type_id', $feeData['fee_type_id'])
                    ->where('fee_master_id', $feeData['fee_master_id'])
                    ->where('student_id', $feeData['student_id'])
                    ->exists();

                if (!$exists) {
                    AssignFee::create($feeData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Fees assigned successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning fees: ' . $e->getMessage()
            ], 500);
        }
    }


    // Edit - returns data for editing
    public function edit($id)
    {
        $assignFee = AssignFee::with(['feeGroup', 'feeType', 'feeMaster', 'student'])->findOrFail($id);
        // dd($assignFee);
        return response()->json($assignFee);
    }

    // Update assigned fee
    public function update(Request $request, $id)
    {
        $request->validate([
            'fee_group_id' => 'required|exists:fee_groups,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'fee_master_id' => 'required|exists:fee_masters,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric',
        ]);

        try {
            $assignFee = AssignFee::findOrFail($id);
            $assignFee->update($request->only(['fee_group_id', 'fee_type_id', 'fee_master_id', 'student_id', 'amount']));

            return response()->json([
                'success' => true,
                'message' => 'Assigned fee updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating fee: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete assigned fee
    public function destroy($id)
    {
        try {
            $assignFee = AssignFee::findOrFail($id);
            $assignFee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assigned fee deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting fee: ' . $e->getMessage()
            ], 500);
        }
    }

    // API endpoint to get filtered students
    public function getFilteredStudents(Request $request)
    {
        $query = Student::with(['class', 'section']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $students = $query->get();
        return response()->json($students);
    }

    // API endpoint to get filtered fee masters
    public function getFilteredFeeMasters(Request $request)
    {
        $query = FeeMaster::with(['feeGroup', 'feeType']);

        if ($request->fee_group_id) {
            $query->where('fee_group_id', $request->fee_group_id);
        }
        if ($request->fee_type_id) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        $feeMasters = $query->get();
        return response()->json($feeMasters);
    }
}
