<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getAll()
    {
        $products = $this->productService->getAll();

        return response()->json([
            'message' => 'data retrieved successfully',
            'data'    => $products
        ]);
    }

    public function find($id)
    {
        try {
            $product = $this->productService->findById($id);

            return response()->json([
                'message' => 'data retrieved successfully',
                'data'    => $product
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function store(StoreProductRequest $request)
    {   
        try {
            $product = $this->productService->store($request);

            return response()->json([
                'message' => 'data stored successfully',
                'data'    => $product
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {   
        try {
            $product = $this->productService->update($request, $id);

            return response()->json([
                'message' => 'data updated successfully',
                'data'    => $product
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function delete($id)
    {
        try {
            $this->productService->delete($id);

            return response()->json([
                'message' => 'data deleted successfully'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
