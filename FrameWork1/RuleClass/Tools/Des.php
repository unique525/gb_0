<?php
class Des {
    /**
     * 匹配JAVA的DEC加密
     * @param string $content 要加密的内容
     * @param string $key 加密key
     * @return string 加密后的字符串
     */
    public static function Encrypt($content, $key)
    {
        $content = urlencode($content);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($content) % $block);
        $content .= str_repeat(chr($pad), $pad);
        return base64_encode(mcrypt_encrypt(MCRYPT_DES, $key, $content, MCRYPT_MODE_ECB));
    }

    /**
     * 匹配JAVA的DEC解密
     * @param string $content 要解密的内容
     * @param string $key 加密key
     * @return string 解密后的字符串
     */
    public static function Decrypt($content, $key)
    {
        $content = urldecode($content);
        $content = mcrypt_decrypt(MCRYPT_DES, $key, $content, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($content[($len = strlen($content)) - 1]);
        return substr($content, 0, strlen($content) - $pad);
    }
}
?>