<?php

/**
 * 提供文件级的数据缓冲方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class DataCache {

    /**
     * 写入缓冲文件
     * @param string $cacheDir 缓冲文件夹名
     * @param string $cacheFile 缓冲文件名
     * @param string $content 要写入的缓冲内容
     */
    public static function Set($cacheDir, $cacheFile, $content) {
        $cacheDir = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheDir;
        FileObject::CreateDir($cacheDir);
        if (file_exists($cacheDir)){
            file_put_contents($cacheDir . DIRECTORY_SEPARATOR . $cacheFile, $content);
        }

    }

    /**
     * 写入缓冲文件(数组数据)
     * @param string $cacheDir 缓冲文件夹名
     * @param string $cacheFile 缓冲文件名
     * @param array $array 要写入的缓冲数组
     */
    public static function SetWithArray($cacheDir, $cacheFile, $array){
        $content = base64_encode(Format::FixJsonEncode($array));
        self::Set($cacheDir, $cacheFile, $content);
    }

    /**
     * 读取缓冲文件内容
     * @param string $cacheFile 缓冲文件名(文件夹+文件名)
     * @return bool|string 返回缓冲内容,没有找到缓存内容时，返回false
     */
    public static function Get($cacheFile) {
        $cacheFile = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheFile;
        if (file_exists($cacheFile)) {
            return trim(file_get_contents($cacheFile));
        } else {
            return false;
        }
    }

    /**
     * 读取缓冲文件内容(数组数据)
     * @param string $cacheFile 缓冲文件名(文件夹+文件名)
     * @return array|null 返回缓冲数组
     */
    public static function GetWithArray($cacheFile) {
        $cacheFile = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheFile;
        if (file_exists($cacheFile)) {
            $content = trim(file_get_contents($cacheFile));
            return Format::FixJsonDecode(base64_decode($content));
        } else {
            return false;
        }
    }

    /**
     * 删除缓冲文件
     * @param string $cacheFile 缓冲文件名（文件夹+文件名）
     */
    public static function Remove($cacheFile) {
        $cacheFile = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheFile;
        FileObject::DeleteFile($cacheFile);
    }

    /**
     * 删除缓冲文件夹
     * @param string $cacheDir 缓冲文件夹名
     * @return int 处理结果
     */
    public static function RemoveDir($cacheDir) {
        $cacheDir = RELATIVE_PATH . '/' . $cacheDir;
        return FileObject::DeleteDir($cacheDir, false);
    }

    /**
     * 根据id，返回子目录路径，用在缓存数据较多的时候，必须建立多级子目录来保存缓存文件的情况
     * @param mixed $id 业务表id或缓存关键字段 (每1000个分一段)
     * @return string 子目录路径
     */
    public static function GetSubPath($id){

        if (is_int($id)){
            $id = intval($id);
            $result = strval($id%1000) . "/" . $id;
        }else{
            $result = $id;
        }


        return $result;
    }
}

?>
