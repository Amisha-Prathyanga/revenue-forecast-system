<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerOpportunity;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class CusOpportunityController extends Controller
{
    public function index()
{
    $loggedInUserId = auth()->id();

    $categories = Category::where('status', 1)->get();
    $subCategories = SubCategory::where('status', 1)->get();
    $customers = Customer::where('status', 1)
        ->where('accMngr_id', $loggedInUserId) // Fetch customers for the logged-in user
        ->get();
    $accountManagers = User::all();
    $customerOpportunities = CustomerOpportunity::with(['customer', 'accountManager', 'category', 'subCategory'])
        ->where('status', 1) // Fetch only active opportunities
        ->where('accMngr_id', $loggedInUserId)
        ->paginate(10);

    return view('cusOpportunities.cusOpportunities', compact('categories', 'subCategories', 'customers', 'accountManagers', 'customerOpportunities'));
}


    public function store(Request $req)
    {
        date_default_timezone_set("Asia/Colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $opportunity = new CustomerOpportunity();
        $opportunity->customer_id = $req->customer_id;
        $opportunity->accMngr_id = auth()->id();
        $opportunity->category_id = $req->category_id;
        $opportunity->sub_category_id = $req->sub_category_id;
        $opportunity->date = $req->date;
        $opportunity->revenue = $req->revenue;
        $opportunity->foreign_costs = $req->foreign_costs;
        $opportunity->local_costs = $req->local_costs;
        $opportunity->created_at = $todayDate;
        $opportunity->updated_at = $todayDate;

        $opportunity->save();

        Alert::success('Saved!', 'Customer Opportunity saved successfully.');
        return redirect()->back();
    }

    public function edit(Request $req)
    {
        $opportunity = CustomerOpportunity::with(['customer', 'accountManager', 'category', 'subCategory'])->find($req->id);

        if ($opportunity) {
            return response()->json([
                'id' => $opportunity->id,
                'customer_id' => $opportunity->customer_id,
                'accMngr_id' => $opportunity->accMngr_id,
                'category_id' => $opportunity->category_id,
                'sub_category_id' => $opportunity->sub_category_id,
                'date' => $opportunity->date,
                'revenue' => $opportunity->revenue,
                'foreign_costs' => $opportunity->foreign_costs,
                'local_costs' => $opportunity->local_costs,
                'customer_name' => $opportunity->customer ? $opportunity->customer->client_name : 'N/A',
                'account_manager_name' => $opportunity->accountManager ? $opportunity->accountManager->name : 'N/A',
                'category_name' => $opportunity->category ? $opportunity->category->name : 'N/A',
                'sub_category_name' => $opportunity->subCategory ? $opportunity->subCategory->name : 'N/A',
            ]);
        }

        return response()->json(['error' => 'Opportunity not found'], 404);
    }

    public function update(Request $req)
{
    try {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        date_default_timezone_set("Asia/Colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $opportunity = CustomerOpportunity::find($req->edit_opportunity_id);
        
        if (!$opportunity) {
            return response()->json(['error' => 'Opportunity not found'], 404);
        }

        $opportunity->customer_id = $req->customer_id;
        $opportunity->accMngr_id = $req->accMngr_id;
        $opportunity->category_id = $req->category_id;
        $opportunity->sub_category_id = $req->sub_category_id;
        $opportunity->date = $req->date;
        $opportunity->revenue = $req->revenue;
        $opportunity->foreign_costs = $req->foreign_costs;
        $opportunity->local_costs = $req->local_costs;
        $opportunity->updated_at = $todayDate;

        $opportunity->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer Opportunity updated successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy(Request $req)
{
    date_default_timezone_set("Asia/Colombo");

    $opportunity = CustomerOpportunity::find($req->id);
    if ($opportunity) {
        $opportunity->status = 0; // Set the status to 0 instead of deleting
        $opportunity->save(); // Update the record
        return response()->json(['success' => 'Customer Opportunity status set to inactive']);
    }

    return response()->json(['error' => 'Opportunity not found'], 404);
}



    public function getSubcategories(Request $request)
    {
        $subcategories = SubCategory::where('category_id', $request->category_id)
            ->where('status', 1) // If you only want active subcategories
            ->get();

        return response()->json($subcategories);
    }

    public function getUniqueSubcategories(Request $request) {
        $categoryId = $request->input('category_id');
        $subCategories = SubCategory::where('category_id', $categoryId)->get();
    
        return response()->json($subCategories);
    }
    

}
