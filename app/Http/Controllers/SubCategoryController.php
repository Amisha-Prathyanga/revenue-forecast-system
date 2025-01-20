<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB; 

class SubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $subCategories = SubCategory::with('category')->where('status', 1)->paginate(10);
   

    return view('subCategories.subCategories', compact('categories', 'subCategories'));

    }

    public function store(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $subCategory = new SubCategory();
        $subCategory->category_id = $req->categoryName;
        $subCategory->name = $req->subCatName;
        $subCategory->created_at = $todayDate;
        $subCategory->updated_at = $todayDate;
        $saved = $subCategory->save();

        Alert::success('Saved!', 'Sub Category saved Successfully.');
        return redirect()->back();
    }

    public function edit(Request $req)
    {
        $subCategories = SubCategory::with('category')->find($req->id);

        
        if ($subCategories) {
            return response()->json([
                'id' => $subCategories->id,
                'name' => $subCategories->name,
                'category_name' => $subCategories->category ? $subCategories->category->name : 'N/A', 
            ]);
        }

        
        return response()->json(['error' => 'SubCategory not found'], 404);
    }

    public function update(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $subCategory = SubCategory::find($req->edit_subCategory_id);

        $subCategory->name = $req->editSubCatName;

        $saved = $subCategory->save();

        if($saved){

            $items = SubCategory::where('status', 1)->orderBy('id')->paginate(10);
            Alert::success('Action Success', 'The Sub Category has been Updated Successfully!');
            return response($items);
        }
    }

    public function destroy(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $deleteSubCategory= DB::table('sub_categories')
        ->where('id', '=', $req->id)
        ->update([
            'status' => 0
        ]);

        return response($deleteSubCategory);
    }
}
