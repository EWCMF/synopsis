@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($games as $game)
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4 text-light">
                                Game {{ $game->id }}
                            </div>
                            <div class="col-4 text-light">
                                Players: {{ $game->users_count}}/{{$game->max_players}}
                            </div>
                            <div class="col-4 text-light">
                                <a class="btn btn-primary btn-block" href="join-game/{{$game->id}}" role="button">Join Game</a>
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
