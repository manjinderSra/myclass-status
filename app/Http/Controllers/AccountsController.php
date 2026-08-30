<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\AccountDetail;


class AccountsController extends Controller
{
    /**
     * Show all accounts for a school
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();

        $accounts = AccountDetail::where('school_id', $schoolId)->get();

        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show form to create account
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a new account
     */
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId();

        $request->validate([
            'account_number' => 'nullable|string|max:50',
            'ifsc'           => 'nullable|string|max:20',
            'name'           => 'nullable|string|max:100',
            'upi_id'         => 'nullable|string|max:100',
            'note'           => 'nullable|string',
        ]);

        AccountDetail::create([
            'school_id'      => $schoolId,
            'account_number' => $request->account_number,
            'ifsc'           => $request->ifsc,
            'name'           => $request->name,
            'upi_id'         => $request->upi_id,
            'note'           => $request->note,
        ]);

        return redirect()->back()->with('success', 'Account added successfully!');
    }

    /**
     * Edit account
     */
    public function edit($id)
    {
        $schoolId = $this->getSchoolId();

        $account = AccountDetail::where('school_id', $schoolId)->findOrFail($id);

        return view('accounts.edit', compact('account'));
    }

    /**
     * Update account
     */
    public function update(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();

        $request->validate([
            'account_number' => 'nullable|string|max:50',
            'ifsc'           => 'nullable|string|max:20',
            'name'           => 'nullable|string|max:100',
            'upi_id'         => 'nullable|string|max:100',
            'note'           => 'nullable|string',
        ]);

        $account = AccountDetail::where('school_id', $schoolId)->findOrFail($id);

        $account->update([
            'account_number' => $request->account_number,
            'ifsc'           => $request->ifsc,
            'name'           => $request->name,
            'upi_id'         => $request->upi_id,
            'note'           => $request->note,
        ]);

        return redirect()->back()->with('success', 'Account updated successfully!');
    }

//    public function show($id)
// {
//     return redirect()->route('school.accountDetail.index');
// }

    /**
     * Delete account
     */
    public function destroy($id)
    {
        $schoolId = $this->getSchoolId();

        $account = AccountDetail::where('school_id', $schoolId)->findOrFail($id);

        $account->delete();

        return redirect()->back()->with('success', 'Account deleted successfully!');
    }

    /**
     * Method to get currently logged-in school ID
     * (Modify based on your project)
     */
    private function getSchoolId()
    {
        $user = auth()->user();

        // If user already has school_id stored
        if (!empty($user->school_id)) {
            return $user->school_id;
        }

        // Otherwise fetch school by admin_id (correct logic for your project)
        $school = \App\Models\School::where('admin_id', $user->id)->first();

        return $school ? $school->id : null;
    }

    public function makeFeatured($id)
    {
        // Set all accounts to not featured
        AccountDetail::query()->update(['is_featured' => false]);

        // Set selected account to featured
        AccountDetail::where('id', $id)->update(['is_featured' => true]);

        return back()->with('success', 'Account marked as featured.');
    }
}
