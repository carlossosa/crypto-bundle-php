<?php

namespace Gdbots\Bundle\CryptoBundle\Service;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\Exception\IOException;
use Defuse\Crypto\File;

/**
 * 
 * @author Carlos Sosa
 */
class CryptoService {

    /**
     * 
     * @var Key
     */
    private $key;
    
    /**
     *
     * @var boolean
     */
    private $debug;
    
    /**
     *
     * @var mixed
     */
    private $lastError;

    /**
     * 
     * @param Key $key
     * @param bool $debug
     */
    public function __construct(Key $key, bool $debug = false) {
        $this->key = $key;
        $this->debug = $debug;
    }

    /**
     * 
     * @param string $plaintext
     * @return bool|string
     */
    public function encrypt( string $plaintext){
        try{
            /* Set to No Error*/
            $this->lastError = false;
            
            /* Encrypt*/
            return Crypto::encrypt($plaintext, $this->key);
            
        } catch (CryptoException $ex) {
            
            /* Store Message Error */
            $this->lastError = $ex->getMessage();
            
            return false;
        } catch (\Exception $ex) {
            if ( $this->debug){
                throw $ex;
            } 
            
            return false;
        }
    }
    
    /**
     * 
     * @param string $ciphertext
     * @return bool|string
     */
    public function decrypt( string $ciphertext){
        try{
            /* Set to No Error*/
            $this->lastError = false;
            
            /* Decrypt */
            return Crypto::decrypt( $ciphertext, $this->key);
            
        } catch (CryptoException $ex) {
            
            /* Store Message Error */            
            $this->lastError = $ex->getMessage();
            
            return false;
        } catch (\Exception $ex) {
            if ( $this->debug){
                throw $ex;
            } 
            
            return false;
        }
    }
    
    /**
     * Return false if not error.
     * 
     * @return bool|string
     */
    public function getLastError()
    {
        return $this->lastError;
    }
    
    
    public function encryptFile ( $plainFile, $encryptedFile) {
        try {
            /* Reset last error */
            $this->lastError = false;
            
            /* Open stream if $planFile is a path */
            if ( ! is_resource($plainFile)) {
                if ( !is_string($plainFile) || 
                        ! file_exists($plainFile) || 
                        ($plainHandler = fopen($plainFile, 'rb')) === false ){
                    throw new IOException('Cannot open input file for encrypting.');
                }
            } else {
                $plainHandler = $plainFile;
            }

            /* Open stream if $encryptedFile is a path */
            if ( ! is_resource($encryptedFile)) {
                if ( !is_string($encryptedFile) || 
                        ! file_exists($encryptedFile) || 
                        ($encryptedHandler = fopen($encryptedFile, 'wb')) === false ){
                    throw new IOException('Cannot open input file for encrypting.');
                }
            } else {
                $encryptedHandler = $encryptedFile;
            }
        
            File::encryptResource( $plainHandler, $encryptedHandler, $this->key);
            
            return true;
        } catch (CryptoException $ex) {
            $this->lastError = $ex->getMessage();
            
            return false;
        } catch (\Exception $ex) {
            if ( $this->debug){
                throw $ex;
            } 
            
            return false;
        }
    }
    
    public function decryptFile ( $encryptedFile, $plainFile) {
        try {
            /* Reset last error */
            $this->lastError = false;
            
            /* Open stream if $planFile is a path */
            if ( ! is_resource($plainFile)) {
                if ( !is_string($plainFile) || 
                        ! file_exists($plainFile) || 
                        ($plainHandler = fopen($plainFile, 'rb')) === false ){
                    throw new IOException('Cannot open input file for encrypting.');
                }
            } else {
                $plainHandler = $plainFile;
            }

            /* Open stream if $encryptedFile is a path */
            if ( ! is_resource($encryptedFile)) {
                if ( !is_string($encryptedFile) || 
                        ! file_exists($encryptedFile) || 
                        ($encryptedHandler = fopen($encryptedFile, 'wb')) === false ){
                    throw new IOException('Cannot open input file for encrypting.');
                }
            } else {
                $encryptedHandler = $encryptedFile;
            }
        
            File::decryptResource( $encryptedHandler, $plainHandler, $this->key);
            
            return true;
        } catch (CryptoException $ex) {
            $this->lastError = $ex->getMessage();
            
            return false;
        } catch (\Exception $ex) {
            if ( $this->debug){
                throw $ex;
            } 
            
            return false;
        }
    }
}
