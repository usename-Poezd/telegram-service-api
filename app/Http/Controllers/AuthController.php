<?php

namespace App\Http\Controllers;

use danog\MadelineProto\API;
use danog\MadelineProto\RPCErrorException;
use Illuminate\Http\Request;

class AuthController extends Controller
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

    public function login(Request $request) {
        $this->telegram_api->phoneLogin($request->phone);

        return response()->json([
            'ok' => true,
            'session_uid' => $this->getUidFromSession($this->telegram_api->session->getSessionPath())
        ]);
    }

    public function code(Request $request) {
        $this->validate($request, [
            'code' => 'required'
        ]);

        try {
            $authorization = $this->telegram_api->completePhoneLogin($request->code);
            return response()->json([
                'ok' => true,
                'need_2fa' => $authorization['_'] === 'account.password',
                'authorization' => $authorization,
                'session_uid' => $this->getUidFromSession($this->telegram_api->session->getSessionPath())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
        }




    }

    public function password(Request $request) {
        try {
            $authorization = $this->telegram_api->complete2falogin($request->password);

            return response()->json([
                'ok' => true,
                'authorization' => $authorization,
                'session_uid' => $this->getUidFromSession($this->telegram_api->session->getSessionPath())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    public function check() {
        return response()->json([
            'ok' =>  true,
        ]);
    }

    public function logout(Request $request) {

        $ok = $this->telegram_api->logout();
        if ($ok) {
            foreach (glob(config('telegram.session') . '.' .  $this->getUidFromSession($this->telegram_api->session->getSessionPath()) . '*') as $filename) {
               unlink($filename);
            }
        }



        return response()->json([
            'ok' =>  $ok,
        ]);
    }
}
