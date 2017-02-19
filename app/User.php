<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Models\Burn, Models\UserMeta;
use Exception;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function getBitcoinBurnAddress()
    {
        //see if they have one set already
        $find_burn = UserMeta::getMeta($this->id, 'burn_address');
        if(!$find_burn){
            //generate a new one
            $burn_address = Burn::generateBurnAddress();
            if(!$burn_address){
                throw new Exception('Failed generated fresh bitcoin burn address');
            }
            
            //see if another user already has this one
            $check = UserMeta::where('metaKey', 'burn_address')->where('value', $burn_address)->first();
            if($check){
                //try again..
                return $this->getBitcoinBurnAddress();
            }
            
            //save address
            UserMeta::setMeta($this->id, 'burn_address', $burn_address);
            UserMeta::setMeta($this->id, 'btc_burned', 0);
        }
        else{
            //use saved version
            $burn_address = $find_burn;
        }
        return $burn_address;
    }
    
}
