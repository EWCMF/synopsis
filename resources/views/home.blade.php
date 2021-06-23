@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark">
                    <div class="card-header text-light">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-6 mb-5">
                                <button class="btn btn-primary mt-3 btn-block" data-toggle="modal"
                                    data-target="#createNewGameModal">Create new
                                    game</button>

                                <button class="btn btn-primary mt-3 btn-block" onclick="window.location = '/user-game-list'" >Currently ongoing games</button>

                                <button class="btn btn-primary mt-3 btn-block" onclick="window.location = '/game-list'">Join
                                    existing game</button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createNewGameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-light" id="exampleModalLabel">Create new game</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <h2 class="text-light">Choose game type</h2>
                        <button class="btn btn-primary" onclick="startNewGame(1)">1 vs 1 game</button>
                        <button class="btn btn-primary" onclick="startNewGame(2)">1 vs 1 game (vs CPU)</button>
                    </div>
                </div>
            </div>
        </div>

        <form action="/new-game" method="POST" id="form">
            @csrf

            <input type="hidden" id="gameType" name="gameType" value="" />
        </form>
    </div>

    <script>
        function startNewGame(type) {
            if (!type) {
                return;
            }

            document.getElementById('gameType').value = type;
            document.getElementById('form').submit();
        }

    </script>
@endsection
