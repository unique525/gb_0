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
            case "async_get_list":
                $result = self::AsyncGetList();
                break;
            case "gen_one":
                $result = self::GenOne();
                break;

        }
        return $result;
    }

    private function GenOne(){
        $channelId = Control::GetRequest("channel_id", 0);
        $templateContent = "";
        if($channelId>0){

            $templateFileUrl = "newspaper/newspaper_page_one.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            $newspaperPublicData = new NewspaperPublicData();
            $currentNewspaperId = $newspaperPublicData->GetNewspaperIdOfNew($channelId);

            if($currentNewspaperId>0){


                $arrOneNewspaper = $newspaperPublicData->GetOne($currentNewspaperId);

                Template::ReplaceOne($templateContent,$arrOneNewspaper);

                $templateContent = str_ireplace("{CurrentNewspaperId}", $currentNewspaperId, $templateContent);

                $newspaperPagePublicData = new NewspaperPagePublicData();

                $currentNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfFirst($currentNewspaperId);

                $templateContent = str_ireplace("{CurrentNewspaperPageId}", $currentNewspaperPageId, $templateContent);




                if($currentNewspaperPageId>0){


                    $arrOneNewspaperPage = $newspaperPagePublicData->GetOne($currentNewspaperPageId);

                    Template::ReplaceOne($templateContent,$arrOneNewspaperPage);

                    $nextNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfNext($currentNewspaperId,$currentNewspaperPageId);

                    $templateContent = str_ireplace("{NextNewspaperPageId}", $nextNewspaperPageId, $templateContent);

                    $previousNewspaperPageId =
                        $newspaperPagePublicData->GetNewspaperPageIdOfPrevious(
                            $currentNewspaperId,
                            $currentNewspaperPageId
                        );

                    $templateContent = str_ireplace("{PreviousNewspaperPageId}", $previousNewspaperPageId, $templateContent);

                }



            }



        }
        return $templateContent;

    }
} 