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

    /**
     * 取消资讯的发布（删除所有发布的文件）
     * @param int $documentNewsId 资讯id
     */
    protected function CancelPublishDocumentNews($documentNewsId){
//从发布或已否状态改为已否状态，从FTP上删除文件及相关附件，重新发布相关频道
        //第1步，从FTP删除文档
        $publishDate = $documentNewsManageData->GetPublishDate($documentNewsId, false);
        $documentNewsContent = $documentNewsManageData->GetDocumentNewsContent($documentNewsId, false);
        $datePath = Format::DateStringToSimple($publishDate);
        $publishFileName = $documentNewsId . '.html';
        $channelManageData = new ChannelManageData();
        $rank = $channelManageData->GetRank($channelId, false);
        $publishPath = parent::GetPublishPath($documentChannelId, $rank);
        $hasftp = $documentChannelManageData->GetHasFtp($documentChannelId);
        $ftptype = 0; //HTML和相关CSS,IMAGE
        $despath = $publishPath . $datePath . DIRECTORY_SEPARATOR . $publishFileName;

        $isDel = parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);

//有详细页面分页的，循环删除各个分页页面
        $arrnewscontent = explode("<!-- pagebreak -->", $documentNewsContent);
        if (count($arrnewscontent) > 0) { //有分页的内容
            for ($cp = 0; $cp < count($arrnewscontent); $cp++) {
                $publishFileName = $documentNewsId . '_' . ($cp + 1) . '.html';
                $despath = "/" . $publishPath . $datePath . '/' . $publishFileName;
                parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);
            }
        }
//第2步，从FTP删除上传文件
        $ftptype = 0; //HTML和相关CSS,IMAGE
        $uploadFileData = new UploadFileData();
        $tabletype = 1; //docnews
        $arrfiles = $uploadFileData->GetList($documentNewsId, $tabletype);
//取得相关的附件文件
        if (count($arrfiles) > 0) {
            for ($i = 0; $i < count($arrfiles); $i++) {
                $uploadFileName = $arrfiles[$i]["UploadFileName"];
                $uploadFilePath = $arrfiles[$i]["UploadFilePath"];
                parent::DelFtp($uploadFilePath . $uploadFileName, $documentChannelId, $hasftp, $ftptype);
            }
        }
//联动发布所在频道和上级频道
        $documentChannelGen = new DocumentChannelGen();
        $documentChannelGen->PublishMuti($documentChannelId);
/////////////////////////////////////////////////////////////
////////////////xunsearch全文检索引擎 索引更新///////////////
/////////////////////////////////////////////////////////////
//                global $xunfile;
//                if (file_exists($xunfile)) {
//                    require_once $xunfile;
//                    try {
//                        $xs = new XS('icms');
//                        $index = $xs->index;
//                        $index->del($documentnewsid);
//                        $index->flush();
//                    } catch (XSException $e) {
//                        $error = strval($e);
//                    }
//                }
        /////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////
    }

}

?>
