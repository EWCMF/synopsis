@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($games as $game)
            <p>This is game {{ $game->id }}</p>
            @endforeach
        </div>
    </div>
</div>
@endsection
