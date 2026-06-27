<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;


class ProductController extends Controller
{


public function index(Request $request)
{


    $query =
    Product::with([
        'stock',
        'category'
    ]);



    // SEARCH PRODUCT

    if($request->search){


        $query->where(

            'name',

            'LIKE',

            '%' . $request->search . '%'

        );


    }





   $products =
$query
->with([
    'stock',
    'category'
])
->where(
    'is_active',
    true
)
->latest()
->paginate(50);





    return response()->json([


        'data' =>
        $products->items(),



        'meta' => [

            'current_page' =>
            $products->currentPage(),


            'last_page' =>
            $products->lastPage()

        ]


    ]);



}










public function show($id)
{


    $product =
    Product::with([

        'category',

        'stock',

        'reviews.user'

    ])
    ->findOrFail($id);



    return response()->json(

        $product

    );


}









public function featured()
{
    $products = Product::with([
        'stock'
    ])
    ->where('is_active', true)
    ->orderBy('rating', 'desc')
    ->limit(4)
    ->get();

    return response()->json([
        'data' => $products
    ]);
}










public function reviews($productId)
{


    $reviews =
    Review::where(

        'product_id',

        $productId

    )


    ->with(
        'user'
    )


    ->recent()


    ->paginate(10);





    return response()->json(

        $reviews

    );


}









public function addReview(Request $request, $productId)
{


    $request->validate([


        'rating' =>
        'required|integer|min:1|max:5',



        'comment' =>
        'required|string|min:5',


    ]);





    $product =
    Product::findOrFail(

        $productId

    );





    $review =
    Review::updateOrCreate(


        [

            'product_id' =>
            $productId,


            'user_id' =>
            auth()->id()

        ],




        [


            'rating' =>
            $request->rating,


            'comment' =>
            $request->comment,


        ]


    );





    $product->updateRating(

        $request->rating

    );





    return response()->json([


        'message' =>
        'Review added successfully',



        'review' =>
        $review


    ],201);



}



}