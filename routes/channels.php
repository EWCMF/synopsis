<?php

use App\Models\Game;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });



Broadcast::channel('game.{id}', function ($user, $id) {
    $allowed = DB::table('game_user')->where([
        'user_id' => $user->id,
        'game_id' => $id,
    ])->exists();

    if ($allowed) {
        return ['id' => $user->id, 'name' => $user->name];
    } else {
        false;
    }
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
