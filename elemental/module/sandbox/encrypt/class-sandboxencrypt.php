<?php

//namespace ParagonIE\EasyRSA;
namespace ElementalPlugin\Module\Sandbox\Encrypt;

require_once("vendor/autoload.php");
// PHPSecLib:
use ParagonIE\EasyRSA\Exception\InvalidKeyException;
use \phpseclib\Crypt\RSA;
// defuse/php-encryption:
use \ParagonIE\ConstantTime\Base64;
use \Defuse\Crypto\Key;
use \Defuse\Crypto\Crypto;
// Typed Exceptions:

use \ParagonIE\EasyRSA\Exception\InvalidCiphertextException;
use \ParagonIE\EasyRSA\EasyRSAInterface;
use \ParagonIE\EasyRSA\PublicKey;
use \ParagonIE\EasyRSA\Kludge;

class SandboxEncrypt implements EasyRSAInterface
{
    const SEPARATOR = '';
    const VERSION_TAG = "";

    static private $rsa;
    /**
     * Set RSA to use in between calls
     *
     * @param RSA|null $rsa
     */
    public static function setRsa(RSA $rsa = null)
    {
        self::$rsa = $rsa;
    }

    /**
     * Get RSA
     *
     * @param int $mode
     *
     * @return RSA
     */
    public static function getRsa($mode)
    {
        if (self::$rsa) {
            $rsa = self::$rsa;
        } else {
            $rsa = new RSA();
            $rsa->setMGFHash('sha256');
        }

        $rsa->setSignatureMode($mode);

        return $rsa;
    }

    /**
     * KEM+DEM approach to RSA encryption.
     *
     * @param string $plaintext
     * @param PublicKey $rsaPublicKey
     *
     * @return string
     */
    public static function encrypt($plaintext,  $rsaPublicKey)
    {

        $pub = new PublicKey($rsaPublicKey);
        // Random encryption key
        $random_key = random_bytes(32);

        // Use RSA to encrypt the random key
        $rsaOut = self::rsaEncrypt($random_key, $pub);

        // Generate a symmetric key from the RSA output and plaintext
        $symmetricKey = hash_hmac(
            'sha256',
            $rsaOut,
            $random_key,
            true
        );
        $ephemeral = self::defuseKey(
            $symmetricKey
        );

        // Now we encrypt the actual message
        $symmetric = Base64::encode(
            Crypto::encrypt($plaintext, $ephemeral, true)
        );

        $packaged = \implode(
            self::SEPARATOR,
            array(
                self::VERSION_TAG,
                Base64::encode($rsaOut),
                $symmetric
            )
        );

        $checksum = \substr(
            \hash('sha256', $packaged),
            0,
            16
        );

        // Return the ciphertext
        return $packaged . self::SEPARATOR . $checksum;
    }


    /**
     * Verify with RSASS-PSS + MGF1+SHA256
     *
     * @param string $message
     * @param string $signature
     * @param PublicKey $rsaPublicKey
     * @return bool
     */
    public static function verify($message, $signature, PublicKey $rsaPublicKey)
    {
        $rsa = self::getRsa(RSA::SIGNATURE_PSS);

        $return = $rsa->loadKey($rsaPublicKey->getKey());
        if ($return === false) {
            throw new InvalidKeyException('Verification failed due to invalid key');
        }

        return $rsa->verify($message, $signature);
    }

    /**
     * Decrypt with RSAES-OAEP + MGF1+SHA256
     *
     * @param string $plaintext
     * @param PublicKey $rsaPublicKey
     * @return string
     * @throws InvalidCiphertextException
     */
    public static function rsaEncrypt($plaintext,  $rsaPublicKey)
    {
        $rsa = self::getRsa(RSA::ENCRYPTION_OAEP);
        $pub = new PublicKey($rsaPublicKey);
        $return = $rsa->loadKey($pub->getKey()); //->setMGFHash('sha256');;
        // ->setHash('sha256')
        // ->setMGFHash('sha256');
        if ($return === false) {
            throw new InvalidKeyException('Ecryption failed due to invalid key');
        }

        return Base64::encode($rsa->encrypt($plaintext));
    }


    /**
     * Use an internally generated key in a Defuse context
     *
     * @param string $randomBytes
     * @return Key
     */
    protected static function defuseKey($randomBytes)
    {
        $kludege = new Kludge();
        return $kludege->defuseKey($randomBytes);
    }
}
