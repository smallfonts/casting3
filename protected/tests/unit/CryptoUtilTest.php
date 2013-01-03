<?php

class CryptoUtilTest extends CDbTestCase {

    public function testGenerateToken() {
        //length 0
        $output = CryptoUtil::generateToken(0);
        echo $output."\n";
        $this->assertTrue(strlen($output) == 0);

        //length 1
        $output = CryptoUtil::generateToken(1);
        echo $output."\n";
        $this->assertTrue(strlen($output) == 1);
        
        //length 100
        $output = CryptoUtil::generateToken(100);
        echo $output."\n";
        $this->assertTrue(strlen($output) == 100);
        
        //length 200
        $output = CryptoUtil::generateToken(200);
        echo $output."\n";
        $this->assertTrue(strlen($output) == 200);
    }
    
    public function testEncryptAndDecryptWithDefaultKey() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $plaintext = '!@#%*&#!^$';
        $key = '12345678qwertyu';
        
        $ciphertext = CryptoUtil::encryptData($plaintext);
        $log->logInfo("ciphertext :".$ciphertext);
        
        $this->assertTrue($ciphertext != $plaintext);
        
        $ciphertext2 = CryptoUtil::encryptData($plaintext);
        $log->logInfo("ciphertext2 :".$ciphertext2);
        
        $this->assertTrue($ciphertext != $ciphertext2);
        
        $decrypted = CryptoUtil::decryptData($ciphertext);
        $log->logInfo("decrypted :".$decrypted);
        $this->assertTrue($plaintext == $decrypted);
        
        $decrypted2 = CryptoUtil::decryptData($ciphertext,'wrongkey');
        $this->assertTrue($plaintext != $decrypted2);
    }
    
    
    
    public function testEncryptAndDecrypt() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $plaintext = '!@#%*&#!^$';
        $key = '12345678qwertyu';
        
        $ciphertext = CryptoUtil::encryptData($plaintext, $key);
        $log->logInfo("ciphertext :".$ciphertext);
        
        $this->assertTrue($ciphertext != $plaintext);
        
        $ciphertext2 = CryptoUtil::encryptData($plaintext, $key);
        $log->logInfo("ciphertext2 :".$ciphertext2);
        
        $this->assertTrue($ciphertext != $ciphertext2);
        
        $decrypted = CryptoUtil::decryptData($ciphertext,$key);
        $log->logInfo("decrypted :".$decrypted);
        $this->assertTrue($plaintext == $decrypted);
        
        $decrypted2 = CryptoUtil::decryptData($ciphertext,'wrongkey');
        $this->assertTrue($plaintext != $decrypted2);
    }

}

?>
