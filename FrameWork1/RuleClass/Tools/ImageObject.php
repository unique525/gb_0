<?php
/**
 * 提供图片处理相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class ImageObject {

    /**
     * 获取图像信息的函数
     * @param string $imageUrl 图片路径
     * @return array 图像信息数组
     */
    public static function GetImageInfo($imageUrl) {
        $imageType = array("", "GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF(intel byte order)", "TIFF(motorola byte order)", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
        $orientation = array("", "top left side", "top right side", "bottom right side", "bottom left side", "left side top", "right side top", "right side bottom", "left side bottom");
        $resolutionUnit = array("", "", "英寸", "厘米");
        $ycbCrPositioning = array("", "the center of pixel array", "the datum point");
        $exposureProgram = array("未定义", "手动", "标准程序", "光圈先决", "快门先决", "景深先决", "运动模式", "肖像模式", "风景模式");
        $arrMeteringMode = array(
            "0" => "未知",
            "1" => "平均",
            "2" => "中央重点平均测光",
            "3" => "点测",
            "4" => "分区",
            "5" => "评估",
            "6" => "局部",
            "255" => "其他"
        );
        $arrLightSource = array(
            "0" => "未知",
            "1" => "日光",
            "2" => "荧光灯",
            "3" => "钨丝灯",
            "10" => "闪光灯",
            "17" => "标准灯光A",
            "18" => "标准灯光B",
            "19" => "标准灯光C",
            "20" => "D55",
            "21" => "D65",
            "22" => "D75",
            "255" => "其他"
        );
        $arrFlash = array(
            "0" => "flash did not fire",
            "1" => "flash fired",
            "5" => "flash fired but strobe return light not detected",
            "7" => "flash fired and strobe return light detected",
        );

        if (function_exists("exif_read_data")) {
            $exif = exif_read_data($imageUrl, "IFD0");
            if ($exif === false) {
                $arrImageInfo = array("文件信息" => "没有图片EXIF信息");
            } else {
                $exif = exif_read_data($imageUrl, 0, true);
                $arrImageInfo = array(
                    "文件信息" => "-----------------------------",
                    "文件名" => $exif["FILE"]["FileName"],
                    "文件类型" => $imageType[$exif["FILE"]["FileType"]],
                    "文件格式" => $exif["FILE"]["MimeType"],
                    "文件大小" => $exif["FILE"]["FileSize"],
                    "时间戳" => date("Y-m-d H:i:s", $exif["FILE"]["FileDateTime"]),
                    "图像信息" => "-----------------------------",
                    "图片说明" => $exif["IFD0"]["ImageDescription"],
                    "制造商" => $exif["IFD0"]["Make"],
                    "型号" => $exif["IFD0"]["Model"],
                    "方向" => $orientation[$exif["IFD0"]["Orientation"]],
                    "水平分辨率" => $exif["IFD0"]["XResolution"] . $resolutionUnit[$exif["IFD0"]["ResolutionUnit"]],
                    "垂直分辨率" => $exif["IFD0"]["YResolution"] . $resolutionUnit[$exif["IFD0"]["ResolutionUnit"]],
                    "创建软件" => $exif["IFD0"]["Software"],
                    "修改时间" => $exif["IFD0"]["DateTime"],
                    "作者" => $exif["IFD0"]["Artist"],
                    "YCbCr位置控制" => $ycbCrPositioning[$exif["IFD0"]["YCbCrPositioning"]],
                    "版权" => $exif["IFD0"]["Copyright"],
                    "摄影版权" => $exif["COMPUTED"]["Copyright . Photographer"],
                    "编辑版权" => $exif["COMPUTED"]["Copyright . Editor"],
                    "拍摄信息" => "-----------------------------",
                    "Exif版本" => $exif["EXIF"]["ExifVersion"],
                    "FlashPix版本" => "Ver. " . number_format($exif["EXIF"]["FlashPixVersion"] / 100, 2),
                    "拍摄时间" => $exif["EXIF"]["DateTimeOriginal"],
                    "数字化时间" => $exif["EXIF"]["DateTimeDigitized"],
                    "拍摄分辨率高" => $exif["COMPUTED"]["Height"],
                    "拍摄分辨率宽" => $exif["COMPUTED"]["Width"],
                    "光圈" => $exif["EXIF"]["ApertureValue"],
                    "快门速度" => $exif["EXIF"]["ShutterSpeedValue"],
                    "快门光圈" => $exif["COMPUTED"]["ApertureFNumber"],
                    "最大光圈值" => "F" . $exif["EXIF"]["MaxApertureValue"],
                    "曝光时间" => $exif["EXIF"]["ExposureTime"],
                    "F-Number" => $exif["EXIF"]["FNumber"],
                    "测光模式" => ImageObject::GetImageInfoVal($exif["EXIF"]["MeteringMode"], $arrMeteringMode),
                    "光源" => ImageObject::GetImageInfoVal($exif["EXIF"]["LightSource"], $arrLightSource),
                    "闪光灯" => ImageObject::GetImageInfoVal($exif["EXIF"]["Flash"], $arrFlash),
                    "曝光模式" => ($exif["EXIF"]["ExposureMode"] == 1 ? "手动" : "自动"),
                    "白平衡" => ($exif["EXIF"]["WhiteBalance"] == 1 ? "手动" : "自动"),
                    "曝光程序" => $exposureProgram[$exif["EXIF"]["ExposureProgram"]],
                    "曝光补偿" => $exif["EXIF"]["ExposureBiasValue"] . "EV",
                    "ISO感光度" => $exif["EXIF"]["ISOSpeedRatings"],
                    "分量配置" => (bin2hex($exif["EXIF"]["ComponentsConfiguration"]) == "01020300" ? "YCbCr" : "RGB"), //'0x04,0x05,0x06,0x00'="RGB" '0x01,0x02,0x03,0x00'="YCbCr"
                    "图像压缩率" => $exif["EXIF"]["CompressedBitsPerPixel"] . "Bits/Pixel",
                    "对焦距离" => $exif["COMPUTED"]["FocusDistance"] . "m",
                    "焦距" => $exif["EXIF"]["FocalLength"] . "mm",
                    "等价35mm焦距" => $exif["EXIF"]["FocalLengthIn35mmFilm"] . "mm",
                    "用户注释编码" => $exif["COMPUTED"]["UserCommentEncoding"],
                    "用户注释" => $exif["COMPUTED"]["UserComment"],
                    "色彩空间" => ($exif["EXIF"]["ColorSpace"] == 1 ? "sRGB" : "Uncalibrated"),
                    "Exif图像宽度" => $exif["EXIF"]["ExifImageLength"],
                    "Exif图像高度" => $exif["EXIF"]["ExifImageWidth"],
                    "文件来源" => (bin2hex($exif["EXIF"]["FileSource"]) == 0x03 ? "digital still camera" : "unknown"),
                    "场景类型" => (bin2hex($exif["EXIF"]["SceneType"]) == 0x01 ? "A directly photographed image" : "unknown"),
                    "缩略图文件格式" => $exif["COMPUTED"]["Thumbnail . FileType"],
                    "缩略图Mime格式" => $exif["COMPUTED"]["Thumbnail . MimeType"]
                );
            }
        } else {
            $arrImageInfo = array("文件信息" => "没有开启EXIF组件");
        }

        return $arrImageInfo;
    }

    /**
     * 从数组中取得图片信息值
     * @param $imageInfo
     * @param $arrValue
     * @return string
     */
    private static function GetImageInfoVal($imageInfo, $arrValue) {
        $valueInfo = "unknown";
        foreach ($arrValue as $name => $val) {
            if ($name == $imageInfo) {
                $valueInfo = &$val;
                break;
            }
        }
        return $valueInfo;
    }

    /**
     * 同比缩小图片
     * @param string $sourceFile
     * @param int $width
     * @param int $height
     * @param string $addFileName 附加的文件名，如 thumb
     * @param int $jpgQuality JPG图片的质量，默认100
     * @return string
     */
    public static function GenThumb($sourceFile, $width, $height, $addFileName, $jpgQuality = 100) {

        sleep(1);
        $sourceFilePath = strtolower(FileObject::GetDirName(str_ireplace("/", DIRECTORY_SEPARATOR, $sourceFile)));
        $sourceFileExName = strtolower(FileObject::GetExtension($sourceFile));
        $sourceFileName = strtolower(FileObject::GetName($sourceFile));
        if(intval($jpgQuality)>100){
            $jpgQuality = 100;
        }
        //if($sourceFileExName !== "jpg"){
        //    return -1;//只处理jpg图像,非jpg图像返回错误代码-1
        //}


        if ($sourceFileName === "") {
            return ""; //文件名错误
        }

        //获取源文件的详细信息(源文件的宽度 高度等信息)

        $sourceFileFullPath = str_ireplace('//','/',PHYSICAL_PATH . $sourceFilePath . DIRECTORY_SEPARATOR . $sourceFileName . "." . $sourceFileExName);

        list($sourceWidth, $sourceHeight, $type) = getimagesize(
            $sourceFileFullPath);

        if(!file_exists($sourceFileFullPath)){
            return ""; //文件名错误
        }

        if (1 == $type) {
            $sourceImage = imagecreatefromgif(
                $sourceFileFullPath);
        } else if (2 == $type) {
            $sourceImage = imagecreatefromjpeg(
                $sourceFileFullPath);
        } else if (3 == $type) {
            $sourceImage = imagecreatefrompng(
                $sourceFileFullPath);
        }else{
            $sourceImage = null;
        }

        if($sourceImage !== null){
            //当目标文件 宽高 小于 源文件(获取缩放比例)
            $radio = 1;
            if (($width && $sourceWidth > $width) || ($height && $sourceHeight > $height)) {
                $resizeWidthTag = false;
                $resizeHeightTag = false;
                $widthRatio = 0;
                $heightRatio = 0;
                if ($width && $sourceWidth > $width) {
                    $widthRatio = $width / $sourceWidth;
                    $resizeWidthTag = true;
                }
                if ($height && $sourceHeight > $height) {
                    $heightRatio = $height / $sourceHeight;
                    $resizeHeightTag = true;
                }

                if ($resizeWidthTag && $resizeHeightTag) {
                    if ($widthRatio < $heightRatio) {
                        $radio = $widthRatio;
                    }
                    else
                        $radio = $heightRatio;
                }
                if ($resizeWidthTag && !$resizeHeightTag) {
                    $radio = $widthRatio;
                }
                if ($heightRatio && !$widthRatio) {
                    $radio = $heightRatio;
                }
            } else if (($width && $sourceWidth < $width) || ($height && $sourceHeight < $height)) {
                $resizeWidthTag = false;
                $resizeHeightTag = false;
                $widthRatio = 0;
                $heightRatio = 0;
                if ($width && $sourceWidth < $width) {
                    $widthRatio = $width / $sourceWidth;
                    $resizeWidthTag = true;
                }
                if ($height && $sourceHeight < $height) {
                    $heightRatio = $height / $sourceHeight;
                    $resizeHeightTag = true;
                }
                if ($resizeHeightTag && $resizeWidthTag) {
                    if ($widthRatio < $heightRatio) {
                        $radio = $widthRatio;
                    }
                    else
                        $radio = $heightRatio;
                }
                if ($resizeWidthTag && !$heightRatio) {
                    $radio = $widthRatio;
                }
                if ($heightRatio && !$widthRatio) {
                    $radio = $heightRatio;
                }
            }
            //按照比例缩放后的宽度和高度
            $newWidth = $sourceWidth * $radio;
            $newHeight = $sourceHeight * $radio;

            $newImage = @imagecreatetruecolor($newWidth, $newHeight);

            //创建透明图片示例
            $backgroundColor = @imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            @imagefill($newImage, 0, 0, $backgroundColor);
            @imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);


            $newFileName = strtolower($sourceFileName . "_" . $addFileName);

            @imagesavealpha($newImage, true);
            //创建新图片

            if (1 == $type) {
                @imagegif($newImage, PHYSICAL_PATH . $sourceFilePath . DIRECTORY_SEPARATOR . $newFileName . "." . $sourceFileExName);
            } else if (2 == $type) {
                @imagejpeg($newImage, PHYSICAL_PATH . $sourceFilePath . DIRECTORY_SEPARATOR . $newFileName . "." . $sourceFileExName, $jpgQuality);
            } else if (3 == $type) {
                @imagepng($newImage, PHYSICAL_PATH . $sourceFilePath . DIRECTORY_SEPARATOR . $newFileName . "." . $sourceFileExName);
            }

            if(is_resource($newImage)) {
                @imagedestroy($newImage);
            }
            if(is_resource($sourceImage)) {
                @imagedestroy($sourceImage);
            }

            $result = str_ireplace(DIRECTORY_SEPARATOR,"/",$sourceFilePath . DIRECTORY_SEPARATOR . $newFileName . "." . $sourceFileExName);
            return $result;
        }else{
            return null;
        }

    }

    /**
     * 模式 0 支持png本身透明度的方式
     */
    const WATERMARK_MODE_PNG = 0;
    /**
     * 模式 1 半透明格式水印
     */
    const WATERMARK_MODE_GIF = 1;
    /**
     * 水印位置 1:顶部居左
     */
    const WATERMARK_POSITION_TOP_LEFT = 1;
    /**
     * 水印位置 2:顶部居右
     */
    const WATERMARK_POSITION_TOP_RIGHT = 2;
    /**
     * 水印位置 3:居中
     */
    const WATERMARK_POSITION_CENTER = 3;
    /**
     * 水印位置 4:底部居左
     */
    const WATERMARK_POSITION_BOTTOM_LEFT = 4;
    /**
     * 水印位置 5:底部居右
     */
    const WATERMARK_POSITION_BOTTOM_RIGHT = 5;

    /**
     * 图片加水印（适用于png/jpg/gif格式）
     * @param string $sourceFilePath    原图片
     * @param string $watermarkFilePath  水印图片
     * @param string $addFileName  附加的文件名称
     * @param int $watermarkPosition   水印位置 1:顶部居左, 2:顶部居右, 3:居中, 4:底部居左, 5:底部居右
     * @param int $mode     模式 0 支持png本身透明度的方式 1 半透明格式水印
     * @param int $alpha     透明度 -- 0:完全透明, 100:完全不透明
     * @return string|int 成功 -- 加水印后的新图片地址
     *      失败 -- -1:原文件不存在, -2:水印图片不存在, -3:原文件图像对象建立失败
     *              -4:水印文件图像对象建立失败 -5:加水印后的新图片保存失败
     */
    public static function GenWatermark(
        $sourceFilePath,
        $watermarkFilePath,
        $addFileName,
        $watermarkPosition = 5,
        $mode = 0,
        $alpha = 100
    ) {

        sleep(1);

        $sourceFileDir = strtolower(FileObject::GetDirName(str_ireplace("/", DIRECTORY_SEPARATOR, $sourceFilePath)));
        $sourceFileExName = strtolower(FileObject::GetExtension($sourceFilePath));
        $sourceFileName = strtolower(FileObject::GetName($sourceFilePath));
        $newFileName = strtolower($sourceFileName . "_" . $addFileName);
        $saveFilePath = PHYSICAL_PATH . $sourceFileDir . DIRECTORY_SEPARATOR . $newFileName . "." . $sourceFileExName;
        $saveFilePath = str_ireplace('//','/',$saveFilePath);

        //获取源文件的详细信息(源文件的宽度 高度等信息)
        $sourceFileFullPath = str_ireplace('//','/',PHYSICAL_PATH . $sourceFilePath);

        $sourceFileInfo = @getimagesize($sourceFileFullPath);
        if (!$sourceFileInfo) {
            return -1;  //原文件不存在
        }
        $watermarkFileInfo = @getimagesize(PHYSICAL_PATH . DIRECTORY_SEPARATOR . $watermarkFilePath);
        if (!$watermarkFileInfo) {
            return -2;  //水印图片不存在
        }
        $sourceImageObject = self::CreateImageByExtention($sourceFileFullPath);
        if (!$sourceImageObject) {
            return -3;  //原文件图像对象建立失败
        }
        $watermarkImageObject = self::CreateImageByExtention(PHYSICAL_PATH . DIRECTORY_SEPARATOR . $watermarkFilePath);
        if (!$watermarkImageObject) {
            return -4;  //水印文件图像对象建立失败
        }
        switch ($watermarkPosition) {

            case 1: //1顶部居左
                $x = $y = 0;
                break;

            case 2: //2顶部居右
                $x = $sourceFileInfo[0] - $watermarkFileInfo[0];
                $y = 0;
                break;

            case 3: //3居中
                $x = ($sourceFileInfo[0] - $watermarkFileInfo[0]) / 2;
                $y = ($sourceFileInfo[1] - $watermarkFileInfo[1]) / 2;
                break;

            case 4: //4底部居左
                $x = 0;
                $y = $sourceFileInfo[1] - $watermarkFileInfo[1];
                break;

            case 5: //5底部居右
                $x = $sourceFileInfo[0] - $watermarkFileInfo[0] - 10;
                $y = $sourceFileInfo[1] - $watermarkFileInfo[1] - 10;
                break;
            default:
                $x = $y = 0;
                break;
        }

        if($mode == self::WATERMARK_MODE_GIF){
            //半透明格式水印
            imagecopymerge($sourceImageObject, $watermarkImageObject, $x, $y, 0, 0, $watermarkFileInfo[0], $watermarkFileInfo[1], $alpha);
        }
        else{
            //支持png本身透明度的方式
            imagecopy($sourceImageObject,$watermarkImageObject,$x, $y,0,0,$watermarkFileInfo[0],$watermarkFileInfo[1]);
        }


        switch ($sourceFileInfo[2]) {
            case 1: imagegif($sourceImageObject, $saveFilePath);
                break;
            case 2: imagejpeg($sourceImageObject, $saveFilePath, 100);
                break;
            case 3: imagepng($sourceImageObject, $saveFilePath);
                break;
            default:
                return -5;  //保存失败
        }
        imagedestroy($sourceImageObject);
        imagedestroy($watermarkImageObject);

        $saveFilePath = str_ireplace(PHYSICAL_PATH,'',$saveFilePath);
        $saveFilePath = str_ireplace('upload','/upload',$saveFilePath);

        return $saveFilePath;

    }

    /**
     * 从扩展名类型创建图片对象
     * @param string $filePath 文件地址
     * @return null|resource 资源对象
     */
    private static function CreateImageByExtention($filePath) {
        $info = getimagesize($filePath);
        $im = null;
        switch ($info[2]) {
            case 1: $im = imagecreatefromgif($filePath);
                break;
            case 2: $im = imagecreatefromjpeg($filePath);
                break;
            case 3: $im = imagecreatefrompng($filePath);
                break;
        }
        return $im;
    }

    /**
     * @param string $sourceImgPath 源图象文件路径。
     * @param float $sourceX 源文件 X 坐标点
     * @param float $sourceY 源文件 Y 坐标点
     * @param float $sourceWidth 源文件的宽度
     * @param float $sourceHeight 源文件的高度
     * @param float $targetWidth 目标文件的宽度
     * @param float $targetHeight 目标文件的高度
     * @param string $addFileName 目标文件的后缀
     * @param int $jpegQuality 截图质量
     * @return string
     */
    public static function CutImg($sourceImgPath, $sourceX,$sourceY,$sourceWidth,$sourceHeight,$targetWidth, $targetHeight,$addFileName = 'cut',$jpegQuality=100) {
        //if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        sleep(1);
        if(intval($jpegQuality)>100){
            $jpegQuality = 100;
        }

        if ($sourceImgPath === "") {
            return -1; //文件名错误
        }

        $src = PHYSICAL_PATH . str_ireplace("/", DIRECTORY_SEPARATOR, $sourceImgPath);
        $sourceFilePath = strtolower(FileObject::GetDirName(str_ireplace("/", DIRECTORY_SEPARATOR, $sourceImgPath)));
        $sourceFileExName = strtolower(FileObject::GetExtension($sourceImgPath));
        $sourceFileName = strtolower(FileObject::GetName($sourceImgPath));

        if($sourceFileExName === "" || $sourceFileName === ""){
            return -2;
        }
        $newFileName = $sourceFileName."_".$addFileName;

        $toFile = PHYSICAL_PATH.$sourceFilePath . DIRECTORY_SEPARATOR .$newFileName."." . $sourceFileExName;
        $imgR = imagecreatefromjpeg($src);
        $dstR = ImageCreateTrueColor($targetWidth, $targetHeight);
        if($imgR == false || $dstR == false){
            return -3;
        }
        $resultCopy = imagecopyresampled($dstR, $imgR, 0, 0, $sourceX, $sourceY, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
        $resultCreate = imagejpeg($dstR, $toFile, $jpegQuality);
        imagedestroy($imgR);
        imagedestroy($dstR);
        if( $resultCopy == true && $resultCreate == true){
            $result = str_ireplace(DIRECTORY_SEPARATOR,"/",$sourceFilePath . DIRECTORY_SEPARATOR . $newFileName . "." . $sourceFileExName);
        }else{
            $result = -4;
        }
        return $result;
        //}
    }

    /**
     * 旋转图片
     * @param string $sourceImgPath 来源文件路径
     * @param int $angle 旋转角度 90,180
     * @param int $bgdColor 旋转后没有覆盖到的部分的颜色
     * @return bool|int 是否成功
     */
    public static function Rotate($sourceImgPath, $angle , $bgdColor = 0){

        $src = PHYSICAL_PATH . str_ireplace("/", DIRECTORY_SEPARATOR, $sourceImgPath);

        $imgResource = imagecreatefromjpeg($src);

        if($imgResource == false){
            return -3;//读取文件失败
        }

        $rotateImage = imagerotate($imgResource, $angle, $bgdColor);

        if (imagejpeg($rotateImage, $src)){
            imagedestroy($imgResource);
            return 1;
        }else{
            imagedestroy($imgResource);
            return -2;//失败
        }

    }
} 