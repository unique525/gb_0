<?php

/**
 * 后台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseManageGen extends BaseGen
{
    const PUBLISH_PATH = "h";
    /**
     * 替换新增时，模板里的{field}值为空，包括后台模板和模板数据库中的模板
     * @param string $tempContent 要处理的模板内容
     * @param array $arrField 字段数组
     */
    protected function ReplaceWhenCreate(&$tempContent, $arrField)
    {
        if (count($arrField) > 0) {
            for ($i = 0; $i < count($arrField); $i++) {
                $tempContent = str_ireplace("{" . $arrField[$i]['Field'] . "}", '', $tempContent);
                $tempContent = str_ireplace("{b_" . $arrField[$i]['Field'] . "}", '', $tempContent);
            }
        }
    }

    protected function PublishChannel(){

    }

    protected function PublishDocumentNews($documentNewsId, FtpQueueManageData $ftpQueueManageData, $executeFtp, $publishChannel){

    }

    protected function CancelPublishDocumentNews($documentNewsId){

    }

}

?>
