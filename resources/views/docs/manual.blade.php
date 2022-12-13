@extends('layouts.authenticated')

@section('content')
<div class="container ">
<iframe src="{{asset('public/images/instmanual.pdf')}}" width="1250" height="1000" alt="pdf" >
</iframe>

</div>
@endsection
