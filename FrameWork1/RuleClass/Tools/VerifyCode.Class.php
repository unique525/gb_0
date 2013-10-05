<?php

/**
 * 提供验证码相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class VerifyCode {

    /**
     * 生成验证码图片
     * @param string $sessionName Session的名称
     */
    public static function Gen($sessionName) {
        //将生成的验证码写入session，备验证页面使用
        Session_start();
        if (strlen($sessionName) > 0) {
            //随机生成一个4位数的数字验证码
            $num = rand(1000, 9999);
            $_SESSION[$sessionName] = $num;
            //创建图片，定义颜色值
            header("Content-type: image/PNG");
            srand((double) microtime() * 1000000);
            $im = imagecreate(60, 20);
            $black = ImageColorAllocate($im, 0, 0, 0);
            $gray = ImageColorAllocate($im, 245, 245, 245);
            imagefill($im, 0, 0, $gray);
            //随机绘制两条虚线，起干扰作用
            $style = array($black, $black, $black, $black, $black, $gray, $gray, $gray, $gray, $gray);
            imagesetstyle($im, $style);
            $y1 = rand(0, 20);
            $y2 = rand(0, 20);
            $y3 = rand(0, 20);
            $y4 = rand(0, 20);
            imageline($im, 0, $y1, 60, $y3, IMG_COLOR_STYLED);
            imageline($im, 0, $y2, 60, $y4, IMG_COLOR_STYLED);

            //在画布上随机生成大量黑点，起干扰作用;
            //for ($i = 0; $i < 80; $i++) {
            //    imagesetpixel($im, rand(0, 60), rand(0, 20), $black);
            //}
            //将四个数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
            $strx = rand(3, 8);
            for ($i = 0; $i < 4; $i++) {
                $strpos = rand(1, 6);
                imagestring($im, 5, $strx, $strpos, substr($num, $i, 1), $black);
                $strx+=rand(8, 12);
            }
            ImagePNG($im);
            ImageDestroy($im);
        }
    }

    /**
     * 检查验证码是否正确
     * @param string $sessionName Session的名称
     * @param int $verifyCodeType 返回值类型 0:int 1:jsonp
     * @param string $verifyCodeValue 录入的验证码的值
     * @return mixed 返回 -1:验证码无效 1:正确 0:默认值，未处理
     */
    public static function Check($sessionName, $verifyCodeType, $verifyCodeValue) {
        $result = 0;
        if ($verifyCodeType == 0) { //返回int值
            $result = -1; //验证码无效，请重试!
            if (strlen($verifyCodeValue) > 0 && strlen($sessionName) > 0) {
                Session_start();
                $num = $_SESSION[$sessionName];
                if ($verifyCodeValue == $num) {
                    $result = 1;
                }
            }
        } else { //返回jsonp
            $result = $_GET['jsonpcallback'] . '([{result:-1}])';
            if (strlen($verifyCodeValue) > 0 && strlen($sessionName) > 0) {
                Session_start();
                $num = $_SESSION[$sessionName];
                if ($verifyCodeValue == $num) {
                    $result = $_GET['jsonpcallback'] . '([{result:1}])';
                }
            }
        }
        return $result;
    }
}

?>
