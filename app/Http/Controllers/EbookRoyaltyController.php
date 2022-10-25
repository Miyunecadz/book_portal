<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Models\EbookTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EbookRoyaltyController extends Controller
{
    public function index(){
        $author = Author::get();
        $author = Author::get();
        $ebooktransaction = EbookTransaction ::orderBy('author_id', 'ASC')->paginate(10);
      return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author'));
       
    }
    public function search(Request $request){

    }
}
