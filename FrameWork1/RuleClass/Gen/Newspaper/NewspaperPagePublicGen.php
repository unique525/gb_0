<?php
/**
 * 公开访问 电子报版面 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperPagePublicGen extends BasePublicGen {

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
            case "modify_pdf_upload_file_id_for_import":
                $result = self::ModifyPdfUploadFileIdForImport();
                break;
            case "modify_pic_upload_file_id_for_import":
                $result = self::ModifyPicUploadFileIdForImport();
                break;
            case "get_newspaper_page_id_for_import":
                $result = self::GetNewspaperPageIdForImport();
                break;

        }
        return $result;
    }

    private function Import(){
        parent::DelAllCache();

        $removeXSS = false;
        $newspaperPageId = -1;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "",$removeXSS));
        $newspaperId = intval(str_ireplace("\r\n","",Control::PostRequest("NewspaperId",0,$removeXSS)));
        $newspaperPageName = str_ireplace("\r\n","",Control::PostRequest("NewspaperPageName","",$removeXSS));
        $newspaperPageNo = str_ireplace("\r\n","",Control::PostRequest("NewspaperPageNo","",$removeXSS));
        $articleCount = str_ireplace("\r\n","",Control::PostRequest("ArticleCount","",$removeXSS));
        $picCount = str_ireplace("\r\n","",Control::PostRequest("PicCount","",$removeXSS));
        $editor = str_ireplace("\r\n","",Control::PostRequest("Editor","",$removeXSS));
        $pageWidth = str_ireplace("\r\n","",Control::PostRequest("PageWidth","",$removeXSS));
        $pageHeight = str_ireplace("\r\n","",Control::PostRequest("PageHeight","",$removeXSS));
        $issueDepartment = str_ireplace("\r\n","",Control::PostRequest("IssueDepartment","",$removeXSS));
        $issuer = str_ireplace("\r\n","",Control::PostRequest("Issuer","",$removeXSS));

        if(
            $authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperId>0 &&
            strlen($newspaperPageName)>0
        )
        {
            $newspaperPagePublicData = new NewspaperPagePublicData();

            $newspaperPageId = $newspaperPagePublicData->CreateForImport(
                $newspaperId,
                $newspaperPageName,
                $newspaperPageNo,
                $articleCount,
                $picCount,
                $editor,
                $pageWidth,
                $pageHeight,
                $issueDepartment,
                $issuer
            );

            //删除缓冲
            parent::DelAllCache();
        }

        return $newspaperPageId;
    }

    private function ModifyPdfUploadFileIdForImport(){
        $result = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "",$removeXSS));
        $newspaperPageId = intval(str_ireplace("\r\n","",Control::PostRequest("NewspaperPageId",0,$removeXSS)));
        $pdfUploadFileId = intval(str_ireplace("\r\n","",Control::PostRequest("PdfUploadFileId",0,$removeXSS)));

        if($authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperPageId>0 &&
            $pdfUploadFileId>0
        )
        {

            parent::DelAllCache();

            $newspaperPagePublicData = new NewspaperPagePublicData();

            $result = $newspaperPagePublicData->ModifyPdfUploadFileIdForImport(
                $newspaperPageId,
                $pdfUploadFileId
            );
        }
        return $result;
    }

    private function ModifyPicUploadFileIdForImport(){
        $result = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "",$removeXSS));
        $newspaperPageId = intval(str_ireplace("\r\n","",Control::PostRequest("NewspaperPageId",0,$removeXSS)));
        $picUploadFileId = intval(str_ireplace("\r\n","",Control::PostRequest("PicUploadFileId",0,$removeXSS)));

        if($authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperPageId>0 &&
            $picUploadFileId>0
        )
        {

            parent::DelAllCache();

            $newspaperPagePublicData = new NewspaperPagePublicData();

            $result = $newspaperPagePublicData->ModifyPicUploadFileIdForImport(
                $newspaperPageId,
                $picUploadFileId
            );


            $mobileWidth = 480;

            parent::GenUploadFileMobile($picUploadFileId, $mobileWidth);

            parent::GenUploadFileCompress1($picUploadFileId, $mobileWidth,0, 60);
        }
        return $result;
    }

    private function GetNewspaperPageIdForImport(){
        $result = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "",$removeXSS));
        $newspaperId = intval(str_ireplace("\r\n","",Control::PostRequest("NewspaperId",0,$removeXSS)));
        $newspaperPageNo = str_ireplace("\r\n","",Control::PostRequest("NewspaperPageNo","",$removeXSS));

        if($authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperId>0 &&
            strlen($newspaperPageNo)>0
        )
        {
            $newspaperPagePublicData = new NewspaperPagePublicData();

            $result = $newspaperPagePublicData->GetNewspaperPageIdForImport(
                $newspaperPageNo,
                $newspaperId
            );
        }
        return $result;
    }
} 