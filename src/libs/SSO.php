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
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set the initiation vector used by the 3DES encryption.
     * @param type $iv
     */
    public function setIv($iv)
    {
        $this->iv = $iv;
    }

    /**
     * Decrypt the given token.
     * @param string $token
     */
    public function decrypt($token)
    {
        
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
    private function hexToAscii($hex)
    {
        $clean_hex = str_replace(' ', '', $hex);
        for ($i = 0; $i < strlen($clean_hex); $i = $i + 2) {
            $ascii .= chr(hexdec(substr($clean_hex, $i, 2)));
        }
        return $ascii;
    }

    private function decryptSSOToken()
    {
        
    }
}
