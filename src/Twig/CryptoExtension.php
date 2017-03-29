<?php

namespace Gdbots\Bundle\CryptoBundle\Twig;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\Key;
use Gdbots\Bundle\CryptoBundle\Service\CryptoService;

class CryptoExtension extends \Twig_Extension
{
    /** @var CryptoService */
    protected $svc;

    /** @var boolean */
    protected $debug = false;

    /**
     * @param CryptoService $svc
     * @param bool $debug
     */
    public function __construct(CryptoService $svc, $debug = false)
    {
        $this->svc = $svc;
        $this->debug = (bool) $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('encrypt', [$this, 'encrypt']),
            new \Twig_SimpleFilter('decrypt', [$this, 'decrypt'])
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gdbots_crypto_extension';
    }

    /**
     * Returns an encrypted string or null if it fails.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \Exception
     */
    public function encrypt($string)
    {
        if ( ($enc = $this->svc->encrypt($string)) !== false ) {
            return $enc;
        }
        
        return null;
    }

    /**
     * Returns the decrypted data or null if it fails.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \Exception
     */
    public function decrypt($string)
    {
        if ( ($dec = $this->svc->decrypt($string)) !== false ) {
            return $dec;
        }
        
        return null;
    }
}
