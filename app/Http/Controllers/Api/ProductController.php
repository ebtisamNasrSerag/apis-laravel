<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\proImage;
use DB;

class ProductController extends Controller
{
	/**
	* get all user products
	* @return \Illuminate\Http\Response
	*/
    public function index()
    {
    	$products = auth()->user()->products;
    	return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    
    /**
	* display  user product by id
	* @param int $id  
	* @return \Illuminate\Http\Response
	*/
    public function show($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $product->toArray()
        ], 400);
    }
    
    /**
	* add  product 
	*  
	* @return \Illuminate\Http\Response
	*/
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'desc' => 'required',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
 
        $product = new Product();
        $product->title = $request->title;
        $product->desc = $request->desc;
        $allImages = null;
        if($request->hasfile('images'))
         {

            foreach($request->file('images') as $image)
            {
                $name= time(). uniqid() .'.'.$image->getClientOriginalExtension();
                $image->move(public_path().'/uploads/products/', $name);
                $allImages .= $allImages == null ? $name : ';' . $name;  
                     
            }
            $product->images = $allImages;
         }
        
        if (auth()->user()->products()->save($product))
        {
        	foreach($request->file('images') as $image)
            {
                $name= time(). uniqid() .'.'.$image->getClientOriginalExtension();

                $product_image = new ProImage();
                $product_image->image = $name;
                $product_image->product_id = $product->id;
                $product_image->save();
                 
                 
            }
            return response()->json([
                'success' => true,
                'data' => $product->toArray(),
            ]);
        }else
            return response()->json([
                'success' => false,
                'message' => 'Product could not be added'
            ], 500);
    }

    /**
	* update  product 
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
    public function update(Request $request, $id)
    {

        $product = auth()->user()->products()->find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
        if($request->title)
            $product->title = $request->title;
        if($request->desc)
            $product->desc = $request->desc;
        // $allImages = null;
        // if($request->hasfile('images'))
        //  {

        //     foreach($request->file('images') as $image)
        //     {
        //         $name= time().'.'.$image->getClientOriginalExtension();
        //         $image->move(public_path().'/uploads/products/', $name);
        //         $allImages .= $allImages == null ? $name : ';' . $name;  
                     
        //     }
        //     $product->images = $allImages;
        //  }

        $updated = auth()->user()->products()->save($product); 
 
        if ($updated)
        {
        	// if($request->hasfile('images'))
        	// {
        	// 	foreach($request->file('images') as $image)
         //    {
         //        $name= time().'.'.$image->getClientOriginalExtension();

         //        $product_image = new ProImage();
         //        $product_image->image = $name;
         //        $product_image->product_id = $product->id;
         //        $product_image->save();
                 
                 
         //    }
        	// }
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully' 
            ]);
        }else
            return response()->json([
                'success' => false,
                'message' => 'Product could not be updated'
            ], 500);
    }
    
    /**
	* delete  product 
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
    public function destroy($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($product->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted'
            ], 500);
        }
    }
}
