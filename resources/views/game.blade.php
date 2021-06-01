@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10">
                <div id="gameArea">

                </div>
            </div>
            <div class="col-2">
                <div class="d-flex flex-column">
                    <div class="mt-3">
                        <h3>Players/Turn order</h3>
                        <div id="players">

                        </div>
                    </div>

                    <div class="mt-3">
                        <h3>Online</h3>
                        <div id="online">

                        </div>
                    </div>

                    <div class="mt-3 flex-grow">
                        <h3>Current turn:</h3>
                        <div id="currentTurn">
                            Game not started
                        </div>
                    </div>

                    <div class="mt-3">
                        <h3>Log</h3>
                        <div id="log">

                        </div>
                    </div>

                    <div></div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.game-modal')

    <script defer>
        const Echo = window.Echo;
        const id = "{{ $id }}";
        let state = JSON.parse(@json($state));
        let started = @json($started);
        let maxPlayers = +"{{ $maxPlayers }}";
        let userId = +"{{ $userId }}";
        let ownerId = state.ownerId;
        let usersCount;
        let gameStarting = false;

        Echo.join(`game.${id}`)
            .here((users) => {
                this.users = users;
                usersCount = users.length;
                updateOnline(this.users);
                updatePlayers(state.players);
                for (const user of users) {
                    updatePlayerStatusInDB(user.id, true);
                }
                checkCanStart(usersCount);
            })
            .joining((user) => {
                this.users.push(user);
                usersCount = usersCount + 1;
                updateOnline(this.users);
                addToLog(user.name + " has joined.");
                checkCanStart(usersCount);
            })
            .leaving((user) => {
                const index = this.users.indexOf(user);
                this.users.splice(index, 1);
                usersCount = usersCount - 1;
                updateOnline(this.users);
                updatePlayerStatusInDB(user.id, false);
                addToLog(user.name + " has left.");
            })
            .error((error) => {
                console.error(error);
            })
            .listen('PlayerJoined', (newState) => {
                state = newState.state;
                updatePlayers(state.players);
            })
            .listen('GameStarted', (newState) => {
                addToLog('Game started. Shuffling deck and randomizing turn order');
                state = newState.state;
                updatePlayers(state.players);
                changeCurrentTurn();
                initialGameState();
            });
    </script>
    <script defer src="{{asset('js/game.js')}}"></script>
    <script defer src="{{asset('js/serveHtml.js')}}"></script>

@endsection
