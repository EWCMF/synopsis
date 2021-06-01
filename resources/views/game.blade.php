@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10">
                <div></div>
            </div>
            <div class="col-2">
                <div class="d-flex flex-column">
                    <div class="mt-3">
                        <h3>Players</h3>
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
                    </div>

                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <script defer>
        const Echo = window.Echo;
        const id = "{{ $id }}";
        let state = JSON.parse(@json($state));
        let started = "{{ $started }}";
        let maxPlayers = +"{{ $maxPlayers }}";

        Echo.join(`game.${id}`)
            .here((users) => {
                this.users = users;
                this.usersCount = users.length;
                updateOnline(this.users);
                updatePlayers(state.players);
                for (const user of users) {
                    updatePlayerStatusInDB(user.id, true);
                }
            })
            .joining((user) => {
                this.users.push(user);
                this.usersCount = this.usersCount + 1;
                updateOnline(this.users);
            })
            .leaving((user) => {
                const index = this.users.indexOf(user);
                this.users.splice(index, 1);
                this.usersCount = this.usersCount - 1;
                updateOnline(this.users);
                updatePlayerStatusInDB(user.id, false);
            })
            .error((error) => {
                console.error(error);
            })
            .listen('PlayerJoined', (newState) => {
                state = newState.state;
                updatePlayers(state.players);
            });

        function checkCanStart() {
            if (state.players.length === maxPlayers) {
                startGame();
            }
        }

        function startGame()

        function updatePlayers(users) {
            let players = document.getElementById('players');
            let innerHtml = '';
            for (const user of users) {
                innerHtml += "<p>" + user.name + "</p>"
            }
            players.innerHTML = innerHtml;
        }

        function updateOnline(users) {
            let players = document.getElementById('online');
            let innerHtml = '';
            for (const user of users) {
                innerHtml += "<p>" + user.name + "</p>"
            }
            players.innerHTML = innerHtml;
        }

        function updatePlayerStatusInDB(userId, isPlaying) {
            let xhr = new XMLHttpRequest();
            let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            xhr.open('POST','/change-playing-state');
            xhr.setRequestHeader("X-CSRF-Token", csrf);
            xhr.setRequestHeader('Content-Type','application/json');
            xhr.send(JSON.stringify({
                'user_id': userId,
                'game_id': id,
                'isPlaying': isPlaying
            }));
        }

    </script>
@endsection
