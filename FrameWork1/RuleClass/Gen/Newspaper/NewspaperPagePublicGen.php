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

        }
        return $result;
    }

    private function Import(){
        $newspaperPageId = -1;
        $authorityCode = Control::PostRequest("AuthorityCode", "");
        $newspaperId = Control::PostRequest("NewspaperId",0);
        $newspaperPageName = Control::PostRequest("NewspaperPageName","");
        $newspaperPageNo = Control::PostRequest("NewspaperPageNo","");
        $articleCount = Control::PostRequest("ArticleCount","");
        $picCount = Control::PostRequest("PicCount","");
        $editor = Control::PostRequest("Editor","");
        $pageWidth = Control::PostRequest("PageWidth","");
        $pageHeight = Control::PostRequest("PageHeight","");
        $issueDepartment = Control::PostRequest("IssueDepartment","");
        $issuer = Control::PostRequest("Issuer","");

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
        }

        return $newspaperPageId;
    }
} 