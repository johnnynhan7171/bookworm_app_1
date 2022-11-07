<?php
namespace App\Respositories;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Repositories\BaseRepository;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\DB;

class BookRespository 
{
    public function getAll(){
        
        return new BookCollection(Book::all());
    }

    public function getById(BookRequest $request){
        return new BookResource(Book::find($request -> id));
    }

    public function getOnSale(){
        $listBooks = Book::select('book.*')
            -> leftjoin('discount as d', 'book.id', '=', 'd.book_id')
            -> where('d.discount_start_date', '<=', now())
            -> where(function($query){
                $query -> where('d.discount_end_date', '>=', now())
                    -> orWhereNull('d.discount_end_date');
            })
            -> get();
        return new BookCollection($listBooks);
    }

    public function getPopular(){
        
        $listBooks = Book::select(
                        'book.*', 
                        DB::raw('count(review.id) as total_review'), 
                        DB::raw('case
                                    when now() >= discount.discount_start_date and (now() <=discount.discount_end_date or discount.discount_end_date is null) then discount.discount_price
                                    else book.book_price
                                end as final_price'))
            -> leftjoin('review', 'book.id', '=', 'review.book_id')
            -> leftjoin('discount', 'book.id', '=', 'discount.book_id')
            -> groupBy('book.id', 'discount.discount_start_date', 'discount.discount_end_date', 'discount.discount_price')
            -> orderBy('total_review', 'desc')
            -> orderBy('final_price', 'asc')
            -> limit(8)
            -> get();
        return new BookCollection($listBooks);
    }

    public function getRecommended(){
        $listBooks = Book::select(
                        'book.*', 
                        DB::raw('case when avg(review.rating_start) is null then 0 else avg(review.rating_start) end as avg_rating_star'), 
                        DB::raw('case
                                    when now() >= discount.discount_start_date and (now() <=discount.discount_end_date or discount.discount_end_date is null) then discount.discount_price
                                    else book.book_price
                                end as final_price'))
            -> leftjoin('review', 'book.id', '=', 'review.book_id')
            -> leftjoin('discount', 'book.id', '=', 'discount.book_id')
            -> groupBy('book.id', 'discount.discount_start_date', 'discount.discount_end_date', 'discount.discount_price')
            -> orderBy('avg_rating_star', 'desc')
            -> orderBy('final_price', 'asc')
            -> limit(8)
            -> get();
        return new BookCollection($listBooks);
    }

    public function getFinalPrice($query){
        return $query -> leftjoin('discount', 'book.id', '=', 'discount.book_id')
                      -> selectRaw('case
                                    when now() >= discount.discount_start_date 
                                    and (now() <= discount.discount_end_date or discount.discount_end_date is null) 
                                    then discount.discount_price
                                    else book.book_price
                                end as final_price')
                      -> groupBy('discount.discount_start_date', 'discount.discount_end_date', 'discount.discount_price');
    }

    public function getSubPrice($query){
        return $query -> leftjoin('discount', 'book.id', '=', 'discount.book_id')
                      -> selectRaw('case
                                    when now() >= discount.discount_start_date 
                                    and (now() <= discount.discount_end_date or discount.discount_end_date is null) 
                                    then book.book_price - discount.discount_price
                                    else 0
                                end as sub_price')
                      -> groupBy('discount.discount_start_date', 'discount.discount_end_date', 'discount.discount_price');
    }
    
}