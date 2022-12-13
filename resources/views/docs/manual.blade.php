@extends('layouts.authenticated')

@section('content')
<div class="container ">
<embed src="{{asset('public/instmanual.pdf')}}" width="1250" height="1000" alt="pdf" />

</div>
@endsection
