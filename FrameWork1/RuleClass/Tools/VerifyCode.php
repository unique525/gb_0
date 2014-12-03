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
            //    imageSetPixel($im, rand(0, 60), rand(0, 20), $black);
            //}
            //将四个数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
            $strNum = rand(3, 8);
            for ($i = 0; $i < 4; $i++) {
                $strPos = rand(1, 6);
                imagestring($im, 5, $strNum, $strPos, substr($num, $i, 1), $black);
                $strNum+=rand(8, 12);
            }
            header("Content-type: image/PNG");
            ImagePNG($im);
            ImageDestroy($im);
        }
    }

    /**
     * 检查验证码是否正确
     * @param string $sessionName Session的名称
     * @param int $verifyCodeType 返回值类型 0:int 1:json
     * @param string $verifyCodeValue 录入的验证码的值
     * @return mixed 返回 -1:验证码无效 1:正确 0:默认值，未处理
     */
    public static function Check($sessionName, $verifyCodeType, $verifyCodeValue) {
        if ($verifyCodeType == 0) { //返回int值
            $result = -1; //验证码无效，请重试!
            if (strlen($verifyCodeValue) > 0 && strlen($sessionName) > 0) {
                Session_start();
                $num = $_SESSION[$sessionName];

                if ($verifyCodeValue == $num) {
                    $result = 1;
                }
            }
        } else { //返回json
            $result = Control::GetRequest("JsonpCallBack","") . '([{result:-1}])';
            if (strlen($verifyCodeValue) > 0 && strlen($sessionName) > 0) {
                Session_start();
                $num = $_SESSION[$sessionName];
                if ($verifyCodeValue == $num) {
                    $result = Control::GetRequest("JsonpCallBack","") . '([{result:1}])';
                }
            }
        }
        return $result;
    }


    /**
     * 生成Gif动画验证码图片
     * @param string $sessionName Session的名称
     * @param string $content 要生成的内容值，默认为空
     * @param int $width 图片宽度
     * @param int $height 图片高度
     */
    public static function GenGif($sessionName, $content = '', $width = 75, $height = 25) {
        $authority = $content ? $content : ((time() % 2 == 0) ? mt_rand(1000, 9999) : mt_rand(10000, 99999));
        if(strlen($sessionName)<=0){
            return;
        }
        session_start();
        $_SESSION[$sessionName] = $authority;
        $boardWidth = $width;
        $boardHeight = $height;

        //生成一个32帧的GIF动画
        $imageData = array();
        for ($i = 0; $i < 32; $i++) {
            ob_start();
            $image = imagecreate($boardWidth, $boardHeight);
            imagecolorallocate($image, 0, 0, 0);

            // 设定文字颜色数组
            $colorList[] = imagecolorallocate($image, 15, 73, 210);
            $colorList[] = imagecolorallocate($image, 0, 64, 0);
            $colorList[] = imagecolorallocate($image, 0, 0, 64);
            $colorList[] = imagecolorallocate($image, 0, 128, 128);
            $colorList[] = imagecolorallocate($image, 27, 52, 47);
            $colorList[] = imagecolorallocate($image, 51, 0, 102);
            $colorList[] = imagecolorallocate($image, 0, 0, 145);
            $colorList[] = imagecolorallocate($image, 0, 0, 113);
            $colorList[] = imagecolorallocate($image, 0, 51, 51);
            $colorList[] = imagecolorallocate($image, 158, 180, 35);
            $colorList[] = imagecolorallocate($image, 59, 59, 59);
            $colorList[] = imagecolorallocate($image, 0, 0, 0);
            $colorList[] = imagecolorallocate($image, 1, 128, 180);
            $colorList[] = imagecolorallocate($image, 0, 153, 51);
            $colorList[] = imagecolorallocate($image, 60, 131, 1);
            $colorList[] = imagecolorallocate($image, 0, 0, 0);
            $fontcolor = imagecolorallocate($image, 0, 0, 0);
            $gray = imagecolorallocate($image, 245, 245, 245);
            $color = imagecolorallocate($image, 255, 255, 255);
            $color2 = imagecolorallocate($image, 255, 0, 0);
            imagefill($image, 0, 0, $gray);
            $space = 15; // 字符间距
            $top = 0;
            if ($i > 0) { // 屏蔽第一帧
                for ($k = 0; $k < strlen($authority); $k++) {
                    $colorRandom = mt_rand(0, sizeof($colorList) - 1);
                    $floatTop = rand(0, 4);
                    //$float_left = rand(0, 3);
                    imagestring($image, 6, $space * $k, $top + $floatTop, substr($authority, $k, 1), $colorList[$colorRandom]);
                }
            }

            for ($k = 0; $k < 20; $k++) {
                $colorRandom = mt_rand(0, sizeof($colorList) - 1);
                imagesetpixel($image, rand() % 70, rand() % 15, $colorList[$colorRandom]);
            }

            // 添加干扰线
            for ($k = 0; $k < 3; $k++) {
                $colorRandom = mt_rand(0, sizeof($colorList) - 1);
                // $toDrawLine = rand(0, 1);
                $toDrawLine = 1;
                if ($toDrawLine) {
                    imageline($image, mt_rand(0, $boardWidth), mt_rand(0, $boardHeight), mt_rand(0, $boardWidth), mt_rand(0, $boardHeight), $colorList[$colorRandom]);
                } else {
                    $w = mt_rand(0, $boardWidth);
                    $h = mt_rand(0, $boardWidth);
                    imagearc($image, $boardWidth - floor($w / 2), floor($h / 2), $w, $h, rand(90, 180), rand(180, 270), $colorList[$colorRandom]);
                }
            }
            imagegif($image);
            imagedestroy($image);
            $imageData[] = ob_get_contents();
            ob_clean();
            ++$i;
        }

        $gif = new GifEncoder($imageData, 100, 0, 2, 0, 0, 1, "bin");
        header('Content-type:image/gif');
        echo $gif->GetAnimation();
    }

}

?>
