<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Models\PodTransaction;

class RoyaltyController extends Controller
{
    public function index()
    {
        $books = Book::all();
        $pods = PodTransaction::all();
        
        return view('royalties.pod', [
            'pod_transactions' => PodTransaction::orderBy('author_id', 'ASC')->paginate(10)
        ], compact('books'));
    }
}
