<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Helpers\MonthHelper;
use App\Helpers\NameHelper;
use App\Models\PodTransaction;
use Illuminate\Support\Facades\DB;

class RoyaltyController extends Controller
{
    public function index()
    {
        $months = MonthHelper::getMonths();
        $author = Author::get();
        $author = Author::all();
        foreach($author as $authors){
            $podtran = Podtransaction ::orderBy('author_id', 'ASC')->paginate(10);
            $podlists = Podtransaction ::where('author_id',$authors->id);
    
            $hbound = Podtransaction::where('author_id' , $authors->id)->where('format' , 'Perfectbound');
            $paperBackquan = 0;
            $paperRev = 0;
            $paperHigh = 0;
            foreach ($hbound as $pod){
                $paperBackquan += $pod->quantity;
                $paperRev += $pod->price * $pod->quantity;
                if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
            }

            $paperRoyalty = $paperRev * 0.15;
            $paperRev  = number_format($paperRev ,2);
           
        }   
        


        return view('royalties.pod', [
            'cnt' => $paperBackquan, 
            'pod_transactions' => $podtran,
        ], compact('author' , 'months'));
    }
    public function search(Request  $request)
    {
        

        if($request->author_id == 'all'){
            $author = Author::all();
      
            return view('royalties.pod', [
                'pod_transactions' => PodTransaction::orderBy('author_id', 'ASC')->paginate(10)
            ], compact('author', 'months'));
        }else{
            $author = Author::all();
      
        return view('royalties.pod', [
            'pod_transactions' => PodTransaction::where('author_id' , $request->author_id)->orderBy('author_id', 'ASC')->paginate(10)
        ], compact('author', 'months'));
        }
        
   
    
    }
    public function sort(Request  $request)
    {
        if($request->months){
            $months = MonthHelper::getMonths();
            $author = Author::all();
            return view('royalties.pod', [
                'pod_transactions' => PodTransaction::where('month', $request->months)->orderBy('book_id','DESC')->paginate(10)
            ], compact('author', 'months'));
        }

        switch($request->sort){
            case 'ASC':
                $author = Author::orderBy('firstname' ,'ASC')->orderBy('lastname' , 'ASC');
                $months = MonthHelper::getMonths();       
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::orderBy('book_id','ASC')->paginate(10)
                ], compact('author', 'months'));
            break;
            case 'DESC':
                $author = Author::orderBy('firstname' ,'DESC')->orderBy('lastname' , 'DESC');
                $months = MonthHelper::getMonths();
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::orderBy('book_id','DESC')->paginate(10)
                ], compact('author', 'months'));
            break;
            case 'RASC':
                $author = Author::orderBy('firstname' ,'ASC')->orderBy('lastname' , 'ASC');
                $months = MonthHelper::getMonths();   
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::orderBy('royalty','ASC')->orderBy('author_id' , 'ASC')->paginate(10)
                ], compact('author', 'months'));
            break;
            case 'RDSC':
                $author = Author::orderBy('firstname' ,'DESC')->orderBy('lastname' , 'DESC');
                $months = MonthHelper::getMonths();    
                return view('royalties.pod', [
                    'pod_transactions' => PodTransaction::orderBy('royalty','DESC')->orderBy('author_id' , 'DESC')->paginate(10)
                ], compact('author', 'months'));
            break;
            default:
                return index();

        }
        
    }
   
}
