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
     * @param string $cacheFile 缓冲文件名
     * @param string $cacheDir 缓冲文件夹名
     * @param string $content 要写入的缓冲内容
     */
    public static function Set($cacheFile, $cacheDir, $content) {
        $cacheDir = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheDir;
        FileObject::CreateFolder($cacheDir);
        file_put_contents($cacheDir . DIRECTORY_SEPARATOR . $cacheFile, $content);
    }

    /**
     * 读取缓冲文件内容
     * @param string $cacheFile
     * @return string 返回缓冲内容 
     */
    public static function Get($cacheFile) {
        $cacheFile = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheFile;
        if (file_exists($cacheFile)) {
            return trim(file_get_contents($cacheFile));
        } else {
            return '';
        }
    }

    /**
     * 删除缓冲文件
     * @param string $cacheFile 缓冲文件名（文件夹+文件名）
     */
    public static function Remove($cacheFile) {
        $cacheFile = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheFile;
        FileObject::DelFile($cacheFile);
    }

    /**
     * 删除缓冲文件夹
     * @param string $cacheDir 缓冲文件夹名
     * @return int 处理结果
     */
    public static function RemoveDir($cacheDir) {
        $cacheDir = RELATIVE_PATH . DIRECTORY_SEPARATOR . $cacheDir;
        return FileObject::DelDir($cacheDir);
    }
}

?>
