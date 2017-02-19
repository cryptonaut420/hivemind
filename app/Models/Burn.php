<?php
namespace Models;
use Exception;

class Burn
{
    const B58_DIGITS = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    
    const DEFAULT_WORD = 'Burn';
    const MAX_TRIES = 1000;
   
   
   public static function generateBurnAddress($network = 'mainnet')
   {
        $address = false;
        $tries = 0;
        while($address == false AND $tries <= self::MAX_TRIES){
            $prefix = self::DEFAULT_WORD.self::selectRandomWords(2);
            try{
                $address = self::generateUnspendableAddress($prefix, $network);
            }
            catch(Exception $e){
                $address = false;
            }
            $tries++;
        }
        return $address;
       
   }
   
   public static function selectRandomWords($count)
   {
        $list = self::getWordList();
        shuffle($list);
        $words = array_values($list);
        $word_count = count($words);
        $selected = '';
        for($i = 0; $i < $count; $i++){
            $selected .= ucfirst($words[random_int(0, ($word_count-1))]);
        }
        return $selected;
   }
   
   public static function getWordList()
   {
       $get = @file_get_contents(resource_path().'/assets/wordlist.txt');
       if(!$get){
           return false;
       }
       $exp = explode("\n", trim($get));
       return $exp;
   }   
   
   
   /***********************/
 
    protected static function generateUnspendableAddress($name, $network = 'mainnet')
    {
        //Pick a network
        switch($network){
            case 'testnet':
                $prefix_string = 'mv';
                $prefix_bytes = "\x64";
                break;
            case 'mainnet':
                $prefix_string = '1';
                $prefix_bytes = "\x00";
                break;
            default:
                throw new Exception('Unknown btc network');
        }
        
        //Pad and prefix
        $prefixed_name = $prefix_string.$name;
        $padded_prefixed_name = str_pad($prefixed_name, 34, "X");

        //Decode, ignoring (bad) checksum
        $decoded_address = self::base58_decode($padded_prefixed_name, $prefix_bytes);
        
        //Re-encode, calculating checksum
        $address = self::base58_check_encode($decoded_address, $prefix_bytes);

        //double check
        if(self::base58_decode($address, $prefix_bytes) !== $decoded_address){
            throw new Exception('Address encoding mismatch');
        }
        
        return $address;
    }
    
       
    protected static function base58_check_encode($b, $version)
    {
        $d = $version.$b;
        $dhash = self::doublehash($d);
        $dhash = substr($dhash, 0, 4);
        $address = $d.$dhash;

        //Convert bid-endian bytes to integer
        $n = self::bchexdec(utf8_decode(bin2hex($address)));
    
        //Divide that integer into base58
        $res = [];
        while(bccomp($n, "0")  === 1){
            $r = intval(bcmod($n, '58'));     
            $n = bcdiv($n, '58');   
            $res[] = self::B58_DIGITS[$r];
        }
        $res = array_reverse($res);
        $res = join('', $res);

        //Encode leading zeros as base58 zeros
        $czero = 0;
        $pad = 0;
        for($i = 0; $i < strlen($d); $i++){
            $c = $d[$i];
            if($c === $czero){
                $pad++;
            }
            else{
                break;
            }
        }
        
        //return $res;
        $output = str_pad(self::B58_DIGITS[0], $pad).$res;

        return $output;
    }
    
    

    
    protected static function base58_decode($s, $version)
    {
        //Convert the string to an integer
        $n = '0';
        $split_digits = str_split(self::B58_DIGITS);
        for($i = 0; $i < strlen($s); $i++){
            $c = $s[$i];
            $n = bcmul($n, "58"); 
            if(!in_array($c, $split_digits)){
                throw new Exception('Invalid base58 digit');
            }
            $digit = 0;
            foreach($split_digits as $k => $split_digit){
                if($split_digit == $c){
                    $digit = $k;
                    break;
                }
            }
            $n = bcadd($n, (string)$digit);
        }
        
        //Convert the integer to bytes
        $h = self::bcdechex($n);
        if(strlen($h) % 2){
            $h = '0'.$h;
        }       
        $res = hex2bin(utf8_encode($h));
        
        //Add padding back
        $pad = 1;
        $slen = strlen($s)-1;
        for($i = 0; $i < $slen; $i++){
            $c = $s[$i];
            if($c == self::B58_DIGITS[0]){
                $pad++;
            }
            else{
                break;
            }
        }
        $k = str_repeat($version, $pad).$res;

        
        $addrbyte = substr($k, 0, 1);
        $data = substr($k, 2, -4);
        $chk0 = substr($k, -4);

        return $data;
    }    
       
       

    
    // Credit: joost at bingopaleis dot com
    // Input: A decimal number as a String.
    // Output: The equivalent hexadecimal number as a String.
    protected static function dec2hex($number)
    {
        $hexvalues = array('0','1','2','3','4','5','6','7',
                   '8','9','A','B','C','D','E','F');
        $hexval = '';
         while($number != '0')
         {
            $hexval = $hexvalues[bcmod($number,'16')].$hexval;
            $number = bcdiv($number,'16',0);
        }
        return $hexval;
    }    
    
    protected static function doublehash($str)
    {
        $hash1 = hash('sha256', $str, true);
        $hash2 = hash('sha256', $hash1, true);
        return $hash2;
    }
    
    protected static function bchexdec($hex)
    {
      $dec = 0;
      $len = strlen($hex);
      for ($i = 1; $i <= $len; $i++) {
        $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
      }
      return $dec;
    }    
        
    protected static function bcdechex($dec) {
        $hex = '';
        do {    
            $last = bcmod($dec, 16);
            $hex = dechex($last).$hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while($dec>0);
        return $hex;
    }       
    
    
}
