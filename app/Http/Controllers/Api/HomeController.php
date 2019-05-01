<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Favorite;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
	* get all products
	* @return \Illuminate\Http\Response
	*/
    public function index()
    {
    	$products = Product::all();
    	return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
	* display product description by id
	* @param int $id  
	* @return \Illuminate\Http\Response
	*/
    public function show_desc($id)
    {
        $product = Product::find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'Description' => $product->desc
        ], 400);
    }

    /**
	* add  product to favorite 
	*  
	* @return \Illuminate\Http\Response
	*/
    public function add_to_favorite(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
        ]);
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
        $user_favorite = DB::table('favorites')
						    ->where('user_id', '=', Auth::user()->id)
						    ->where('product_id', '=', $request->product_id)
						    ->first();
        
	    if (is_null($user_favorite))
	    {
	        $favorite = new Favorite();
	        $favorite->product_id = $request->product_id;
	        $favorite_added = auth()->user()->products()->save($favorite);
	        if ($favorite_added)
	            return response()->json([
	                'success' => true,
	                'message' => 'Product added to Favorite successfully',
	                'Product data' => $product->toArray(),
	            ]);
	        else
	            return response()->json([
	                'success' => false,
	                'message' => 'Product could not be add to Favorites'
	            ], 500);
	    }else
	    {
	    	DB::table('favorites')
						    ->where('user_id', '=', Auth::user()->id)
						    ->where('product_id', '=', $request->product_id)
						    ->delete();
	    	 return response()->json([
                'success' => true,
                'message' => 'Product removed from Favorites'
            ]);
	    }
    }

    /**
	* display  user favorites by id
	*  
	* @return \Illuminate\Http\Response
	*/
    public function user_favorite()
    {
        $favorites = auth()->user()->favorites()->get();
 
        if (!$favorites) {
            return response()->json([
                'success' => false,
                'message' => 'there is not products in favorites'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $favorites->toArray()
        ], 400);
    }
}
