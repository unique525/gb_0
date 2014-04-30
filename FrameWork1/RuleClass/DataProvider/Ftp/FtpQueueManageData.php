<?php
/**
 * 后台管理 FTP 传输队列 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Ftp
 * @author zhangchi
 */
class FtpQueueManageData extends BaseManageData {

    /**
     * @var array FTP上传队列数组
     */
    public $ArrayUpload = array();

    /**
     * 增加一条FTP队列信息
     * @param string $destinationPath 目标路径
     * @param string $sourcePath 来源路径
     * @param string $content 内容
     */
    public function Add($destinationPath, $sourcePath, $content) {
        if (!empty($destinationPath)) {
            $arr_tmp = array("DestinationPath" => $destinationPath, "SourcePath" => $sourcePath, "Content" => $content, "Result" => 0);
            array_push($this->ArrayUpload, $arr_tmp);
        }
    }

    /**
     * 清空对象数组
     */
    public function Clear() {
        unset($this->ArrayUpload);
    }
} 