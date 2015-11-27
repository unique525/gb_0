<?php

class DesFitAllPlatForm
{

    private static $_instance = NULL;

    /**
     * @return DesFitAllPlatForm
     */

    public static function GetInstance()
    {

        if (is_null(self::$_instance)) {

            self::$_instance = new DesFitAllPlatForm();

        }

        return self::$_instance;

    }


    /**
     * 加密
     * @param string $str 要处理的字符串
     * @param string $key 加密Key，为8个字节长度
     * @return string
     */

    public function Encode($str, $key)
    {

        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);

        $str = $this->PKCS5Padding($str, $size);

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

    public function Decode($str, $key)
    {

        $strBin = base64_decode($str);

        $str = mcrypt_cbc(MCRYPT_DES, $key, $strBin, MCRYPT_DECRYPT, $key);

        $str = $this->PKCS5UnPadding($str);

        return $str;

    }


    function HexToBin($hexData)
    {

        $binData = "";

        for ($i = 0; $i < strlen($hexData); $i += 2) {

            $binData .= chr(hexdec(substr($hexData, $i, 2)));

        }

        return $binData;

    }


    function PKCS5Padding($text, $blockSize)
    {

        $pad = $blockSize - (strlen($text) % $blockSize);

        return $text . str_repeat(chr($pad), $pad);

    }


    function PKCS5UnPadding($text)
    {

        $pad = ord($text{strlen($text) - 1});

        if ($pad > strlen($text))

            return false;


        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)

            return false;


        return substr($text, 0, -1 * $pad);

    }
}

?>