<?php
phpinfo();

die();
Gen("ddd");
function Gen($sessionName) {
    //将生成的验证码写入session，备验证页面使用
    Session_start();
    if (strlen($sessionName) > 0) {
        //随机生成一个4位数的数字验证码
        $num = rand(1000, 9999);
        $_SESSION[$sessionName] = $num;
        //创建图片，定义颜色值

        if(function_exists("ImageCreate")){
            echo 111;
        }else{
            echo 222;
        }
        die();
        srand((double) microtime() * 1000000);
        $im = ImageCreate(60, 20);
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
        echo 11;
        //header("Content-type: image/PNG");
        //ImagePNG($im);
        //ImageDestroy($im);
    }
}
?>
