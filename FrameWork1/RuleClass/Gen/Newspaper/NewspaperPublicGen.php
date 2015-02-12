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
            case "gen_select":
                $result = self::GenSelect();
                break;
            case "gen_page_list":
                $result = self::GenPageList();
                break;
            case "get_newspaper_id_for_import":
                $result = self::GetNewspaperIdForImport();
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
        $newspaperPagePublicData = new NewspaperPagePublicData();
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);

        $newspaperPublicData = new NewspaperPublicData();

        if($newspaperPageId>0 && $channelId<=0){
            $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);
            $channelId = $newspaperPublicData->GetChannelId($newspaperId, true);
        }



        $templateContent = "";
        if($channelId>0){
            $publishDate = Control::GetRequest("publish_date", "");
            //$templateFileUrl = "newspaper/newspaper_page_one.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("newspaper_page_one");
            parent::ReplaceFirst($templateContent);
            $channelPublicData = new ChannelPublicData();
            $siteId = $channelPublicData->GetSiteId($channelId, true);

            parent::ReplaceSiteInfo($siteId, $templateContent);


            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);


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


                if($newspaperPageId<=0){
                    $currentNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfFirst($currentNewspaperId);
                }else{
                    $currentNewspaperPageId = $newspaperPageId;
                }


                $templateContent = str_ireplace("{CurrentNewspaperPageId}", $currentNewspaperPageId, $templateContent);




                if($currentNewspaperPageId>0){


                    $arrOneNewspaperPage = $newspaperPagePublicData->GetOne($currentNewspaperPageId);

                    Template::ReplaceOne($templateContent,$arrOneNewspaperPage);

                    $nextNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfNext(
                        $currentNewspaperId,
                        $currentNewspaperPageId,
                        true
                    );

                    $templateContent = str_ireplace("{NextNewspaperPageId}",
                        $nextNewspaperPageId,
                        $templateContent
                    );

                    $previousNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfPrevious(
                            $currentNewspaperId,
                            $currentNewspaperPageId,
                            true
                        );

                    $templateContent = str_ireplace("{PreviousNewspaperPageId}",
                        $previousNewspaperPageId,
                        $templateContent
                    );

                    //版面选择
                    $arrNewspaperPages = $newspaperPagePublicData -> GetListForSelectPage($currentNewspaperId);
                    $listName = "newspaper_page";

                    if(count($arrNewspaperPages)>0){
                        Template::ReplaceList($templateContent, $arrNewspaperPages, $listName);
                    }else{
                        Template::RemoveCustomTag($tempContent, $listName);
                    }
                }
            }
        }
        return $templateContent;

    }


    /**
     * 版面选择
     */

    private function GenSelect(){
        $channelId = Control::GetRequest("channel_id", 0);
        $templateContent = "";
        if($channelId>0){
            //$templateFileUrl = "newspaper/newspaper_select.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("newspaper_select");
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            parent::ReplaceFirst($templateContent);
            $channelPublicData = new ChannelPublicData();
            $siteId = $channelPublicData->GetSiteId($channelId, true);

            parent::ReplaceSiteInfo($siteId, $templateContent);

        }
        return $templateContent;

    }


    /**
     * 版面选择列表
     */

    private function GenPageList(){
        $channelId = Control::GetRequest("channel_id", 0);
        $newspaperId = Control::GetRequest("newspaper_id", 0);
        $templateContent = "";
        if($channelId>0){
            //$templateFileUrl = "newspaper/newspaper_page_list.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("newspaper_page_list");
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            parent::ReplaceFirst($templateContent);
            $channelPublicData = new ChannelPublicData();
            $siteId = $channelPublicData->GetSiteId($channelId, true);

            parent::ReplaceSiteInfo($siteId, $templateContent);
            //版面列表数据集
            $newspaperPagePublicData=new NewspaperPagePublicData();
            $arrNewspaperPages = $newspaperPagePublicData -> GetListForSelectPage($newspaperId);

            if(count($arrNewspaperPages)>0){

                //默认只显示已发状态的新闻
                $state = 0;

                $newspaperPageIds="";
                foreach($arrNewspaperPages as $page){
                    $newspaperPageIds.=",".$page["NewspaperPageId"];
                }

                if(strpos($newspaperPageIds,',') == 0){
                    $newspaperPageIds = substr($newspaperPageIds,1);
                }

                //文章列表数据集
                $newspaperArticlePublicData= new NewspaperArticlePublicData();
                $arrNewspaperArticles=$newspaperArticlePublicData->GetListOfMultiPage($newspaperPageIds,$state);

                $listName = "newspaper_page_and_article";
                $tagName = Template::DEFAULT_TAG_NAME;
                $tableIdName = "NewspaperPageId";
                $parentIdName = "NewspaperPageId";
                Template::ReplaceList($templateContent, $arrNewspaperPages, $listName, $tagName,$arrNewspaperArticles,$tableIdName,$parentIdName);
            }else{
                $listName = "newspaper_page_and_article";
                Template::RemoveCustomTag($tempContent, $listName);
            }
        }
        return $templateContent;

    }


    private function GetNewspaperIdForImport(){
        $result = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "",$removeXSS));
        $publishDate = str_ireplace("\r\n","",Control::PostRequest("PublishDate","",$removeXSS));

        if($authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&

            strlen($publishDate)>0
        )
        {
            $newspaperPublicData = new NewspaperPublicData();

            $result = $newspaperPublicData->GetNewspaperIdByPublishDateForImport(
                $publishDate
            );
        }
        return $result;
    }
} 