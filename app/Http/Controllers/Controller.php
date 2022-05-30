<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function getUidFromSession($sessionPath) {
        return str_replace('.safe.php', '', explode(config('telegram.session') . '.', $this->telegram_api->session->getSessionPath())[1]);
    }
}
