<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class ProductManagementController extends Controller
{


    public function index(Request $request)
    {


        $query =
        Product::with([
            'category',
            'stock'
        ]);



        if($request->search){


            $query->where(
                'name',
                'LIKE',
                '%' . $request->search . '%'
            );


        }




        if($request->category_id){


            $query->where(
                'category_id',
                $request->category_id
            );


        }




        $products =
        $query->paginate(
            $request->per_page ?? 50
        );




        return response()->json(
            $products
        );


    }










    public function store(Request $request)
    {



        $request->validate([


            'name' =>
            'required|string|max:255',


            'description' =>
            'required|string',


            'price' =>
            'required|numeric|min:0',


            'category_id' =>
            'required|exists:categories,id',


            'quantity' =>
            'required|integer|min:0',


            'image' =>
            'nullable|file|mimes:jpg,jpeg,png,webp|max:10000'


        ]);






        $imageUrl =
        null;






        if(
            $request->hasFile('image')
        ){



            $path =
            $request
            ->file('image')
            ->store(
                'products',
                'public'
            );



            $imageUrl =
            '/storage/' . $path;



        }









        $product =
        Product::create([



            'name' =>
            $request->name,



            'slug' =>
            Str::slug(
                $request->name
            ),



            'description' =>
            $request->description,



            'short_description' =>
            $request->short_description,



            'price' =>
            $request->price,



            'cost' =>
            $request->cost,



            'category_id' =>
            $request->category_id,



            'sku' =>
            'CD-' . strtoupper(
                Str::random(8)
            ),



            'brand' =>
            $request->brand,



            'image_url' =>
            $imageUrl,



            'is_active' =>
            true,



            'is_featured' =>
            false



        ]);









        Stock::create([



            'product_id' =>
            $product->id,



            'quantity' =>
            $request->quantity,



            'available' =>
            $request->quantity,



            'reorder_level' =>
            10



        ]);








        return response()->json([


            'message' =>
            'Product created successfully',



            'product' =>
            $product



        ],201);



    }












    public function update(Request $request,$productId)
    {


        $product =
        Product::findOrFail(
            $productId
        );






        $request->validate([



            'name' =>
            'sometimes|string|max:255',



            'price' =>
            'sometimes|numeric|min:0',



            'image' =>
            'nullable|file|mimes:jpg,jpeg,png,webp|max:10000'



        ]);







        $data =
        $request->only([


            'name',
            'description',
            'short_description',
            'price',
            'cost',
            'category_id',
            'brand',
            'is_active',
            'is_featured'


        ]);








        if(
            $request->hasFile('image')
        ){



            if(
                $product->image_url
            ){


                $old =
                str_replace(
                    '/storage/',
                    '',
                    $product->image_url
                );



                Storage::disk('public')
                ->delete(
                    $old
                );


            }





            $path =
            $request
            ->file('image')
            ->store(
                'products',
                'public'
            );




            $data['image_url'] =
            '/storage/' . $path;



        }







        $product->update(
            $data
        );






        return response()->json([


            'message' =>
            'Product updated successfully',



            'product' =>
            $product



        ]);



    }













    public function delete($productId)
    {



        $product =
        Product::findOrFail(
            $productId
        );



        $product->delete();




        return response()->json([


            'message' =>
            'Product deleted successfully'


        ]);



    }









    public function restore($productId)
    {



        $product =
        Product::withTrashed()
        ->findOrFail(
            $productId
        );




        $product->restore();




        return response()->json([


            'message' =>
            'Product restored successfully'


        ]);



    }



}