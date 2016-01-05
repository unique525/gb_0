<?php
/**
 * 公开访问 电子报文章图片 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePicPublicGen extends BasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "import":
                $result = self::Import();
                break;
        }
        return $result;
    }


    private function Import(){
        $newspaperArticlePicId = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "",$removeXSS));
        $newspaperArticleId = intval(str_ireplace("\r\n","",Control::PostRequest("NewspaperArticleId",0,$removeXSS)));
        $remark = str_ireplace("\r\n","",Control::PostRequest("Remark","",$removeXSS));
        $uploadFileId = intval(str_ireplace("\r\n","",Control::PostRequest("UploadFileId",0,$removeXSS)));
        $picMapping = str_ireplace("\r\n","",Control::PostRequest("PicMapping","",$removeXSS));
        $fileName = str_ireplace("\r\n","",Control::PostRequest("FileName","",$removeXSS));

        if(
            $authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperArticleId>0 &&
            $uploadFileId>0 &&
            strlen($fileName)>0 &&
            strlen($picMapping)>0
        )
        {
            $newspaperArticlePicPublicData = new NewspaperArticlePicPublicData();

            $newspaperArticlePicId = $newspaperArticlePicPublicData->CreateForImport(
                $newspaperArticleId,
                $remark,
                $uploadFileId,
                $picMapping,
                $fileName
            );

            $mobileWidth = 640;

            parent::GenUploadFileMobile($uploadFileId, $mobileWidth);

            parent::GenUploadFileCompress1($uploadFileId, $mobileWidth,0, 60);

            //水印图
            $siteId = parent::GetSiteIdByDomain();
            if($siteId>0){
                $siteConfigData = new SiteConfigData($siteId);
                $watermarkUploadFileId = $siteConfigData->NewspaperArticlePicWatermarkUploadFileId;
                if($watermarkUploadFileId>0){
                    $uploadFileData = new UploadFileData();
                    $watermarkUploadFilePath = $uploadFileData->GetUploadFilePath($watermarkUploadFileId, true);
                    parent::GenUploadFileWatermark1($uploadFileId, $watermarkUploadFilePath);
                }
            }

            //更新文章里的TitlePic1UploadFileId
            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $newspaperArticlePublicData->ModifyTitlePic1UploadFileId($newspaperArticleId, $uploadFileId);

            //删除缓冲
            parent::DelAllCache();

        }

        return $newspaperArticlePicId;
    }
} 