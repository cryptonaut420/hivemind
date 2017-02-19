<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Models\Burn, Models\UserMeta;
use Auth, Config, Exception, Session;
use \Blocktrail\SDK\BlocktrailSDK;

class AccountController extends Controller
{
    
    public function showBurnActivation()
    {
        $user = Auth::user();
        if($user->activated == 1){
            //redirect to dashboard
            return redirect('/home');
        }               
        
        $burn_address = $user->getBitcoinBurnAddress();
        $burn_req = Config::get('settings.burn_requirement');
        $btc_burned = UserMeta::getMeta($user->id, 'btc_burned');
        
        return view('account.burn', array('user' => $user, 'burn_address' => $burn_address, 'burn_req' => $burn_req, 'btc_burned' => $btc_burned));
        
    }
    
    
    public function checkBTCBurned()
    {
        $user = Auth::user();
        if($user->activated == 1){
            //redirect to dashboard
            return redirect('/home');
        }   
        
        //load burn address
        $burn_address = $user->getBitcoinBurnAddress();
        $burn_req = Config::get('settings.burn_requirement');
        
        //check how much their burn address has received
        try{
            $blocktrail = new BlocktrailSDK(env('BLOCKTRAIL_KEY'), env('BLOCKTRAIL_SECRET'), "BTC", false /* livenet */);
            $burn_details = $blocktrail->address($burn_address);
        }
        catch(Exception $e){
            Session::flash('message', 'Error connecting to blocktrail');
            Session::flash('message-class', 'alert-danger');
            return redirect(route('account.burn'));
        }
        
        $received = $burn_details['received'];
        UserMeta::setMeta($user->id, 'btc_burned', $received);
        
        if($received >= $burn_req){
            $user->activated = 1;
            $user->save();
            Session::flash('message', 'Burn requirement met! Enjoy your stay');
            Session::flash('message-class', 'alert-success');                
            return redirect('/home');
        }
        
        Session::flash('message', 'Not enough BTC burned');
        Session::flash('message-class', 'alert-warning');        
        
        return redirect(route('account.burn'));

    }
    
    
    public function showSettings()
    {
        
        
    }
    
    public function updateSettings()
    {
        
    }
    
}
