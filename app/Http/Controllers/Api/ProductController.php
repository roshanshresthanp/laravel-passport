<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ProductNotFound;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductResource;
use App\Models\User;
use App\Models\Product;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response([
            'data'=>ProductResource::collection($products)]);
        // return response(['message'=>'sasasa']);
        // return response()->json(['message'=>$products]);

    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
   
        if($validator->fails()){
            return response(['message'=>'validation failed']);       
        }
   
        $product = Product::create($input);
        return response(['data' => new ProductResource($product),'message'=>'Product addded successfully']);
        // return response(['data' =>new ProductResource($product)],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->ProductCheck($id);
        $product = Product::find($id);
        return response(new ProductResource($product));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->ProductCheck($id);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->ProductCheck($id);
        $product = Product::find($id);
        $product->update($request->all());

        return response(['data'=>new ProductResource($product),'message'=>'Product updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->ProductCheck($id);
        Product::find($id)->delete();
        return response(['message'=>'Record deleted successfully']);

    }

    public function ProductCheck($id){
        
        if(is_null(Product::find($id))){
            throw new ProductNotFound;
        }
    }

}
