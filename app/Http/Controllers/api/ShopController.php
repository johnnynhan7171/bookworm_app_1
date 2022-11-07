<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FilterRequest;
use App\Respositories\ShopRepository;
use App\Http\Resources\BookCollection;
use App\Http\Resources\FilterResource;

class ShopController extends Controller
{
    private ShopRepository $shopRepository;
    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $request)
    {
        $queryParamsArr = $this->shopRepository->filterQueryParams($request);
        $products = $this->shopRepository->filterProducts(...$queryParamsArr);
        return new BookCollection($products);
    }

    public function getListFiltering(){
        return new FilterResource([]);
    }
}
