<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatterHelper;
use App\Helpers\UtilityHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use App\Models\PodTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberFormatter;
use PDF;

class GeneratePdfController extends Controller
{
    
    public function generate(Request $request)
    {
        $request->validate([
            'author' => 'required',
            
            'book' => 'required',
            'fromYear' => 'required',
            'fromMonth' => 'required',
            'toYear' => 'required',
            'toMonth' => 'required'
            
        ]);

            if($request->has('print')){
                if($request->fromYear > $request->toYear){
                    return back()->withErrors(['fromYear' => 'Date From Year should not be greater than Date To Year']);
                }

                if($request->fromMonth > $request->toMonth){
                    return back()->withErrors(['fromMonth' => 'Date From Month should not be greater than Date To Month']);
                }

                $author = Author::find($request->author);
                $pods = collect();
                $totalPods = collect(['title' => 'Grand Total', 'quantity' =>  0, 'price' => 0, 'revenue'=> 0, 'royalty' => 0]);
                foreach($request->book as $book){
                    $podTransactions = PodTransaction::where('author_id', $request->author)->where('book_id', $book)
                                            ->where('quantity','>', 0)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->orderByRaw('month +0 ASC' )->orderBy('isbn','ASC')->orderBy('format','ASC')->get();

                    if(count($podTransactions) > 0){
                        $gr = PodTransaction::where('author_id', $request->author)->where('book_id', $book)
                        ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                        ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                        ->select(PodTransaction::raw('sum(price * quantity * 0.15) as total'))->first();
                        $years = [];
                        $months = [];
                        foreach($podTransactions as $key=>$pod){
                            if(!in_array($pod->year, $years)){ array_push($years, $pod->year); }
                            if(!in_array($pod->month, $months)){ array_push($months, $pod->month); }
                        }

                        foreach($years as $year){
                            foreach($months as $month){
                                $podFirst = $podTransactions->where('year', $year)->where('month', $month)->first();

                                if($podFirst){
                                    /* Get all PaperBack PodTransaction */
                                    $perfectbound = $podTransactions->where('year', $year)->where('month', $month)->where('format', 'Perfectbound');
                                    $paperBackquan = 0;
                                    $paperRev = 0;
                                    $paperHigh = 0;
                                    $paperRoyal = 0;
                                    foreach ($perfectbound as $pod){
                                        $paperBackquan += $pod->quantity;
                                        $paperRev += $pod->price * $pod->quantity;
                                        if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
                                        if ($pod->royalty > $paperRoyal) { $paperRoyal = $pod->royalty;}
                                    }

                                    $paperRoyalty = number_format($paperRev * 0.15,2) ;
                                    $paperRev  = number_format($paperRev ,2);
                                    $pods->push(['title' => $podFirst->book->title,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Paperback', 'quantity' => $paperBackquan, 'price' => '$'.number_format($paperHigh, 2),  'royalty' =>'$'. $paperRoyalty]);

                                    /* Get all  Laminated  Transactions */
                                    $hardBound = $podTransactions->where('year', $year)->where('month', $month)->where('format', '!=', 'Perfectbound');
                                    $hardBackQuan = 0;
                                    $hardbackRev = 0;
                                    $hardHigh = 0;
                                    $hardRoyal = 0;
                                    foreach ($hardBound as $pod){
                                        $hardBackQuan += $pod->quantity;
                                        $hardbackRev += $pod->price * $pod->quantity;
                                        if($pod->price > $hardHigh) { $hardHigh = $pod->price; }
                                        if ($pod->royalty > $hardRoyal) { $hardRoyal = $pod->royalty;}
                                    }

                                    $hardRoyalty = number_format($hardbackRev * 0.15 ,2);
                                
                                    $pods->push(['title' => $podFirst->book->title,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Hardback', 'quantity' =>  $hardBackQuan, 'price' =>'$'. number_format($hardHigh, 2) , 'royalty' =>'$'. number_format($hardRoyalty,2)]);
                                    
                                }   
                            }
                        }
                        $countAllTransaction = number_format($podTransactions->sum('royalty'),2);
                        if($podTransactions->sum('quantity')){

                        }
                        $pods->push([
                            'books' => $podTransactions[0]->book->id ,
                            'title' => $podTransactions[0]->book->title . " Total",
                            'quantity' => $podTransactions->sum('quantity'),
                           
                            
                            'royalty' => $gr->total,
                            'price' => (($paperHigh > $hardHigh) ? number_format($paperHigh, 2) : number_format($hardHigh, 2))
                        ]);
                    }
                

                $grand_quantity = 0;
                $grand_royalty = 0.00;
                $grand_price = 0;
                $grand_revenue = 0;
                foreach($pods as $pod){
                    if(UtilityHelper::hasTotalString($pod)){
                        $grand_quantity += $pod['quantity'];
                        if($grand_quantity > 1){
                            $grand_royalty += $pod['royalty'];
                           
                        }else{
                            $grand_royalty += $pod['royalty'];
                        } 
               
                    }
                    if($pod['price'] > $grand_price) { $grand_price = $pod['price']; }
                }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = $grand_price;
                $totalPods['revenue'] = number_format($grand_revenue, 2);
                $totalPods['royalty'] = number_format($grand_royalty,2);
            }
              

                $ebooks = collect();
                $totalEbooks = collect(['title' => 'Grand Total' , 'quantity' => 0, 'revenue' => 0, 'royalty' => 0]);
        
                foreach($request->book as $book){
                    $ebookTransactions = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)
                                                ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                                ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                                ->where('royalty', '<>', 0)
                                                ->get();
        
                    if(count($ebookTransactions) > 0){
                        
                        $years = [];
                        $months = [];
                        foreach($ebookTransactions as $ebook)
                        {
                            if(!in_array($ebook->year, $years)){
                                array_push($years, $ebook->year);
                            }
                            if(!in_array($ebook->month, $months)){
                                array_push($months, $ebook->month);
                            }
                        }
        
                        foreach($years as $year)
                        {
                            foreach($months as $month){
                                $ebook = $ebookTransactions->where('year', $year)->where('month', $month)->first();
                                if($ebook){
                                    $quantity = $ebookTransactions->where('year', $year)->where('month', $month)->sum('quantity');
                                    $revenue = number_format($ebook->price * $quantity ,2);
                                    $royalty = number_format((float)$ebookTransactions->where('year', $year)->where('month', $month)->sum('royalty'), 2);
                                    
                                    $ebooks->push(['title' => $ebook->book->title, 'year' => $year, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $quantity, 'price' => $ebook->price, 'revenue' => $revenue, 'royalty' => $royalty]);
                                }
                            }
                        }
        
                        $ebooks->push([
                            'books' => $ebookTransactions[0]->book->id ,
                            'title' => $ebookTransactions[0]->book->title . " Total",
                            'quantity' => $ebookTransactions->sum('quantity'),
                           
                            'royalty' => $ebookTransactions->sum('royalty'),
                            'price' => $ebookTransactions[0]->price,
                           
                        ]);
                    }
                }
        
                foreach($ebooks as $ebook){
                    if(UtilityHelper::hasTotalString($ebook)){
                        if(UtilityHelper::hasTotalString($ebook)){
                            $totalEbooks->put('quantity',$totalEbooks['quantity'] + $ebook['quantity']);
                            $totalEbooks->put('royalty', $totalEbooks['royalty'] + $ebook['royalty']);
                          
                            $totalEbooks->put('price',  $ebook['price']);
                        }
                    
                    }
                }
        
               // $totalRoyalties = number_format($totalPods['royalty'] + $totalEbooks['royalty'],2);
                $currentDate = Carbon::now()->format(' m/d/Y g:i A');
                $imageUrl = asset('images/header.png');
          //print pdf
                $pdf = PDF::loadView('report.pdf',[
                    'pods' => $pods,
                    'ebooks' => $ebooks,
                    'author' => $author,
                    'totalPods' => $totalPods,
                    'totalEbooks' => $totalEbooks,
                    'fromYear' => $request->fromYear,
                    'fromMonth' => $request->fromMonth,
                    'toYear' => $request->toYear,
                    'toMonth' => $request->toMonth,
                    //'allRoyal' =>$totalRoyalties,
                    'currentDate' => $currentDate,
                    'imageUrl' => $imageUrl,
                ]);
              $authorName = $author->getFullName();
              $date = $request->fromMonth.$request->fromYear.htmlentities('-').$request->toMonth.$request->toYear ;
                return $pdf->download($authorName.$date.'Royalty.pdf');
        
            }
            elseif($request->has('preview')){
                if($request->fromYear > $request->toYear){
                    return back()->withErrors(['fromYear' => 'Date From Year should not be greater than Date To Year']);
                }

                if($request->fromMonth > $request->toMonth){
                    return back()->withErrors(['fromMonth' => 'Date From Month should not be greater than Date To Month']);
                }

                $author = Author::find($request->author);
                $pods = collect();
                $totalPods = collect(['title' => 'Grand Total', 'quantity' =>  0, 'price' => 0, 'revenue'=> 0, 'royalty' => 0]);
                foreach($request->book as $book){
                    $podTransactions = PodTransaction::where('author_id', $request->author)->where('book_id', $book)
                                            ->where('quantity','>', 0)
                                            ->where('price','>', 0)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->orderByRaw('month +0 DESC' )->orderByRaw('year +0 DESC' )->orderBy('isbn','DESC')->orderBy('format','DESC')->get();

                    if(count($podTransactions) > 0){
                       
                        $gr = PodTransaction::where('author_id', $request->author)->where('book_id', $book)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->select(PodTransaction::raw('sum(price * quantity * 0.15) as total'))->first();
                      
                      
                        $years = [];
                        $months = [];
                        foreach($podTransactions as $key=>$pod){
                            if(!in_array($pod->year, $years)){ array_push($years, $pod->year); }
                            if(!in_array($pod->month, $months)){ array_push($months, $pod->month); }
                        }

                        foreach($years as $year){
                            foreach($months as $month){
                                $podFirst = $podTransactions->where('quantity','>', 0)->where('year', $year)->where('month', $month);

                                if($podFirst){
                                    /* Get all PaperBack PodTransaction */
                                    $perfectbound = $podTransactions->where('year', $year)->where('month', $month)->where('format', 'Perfectbound');
                                    $paperBackquan = 0;
                                    $paperRev = 0;
                                    $paperHigh = 0;
                                    $paperRoyal = 0;
                                    foreach ($perfectbound as $pod){
                                        $paperBackquan += $pod->quantity;
                                        $paperRev += $pod->price * $pod->quantity;
                                        if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
                                        if ($pod->royalty > $paperRoyal) { $paperRoyal = $pod->royalty;}
                                    }

                                    $paperRoyalty = number_format($paperRev * 0.15,3) ;
                                    $paperRev  = number_format($paperRev ,2);
                                    $pods->push(['title' => $podTransactions[0]->book->title,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Paperback', 'quantity' => $paperBackquan, 'price' => '$'.number_format($paperHigh, 2),  'royalty' =>'$'. $paperRoyalty]);

                                    /* Get all  Laminated  Transactions */
                                    $hardBound = $podTransactions->where('year', $year)->where('month', $month)->where('format', '!=', 'Perfectbound');
                                    $hardBackQuan = 0;
                                    $hardbackRev = 0;
                                    $hardHigh = 0;
                                    $hardRoyal = 0;
                                    foreach ($hardBound as $pod){
                                        $hardBackQuan += $pod->quantity;
                                        $hardbackRev += $pod->price * $pod->quantity;
                                        if($pod->price > $hardHigh) { $hardHigh = $pod->price; }
                                        if ($pod->royalty > $hardRoyal) { $hardRoyal = $pod->royalty;}
                                    }

                                    $hardRoyalty = number_format($hardbackRev * 0.15 ,2);
                                
                                    $pods->push(['title' => $podTransactions[0]->book->title,'refkey'=>$pod->isbn, 'year' => $year, 'month' => $month, 'format' => 'Hardback', 'quantity' =>  $hardBackQuan, 'price' =>'$'. number_format($hardHigh, 2) , 'royalty' =>'$'. number_format($hardRoyalty,3)]);
                                    
                                }   
                            }
                        }
                        $countAllTransaction = number_format($podTransactions->sum('royalty'),2);
                        if($podTransactions->sum('quantity')){

                        }
                        $pods->push([
                            'books' => $podTransactions[0]->book->id ,
                            'title' => $podTransactions[0]->book->title . " Total (Royalty):",
                            'quantity' => $podTransactions->sum('quantity'),
                           
                            
                            'royalty' => $gr->total,
                            'price' => (($paperHigh > $hardHigh) ? number_format($paperHigh, 2) : number_format($hardHigh, 2))
                        ]);
                    }
                

                $grand_quantity = 0;
                $grand_royalty = 0.00;
                $grand_price = 0;
                $grand_revenue = 0;
                foreach($pods as $pod){
                    if(UtilityHelper::hasTotalString($pod)){
                        $grand_quantity += $pod['quantity'];
                        if($grand_quantity > 1){
                            $grand_royalty += $pod['royalty'];
                           
                        }else{
                            $grand_royalty += $pod['royalty'];
                        } 
               
                    }
                    if($pod['price'] > $grand_price) { $grand_price = $pod['price']; }
                }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = $grand_price;
                $totalPods['revenue'] = number_format($grand_revenue, 3);
                $totalPods['royalty'] = number_format($grand_royalty,2);
            }
              
                
             

                $ebooks = collect();
                $totalEbooks = collect(['title' => 'Grand Total' , 'price' => 0  ,'quantity' => 0, 'revenue' => 0, 'royalty' => 0]);
        
                foreach($request->book as $book){
                    $ebookTransactions = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)
                                                ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                                ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                                ->where('royalty', '<>', 0)
                                                ->orderByRaw('month +0 ASC' )
                                                ->get();
        
                    if(count($ebookTransactions) > 0){
                        $eprev = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)
                        ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                        ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                        ->select(EbookTransaction::raw('sum(price * quantity * 0.15) as total'))->first();
                        $years = [];
                        $months = [];
                        foreach($ebookTransactions as $ebook)
                        {
                            if(!in_array($ebook->year, $years)){
                                array_push($years, $ebook->year);
                            }
                            if(!in_array($ebook->month, $months)){
                                array_push($months, $ebook->month);
                            }
                        }
                        foreach($years as $year)
                        {
                            foreach($months as $month){
                                $ebook = $ebookTransactions->where('year', $year)->where('month', $month)->first();
                                if($ebook){
                                    $quantity = $ebookTransactions->where('year', $year)->where('month', $month)->sum('quantity');
                                    $revenue = number_format($ebook->price * $quantity ,2);
                                    $royalty = number_format((float)$ebookTransactions->where('year', $year)->where('month', $month)->sum('royalty'), 2);
                                    
                                    $ebooks->push(['title' => $ebook->book->title, 'year' => $year, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $quantity, 'price' => $ebook->price, 'revenue' => $revenue, 'royalty' => $royalty]);
                                }
                               
                            }
                        }
        
                        $ebooks->push([
                            'books' => $ebookTransactions[0]->book->id ,
                            'title' => $ebookTransactions[0]->book->title . " Total (Royalty):",
                            'quantity' => $ebookTransactions->sum('quantity'),
                           
                            'royalty' =>  $eprev,
                            'price' => $ebookTransactions[0]->price,
                            
                        ]);
                    }
                }
                $grande_quantity = 0;
                $grande_royalty = 0.00;
                $grande_price = 0;
                $grande_revenue = 0;
                foreach($ebooks as $ebook){
                    if(UtilityHelper::hasTotalString($ebook)){
                        $grande_quantity += $ebook['quantity'];
                        if($grande_quantity > 1){
                            $grande_royalty += $ebook['royalty'];
                           
                        }else{
                            $grande_royalty += $ebook['royalty'];
                        } 
                        if($ebook['price'] > $grande_price) { $grande_price = $ebook['price']; }
                    }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = $grand_price;
                $totalPods['revenue'] = number_format($grand_revenue, 3);
                $totalPods['royalty'] = number_format($grand_royalty,2);
                  
                }
        
                $totalRoyalties = number_format((float) $totalPods['royalty'] + $totalEbooks['royalty'], 3);
              //  $numberFormatter = NumberFormatterHelper::numtowords($totalRoyalties);
                $currentDate = Carbon::now();
                // preview data 
                return view('prev',[
                    
                    
                    'pods' => $pods,
                    'ebooks' => $ebooks,
                    'author' => $author,
                    'totalPods' => $totalPods,
                    'totalEbooks' => $totalEbooks,
                    'totalRoyalties' => $totalRoyalties,
                    'fromYear' => $request->fromYear,
                    'fromMonth' => $request->fromMonth,
                    'toYear' => $request->toYear,
                    'toMonth' => $request->toMonth,
                    'currentDate' => $currentDate,
                   
                ]);
        
            }
            

           
        
       

    }
}