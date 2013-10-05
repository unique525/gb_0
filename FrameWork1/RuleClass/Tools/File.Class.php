<?php

/**
 * 提供文件操作相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class File {

    /**
     * 创建文件夹(777权限)
     * @param string $path
     */
    public static function CreateFolder($path) {
        if (!file_exists($path)) {
            self::CreateFolder(dirname($path));
            mkdir($path, 0777);
        }
    }

    /**
     * 写入文件
     * @param string $fileName 文件路径
     * @param mixed $fileData 要写入文件的数据。可以是字符串、数组或数据流。
     */
    public static function Write($fileName, $fileData) {
        if (!empty($fileName)) {
            //目录处理
            $dir = dirname($fileName);
            File::MkDirs($dir);

            $fp = fopen($fileName, "w+"); //打开文件指针，创建文件
            if (!is_writable($fileName)) {
                die("文件:" . $fileName . "不可写，发布失败，请联系技术人员处理！");
            }
            file_put_contents($fileName, $fileData);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据数组数据写入文件
     * @param array $arrFileContents 写入数据信息数组 {dest,source,content}
     * @return int 写入结果 
     */
    public static function WriteQueue(&$arrFileContents = null) {
        $result = 0;
        if (empty($arrFileContents)) {
            $result = -5;           //上传数组为空
        } else {
            for ($i = 0; $i < count($arrFileContents); $i++) {
                $destPath = P_PATH . DIRECTORY_SEPARATOR . $arrFileContents[$i]["dest"];


                $destPath = str_ireplace("/", DIRECTORY_SEPARATOR, $destPath);
                $destPath = str_ireplace("\\", DIRECTORY_SEPARATOR, $destPath);
                $source = P_PATH . DIRECTORY_SEPARATOR . $arrFileContents[$i]["source"];

                $source = str_ireplace("/", DIRECTORY_SEPARATOR, $source);
                $source = str_ireplace("\\", DIRECTORY_SEPARATOR, $source);
                $sourceContent = $arrFileContents[$i]["content"];


                if ($destPath != $source) { //写本地文件时，目标路径和来源路径一致时，不进行操作
                    if (strlen($sourceContent) > 0) {     //内容
                        $isWrite = File::Write($destPath, $sourceContent);
                    } else {            //附件文件
                        $isWrite = File::Move($source, $destPath);
                    }

                    if ($isWrite) {
                        $arrFileContents[$i]["result"] = 1;
                    } else {
                        $arrFileContents[$i]["result"] = 0;
                    }
                }
            }
            $result = 1;    //写入成功
        }
        return $result;
    }

    /**
     * 根据来源文件写入目标文件
     * @param type $destPath 目标文件路径
     * @param type $sourcePath 来源文件路径
     * @param type $sourceContent 写入内容
     * @return int 返回写入结果 $result = -5 目标为空 $result = 1 写入成功
     */
    public static function WriteSingle($destPath, $sourcePath, $sourceContent) {
        $result = 0;
        if (empty($sourcePath) && empty($sourceContent)) {
            $result = -5;           //来源为空
        } else {
            $destPath = P_PATH . DIRECTORY_SEPARATOR . $destPath;
            $destPath = str_ireplace("/", DIRECTORY_SEPARATOR, $destPath);
            $destPath = str_ireplace("\\", DIRECTORY_SEPARATOR, $destPath);

            $sourcePath = P_PATH . DIRECTORY_SEPARATOR . $sourcePath;
            $sourcePath = str_ireplace("/", DIRECTORY_SEPARATOR, $sourcePath);
            $sourcePath = str_ireplace("\\", DIRECTORY_SEPARATOR, $sourcePath);

            if (strlen($sourceContent) > 0) {     //内容
                $isWrite = File::Write($destPath, $sourceContent);
            } else {
                $isWrite = File::Move($sourcePath, $destPath);
            }
            $result = 1;    //写入成功
        }
        return $result;
    }

    /**
     * 移动并重命名文件
     * @param string $sourcePath 来源文件路径
     * @param string $destPath 目标文件路径
     * @return boolean 返回逻辑值 
     */
    public static function Move($sourcePath, $destPath) {
        $sourcePath = str_ireplace("//", "/", $sourcePath);
        $destPath = str_ireplace("//", "/", $destPath);
        if (!empty($sourcePath) && !empty($destPath) && is_file($sourcePath) && $sourcePath != $destPath) {

            //self::MkDirs($destPath);

            return rename($sourcePath, $destPath);
        } else {
            return false;
        }
    }

    /**
     * 递归创建目录
     * @param string $dirPath 文件夹路径
     */
    public static function MkDirs($dirPath) {
        $dirPath = explode(DIRECTORY_SEPARATOR, $dirPath);
        for ($i = 0; $i < count($dirPath); $i++) {
            $mkdir .= $dirPath[$i] . DIRECTORY_SEPARATOR;
            $mkdir = str_replace("//", "/", $mkdir);
            @mkdir($mkdir, 0777);
        }
    }

    /**
     * 复制文件到目标路径
     * @param string $sourcePath     源文件路径
     * @param string $destPath        目标文件路径
     */
    public static function Copy($sourcePath, $destPath) {
        if (file_exists($sourcePath)) {       //判断源文件是否存在
            $dir = dirname($destPath);       //判断目标文件夹是否存在
            if (!file_exists($dir)) {           //不存在就创建
                File::MkDirs($dir);
            }
            @copy($sourcePath, $destPath);
        }
    }

    /**
     * 将文件夹打包成zip文件
     * @param string $sourcePath 要打包的源文件
     * @param ZipArchive $zip ZipArchive对象
     */
    public static function AddFileToZip($sourcePath, $zip) {
        $handler = opendir($sourcePath);      //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {       //文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($sourcePath . "/" . $filename)) {          // 如果读取的某个对象是文件夹，则递归
                    File::AddFileToZip($sourcePath . "/" . $filename, $zip);
                } else {         //将文件加入zip对象
                    $zip->addFile($sourcePath . "/" . $filename);
                }
            }
        }
        @closedir($sourcePath);
    }

    /**
     * 递归删除目录
     * @param string $dirPath 要删除的目录路径
     */
    public static function RmDirs($dirPath) {
        $dir = dir($dirPath);
        while (false !== ($childDirPath = $dir->read())) {
            if ($childDirPath != '.' && $childDirPath != '..') {
                if (is_dir($dirPath . '/' . $childDirPath))
                    RmDirs($dirPath . '/' . $childDirPath);
                else
                    unlink($dirPath . '/' . $childDirPath);
            }
        }
        $dir->close();
        rmdir($dirPath);
    }

    /**
     * 取得文件后缀名
     * @param string $fileName 要处理的文件名
     * @return string 文件后缀名
     */
    public static function GetEx($fileName) {
        $index = strrpos($fileName, '.');
        $fileEx = substr($fileName, $index + 1);
        return $fileEx;
    }

    /**
     * 取得文件名+后缀名
     * @param string $fileName 要处理的文件名
     * @return string 文件名+后缀名 
     */
    public static function GetNameAndEx($fileName) {
        $index = strrpos($fileName, '\\');
        $fileEx = substr($fileName, $index + 1);
        return $fileEx;
    }

    /**
     * 删除文件
     * @param string $fileName 要处理的文件名
     * @return boolean 是否成功
     */
    public static function DelFile($fileName) {
        if (file_exists($fileName)) {
            if (!is_dir($fileName)) {
                if (unlink($fileName)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * 删除文件夹
     * @param string $dirPath 要处理的文件夹路径
     * @return boolean 是否成功
     */
    public static function DelDir($dirPath) {
        if (is_dir($dirPath)) {
            $dh = opendir($dirPath);
            while ($file = readdir($dh)) {
                if ($file != "." && $file != "..") {
                    $fullPath = $dirPath . "/" . $file;
                    if (!is_dir($fullPath)) {
                        unlink($fullPath);
                    } else {
                        self::DelDir($fullPath);
                    }
                }
            }

            closedir($dh);

            if (rmdir($dirPath)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 生成缩略图
     * @param string $source    原图
     * @param int $toWidth         生成缩略图宽度
     * @param int $toHeight         生成缩略图高度
     * @param string $thumbFileName   
     * @param int $isThumb     为1则处理缩略图高度,否则不处理缩略图,返回原图
     * @return 成功 -- 新图片地址
     *      失败 -- -1:原文件不存在, 
     *              -2:源图片过大,需要服务器内存支持GD;, 
     *              -3:GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式;
     *              -4:缩略图创建失败,
     *              -5;no rewrite;
     */
    public static function CreatThumb($source, $toWidth = 0, $toHeight = 0, $thumbFileName = "thumb", $isThumb = 1) {
        $error = 0;
        $absolutelyPath = P_PATH . str_ireplace("/", DIRECTORY_SEPARATOR, $source);         //绝对路径

        if (!file_exists($absolutelyPath)) {
            $error = -1;        //'源图片文件不存在!'.$source;
            return $error;
        }

//设置memory_limit 
        if (function_exists('ini_get')) {
            $memorylimit = @ini_get('memory_limit');
            if ($memorylimit && self::return_bytes($memorylimit) < 335544320 && function_exists('ini_set')) {        //320M
                @ini_set('memory_limit', '4000m');
            }
        }

        $imgtype = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
        $data = @getimagesize($absolutelyPath);    //取得原文件信息
        if ($data[0] > 8000 || $data[1] > 8000) {     //宽与高的值不能大于8000
            $error = -2;        //源图片过大,需要服务器内存支持GD;
            return $error;
        }
        $thumbname = '.' . $thumbFileName . '.' . $imgtype[$data[2]]; //缩略图名
        $thumbname = str_ireplace("jpeg", "jpg", $thumbname);

        $to_File = $absolutelyPath . $thumbname;            //要生成的缩略图路径

        $func_create = "imagecreatefrom" . $imgtype[$data[2]];      //取得GD库函数类型
        if (!function_exists($func_create)) {
            $error = -3;        //你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式;
            return $error;
        }

        $absolutely_id = @$func_create($absolutelyPath);    //GD函数
        $width = @ImageSX($absolutely_id);        //原图宽
        $height = @ImageSY($absolutely_id);       //原图高
        if ($toWidth > $width && $toHeight > $height && $isThumb == 0) {         //原图都小于要生成的图
            return $source;          //直接返回原图
        } else {
            if ($toWidth < $width || $toHeight < $height) {
                if ($toWidth <= 0 && $toHeight <= 0) {         //都为0则缩略图不处理大小
                    $toWidth = $width;
                    $toHeight = $height;
                } else {
                    if ($toWidth > 0 && $toHeight <= 0) {
                        $toHeight = round($height * ($toWidth / $width));    //按宽度缩小
                    } else if ($toHeight > 0 && $toWidth <= 0) {
                        $toWidth = round($width * ($toHeight / $height));    //按高度缩小
                    } else if ($toWidth > 0 && $toHeight > 0) {

                        if ($width > $height) { //原图宽度大于高度 按宽度缩小
                            $toHeight = round($height * ($toWidth / $width));
                        } else {
                            $toWidth = round($width * ($toHeight / $height));    //按高度缩小
                        }
                    }
                }
            } else {
                $toWidth = $width;
                $toHeight = $height;
            }
        }

//        $toWH = $toW / $toH;
//        $srcWH = $width / $height;
//        if ($toWH <= $srcWH) {
//            $ftoW = $toW;
//            $ftoH = $ftoW * ($height / $width);
//        } else {
//            $ftoH = $toH;
//            $ftoW = $ftoH * ($width / $height);
//        }
//
//        if ($width > $toW || $height > $toH) {
//            $cImg = self::CreatImage($absolutely_id, $ftoW, $ftoH, 0, 0, 0, 0, $width, $height);        //创建文件流内容
//        } else {
//            $cImg = self::CreatImage($absolutely_id, $width, $height, 0, 0, 0, 0, $width, $height);
//        }
        $cImg = self::CreatImage($absolutely_id, $toWidth, $toHeight, 0, 0, 0, 0, $width, $height);        //创建文件流内容

        if (function_exists('imagejpeg')) {
            $result = @ImageJpeg($cImg, $to_File, 100);           //保存文件  ImageJpeg($cImg);       输出文件流 100%的高清
        } else {
            $result = @ImagePNG($cImg, $to_File, 100);
        }
        @ImageDestroy($cImg);        //清除缓存文件流内容

        if ($result == 1) {
            if (!file_exists($to_File)) {
                $error = -4;        //缩略图创建失败;
                return $error;
            }
        } else {
            $error = -5;        //no rewrite;
            return $error;
        }
//echo $to_File . "<br>" . $result . "<br>" . $error;
        $to_File = str_ireplace("../", "/", $to_File);
        $to_File = str_ireplace("./", "/", $to_File);
        $to_File = str_ireplace("//", "/", $to_File);

        return $source . $thumbname;          //原图加缩略图名
    }

    /**
     * 题图进行缩略处理
     * @param string $source    原文件路径
     * @param int $toWidth      处理后的宽度；为0时表示不作处理
     * @param int $toHeight     处理后的高度；为0时表示不作处理
     * @param string $thumbFileName 处理后生成图命名
     * @return string   返回处理后的路径
     */
    public static function CreatDocumentNewsTitlePic($source, $toWidth = 0, $toHeight = 0, $thumbFileName = "thumb") {
        $result = 0;
        $absolutelyPath = P_PATH . str_ireplace("/", DIRECTORY_SEPARATOR, $source);         //绝对路径

        if (!file_exists($absolutelyPath)) {
            $result = -1;        //'源图片文件不存在!'.$source;
            return $result;
        }

        //设置memory_limit
        if (function_exists('ini_get')) {
            $memorylimit = @ini_get('memory_limit');
            if ($memorylimit && self::return_bytes($memorylimit) < 335544320 && function_exists('ini_set')) {        //320M
                @ini_set('memory_limit', '4000m');
            }
        }

        $imgtype = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
        $data = @getimagesize($absolutelyPath);    //取得原文件信息
        if ($data[0] > 8000 || $data[1] > 8000) {     //宽与高的值不能大于8000 返回原图，不作任何处理
            $result = -2;        //'源图片文件尺寸太大!'.$source;
            return $result;
        }
        $thumbname = '.' . $thumbFileName . '.' . $imgtype[$data[2]]; //缩略图名
        $thumbname = str_ireplace("jpeg", "jpg", $thumbname);

        $to_File = $absolutelyPath . $thumbname;            //要生成的缩略图路径

        $func_create = "imagecreatefrom" . $imgtype[$data[2]];      //取得GD库函数类型
        if (!function_exists($func_create)) {       //你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式;
            $result = -3;        //'服务器不支持 imagecreatefrom函数
            return $result;
        }

        $absolutely_id = @$func_create($absolutelyPath);    //GD函数
        $width = @ImageSX($absolutely_id);        //原图宽
        $height = @ImageSY($absolutely_id);       //原图高
        $creatWidth = $width;               //处理后的宽度
        $creatHeight = $height;             //处理后的高度
        if (($toWidth > $width) && ($toHeight > $height)) {       //原图的高与宽都小于要处理的尺寸时就不作处理
            $result = -6;
            return $result;          //直接返回原图
        } else {
            if ($toWidth == 0) {        //设置宽度设置为0 表示宽度可灵活设置
                if ($toHeight == 0) {   //设置高度设置为0 表示高度可灵活设置
                    $result = -7;
                    return $result;          //都设置为0时，不作任何处理，直接返回原图
                } elseif ($toHeight < $height) {     //设置宽度设置0  设置高度小于实际高度时:高度为设置高度，宽度按比例处理
                    $creatHeight = $toHeight;      //等于设置高度
                    $creatWidth = round($creatHeight * ($width / $height));     //宽度按比例处理
                } else {            //设置宽度设置为0 设置高度大于实际高度时：高度与宽度都取实际值
                    $result = -8;
                    return $result;          //都取原图时，不作任何处理，直接返回原图
                }
            } elseif ($toWidth < $width) {      //设置宽度小于实际宽度时
                $creatWidth = $toWidth;         //宽度为设置的宽度
                if ($toHeight == 0) {           //设置宽度小于实际宽度时，设置高度为0：宽度为设置宽度，高度按比例
                    $creatHeight = round($creatWidth * ($height / $width));      //按比例处理
                } elseif ($toHeight < $height) {        //设置宽度小于实际宽度时，设置高度小于实际高度时，按设置处理
                    $creatHeight = $toHeight;
                } else {                            //设置宽度小于实际宽度时，且设置高度大于实际高度时，宽度取设置值，高度取实际的
                    $creatHeight = $height;
                }
            } else {                        //设置宽度大于实际宽度，不作宽度处理
                $creatWidth = $width;
                if ($toHeight == 0) {       //设置宽度大于实际宽度，且设置高度为0时，不作宽与高度处理,直接返回
                    $result = -9;
                    return $result;          //都设置为0时，不作任何处理，直接返回原图
                } elseif ($toHeight < $height) {            //设置宽度大于实际宽度，设置高度小于实际高度时，宽取实际，高度按比例处理
                    $creatHeight = round($creatWidth * ($height / $width));
                } else {                                    //设置宽度大于实际宽度，设置高度大于实际高度时，宽与高都不作处理，为原图
                    $result = -10;
                    return $result;          //都取原图时，不作任何处理，直接返回原图
                }
            }
        }

        $cImg = self::CreatImage($absolutely_id, $creatWidth, $creatHeight, 0, 0, 0, 0, $width, $height);        //创建文件流内容

        if (function_exists('imagejpeg')) {
            $imageResult = @ImageJpeg($cImg, $to_File, 100);           //保存文件  ImageJpeg($cImg);       输出文件流 100%的高清
        } else {
            $imageResult = @ImagePNG($cImg, $to_File, 100);
        }
        @ImageDestroy($cImg);        //清除缓存文件流内容

        if ($imageResult == 1) {
            if (!file_exists($to_File)) {
                $result = -4;        //缩略图创建失败;
            } else {
                //echo $to_File . "<br>" . $result . "<br>" . $error;
                $to_File = str_ireplace("../", "/", $to_File);
                $to_File = str_ireplace("./", "/", $to_File);
                $to_File = str_ireplace("//", "/", $to_File);
                $result = $source . $thumbname;
            }
        } else {
            $result = -5;        //no rewrite;
        }

        return $result;          //原图加缩略图名
        //return $source . $thumbname;          //原图加缩略图名
    }

    /**
     * 判断图片文件是否边长超过了限制值
     * @param string $filePath 图片文件路径
     * @param int $maxValue 宽高限制的最大值
     * @return boolean TRUE:超过限制值,FALSE:没有超过限制值
     */
    public static function IsOverWidthOrHeight($filePath, $maxValue = 8000) {
        $filePath = P_PATH . str_ireplace("/", DIRECTORY_SEPARATOR, $filePath);         //绝对路径

        if (!file_exists($filePath)) {
            //$error = -1;        //源图片文件不存在!
            return TRUE;
        }
        $data = @getimagesize($filePath);    //取得原文件信息
        if ($data[0] > $maxValue || $data[1] > $maxValue) {     //宽与高的值不能大于8000
            //源图片过大,需要服务器内存支持GD;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 创建图像信息
     * @param string $img
     * @param int $creatW
     * @param int $creatH
     * @param int $dstX
     * @param int $dstY
     * @param int $srcX
     * @param int $srcY
     * @param int $srcImgW
     * @param int $srcImgH
     * @return type 
     */
    public static function CreatImage($img, $creatW, $creatH, $dstX, $dstY, $srcX, $srcY, $srcImgW, $srcImgH) {
        if (function_exists("imagecreatetruecolor")) {
            @$creatImg = @ImageCreateTrueColor($creatW, $creatH);
            if ($creatImg) {
                @ImageCopyResampled($creatImg, $img, $dstX, $dstY, $srcX, $srcY, $creatW, $creatH, $srcImgW, $srcImgH);
            } else {
                $creatImg = @ImageCreate($creatW, $creatH);
                @ImageCopyResized($creatImg, $img, $dstX, $dstY, $srcX, $srcY, $creatW, $creatH, $srcImgW, $srcImgH);
            }
        } else {
            $creatImg = @ImageCreate($creatW, $creatH);
            @ImageCopyResized($creatImg, $img, $dstX, $dstY, $srcX, $srcY, $creatW, $creatH, $srcImgW, $srcImgH);
        }
        return $creatImg;
    }

    /**
     * 大小格式化处理
     * @param type $val
     * @return int
     */
    public static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    /**
     * 截图
     * @param type $source 源文件路径
     * @param type $width 要截图的宽度
     * @param type $height 要截图的高度
     * @return string 截出的图的文件路径和文件名
     */
    public static function CutImg($source, $width, $height) {
        //if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targ_w = $width;
            $targ_h = $height;
            $jpeg_quality = 100;
            $src = P_PATH . str_ireplace("/", DIRECTORY_SEPARATOR, $source);
            $imgtype = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
            $data = getimagesize($src);    //取得原文件信息
//header('Content-type: image/jpeg');
            $thumbname = '.thumb.' . $imgtype[$data[2]]; //缩略图名
//            $thumbname = str_ireplace("jpeg", "jpg", $thumbname);
//            $thumb_src = self::CreatThumb($source, 750, 750);
            $to_File = $src . $thumbname;
            $img_r = imagecreatefromjpeg($src);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
            imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
            imagejpeg($dst_r, $to_File, $jpeg_quality);
            imagedestroy($img_r);
            imagedestroy($dst_r);
            return $source . $thumbname;
        //}
    }

    /**
     * 图片加水印（适用于png/jpg/gif格式）
     *
     * @author flynetcn
     *
     * @param $srcImg    原图片
     * @param $waterImg  水印图片
     * @param $savepath  保存路径
     * @param $savename  保存名字
     * @param $positon   水印位置
     *                   1:顶部居左, 2:顶部居右, 3:居中, 4:底部局左, 5:底部居右
     * @param $alpha     透明度 -- 0:完全透明, 100:完全不透明
     *
     * @return 成功 -- 加水印后的新图片地址
     *      失败 -- -1:原文件不存在, -2:水印图片不存在, -3:原文件图像对象建立失败
     *              -4:水印文件图像对象建立失败 -5:加水印后的新图片保存失败
     */
    public static function img_water_mark($srcImg, $waterImg, $savepath = null, $savename = null, $positon = 5, $alpha = 50) {
        $temp = pathinfo($srcImg);
        $name = $temp[basename];
        $path = $temp[dirname];
        $exte = $temp[extension];
        $savename = $savename ? $savename : $name;
        $savepath = $savepath ? $savepath : $path;
        $savefile = $savepath . $savename;
        $srcinfo = @getimagesize($srcImg);
        if (!$srcinfo) {
            return -1;  //原文件不存在
        }
        $waterinfo = @getimagesize($waterImg);
        if (!$waterinfo) {
            return -2;  //水印图片不存在
        }
        $srcImgObj = self::image_create_from_ext($srcImg);
        if (!$srcImgObj) {
            return -3;  //原文件图像对象建立失败
        }
        $waterImgObj = self::image_create_from_ext($waterImg);
        if (!$waterImgObj) {
            return -4;  //水印文件图像对象建立失败
        }
        switch ($positon) {
//1顶部居左
            case 1: $x = $y = 0;
                break;
//2顶部居右
            case 2: $x = $srcinfo[0] - $waterinfo[0];
                $y = 0;
                break;
//3居中
            case 3: $x = ($srcinfo[0] - $waterinfo[0]) / 2;
                $y = ($srcinfo[1] - $waterinfo[1]) / 2;
                break;
//4底部居左
            case 4: $x = 0;
                $y = $srcinfo[1] - $waterinfo[1];
                break;
//5底部居右
            case 5: $x = $srcinfo[0] - $waterinfo[0] - 10;
                $y = $srcinfo[1] - $waterinfo[1] - 10;
                break;
            default: $x = $y = 0;
        }
        imagecopymerge($srcImgObj, $waterImgObj, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $alpha);
        switch ($srcinfo[2]) {
            case 1: imagegif($srcImgObj, $savefile);
                break;
            case 2: imagejpeg($srcImgObj, $savefile, 100);
                break;
            case 3: imagepng($srcImgObj, $savefile);
                break;
            default: return -5;  //保存失败
        }
        imagedestroy($srcImgObj);
        imagedestroy($waterImgObj);
        return $savefile;
    }

    public static function image_create_from_ext($imgfile) {
        $info = getimagesize($imgfile);
        $im = null;
        switch ($info[2]) {
            case 1: $im = imagecreatefromgif($imgfile);
                break;
            case 2: $im = imagecreatefromjpeg($imgfile);
                break;
            case 3: $im = imagecreatefrompng($imgfile);
                break;
        }
        return $im;
    }

    public static function DefaultImageCut() {
        
    }

    /**
     * 生成excel文件
     * @param string $dest  保存路径
     * @param <type> $headArr   表头信息
     * @param <type> $data      表内内容的
     * @param string $fileName  Excel文件名
     * @param string $tableName excel表名
     * @return <type> 
     */
    public static function CreateExcel($dest, $headArr, $data, $fileName = "excel", $tableName = "simple") {
        $reSult = 0;
        include ROOTPATH . '/Rules/Plugins/PHPExcel/PHPExcel.php';
        include ROOTPATH . '/Rules/Plugins/PHPExcel/PHPExcel/Reader/Excel2007.php';
        include ROOTPATH . '/Rules/Plugins/PHPExcel/PHPExcel/Reader/Excel5.php';
        include ROOTPATH . '/Rules/Plugins/PHPExcel/PHPExcel/IOFactory.php';

        if (empty($data) || !is_array($data)) {
            $reSult = -2;        //数据内容为空或不符合标准    die("data must be a array");
            return $reSult;
        }

        if (empty($fileName)) {
            $fileName = "excel";
        }

        if (empty($tableName)) {
            $tableName = "simple";      //默认表名
        }

        if (substr($dest, strlen($dest) - 1, 1) != "/") {       //判断最后一位是否为 /
            $dest = $dest . "/";
        }

        self::CreateFolder($dest);
        $excelFile = $dest . $fileName;
        //创建新的PHPExcel对象
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($data as $key => $rows) { //行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) {// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span++;
            }
            $column++;
        }
        $excelFile = iconv("utf-8", "gb2312", $excelFile);
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle($tableName);
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        //将输出重定向到一个客户端web浏览器(Excel2007)
        //
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header("Content-Disposition: attachment; filename=".$excelFile."");
        //header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save($excelFile); //脚本方式运行，保存在当前目录

        if (file_exists($excelFile)) {    //生成Excel文件成功
            $reSult = 1;    //成功
        } else {
            $reSult = -1;  //生成失败
        }
        //$objWriter->save('php://output');
        return $reSult;
//        if (!empty($_GET['excel'])) {
//            $objWriter->save('php://output'); //文件通过浏览器下载
//        } else {
//            $objWriter->save($fileName); //脚本方式运行，保存在当前目录
//        }
//        exit;
    }

    /**
     * 通过curl方式获取制定的图片到本地
     * @param string $url 完整的图片地址
     * @param string $savePath 要存储的文件路径
     * @param string $filename 要存储的文件名
     * @return <type>
     */
    public static function GrabImage($url = "", $savePath = "", $filename = "") {
        if (!empty($url)) {     //源图片路径存在则进行本地保存
            //处理保路径
            if ($savePath == "") {//如果没有指定新的文件保存路径则自动保存到 images目录下
                $savePath = P_PATH . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR;
            }
            self::CreateFolder($savePath);
            //处理新文件名
            if ($filename == "") {//如果没有指定新的文件名
                $ext = strtolower(strrchr($url, ".")); //得到$url的图片格式
                if ($ext != ".gif" && $ext != ".jpg" && $ext != ".jpge"):return false;
                endif; //如果图片格式不为.gif或者.jpg，直接退出
                $filename = date("dMYHis") . $ext; //用天月面时分秒来命名新的文件名
            }

            if (is_dir(basename($filename))) {      //The Dir was not exits
                return false;
            }
            $filename = $savePath . $filename;

            //去除URL连接上面可能的引号
            $url = preg_replace('/(?:^[\'"]+|[\'"\/]+$)/', '', $url);

            $hander = curl_init();
            $fp = fopen($filename, 'wb');
            curl_setopt($hander, CURLOPT_URL, $url);
            curl_setopt($hander, CURLOPT_FILE, $fp);
            curl_setopt($hander, CURLOPT_HEADER, 0);
            curl_setopt($hander, CURLOPT_FOLLOWLOCATION, 1);
            //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
            curl_setopt($hander, CURLOPT_TIMEOUT, 60);
            /* $options = array(
              CURLOPT_URL=> 'http://jb51.net/content/uploadfile/201106/thum-f3ccdd27d2000e3f9255a7e3e2c4880020110622095243.jpg',
              CURLOPT_FILE => $fp,
              CURLOPT_HEADER => 0,
              CURLOPT_FOLLOWLOCATION => 1,
              CURLOPT_TIMEOUT => 60
              );
              curl_setopt_array($hander, $options);
             */
            curl_exec($hander);
            curl_close($hander);
            fclose($fp);
            return true;
        } else {
            return false;       //url 原图片路径为空
        }
    }

}

?>
