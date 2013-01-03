<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CryptoUtil {
    
    
    
    public static function hashPassword($password) {
        $hashType = 'sha256';
        return hash($hashType, $password);
    }

    public static function generateToken($length) {

        $charList = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charList .= 'abcdefjhijklmnopqrstuvwxyz';
        $charList .= '1234567890';

        $charListLength = strlen($charList);
        $output = "";
        for ($i = 0; $i < $length; $i++) {
            $num = rand(0, $charListLength - 1);
            $output .= $charList[$num];
        }

        return $output;
    }

    //Encrypts data as a json string
    //Data can be a php object as well
    public static function encryptData($plaintext, $key='defaultPassword') {
        
        $data = array(
            'token' => CryptoUtil::generateToken(5),
            'time' => time(),
            'data' => $plaintext,
        );

        $json = json_encode($data);
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_3DES, $key, $json, MCRYPT_MODE_ECB)));
    
        
    }

    //Decrypts data
    public static function decryptData($ciphertext, $key='defaultPassword') {
        $json = trim(mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($ciphertext), MCRYPT_MODE_ECB));
        
        try{
            $plaintext = json_decode($json, true);
        } catch(Exception $e){
            return false;
        }
        
        //if time is more than 2 days, data is no longer relevant
        if (!$plaintext['time'] || (time() - $plaintext['time'] > 60*60*24*2)){
            return false;
        } else {
            return $plaintext['data'];
        }
    }

}

?>
