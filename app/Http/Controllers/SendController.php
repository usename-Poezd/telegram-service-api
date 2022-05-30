<?php

namespace App\Http\Controllers;

use danog\MadelineProto\API;
use Illuminate\Http\Request;

class SendController extends Controller
{
    protected API $telegram_api;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(API $telegram_api)
    {
        $this->telegram_api = $telegram_api;
    }

   public function send(Request $request) {
        $this->validate($request, [
            'peer' => 'required',
            'message' => 'required|string'
        ]);

        $this->telegram_api->messages->sendMessage([
            'peer' => $request->peer,
            'message' => $request->message
        ]);

        return response()->json([
            'ok' => true,
        ]);
   }
}
