@extends('index')

@section('content')

    <div class="row error-page">
        <div class="col-md-6 offset-md-3 text-center align-self-center">
            <h1>{{__('errors.403_name')}}</h1>
            <p>{{__('errors.403_description')}}</p>
        </div>
    </div>

@endsection
