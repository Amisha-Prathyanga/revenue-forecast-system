<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('accountManager')->paginate(10); // Fetch customers with their account managers
        return view('customers.customers', compact('customers'));

    }

    public function store(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $customer = new Customer();
        $customer->accMngr_id = auth()->id();
        $customer->client_name = $req->cusName;
        $customer->industry_sector = $req->cusIndustry;
        $customer->controlling_ministry = $req->cusMinistry;
        $customer->ministry_contact = $req->cusMinContact;
        $customer->key_client_contact_name = $req->cusContact;
        $customer->key_client_contact_designation = $req->cusDesignation;
        $customer->key_projects_or_sales_activity = $req->cusProjects;
        $customer->account_servicing_persons_initials = $req->cusAccPersIni;
        $customer->created_at = $todayDate;
        $customer->updated_at = $todayDate;
        $saved = $customer->save();

        Alert::success('Saved!', 'Customer saved Successfully.');
        return redirect()->back();
    }

    public function edit(Request $req)
    {
        $bankCharges = BankCharges::find($req->id);
        return response($bankCharges);
    }

    public function update(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $bnkCharge = BankCharges::find($req->edit_bankCharge_id);

        $amount = str_replace(',', '', $req->editAmount);
        $amount = floatval($amount);

        $bnkCharge->date = $req->editDate;
        $bnkCharge->bank_id = $req->editBank;
        $bnkCharge->reference_number = $req->editRefNo;
        $bnkCharge->charges_id = $req->editChrgType;
        $bnkCharge->description = $req->editDes;
        $bnkCharge->amount = $amount;

        $saved = $bnkCharge->save();

        if($saved){

            $items = BankCharges::where('status', 1)->orderBy('id')->paginate(10);
            Alert::success('Action Success', 'The Bank Charge has been Updated Successfully!');
            return response($items);
        }
    }

    public function destroy(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $deleteBankCharge = DB::table('bank_charges')
        ->where('id', '=', $req->id)
        ->update([
            'status' => 0
        ]);

        return response($deleteBankCharge);
    }
}
