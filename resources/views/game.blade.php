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
                        <h1>Players</h1>
                        <div id="players">

                        </div>
                    </div>

                    <div class="mt-3 flex-grow">
                        <h1>Current turn:</h1>
                        <div id="currentTurn">
                            Game not started
                        </div>
                    </div>

                    <div class="mt-3">
                        <h1>Log</h1>
                    </div>

                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <script defer>
        let Echo = window.Echo;
        let id = "{{ $id }}"

        Echo.join(`game.${id}`)
            .here((users) => {
                this.users = users;
                this.usersCount = users.length;
                updatePlayers(this.users);
            })
            .joining((user) => {
                this.users.push(user);
                this.usersCount = this.usersCount + 1;
                updatePlayers(this.users);
            })
            .leaving((user) => {
                this.users.push(user);
                this.usersCount = this.usersCount - 1;
                updatePlayers(this.users);
            })
            .error((error) => {
                console.error(error);
            });


        function updatePlayers(users) {
            let players = document.getElementById('players');
            let innerHtml;
            for (const user of users) {
                innerHtml += "<p>" + user + "</p>"
            }
            players.innerHTML = innerHtml;
        }

    </script>
@endsection
