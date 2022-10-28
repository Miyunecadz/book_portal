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
        if($request->author_id == 'all'){
            $author = Author::all();
            $ebooktransaction = EbookTransaction ::orderBy('author_id', 'ASC')->paginate(10);
            return view('royalties.ebook',['ebook_transactions' => $ebooktransaction,],compact('author'));
        }else{
            $author = Author::all();
            return view('royalties.ebook',['ebook_transactions' => EbookTransaction::where('author_id',$request->author_id)->orderBy('author_id', 'ASC')->paginate(10)], compact('author'));
        }
    }
    public function sort(Request $request){
        switch($request->sort){
            case 'ASC':
                $author = Author::orderBy('firstname' ,'ASC')->orderBy('lastname' , 'ASC');
                        
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('book_id','ASC')->paginate(10)
                ], compact('author'));
            break;
            case 'DESC':
                $author = Author::orderBy('firstname' ,'DESC')->orderBy('lastname' , 'DESC');
                
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('book_id','DESC')->paginate(10)
                ], compact('author'));
            break;
            case 'EASC':
                $author = Author::orderBy('firstname' ,'ASC')->orderBy('lastname' , 'ASC');
                        
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('royalty','ASC')->orderBy('author_id' , 'ASC')->paginate(10)
                ], compact('author'));
            break;
            case 'EDSC':
                $author = Author::orderBy('firstname' ,'DESC')->orderBy('lastname' , 'DESC');
                        
                return view('royalties.ebook', [
                    'ebook_transactions' => EbookTransaction::orderBy('royalty','DESC')->orderBy('author_id' , 'DESC')->paginate(10)
                ], compact('author'));
            break;
            default:
                return index();

        }
    }
}
