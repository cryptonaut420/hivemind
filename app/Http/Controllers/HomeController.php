<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Models\Burn, Models\UserMeta;
use Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->activated == 0){
            //redirect to BTC burn page
            return redirect(route('account.burn'));
        }        
        
        return view('home', array('user' => $user));
    }
}
