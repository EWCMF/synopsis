@extends('layouts.app')

@section('content')
    <div>
        Test
    </div>

    <script defer>
        let Echo = window.Echo;
        let id = "{{ $id }}"

        Echo.join(`game.${id}`)
            .here((users) => {
                //
            })
            .joining((user) => {
                console.log(user.name);
            })
            .leaving((user) => {
                console.log(user.name);
            })
            .error((error) => {
                console.error(error);
            });

    </script>
@endsection
