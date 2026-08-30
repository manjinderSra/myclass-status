<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\AccountDetail;

class AccountController extends Controller
{
    public function getAccount(Request $request)
    {
        // Simple: only check one by one
        $upi     = $request->upi_id;
        $account = $request->account_number;
        $ifsc    = $request->ifsc;

        $data = AccountDetail::where('is_featured', true)->first();
// dd($data);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}
