<?php
namespace Wepesi\App;

class JWT
{
    private $decryption_key, $app_encryption_iv, $encryption_key, $iv_length;
    const CIPHERING = "AES-128-CTR";
    const OPTION = 110;
    function __construct()
    {
        $this->decryption_key = bin2hex(random_bytes(32));
        $this->encryption_key = $this->decryption_key;
        $this->iv_length = 16;
        // Use OpenSSl Encryption method
        $this->iv_length = openssl_cipher_iv_length(self::CIPHERING);
        // Non-NULL Initialization Vector for encryption
        $this->app_encryption_iv = random_bytes($this->iv_length);
    }
    function generate(array $data, string $cypherkey = null)
    {
        try {
            if (!isset($data["data"]) && !empty($data["data"])) {
                throw new \Exception("no data to be managed");
            }
            $expired = isset($data["expired"]) ? $data["expired"] : 3600;
            $time = strtotime("now + $expired second");
            $data["time"] = $time;
            $_token = bin2hex($this->app_encryption_iv) . "." . $this->cryptData($data, $cypherkey) . "." . $this->encryption_key;
            return $_token;
        } catch (\Exception $ex) {
            return ["exception" => $ex->getMessage()];
        }
    }
    function decode(string $token_value, string $cypherkey = null)
    {
        try {
            $decrypt_data = $this->decryptData($token_value, $cypherkey);
            if(isset($decrypt_data['exception'])){
                return $decrypt_data;
            }
            if (!is_array($decrypt_data)) return false;
            $decrypt_time = isset($decrypt_data["time"]) ? $decrypt_data["time"] : 0;
            $_thisTime = strtotime("now");
            if (($_thisTime - $decrypt_time) > 0) {
                throw new \Exception("token expired");
            }
            return $decrypt_data;
        } catch (\Exception $ex) {
            return ["exception" => $ex->getMessage()];
        }
    }

    private function cryptData(array $data, string $cypherkey = null)
    {
        $simple_string = json_encode($data, true);
        // Store the encryption key
        $this->encryption_key = $cypherkey ?? $this->decryption_key;
        // Use openssl_encrypt() function to encrypt the data
        return openssl_encrypt(
            $simple_string,
            self::CIPHERING,
            $this->encryption_key,
            self::OPTION,
            $this->app_encryption_iv
        );
    }
    private function decryptData(string $token_key, string $cypherkey = null)
    {
        try{
            $explode = explode(".", $token_key);
            $this->decryption_key = $cypherkey ?? $explode[2];            
            $this->app_encryption_iv = hex2bin($explode[0]);
            if(strlen($this->app_encryption_iv)!=16){
                throw new \Exception("token invalid");
            }
            $token_key = $explode[1];
            $decryption = openssl_decrypt(
                $token_key,
                self::CIPHERING,
                $this->decryption_key,
                self::OPTION,
                $this->app_encryption_iv,
            );
            return json_decode($decryption, true);
        }catch(\Exception $ex){
            return ["execption"=>$ex->getMessage()];
        }
        
    }
}
