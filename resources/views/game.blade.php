@extends('layouts.app')

@section('content')
    <div class="container-fluid cobalt">
        <div class="row">
            <div class="col-10 border-right">
                <div id="gameArea">
                    @include('partials.game-area')
                </div>
                <div class="row light-grey mt-3">
                    <div class="col-4">
                        <h5 class="mt-1">Selected card(s)</h5>
                        <div id="selectedCards"></div>
                        <div id="useButtonContainer"></div>
                    </div>
                    <div class="col-6">
                        <h5 class="mt-1">Card description</h5>
                        <div id="cardDescription">

                        </div>
                    </div>
                    <div class="col-2">
                        <h5 class="mt-1">Turn sequence</h5>
                        <p id="turnSequence">
                            @isset($turnSequence)
                                @isset($currentTurn['id'])
                                    @if ($userId != $currentTurn['id'])
                                        <p>Opponent turn</p>
                                    @else
                                        @switch($turnSequence)
                                            @case(2)
                                                <p>Purchasing of cards</p>
                                            @break
                                            @case(3)
                                                <p>Combat</p>
                                            @break
                                            @case(4)
                                                <p>Draw and Discard</p>
                                            @break
                                            @case(5)
                                                <p>Select starting plots</p>
                                            @break
                                            @case(6)
                                                <p>Discard 2 cards</p>
                                            @break
                                            @default
                                        @endswitch
                                    @endif
                                @endisset
                            @endisset
                        </p>
                        <h5 class="mt-1">Options</h5>
                        <div id="options">
                            @isset($turnSequence)
                                @isset($currentTurn['id'])
                                    @if ($userId != $currentTurn['id'])

                                    @else
                                        @switch($turnSequence)
                                            @case('2')
                                                <button onclick="skipTurnSequence()">Skip</button>
                                            @break
                                            @case('3')
                                                <button onclick="skipTurnSequence()">Skip</button>
                                            @break
                                            @case('4')

                                                @default
                                            @endswitch
                                        @endif


                                    @endisset
                                @endisset
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2 eggplant">
                <div class="d-flex flex-column vh-100">
                    <div class="mt-3">
                        <h3>Players/Turn order</h3>
                        <div id="players">
                            @foreach ($players as $player)
                                <p>{{ $player['name'] }}</p>
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
                                <p>{{ $currentTurn['name'] }}</p>
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
    <div id="plot-modal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content light-grey" id="modal-content"></div>
        </div>
    </div>



    <script src="{{ asset('js/game.js') }}"></script>
    <script defer>
        const Echo = window.Echo;
        const id = "{{ $id }}";
        let started = @json($started);
        let players = @json($players);
        let maxPlayers = +"{{ $maxPlayers }}";
        let userId = +"{{ $userId }}";
        let ownerId = +"{{ $ownerId }}";
        @if ($currentTurn)
            let currentTurn = @json($currentTurn);
        @else
            let currentTurn = null;
        @endif
        let turnSequence = +"{{ $turnSequence }}";
        let usersCount;
        let gameStarting = false;
        let selectedCards = [];


        Echo.join(`game.${id}`)
            .here((users) => {
                this.users = users;
                usersCount = users.length;
                updateOnline(this.users);
                for (const user of users) {
                    updatePlayerStatusInDB(user.id, true);
                }
                if (!started) {
                    checkCanStart(usersCount);
                } else {
                    requestCurrentGameView();
                    if (turnSequence == 5) {
                        requestPlotModal();
                    }
                }

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
                currentTurn = data.currentTurn;
                turnSequence = 5;
                changeCurrentTurn();
                requestPlotModal();
            })
            .listen('NewMove', (data) => {
                addToLog(data.log);
                requestCurrentGameView();
                checkMove();
                currentTurn = data.currentTurn;
                turnSequence = +data.turnSequence;
                changeCurrentTurn();
                changeTurnSequence();
            });

    </script>

@endsection
