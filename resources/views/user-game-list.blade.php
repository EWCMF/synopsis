@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse ($games as $game)
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4 text-light align-items-center">
                                <p>Game {{ $game->id }}</p>

                            </div>
                            <div class="col-4 text-light align-items-center">
                                <p>
                                    Players: @foreach ($game->users as $player)
                                    @if ($you != $player->name)
                                    <p>{{$player->name}}</p>
                                    @endif
                                    @if (count($game->users) == 1)
                                    vs CPU
                                    @endif
                                    
                                    @endforeach

                                </p>

                            </div>
                            <div class="col-4">
                                <a class="btn btn-primary btn-block" href="/game/{{$game->id}}" role="button">Join Game</a>
                            </div>
                        </div>
                    </div>

                </div>
              </div>
            @empty
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col text-center text-light">
                                No games present
                            </div>

                        </div>
                    </div>

                </div>
              </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
