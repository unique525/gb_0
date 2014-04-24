<?php

/**
 * 提供Gif图片验证码相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Plugins
 * @author zhangchi
 */
class GifEncoder
{
    var $GIF = "GIF89a"; /* GIF header 6 bytes */
    var $VER = "GIFEncoder V2.05"; /* Encoder version */
    var $BUF = Array();
    var $LOP = 0;
    var $DIS = 2;
    var $COL = -1;
    var $IMG = -1;
    var $ERR = Array(
        'ERR00' => "Does not supported function for only one image!",
        'ERR01' => "Source is not a GIF image!",
        'ERR02' => "Unintelligible flag ",
        'ERR03' => "Does not make animation from animated GIF source",
    );

    /**
     * @param array $gifPath   静态gif文件路径数组
     * @param array $gifDelay   每个静态gif文件的显示时间数组，单位百分之一秒
     * @param int $gifLop   循环次数，0表示无限循环
     *      0:  “No disposal method”：不做任何处理直接显示下一帧；
     *      1:  “Leave alone”：和上面一项效果相同，也是不做任何处理直接显示下一帧；
     *      2:  “Background color”：在显示下一帧前先用背景色填充画面；
     *      3:  “Restore previous”：在显示下一帧前先把画面恢复为显示当前帧的上一帧；
     * @param int $gifDis
     * @param int $gifRed  指定静态gif文件上面的透明色，红色代码，如不指定则，统一为-1
     * @param int $gifGreen  指定静态gif文件上面的透明色，绿色代码，如不指定则，统一为-1
     * @param int $gifBlue  指定静态gif文件上面的透明色，蓝色代码，如不指定则，统一为-1
     * @param int $gifMod  一般采用url，指$gifPath指向了静态gif文件
     * */
    public function GifEncoder($gifPath, $gifDelay, $gifLop, $gifDis, $gifRed, $gifGreen, $gifBlue, $gifMod)
    {
        if (!is_array($gifPath) && !is_array($gifDelay)) {
            printf("%s: %s", $this->VER, $this->ERR ['ERR00']);
            exit(0);
        }

        $this->LOP = ($gifLop > -1) ? $gifLop : 0;
        $this->DIS = ($gifDis > -1) ? (($gifDis < 3) ? $gifDis : 3) : 2;
        $this->COL = ($gifRed > -1 && $gifGreen > -1 && $gifBlue > -1) ? ($gifRed | ($gifGreen << 8) | ($gifBlue << 16)) : -1;

        for ($i = 0; $i < count($gifPath); $i++) {
            if (strToLower($gifMod) == "url") {
                $this->BUF [] = fread(fopen($gifPath [$i], "rb"), filesize($gifPath [$i]));
            } else if (strToLower($gifMod) == "bin") {
                $this->BUF [] = $gifPath [$i];
            } else {
                printf("%s: %s ( %s )!", $this->VER, $this->ERR ['ERR02'], $gifMod);
                exit(0);
            }

            if (substr($this->BUF [$i], 0, 6) != "GIF87a" && substr($this->BUF [$i], 0, 6) != "GIF89a") {
                printf("%s: %d %s", $this->VER, $i, $this->ERR ['ERR01']);
                exit(0);
            }

            for ($j = (13 + 3 * (2 << (ord($this->BUF [$i]{10}) & 0x07))), $k = TRUE; $k; $j++) {
                switch ($this->BUF [$i]{$j}) {
                    case "!":
                        if ((substr($this->BUF [$i], ($j + 3), 8)) == "NETSCAPE") {
                            printf("%s: %s ( %s source )!", $this->VER, $this->ERR ['ERR03'], ($i + 1));
                            exit(0);
                        }
                        break;
                    case ";":
                        $k = FALSE;
                        break;
                }
            }
        }

        GifEncoder::GifAddHeader();
        for ($i = 0; $i < count($this->BUF); $i++) {
            GifEncoder::GifAddFrames($i, $gifDelay [$i]);
        }
        GifEncoder::GifAddFooter();
    }

    private function GifAddHeader()
    {
        if (ord($this->BUF [0]{10}) & 0x80) {
            $cMap = 3 * (2 << (ord($this->BUF [0]{10}) & 0x07));
            $this->GIF .= substr($this->BUF [0], 6, 7);
            $this->GIF .= substr($this->BUF [0], 13, $cMap);
            $this->GIF .= "!\377\13NETSCAPE2.0\3\1" . GifEncoder::GifWord($this->LOP) . "\0";
        }
    }

