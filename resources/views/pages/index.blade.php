@extends('layouts.app')

@section('content')
  <div class="jumbotron text-center"> 
        {{--this is the main index page--}}
      <h1>{{$title}}</h1>
      <p>
        ConstructionManager: Your trusted construction management solution 
      </p>
  </div>
  
 @endsection   

{{--this means that the  whole layout(html)will be extended from the layouts.app file and the only changes to be made will be dictated in the respective files of such as index,about and services--}}

