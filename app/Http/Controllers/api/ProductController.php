<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Respositories\ProductRepository;
use App\Http\Resources\DetailResource;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function show(Request $request)
    {
        $bookDetail = $this->productRepository->getProductById($request -> id);
        return new DetailResource($bookDetail);
    }
}
