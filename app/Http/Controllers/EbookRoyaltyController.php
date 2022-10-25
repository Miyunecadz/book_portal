<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Models\PodTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EbookRoyaltyController extends Controller
{
    public function index(){
        $author = Author::get();
    return view('royalties.ebook',compact('author'));
       
    }
}
