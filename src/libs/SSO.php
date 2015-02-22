<?php

/**
 * SSO - A single sign-on module for Sentora.
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-sso
 * @license https://github.com/supared/sentora-sso/blob/master/LICENSE
 * @version 1.0.0
 */
class SSO
{

    /**
     * The crypto key.
     * @var string
     */
    private $key;

    /**
     * The initiation vector.
     * @var string
     */
    private $iv;

    /**
     * The SSO token string.
     * @var string
     */
    private $token;

    /**
     * Holds the unencrypted token data as an array.
     * @var array
     */
    private $data = array();

    /**
     * Registry pattern instance storage.
     * @var SSO
     */
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new SSO();
        }

        return self::$instance;
    }

    private function __clone()
    {
        
    }

    private function __construct()
    {
        if (!$this->mcryptCheck()) {
            die("The mycrypt extention is required for this module!");
        }
    }

    /**
     * Set the encryption key.
     * @param string $key 48-bit string
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set the initiation vector used by the 3DES encryption.
     * @param string $iv 16-bit string
     */
    public function setIv($iv)
    {
        $this->iv = $iv;
    }

    /**
     * Decrypt the given token.
     * @param string $token
     * @return SSO
     */
    public function decrypt($token)
    {
        $this->token = $token;
        $this->tokenDataToArray($this->decryptSSOToken());
        return $this;
    }

    /**
     * Return the SSO data as an array.
     * @return array
     */
    public function ssoData()
    {
        return $this->data;
    }

    /**
     * Checks that the required (mcrypt) PHP extention is available.
     * @return boolean
     */
    private function mcryptCheck()
    {
        if (!function_exists('mcrypt_encrypt') || !function_exists('mcrypt_encrypt')) {
            return false;
        }
        return true;
    }

    /**
     * Converts Hexidecimal strings to ASCII.
     * @param string $hex
     * @return string
     */
    private static function hexToAscii($hex)
    {
        $ascii = "";
        $clean_hex = str_replace(' ', '', $hex);
        for ($i = 0; $i < strlen($clean_hex); $i = $i + 2) {
            $ascii .= chr(hexdec(substr($clean_hex, $i, 2)));
        }
        return $ascii;
    }

    /**
     * Decrypts the object token using the set key and IV.
     * @return string
     */
    private function decryptSSOToken()
    {
        $asciiToken = self::hexToAscii($this->token);
        return mcrypt_decrypt(MCRYPT_3DES, self::hexToAscii($this->key), $asciiToken, MCRYPT_MODE_CBC, self::hexToAscii($this->iv));
    }

    /**
     * Generates an key and value array from the decrypted token.
     * @return void
     */
    private function tokenDataToArray($token)
    {
        $keyvalues = explode(';', $token);
        foreach ($keyvalues as $keyvalue) {
            $sorted = split('=', $keyvalue);
            $this->data[$sorted[0]] = $sorted[1];
        }
    }
}
