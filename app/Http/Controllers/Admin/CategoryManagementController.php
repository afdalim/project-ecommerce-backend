<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryManagementController extends Controller
{


    public function index()
    {


        $categories =
        Category::orderBy(
            'id',
            'desc'
        )
        ->get();



        return response()->json([

            'data' =>
            $categories

        ]);


    }







    public function store(Request $request)
    {


        $request->validate([

            'name' =>
            'required|string|max:255',

            'description' =>
            'nullable|string'

        ]);




        $category =
        Category::create([

            'name' =>
            $request->name,


            'slug' =>
            Str::slug(
                $request->name
            ),


            'description' =>
            $request->description,


            'is_active' =>
            true

        ]);





        return response()->json([

            'message' =>
            'Category created',


            'category' =>
            $category

        ],201);


    }








    public function update(Request $request,$id)
    {


        $category =
        Category::findOrFail($id);




        $category->update([


            'name' =>
            $request->name,


            'slug' =>
            Str::slug(
                $request->name
            ),


            'description' =>
            $request->description


        ]);





        return response()->json([

            'message' =>
            'Category updated',

            'category' =>
            $category

        ]);


    }









    public function delete($id)
    {


        $category =
        Category::findOrFail($id);



        $category->delete();




        return response()->json([

            'message' =>
            'Category deleted'

        ]);


    }


}