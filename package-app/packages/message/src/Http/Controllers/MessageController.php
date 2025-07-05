<?php

namespace Package\Message\Http\Controllers;

use App\Http\Controllers\Controller; // Appのベースコントローラーを継承

class MessageController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'message' => 'Hello from the MessageController!'
        ]);
    }
}