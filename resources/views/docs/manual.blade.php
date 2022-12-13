@extends('layouts.authenticated')

@section('content')
<div class="container ">
<iframe src="{{ asset('images/Instmanual.pdf') }}" width="1250" height="1000">
            This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('images/Instmanual.pdf') }}">Download PDF</a>
    </iframe>


</div>
@endsection
