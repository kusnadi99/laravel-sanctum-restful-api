<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll()
    {
        return $this->product->select('id', 'name', 'description', 'price', 'quantity')->get();
    }

    public function findById($id)
    {
        return $this->product->find($id);
    }

    public function store($request)
    {
        $product = new $this->product;

        $product->user_id     = auth()->user()->id;
        $product->name        = $request->name;
        $product->description = $request->description;
        $product->price       = $request->price;
        $product->quantity    = $request->quantity;

        $product->save();

        return $product;
    }

    public function update($request, $product)
    {
        $product->name        = $request->name;
        $product->description = $request->description;
        $product->price       = $request->price;
        $product->quantity    = $request->quantity;
        
        $product->save();
        
        return $product;
    }

    public function delete($product)
    {
        return $product->delete();
    }
}