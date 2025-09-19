<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public function generateUniqueId(){
		$random = mt_rand(111,999).mt_rand(11,99).mt_rand(111,999);
		return str_shuffle($random);
	}
	#admin dashboard page
    public function builtUrl($input_lines){
		preg_match_all("/[0-9A-Za-z\s]/", trim($input_lines), $output_array);
		$slug = strtolower(preg_replace("/[\s]/", "-", join($output_array[0])));
		return preg_replace("/-{2,}/", "-", $slug);
	}
	public function slugify($text){
		if(!empty($text)){
			$text = trim($text);
			return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));			
		}
	}
	/*Generate Randam Password*/
	public function __randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for($i = 0; $i < 9; $i++){
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}
	
	#encryptData
    public function encryptData($value = NULL){
        if(!empty($value)){
            $value = trim(preg_replace('/\s+/', ' ', $value));
            date_default_timezone_set('UTC');
            $encryptionMethod = "AES-256-CBC";
            $secret = "MYSECURITYSS12020PKSEncryption19";  //must be 32 char length
            $iv = substr($secret, 0, 16);
            $encryptedText = openssl_encrypt($value, $encryptionMethod, $secret,0,$iv);
            $result = "";
            if($encryptedText != ""){
                $result = trim($encryptedText);
            }
            return $result;
        }else{
            return $value;
        }
    }

    #decryptData
    public function decryptData($value = NULL){
        if(!empty($value)){
            date_default_timezone_set('UTC');
            $encryptionMethod = "AES-256-CBC";
            $secret = "MYSECURITYSS12020PKSEncryption19";  //must be 32 char length
            $iv = substr($secret, 0, 16);
            $decryptedText = openssl_decrypt($value, $encryptionMethod, $secret,0,$iv);
            $result = "";
            if($decryptedText != ""){
                $result = trim($decryptedText);
            }
            return $result;
        }else{
            return $value;
        }
    }
}
