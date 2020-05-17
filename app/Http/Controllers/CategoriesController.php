<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DB;

class CategoriesController extends Controller
{

    protected function validateCategory(Request $request)
    {
        return $request->validate([
            'name'      =>  'required' 
        ]);
    }

    public function index()
    {
        $categories = Category::all();
        return view('category.index',compact('categories'));
    }

    public function store(Request $request)
    {

        $validatedAttribute = $this->validateCategory($request);
        $insert_data = Category::create($validatedAttribute);
        if($insert_data){
            $response = array(
                'status'    =>  'success',
                'data'      => array(
                    'id'   => $insert_data->id,
                    'name' => request('name'),
                    'number' => (DB::table('categories')->count())
                )
                );
            return $response;
        }
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return redirect('/categories');
    }
}
