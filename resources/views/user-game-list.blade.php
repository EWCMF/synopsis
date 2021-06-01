@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($games as $game)
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                Game {{ $game->id }}
                            </div>
                            <div class="col-4">
                                In-game: {{ $game->users_count}}/{{$game->max_players}}
                            </div>
                            <div class="col-4">
                                <a class="btn btn-primary btn-block" href="/game/{{$game->id}}" role="button">Join Game</a>
                            </div>
                        </div>
                    </div>

                </div>
              </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
