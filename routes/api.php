<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/line', function (Request $request) {

    $events = $request->input('events', []);

    foreach ($events as $event) {

        if (($event['type'] ?? '') === 'message') {

            $userId = $event['source']['userId'] ?? null;

            if ($userId) {
                file_put_contents(
                    storage_path('logs/line.txt'),
                    "USER_ID: ".$userId.PHP_EOL,
                    FILE_APPEND
                );
            }
        }
    }

    return response()->json(['status' => 'ok']);
});