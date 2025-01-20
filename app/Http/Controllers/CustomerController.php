<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB; 

class CustomerController extends Controller
{
    public function index()
    {
        
    $user = auth()->user();
    
    
    if ($user->usertype === 'accMngr') {
        
        $customers = Customer::with('accountManager')
                             ->where('status', 1)
                             ->where('accMngr_id', $user->id) 
                             ->paginate(10);
    } else {
        
        $customers = Customer::with('accountManager')
                             ->where('status', 1)
                             ->paginate(10);
    }

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
        $customers = Customer::find($req->id);
        return response($customers);
    }

    public function update(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $customer = Customer::find($req->edit_customer_id);

        $customer->client_name = $req->editCusName;
        $customer->industry_sector = $req->editCusIndustry;
        $customer->controlling_ministry = $req->editCusMinistry;
        $customer->ministry_contact = $req->editCusMinContact;
        $customer->key_client_contact_name = $req->editCusContact;
        $customer->key_client_contact_designation = $req->editCusDesignation;
        $customer->key_projects_or_sales_activity = $req->editCusProjects;
        $customer->account_servicing_persons_initials = $req->editCusAccPersIni;

        $saved = $customer->save();

        if($saved){

            $items = Customer::with('accountManager')->where('status', 1)->orderBy('id')->paginate(10);
            Alert::success('Action Success', 'The Customer has been Updated Successfully!');
            return response($items);
        }
    }

    public function destroy(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $deleteCustomer= DB::table('customers')
        ->where('id', '=', $req->id)
        ->update([
            'status' => 0
        ]);

        return response($deleteCustomer);
    }
}
