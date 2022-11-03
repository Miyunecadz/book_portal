@extends('layouts.app')

@section('content')
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
    body{
        background: #ffffff;
        font-family: 'Arial';
    }
    
</style>

<div class="upper">
    {{-- <div class="image-container">
        <img src="https://readersmagnet.com/wp-content/uploads/2019/08/ReadersMagnet-Favicon.png" height="150px" width="150px" alt="Readers Magnet Image" srcset="">
    </div>
    <div class="detail-container" style="position:absolute; right:0; top:40px;">
        <b style="position: relative; bottom: 10px;">Readers Magnet</b>
        <br>
        <a href="info@readersmagnet.com" style="position: relative; bottom: 10px;">info@readersmagnet.com</a>
        <p>(800) 805-0762</p>
    </div> --}}

    <img src="https://res.cloudinary.com/dadkdj2t7/image/upload/v1660771247/header_m3yppc.png" alt="" srcset="" style="width:100%">
</div>
<div id="lower" style ="font-size: 14px;  font-family:Roboto;">
    {{-- <div class="title" style="text-align: center;">
        <h6 style="font-size: 30px;">Royalty Statement</h6>
    </div> --}}
    <div class="details" style="margin-top: 30px;">
       {{$currentDate}} EST
        <br>
        <span style="font-family:Verdana,  sans-serif;">Statement Period: <b>{{App\Helpers\MonthHelper::getStringMonth($fromMonth)}} {{$fromYear}}</b> to <b>{{App\Helpers\MonthHelper::getStringMonth($toMonth)}} {{$toYear}}</b></span>
        <br>
        <h6 class="mt-4" style="font-family:Verdana,  sans-serif; font-size: 15px;"><b>{{$author->getFullName()}}</b></h6>
    </div>
    <div class="details" style="margin-top: 45px; " >
      Dear {{$author->firstname}},<br>
    <!-- @if(!empty($totalPods['royalty']))-->  <!-- Enclosed is the royalty payment amounting to <span>${{substr($totalPods['royalty'],0,-1)}}</span> ({{App\Helpers\NumberFormatterHelper::numtowords(substr($totalPods['royalty'],0,-1)) }}).
      @endif
    --> 
    
    <!-- @if(!empty($totalEbooks['royalty']))
      Enclosed is the royalty payment amounting to <strong>${{substr($totalEbooks['royalty'],0,-1)}}<!--{{$totalRoyalties}}</strong> ({{App\Helpers\NumberFormatterHelper::numtowords($totalEbooks['royalty'])}}).-->
    <!-- @endif--->
 
    Enclosed is the royalty payment amounting to <span>${{$totalPods['royalty'] ?? $totalEbooks['royalty']}}</span> <!--({{App\Helpers\NumberFormatterHelper::numtowords($totalPods['royalty'])??  $totalEbooks['royalty'] }})-->
     
     <br>
    </div>
    <div class="transaction" style="margin-top: 10px; padding-top: 20px;">
    <span  style="font-size: 15px;">Royalty statement details below:</span>
       <br>
       <br>
        @if(count($pods) > 0)
        <table style="padding-right: 20px; width:80%; font-size: 14px; font-family:Roboto;">
            <thead style=" background-color:#336EFF ;border: 1px solid;font-size: 12px; color:#EBD5D1;">
                <tr style="font-family:Verdana, Helvetica, sans-serif; text-align:center; ">
                    <th style=" border: 1px solid;"><span>Book Title</span></th>
                    <th style=" border: 1px solid;">Format</th>
                    <th style=" border: 1px solid;">Month</th>
                    <th style=" border: 1px solid;">Year</th>
                    <th style=" border: 1px solid;">Copies Sold</th>
                    <th style=" border: 1px solid;">Retail Price</th>
                  
                    <th style=" border: 1px solid;">15% Royalty</th>
                </tr>
            </thead>
            <tbody style="" >
                @foreach ($pods as $pod)
                    @if(App\Helpers\UtilityHelper::hasTotalString($pod))
                        <tr style="">
                            <td colspan="4"style="font-family:Verdana, Helvetica, sans-serif; border:1px solid; width:90px; background-color:#84A2FF ;">{{$pod['title']}}</td>
                            <td style="font-family:Verdana, Helvetica, sans-serif; border: 1px solid; width:70px; background-color:#84A8FF ; text-align:center;">{{$pod['quantity']}}</td>
                            <td style="font-family:Verdana, Helvetica, sans-serif; border: 1px solid; width:70px; background-color:#84A8FF ;text-align:center;">${{$pod['price']}}</td>

                            <td style="font-family:Verdana, Helvetica, sans-serif; border: 1px solid; width:70px; background-color:#84A8FF ;text-align:center;">${{$pod['royalty']}}</td>
                        </tr>
                    @else
                        <tr>
                        @if(!empty($pod['format']) && $pod['quantity'] > 0  )
                            <td style="border: 1px solid; width:200px;" >{{$pod['title']}}</td>
                            <td style="border: 1px solid; width:80px; text-align:center;">{{$pod['format']}}</td>
                            <td style="border: 1px solid; width:50px; text-align:center;">{{App\Helpers\MonthHelper::getStringMonth($pod['month'])}}</td>
                            <td style="border: 1px solid; width:45px; text-align:center;">{{$pod['year']}}</td>
                            <td style="border: 1px solid; width:60px; text-align:center;">{{$pod['quantity']}}</td>
                            <td style="border: 1px solid; width:60px; text-align:center;">{{$pod['price']}}</td>

                            <td style="border: 1px solid; width:60px; text-align:center;">{{$pod['royalty']}}</td>
                        @endif
                        </tr>
                    @endif
                @endforeach
                <tr style="font-family:Calibri; width:70px;background-color:#336EFF; color: #FFFFFF; ;">
                <td colspan="4" style="border: 1px solid; width:90px; font-family:Verdana, Helvetica, sans-serif;  background-color:#336EFF;color: #FFFFFF;"><b>{{$totalPods['title']}}</b></td>
                    <td style="font-family:Verdana, Helvetica, sans-serif; text-align:center "><b>{{$totalPods['quantity']}}</b></td>
                    <td style="font-family:Verdana, Helvetica, sans-serif; text-align:center"><b>${{$totalPods['price']}}</b></td> 

                    <td style="font-family:Verdana, Helvetica, sans-serif; text-align:center"><b>${{$totalPods['royalty']}}</b></td>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
    @if(count($ebooks) > 0)
    <div class="transaction" style="margin-top: 10px;">
        <table style="width:100%;font-size: 14px;">
        <thead style=" background-color:#336EFF ;border: 1px solid;font-size: 12px; color:#EBD5D1;">
                <tr style="font-family:Verdana, Helvetica, sans-serif; text-align:center; ">
                <th style="border: 1px solid;">eBook</th>
                    <th style="border: 1px solid;">Month</th>
                    <th style="border: 1px solid;">Year</th>
                    <th style="border: 1px solid;">TradeType</th>
                    <th style="border: 1px solid;">Quantity</th>
                    <th style="border: 1px solid;">Retail Price</th>
         
                    <th style="border: 1px solid;">Author Royalty</th>
                </tr>
            </thead>
            <tbody style="">
                @foreach ($ebooks as $ebook)
                    @if(App\Helpers\UtilityHelper::hasTotalString($ebook))
                    <tr>
                        <td colspan="4"style="font-family:Verdana, Helvetica, sans-serif; border:1px solid; width:80px; background-color:#84A2FF ;">{{$ebook['title']}}</td>

                        <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; background-color:#84A8FF ; text-align:center;">{{$ebook['quantity']}}</td>
                        <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; background-color:#84A8FF ; text-align:center;">{{$ebook['price']}}</td>

                        <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; background-color:#84A8FF ; text-align:center;">{{$ebook['royalty']}}</td>
                    </tr>
                    @else
                    <tr>
                        <td style="border: 1px solid; width:230px;" >{{$ebook['title']}}</td>
                        <td style="border: 1px solid; width:75px; text-align:center;">{{App\Helpers\MonthHelper::getStringMonth($ebook['month'])}}</td>
                        <td style="border: 1px solid; width:40px; text-align:center;">{{$ebook['year']}}</td>
                        <td style="border: 1px solid; width:40px; text-align:center;">{{$ebook['trade']}}</td>
                        <td style="border: 1px solid; width:40px; text-align:center;">{{$ebook['quantity']}}</td>
                        <td style="border: 1px solid; width:50px; text-align:center;">{{$ebook['price']}}</td>

                        <td style="border: 1px solid; width:50px; text-align:center;">{{$ebook['royalty']}}</td>
                    </tr>
                    @endif
                @endforeach
                <tr style="font-family:Calibri; width:70px;background-color:#336EFF; color: #FFFFFF;">
                <td colspan="3" style="font-family:Verdana, Helvetica, sans-serif; border: 1px solid; width:90px; "><b>{{$totalEbooks['title']}}</b></td>
                    <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; text-align:center;"><b></b></td>
                    <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; text-align:center;"><b>{{$totalEbooks['quantity']}}</b></td>
                    <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; text-align:center;"><b></b></td>

                    <td style="font-family:Verdana, Helvetica, sans-serif;border: 1px solid; width:70px; text-align:center;"><b>{{$totalEbooks['royalty']}}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    <h5 class="mt-4 my-4" ><p style="font-size: 14px; font-family:Roboto;">Should you have any questions, please don't hesitate to contact your Author Relations Officer at 800-805-0762 or send us an email at <a>info@readersmagnet.com</a>.</p></h5>
    <br>
    <span style="font-size: 15px; font-family:Roboto;">Sincerely,</span>
    <h5 style="font-size: 15px; font-family:Verdana, Helvetica, sans-serif; "><span><b style="font-size: 15px;">ReadersMagnet</b></span></h5>
</div>

@endsection
