@extends('layouts.app')

@section('content')
    <div class="container-fluid cobalt">
        <div class="row">
            <div class="col-10 border-right" id="gameArea">
                @include('partials.game-area')
            </div>
            <div class="col-2 eggplant">
                <div class="d-flex flex-column vh-100">
                    <div class="mt-3">
                        <h3>Players/Turn order</h3>
                        <div id="players">
                            @foreach ($players as $player)
                                <p>{{$player['name']}}</p>
                            @endforeach
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
                            @if (isset($currentTurn))
                            <p>{{$currentTurn['name']}}</p>
                            @else
                            Game not started
                            @endif
                        </div>
                    </div>

                    <div class="mt-5">
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
    <div id="plot-modal-container"></div>

    <script src="{{ asset('js/game.js') }}"></script>
    <script src="{{ asset('js/serveHtml.js') }}"></script>
    <script defer>
        const Echo = window.Echo;
        const id = "{{ $id }}";
        let started = @json($started);
        let players = @json($players);
        let maxPlayers = +"{{ $maxPlayers }}";
        let userId = +"{{ $userId }}";
        let ownerId = +"{{ $ownerId }}";
        let turnSequence = +"{{ $turnSequence }}"
        let usersCount;
        let gameStarting = false;

        Echo.join(`game.${id}`)
            .here((users) => {
                this.users = users;
                usersCount = users.length;
                updateOnline(this.users);
                updatePlayers(users);
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
            .listen('PlayerJoined', (data) => {
                players = data.newPlayers
                updatePlayers(players);
            })
            .listen('GameStarted', (data) => {
                addToLog('Game started. Shuffling deck and randomizing turn order.');
                players = data.players;
                updatePlayers(players);
                currentTurn = data.currentTurn.name
                changeCurrentTurn();
                initialGameState();
            })
            .listen('NewMove', (data) => {
                requestCurrentGameView();
                currentTurn = data.currentTurn.name
                changeCurrentTurn();
            });

    </script>

@endsection
