<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Bank;
use App\Models\BankToBankTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BankTransferController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        return view('pos.bank.bank_to_bank_transfer.bank_transfer', compact('banks'));
    }
    public function storebankTransfer(Request $request)
    {
        // dd( $request->all());
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'from' => 'required',
                'to' => 'required',
                'amount' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validator->messages()
                ], 422);
            }

            /////from/////

            $oldBalanceFrom = AccountTransaction::where('account_id', $request->from)->latest('created_at')->first();
            if ($oldBalanceFrom != null) {
                if ($oldBalanceFrom->balance > 0 && $oldBalanceFrom->balance >= $request->amount) {
                    $bankTransfer = new BankToBankTransfer();
                    $bankTransfer->branch_id = Auth::user()->branch_id;
                    $bankTransfer->from = $request->from;
                    $bankTransfer->to = $request->to;
                    $bankTransfer->amount = $request->amount;
                    $bankTransfer->transfer_date = $request->date;
                    $bankTransfer->description = $request->description;
                    if ($request->image) {
                        $imageName = rand() . '.' . $request->image->extension();
                        $request->image->move(public_path('uploads/bank_transfer/'), $imageName);
                        $bankTransfer->image = $imageName;
                    }
                    $bankTransfer->save();

                    $accountTransaction = new AccountTransaction;
                    $accountTransaction->branch_id =  Auth::user()->branch_id;
                    $accountTransaction->purpose =  'from bank to bank transfer';
                    $accountTransaction->account_id =  $request->from;
                    $accountTransaction->debit = $request->amount;
                    $accountTransaction->balance = $oldBalanceFrom->balance - $request->amount;
                    $accountTransaction->created_at = Carbon::now();
                    $accountTransaction->save();

                    ////////To////////
                    $oldBalanceTo = AccountTransaction::where('account_id', $request->to)->latest('created_at')->first();
                    $accountTransaction = new AccountTransaction;
                    $accountTransaction->branch_id =  Auth::user()->branch_id;
                    $accountTransaction->purpose =  'to bank to bank transfer';
                    $accountTransaction->account_id =  $request->to;
                    $accountTransaction->credit = $request->amount;
                    $accountTransaction->balance = $oldBalanceTo->balance + $request->amount;
                    $accountTransaction->created_at = Carbon::now();
                    $accountTransaction->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Bank to Bank Transfer Successfully Completed.',
                    ]);
                } else {
                    return response()->json([
                        'status' => 405,
                        'errormessage' => 'Not Enough Balance in this Account. Please choose Another Account',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 405,
                    'errormessage' => 'Please Add Balance to Account or Deposit Account Balance',
                ]);
            }
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error saving bank transfer: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'error' => 'An internal server error occurred. Please try again later.'
            ], 500);
        }
    }
    public function view()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $banks = BankToBankTransfer::with(['fromBank', 'toBank'])->get();
        } else {
            $banks = BankToBankTransfer::where('branch_id', Auth::user()->branch_id)
                ->with(['fromBank', 'toBank']) // Use the correct relationship names
                ->latest()
                ->get();
        }
        return response()->json([
            "status" => 200,
            "data" => $banks,
        ]);
    }
}
