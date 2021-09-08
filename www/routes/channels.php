<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

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

Broadcast::channel('App.Models.User.*', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('post-created-event', function ($user) {
    return true;
    // return (int) $user->id === (int) $id;
});

Broadcast::channel('refresh-comments-{postId}', function ($user, $postId) {
    Log::info("Listen", [
        "data" => [
            "name" => "refresh-comments-{$postId}"
        ]
    ]);
    return true;
});

Broadcast::channel('refresh-posts-{type}', function ($user, $type) {
    Log::info("Listen post refresh event", [
        "data" => [
            "name" => "refresh-posts-{$type}"
        ]
    ]);

    return true;
});