    private function GifAddFrames($i, $d)
    {
        $localsString = 13 + 3 * (2 << (ord($this->BUF [$i]{10}) & 0x07));
        $localsEnd = strlen($this->BUF [$i]) - $localsString - 1;
        $localsTmp = substr($this->BUF [$i], $localsString, $localsEnd);
        $globalLength = 2 << (ord($this->BUF [0]{10}) & 0x07);
        $localsLength = 2 << (ord($this->BUF [$i]{10}) & 0x07);
        $globalRgb = substr($this->BUF [0], 13, 3 * (2 << (ord($this->BUF [0]{10}) & 0x07)));
        $localsRgb = substr($this->BUF [$i], 13, 3 * (2 << (ord($this->BUF [$i]{10}) & 0x07)));
        $localsExt = "!\xF9\x04" . chr(($this->DIS << 2) + 0) .
            chr(($d >> 0) & 0xFF) . chr(($d >> 8) & 0xFF) . "\x0\x0";

        if ($this->COL > -1 && ord($this->BUF [$i]{10}) & 0x80) {
            for ($j = 0; $j < (2 << (ord($this->BUF [$i]{10}) & 0x07)); $j++) {
                if (
                    ord($localsRgb{3 * $j + 0}) == (($this->COL >> 16) & 0xFF) &&
                    ord($localsRgb{3 * $j + 1}) == (($this->COL >> 8) & 0xFF) &&
                    ord($localsRgb{3 * $j + 2}) == (($this->COL >> 0) & 0xFF)
                ) {
                    $localsExt = "!\xF9\x04" . chr(($this->DIS << 2) + 1) .
                        chr(($d >> 0) & 0xFF) . chr(($d >> 8) & 0xFF) . chr($j) . "\x0";
                    break;
                }
            }
        }

        $localImage = null;
        switch ($localsTmp{0}) {
            case "!":
                $localImage = substr($localsTmp, 8, 10);
                $localsTmp = substr($localsTmp, 18, strlen($localsTmp) - 18);
                break;
            case ",":
                $localImage = substr($localsTmp, 0, 10);
                $localsTmp = substr($localsTmp, 10, strlen($localsTmp) - 10);
                break;
        }

        if (ord($this->BUF [$i]{10}) & 0x80 && $this->IMG > -1) {
            if ($globalLength == $localsLength) {
                if (GifEncoder::GifBlockCompare($globalRgb, $localsRgb, $globalLength)) {
                    $this->GIF .= ($localsExt . $localImage . $localsTmp);
                } else {
                    $byte = ord($localImage{9});
                    $byte |= 0x80;
                    $byte &= 0xF8;
                    $byte |= (ord($this->BUF [0]{10}) & 0x07);
                    $localImage{9} = chr($byte);
                    $this->GIF .= ($localsExt . $localImage . $localsRgb . $localsTmp);
                }
            } else {
                $byte = ord($localImage{9});
                $byte |= 0x80;
                $byte &= 0xF8;
                $byte |= (ord($this->BUF [$i]{10}) & 0x07);
                $localImage{9} = chr($byte);
                $this->GIF .= ($localsExt . $localImage . $localsRgb . $localsTmp);
            }
        } else {
            $this->GIF .= ($localsExt . $localImage . $localsTmp);
        }
        $this->IMG = 1;
    }

    private function GifAddFooter()
    {
        $this->GIF .= ";";
    }

    private function GifBlockCompare($globalBlock, $localBlock, $length)
    {
        for ($i = 0; $i < $length; $i++) {
            if (
                $globalBlock{3 * $i + 0} != $localBlock{3 * $i + 0} ||
                $globalBlock{3 * $i + 1} != $localBlock{3 * $i + 1} ||
                $globalBlock{3 * $i + 2} != $localBlock{3 * $i + 2}
            ) {
                return (0);
            }
        }
        return (1);
    }

    private function GifWord($int)
    {
        return (chr($int & 0xFF) . chr(($int >> 8) & 0xFF));
    }

    public function GetAnimation()
    {
        return ($this->GIF);
    }
}