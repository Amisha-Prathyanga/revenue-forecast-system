<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB; 

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->paginate(10);
   

    return view('categories.category', compact('categories'));

    }

    public function store(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $category = new Category();
        $category->name = $req->catName;
        $category->created_at = $todayDate;
        $category->updated_at = $todayDate;
        $saved = $category->save();

        Alert::success('Saved!', 'Category saved Successfully.');
        return redirect()->back();
    }

    public function edit(Request $req)
    {
        $categories = Category::find($req->id);
        return response($categories);
    }

    public function update(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $category = Category::find($req->edit_category_id);

        $category->name = $req->editCatName;

        $saved = $category->save();

        if($saved){

            $items = Category::where('status', 1)->orderBy('id')->paginate(10);
            Alert::success('Action Success', 'The Category has been Updated Successfully!');
            return response($items);
        }
    }

    public function destroy(Request $req)
    {
        date_default_timezone_set("Asia/colombo");
        $todayDate = date('Y-m-d h:i:sa');

        $deleteCategory= DB::table('categories')
        ->where('id', '=', $req->id)
        ->update([
            'status' => 0
        ]);

        return response($deleteCategory);
    }
}
