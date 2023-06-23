<?php

      // Encrypt user data 
      function encryptData($data){
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "CapstoneProject";

        return openssl_encrypt($data, $ciphering,
            $encryption_key, $options, $encryption_iv);
      }

      // Decrypt user data
      function decryptData($data){
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = '1234567891011121';
        $decryption_key = "CapstoneProject";

        return openssl_decrypt ($data, $ciphering,
            $decryption_key, $options, $decryption_iv);
      }

?>
