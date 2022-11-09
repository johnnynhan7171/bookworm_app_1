<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Respositories\BookRespository;
use App\Models\Book;
use App\Http\Requests\BookRequest;
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private BookRespository $bookRepository;

    public function __construct(BookRespository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getListBooks()
    {
        $books = $this->bookRepository->getAll();

        return response()->json($books);
    }

    public function getBook(BookRequest $request)
    {
        //get book by id
        $book = $this->bookRepository->getById($request->id);
        return response()->json($book);
    }
    public function getOnSale(){
        $books = $this->bookRepository->getOnSale();
        return response()->json(new BookCollection($books), 200);
    }

    public function getPopular(){
        $books = $this->bookRepository->getPopular();
        return response()->json(new BookCollection($books), 200);
    }

    public function getRecommended(){
        $books = $this->bookRepository->getRecommended();
        return response()->json(new BookCollection($books), 200);
    }
    public function getFeatured(){
        $books = [
            'popular' => new BookCollection($this->bookRepository->getPopular()),
            'recommended' => new BookCollection($this->bookRepository->getRecommended())
        ];
        return response() -> json($books);
    }
}
