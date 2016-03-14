<?php

class Des
{


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
    public static function Encrypt($content, $key)
    {

        //5.6以上版本不支持8位KEY
        $key = $key . $key;

        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passCrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_ECB, $iv);
        $encode = urlencode($passCrypt);
        return $encode;
    }

    /**
     * DEC解密
     * @param string $content 要解密的内容
     * @param string $key 加密key
     * @return string 解密后的字符串
     */
    public static function Decrypt($content, $key)
    {

        //5.6以上版本不支持8位KEY
        $key = $key . $key;

        $decoded = urldecode($content);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv);
        return $decrypted;
    }

    /**
     * 加密
     * @param string $str 要处理的字符串
     * @param string $key 加密Key，为8个字节长度
     * @return string
     */

    public static function EncryptFitAll($str, $key)
    {

        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);

        $str = self::PKCS5Padding($str, $size);

        $aaa = mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $key);

        $ret = base64_encode($aaa);

        return $ret;

    }


    /**
     * 解密
     * @param string $str 要处理的字符串
     * @param string $key 解密Key，为8个字节长度
     * @return string
     */

    public static function DecryptFitAll($str, $key)
    {

        $strBin = base64_decode($str);

        $str = mcrypt_cbc(MCRYPT_DES, $key, $strBin, MCRYPT_DECRYPT, $key);

        $str = self::PKCS5UnPadding($str);

        return $str;

    }


    private static function PKCS5Padding($text, $blockSize)
    {

        $pad = $blockSize - (strlen($text) % $blockSize);

        return $text . str_repeat(chr($pad), $pad);

    }


    private static function PKCS5UnPadding($text)
    {

        $pad = ord($text{strlen($text) - 1});

        if ($pad > strlen($text))

            return false;


        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)

            return false;


        return substr($text, 0, -1 * $pad);

    }

    /**
     * Encrypt for Obj-c
     * @param $encrypt
     * @param $key
     * @return string
     */
    public static function EncryptForObjc ($encrypt, $key)
    {
        // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 加入 Padding
        $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
        $pad = $block - (strlen($encrypt) % $block);
        $encrypt .= str_repeat(chr($pad), $pad);

        // 不需要設定 IV 進行加密
        $result = mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB);
        return base64_encode($result);
    }

    /**
     * Decrypt for Obj-c
     * @param $decrypt
     * @param $key
     * @return string
     */
    public static function DecryptForObjc($decrypt, $key)
    {
        // 不需要設定 IV
        $str = mcrypt_decrypt(MCRYPT_DES, $key, base64_decode($decrypt), MCRYPT_MODE_ECB);

        // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 移除 Padding
        $pad = ord($str[strlen($str) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }
}

?>