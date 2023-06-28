<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = DB::table('events')
            ->select(DB::raw('step, COUNT(step) as interacciones '))
            ->groupBy('step')
            ->get();
        $menus = DB::table('events')
            ->select(DB::raw('menu, COUNT(menu) as interacciones '))
            ->where('step', 'Menu')
            ->groupBy('menu')
            ->get();
        return view('home', ['events' => $events, 'menus' => $menus]);
    }
}
