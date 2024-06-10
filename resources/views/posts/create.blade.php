@extends ('layouts.app') 
@section ('content')
<div class="container">
  <h1>New Item</h1>

  {!! Form::open(['action'=> 'PostsController@store', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
  <div class="form-row">
      <div class="form-group col-md-6">
        {{-- Starts with the label (first is the label name associated with the second parameter(displayed on the screen)) Then the
        text has the text name associated with what will be entered in the second parameter (empty because user must provide),
        then the third is the attribute of that text or textarea etc using bootstrap --}} 
        {{Form::label('title','Item Name')}} 
        {{Form::text('title', '', ['class'=>'form-control', 'placeholder'=>'Item Name'])}}
      </div>
    
      <div class="form-group col-md-6">
        {{Form::label('location','My Location')}} 
        {{Form::text('location', '', ['class'=>'form-control ', 'placeholder'=>'My Location'])}}
      </div>
     <div class="form-group col-md-6">
        {{Form::label('phone_number','My Phone Number')}} {{Form::text('phone_number', '', ['class'=>'form-control ', 'placeholder'=>'Phone Number','maxlength'=>'13'])}}
      </div>
  
    <div class="form-group">
      {{Form::label('body','Item Description')}} 
      {{Form::textarea('body', '', [ 'class'=>'form-control',
      'placeholder'=>'Item Description'])}}
    </div>
  <div class="form-group">
    {{Form::file('cover_image')}}
  </div>
</div> 
  {{Form::submit('Submit', ['class'=>'btn btn-primary'])}} {!! Form::close() !!}

</div>
@endsection

