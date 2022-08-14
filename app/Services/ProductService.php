<?php

namespace App\Services;

use Illuminate\Http\Response;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        return new ProductCollection($this->productRepository->getAll());
    }

    public function findById($id)
    {
        $product = $this->productRepository->findById($id);

        if (! $product) {
            throw new InvalidArgumentException('Product not found', Response::HTTP_NOT_FOUND);
        }

        if ($product->user_id !== auth()->user()->id) {
            throw new InvalidArgumentException('You are not authorized to access this product', Response::HTTP_UNAUTHORIZED);
        }

        return new ProductResource($product);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->store($request);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            throw new InvalidArgumentException('Unable to store product', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        DB::commit();

        return new ProductResource($product);
    }

    public function update($request, $id)
    {
        $product = $this->productRepository->findById($id);

        if (! $product) {
            throw new InvalidArgumentException('Product not found', Response::HTTP_NOT_FOUND);
        }

        if ($product->user_id !== auth()->user()->id) {
            throw new InvalidArgumentException('You are not authorized to access this product', Response::HTTP_UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $update = $this->productRepository->update($request, $product);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            throw new InvalidArgumentException('Unable to update product', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        DB::commit();

        return new ProductResource($update);
    }

    public function delete($id)
    {
        $product = $this->productRepository->findById($id);

        if (! $product) {
            throw new InvalidArgumentException('Product not found', Response::HTTP_NOT_FOUND);
        }

        if ($product->user_id !== auth()->user()->id) {
            throw new InvalidArgumentException('You are not authorized to access this product', Response::HTTP_UNAUTHORIZED);
        }

        DB::beginTransaction();
        try {
            $delete = $this->productRepository->delete($product);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            throw new InvalidArgumentException('Unable to update product', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        DB::commit();
        return $delete;
    }
}