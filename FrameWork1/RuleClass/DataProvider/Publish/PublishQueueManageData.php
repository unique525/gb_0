<?php
/**
 * 后台管理 发布队列 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Publish
 * @author zhangchi
 */
class PublishQueueManageData extends BaseManageData {

    /**
     * @var array FTP上传队列数组
     */
    public $Queue = array();

    /**
     * 增加一条FTP队列信息
     * @param string $destinationPath 目标路径
     * @param string $sourcePath 来源路径
     * @param string $content 内容
     */
    public function Add($destinationPath, $sourcePath, $content) {
        if (!empty($destinationPath)) {
            $arrTemp = array(
                "DestinationPath" => $destinationPath,
                "SourcePath" => $sourcePath,
                "Content" => $content,
                "Result" => 0
            );
            array_push($this->Queue, $arrTemp);
        }
    }

    /**
     * 清空对象数组
     */
    public function Clear() {
        unset($this->Queue);
    }
} 