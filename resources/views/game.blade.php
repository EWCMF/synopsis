@extends('layouts.app')

@section('content')
    <div class="container-fluid cobalt">
        <div class="row">
            <div class="col-10 border-right">
                <div id="gameArea" class="d-flex flex-column vh-100">
                    <div class="h-100 mb-5">
                        <div class="row h-100 border light-grey">
                            <div class="col-3">
                                <h5 class="mt-1">Resources</h5>
                                <div id="foeResources"></div>
                            </div>
                            <div class="col-3">
                                <h5 class="mt-1">Tech</h5>
                                <div id="foeTech"></div>
                            </div>
                            <div class="col-3">
                                <h5 class="mt-1">Plots</h5>
                                <div id="foePlots"></div>
                            </div>
                            <div class="col-3">

                            </div>
                        </div>
                    </div>
                    <div class="h-100">
                        <div class="row h-100">
                            <div class="col-2">
                                <div
                                    class="d-flex flex-column border h-100 justify-content-center align-items-center light-grey">
                                    <h5>
                                        Deck
                                    </h5>
                                    <div id="deck">
                                        (Number)
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 pl-0 pr-4">
                                <div
                                    class="d-flex flex-column border h-100 justify-content-center align-items-center light-grey">
                                    <h5>
                                        Discard pile
                                    </h5>
                                    <div id="discardPile">
                                        (Number)
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row w-100 h-100 border light-grey">
                                    <div class="col-6 h-100 border-right">
                                        <h5 class="mt-1">
                                            Purchaseable plots
                                        </h5>
                                        <div id="purchaseablePlots"></div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="mt-1">
                                            Purchaseable tech
                                        </h5>
                                        <div id="purchaseableTech"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row w-100 h-100 border light-grey">
                                    <div class="col-6 h-100 border-right">
                                        <h5 class="mt-1">
                                            Attacking
                                        </h5>
                                        <div id="attacking"></div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="mt-1">
                                            Defending
                                        </h5>
                                        <div id="defending"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-100 mt-5">
                        <div class="row h-100 border light-grey">
                            <div class="col-3">
                                <h5 class="mt-1">Resources</h5>
                                <div id="ownResources"></div>
                            </div>
                            <div class="col-3">
                                <h5 class="mt-1">Tech</h5>
                                <div id="ownTech"></div>
                            </div>
                            <div class="col-3">
                                <h5 class="mt-1">Plots</h5>
                                <div id="ownPlots"></div>
                            </div>
                            <div class="col-3">
                                <h5 class="mt-1">Play cards</h5>
                                <div id="ownPlayCards"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2 eggplant">
                <div class="d-flex flex-column vh-100">
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
    @include('modals.plot-modal')

    <script src="{{ asset('js/game.js') }}"></script>
    <script src="{{ asset('js/serveHtml.js') }}"></script>
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
                addToLog('Game started. Shuffling deck and randomizing turn order.');
                state = newState.state;
                updatePlayers(state.players);
                changeCurrentTurn();
                initialGameState();
            });

    </script>

@endsection
