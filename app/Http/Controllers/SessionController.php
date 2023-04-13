<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{

    public function store(String $from)
    {
        $now = now();
        $uuid = Str::uuid();
        $session = new Session();
        $session->from = $from;
        $session->start = $now;
        $session->last_access = $now;
        $session->session_uid = $uuid;
        $session->step = 1;
        $session->save();
        session(['session_from' => $from, 'session_tito' => $session->toJson()]);
        return $session;
    }

    public function update(Session &$session)
    {
        $lifetime = config('session.lifetime');
        $now = Carbon::now();
        $last_access = $session->last_access;
        $diff = strtotime($now) - strtotime($last_access);
        if ($diff <= $lifetime) {
            $session->last_access = $now;
            $session->step += 1;
        } else {
            $session->start = $now;
            $session->last_access = $now;
            $session->step = 1;
        }
        $session->save();
    }

}
