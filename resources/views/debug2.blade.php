@extends('layouts.app')

@section('content')
    <div></div>

    @include('modals.game-modal')

    <script defer>
        $(document).ready(function () {

            $('#game-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            $("#game-modal").modal('show');
        });
    </script>
    <script defer src="{{asset('js/serveHtml.js')}}"></script>

@endsection
