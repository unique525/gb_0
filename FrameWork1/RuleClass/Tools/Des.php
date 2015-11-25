<?php
class Des {



    /**
     * 匹配JAVA的DEC加密
     * @param string $content 要加密的内容
     * @param string $key 加密key
     * @return string 加密后的字符串
     */
    public static function EncryptFitJava($content, $key)
    {
        $content = urlencode($content);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($content) % $block);
        $content .= str_repeat(chr($pad), $pad);
        return mcrypt_encrypt(MCRYPT_DES, $key, $content, MCRYPT_MODE_ECB);
    }

    /**
     * 匹配JAVA的DEC解密
     * @param string $content 要解密的内容
     * @param string $key 加密key
     * @return string 解密后的字符串
     */
    public static function DecryptFitJava($content, $key)
    {
        $content = urldecode($content);
        $content = mcrypt_decrypt(MCRYPT_DES, $key, $content, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($content[($len = strlen($content)) - 1]);
        return substr($content, 0, strlen($content) - $pad);
    }

    /**
     * DEC加密
     * @param string $content 要加密的内容
     * @param string $key 加密key
     * @return string 加密后的字符串
     */
    public static function Encrypt($content,$key) {

        //5.6以上版本不支持8位KEY
        $key = $key.$key;

        $iv = mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
        $passCrypt = mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_ECB, $iv );
        $encode = urlencode( $passCrypt );
        return $encode;
    }

    /**
     * DEC解密
     * @param string $content 要解密的内容
     * @param string $key 加密key
     * @return string 解密后的字符串
     */
    public static function Decrypt($content,$key) {

        //5.6以上版本不支持8位KEY
        $key = $key.$key;

        $decoded = urldecode( $content );
        $iv = mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
        $decrypted = mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv );
        return $decrypted;
    }
}
?>