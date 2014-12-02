<?php
/**
 * 公开访问 电子报 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperPublicGen extends BasePublicGen {

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
            case "gen_one":
                $result = self::GenOne();
                break;

        }
        return $result;
    }


    private function Import(){
        $newspaperId = -10;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", ""));
        $removeXSS = false;
        $siteId = intval(str_ireplace("\r\n","",Control::PostRequest("SiteId",0,$removeXSS)));
        $channelId = intval(str_ireplace("\r\n","",Control::PostRequest("ChannelId",0,$removeXSS)));
        $newspaperTitle = str_ireplace("\r\n","",Control::PostRequest("NewspaperTitle","",$removeXSS));
        $publishDate = str_ireplace("\r\n","",Control::PostRequest("PublishDate","",$removeXSS));

        if(
            $authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $siteId>0 &&
            $channelId>0 &&
            strlen($newspaperTitle)>0 &&
            strlen($publishDate)>0
        ){
            $newspaperPublicData = new NewspaperPublicData();

            $newspaperId = $newspaperPublicData->CreateForImport($siteId, $channelId, $newspaperTitle, $publishDate);
        }

        return $newspaperId;
    }


    private function GenOne(){
        $channelId = Control::GetRequest("channel_id", 0);
        $templateContent = "";
        if($channelId>0){
            $publishDate = Control::GetRequest("publish_date", "");
            $templateFileUrl = "newspaper/newspaper_page_one.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            $newspaperPublicData = new NewspaperPublicData();
            if(strlen($publishDate)>0){
                $currentNewspaperId = $newspaperPublicData->GetNewspaperIdByPublishDate($channelId, $publishDate);
                $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);

            }else{
                $currentNewspaperId = $newspaperPublicData->GetNewspaperIdOfNew($channelId);
            }

            $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);

            if($currentNewspaperId>0){


                $arrOneNewspaper = $newspaperPublicData->GetOne($currentNewspaperId);

                Template::ReplaceOne($templateContent,$arrOneNewspaper);

                $templateContent = str_ireplace("{CurrentNewspaperId}", $currentNewspaperId, $templateContent);

                $newspaperPagePublicData = new NewspaperPagePublicData();

                $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
                if($newspaperPageId<=0){
                    $currentNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfFirst($currentNewspaperId);
                }else{
                    $currentNewspaperPageId = $newspaperPageId;
                }


                $templateContent = str_ireplace("{CurrentNewspaperPageId}", $currentNewspaperPageId, $templateContent);




                if($currentNewspaperPageId>0){


                    $arrOneNewspaperPage = $newspaperPagePublicData->GetOne($currentNewspaperPageId);

                    Template::ReplaceOne($templateContent,$arrOneNewspaperPage);

                    $nextNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfNext($currentNewspaperId,$currentNewspaperPageId);

                    $templateContent = str_ireplace("{NextNewspaperPageId}",
                        $nextNewspaperPageId,
                        $templateContent
                    );

                    $previousNewspaperPageId =
                        $newspaperPagePublicData->GetNewspaperPageIdOfPrevious(
                            $currentNewspaperId,
                            $currentNewspaperPageId
                        );

                    $templateContent = str_ireplace("{PreviousNewspaperPageId}",
                        $previousNewspaperPageId,
                        $templateContent
                    );
                }
            }
        }
        return $templateContent;

    }
} 