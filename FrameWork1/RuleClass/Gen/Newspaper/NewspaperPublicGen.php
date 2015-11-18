<?php

/**
 * 公开访问 电子报 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperPublicGen extends NewspaperBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
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


    private function Import()
    {
        $newspaperId = -10;
        $authorityCode = str_ireplace("\r\n", "", Control::PostRequest("AuthorityCode", ""));
        $removeXSS = false;
        $siteId = intval(str_ireplace("\r\n", "", Control::PostRequest("SiteId", 0, $removeXSS)));
        $channelId = intval(str_ireplace("\r\n", "", Control::PostRequest("ChannelId", 0, $removeXSS)));
        $newspaperTitle = str_ireplace("\r\n", "", Control::PostRequest("NewspaperTitle", "", $removeXSS));
        $publishDate = str_ireplace("\r\n", "", Control::PostRequest("PublishDate", "", $removeXSS));

        if (
            $authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $siteId > 0 &&
            $channelId > 0 &&
            strlen($newspaperTitle) > 0 &&
            strlen($publishDate) > 0
        ) {
            $newspaperPublicData = new NewspaperPublicData();

            $newspaperId = $newspaperPublicData->CreateForImport($siteId, $channelId, $newspaperTitle, $publishDate);


            //删除缓冲
            parent::DelAllCache();

        }



        return $newspaperId;
    }


    private function GenOne()
    {
        $channelId = Control::GetRequest("channel_id", 0);

        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $publishDate = Control::GetRequest("publish_date", "");

        $newspaperPagePublicData = new NewspaperPagePublicData();
        $newspaperPublicData = new NewspaperPublicData();

        if ($newspaperPageId > 0 && $channelId <= 0) {
            $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);
            $channelId = $newspaperPublicData->GetChannelId($newspaperId, true);
        }


        $channelPublicData = new ChannelPublicData();
        $siteId = $channelPublicData->GetSiteId($channelId, true);
        $defaultTemp = "newspaper_page_one";
        $templateContent = parent::GetDynamicTemplateContent(
            $defaultTemp,
            $siteId,
            "",
            $templateMode);

        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'newspaper_default_site_id_' . $siteId .
            '_channel_id_'.$channelId.
            '_temp_'.$defaultTemp.
            '_pub_'.$publishDate.
            '_pid_'.$newspaperPageId.
            '_mode_' . $templateMode;
        $withCache = false;
        if($withCache){
            $pageCache = parent::GetCache($cacheDir, $cacheFile);

            if ($pageCache === false) {
                $result = self::getOneTemplateContent(
                    $siteId,
                    $channelId,
                    $publishDate,
                    $newspaperPageId,
                    $templateContent);
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::getOneTemplateContent(
                $siteId,
                $channelId,
                $publishDate,
                $newspaperPageId,
                $templateContent);
        }

        /*******************页面级的缓存 end  ********************** */

        parent::ReplaceUserInfoPanel($result, $siteId);

        return $result;
    }



    private function getOneTemplateContent(
        $siteId,
        $channelId,
        $publishDate,
        $newspaperPageId,
        $templateContent)
    {
        if ($channelId > 0) {
            $newspaperPublicData = new NewspaperPublicData();
            $newspaperPagePublicData = new NewspaperPagePublicData();

            parent::ReplaceFirst($templateContent);

            parent::ReplaceSiteInfo($siteId, $templateContent);


            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);


            if (strlen($publishDate) > 0) {
                $currentNewspaperId = $newspaperPublicData->GetNewspaperIdByPublishDate($channelId, $publishDate);
                $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);

            } else {
                $currentNewspaperId = $newspaperPublicData->GetNewspaperIdOfNew($channelId);
            }

            $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);

            if ($currentNewspaperId > 0) {


                $arrOneNewspaper = $newspaperPublicData->GetOne($currentNewspaperId);

                Template::ReplaceOne($templateContent, $arrOneNewspaper);

                $templateContent = str_ireplace("{CurrentNewspaperId}", $currentNewspaperId, $templateContent);
                $templateContent = str_ireplace("{CurrentPublishDate}", $arrOneNewspaper["PublishDate"], $templateContent);


                if ($newspaperPageId <= 0) {
                    $currentNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfFirst($currentNewspaperId);
                } else {
                    $currentNewspaperPageId = $newspaperPageId;
                }


                $templateContent = str_ireplace("{CurrentNewspaperPageId}", $currentNewspaperPageId, $templateContent);


                if ($currentNewspaperPageId > 0) {
                    //$firstNewspaperPageMustPay=1;
                    //$secondNewspaperPageMustPay=1;
                    //$userId = Control::GetUserId();
                    //$IsAuthorizedUser = self::IsAuthorizedUser($userId, $currentNewspaperId);
                    //if ($IsAuthorizedUser || self::IsFreeRead($currentNewspaperId,$currentNewspaperPageId)) {

                        //比如手机版{UploadFileCompressPath1}这个也要替换，手机版只有一个页面，要统一逻辑，如此的话，统一从这里得数据是不是好些，留到后面处理
                        $arrOneNewspaperPage = $newspaperPagePublicData->GetOne($currentNewspaperPageId);
                        Template::ReplaceOne($templateContent, $arrOneNewspaperPage);

                        //pc 当前第一个版面
                        $firstNewspaperPageMustPay=0;

                        $firstNewspaperPageNo = $newspaperPagePublicData->GetNewspaperPageNo(
                            $currentNewspaperPageId,
                            true
                        );
                        $templateContent = str_ireplace("{NewspaperPageNo}",
                            $firstNewspaperPageNo,
                            $templateContent
                        );

                        $templateContent = str_ireplace("{NewspaperFirstPageId}",
                            $currentNewspaperPageId,
                            $templateContent
                        );


                        $firstUploadFilePath = $newspaperPagePublicData->GetUploadFilePath(
                            $currentNewspaperPageId,
                            true
                        );

                        $templateContent = str_ireplace("{UploadFilePath_First}",
                            $firstUploadFilePath,
                            $templateContent
                        );

                        $firstPdfUploadFilePath = $newspaperPagePublicData->GetPdfUploadFilePath(
                            $currentNewspaperPageId,
                            true
                        );
                        $templateContent = str_ireplace("{FirstPdfUploadFilePath}",
                            $firstPdfUploadFilePath,
                            $templateContent
                        );


                        //生成第一个版面文章位置xy坐标数据
                        $newspaperArticlePublicData = new NewspaperArticlePublicData();
                        $arrFirstPageArticleList = $newspaperArticlePublicData->GetList($currentNewspaperPageId, 100, 0);
                        $arrFirstPageArticlePointList = array();
                        if (!empty($arrFirstPageArticleList)) {
                            foreach ($arrFirstPageArticleList as $value) {
                                $newsPaperArticleId = $value["NewspaperArticleId"];
                                $newsPaperArticleTitle = $value["NewspaperArticleTitle"];
                                $picMapping = $value["PicMapping"];
                                $arrFirstPageArticlePointList[] = self::GenPoint($newsPaperArticleId, $newsPaperArticleTitle, $picMapping);
                            }
                            $templateContent = str_ireplace("{FirstPageArticlePoint}",
                                $arr = Format::FixJsonEncode($arrFirstPageArticlePointList),
                                $templateContent
                            );
                        } else {
                            $templateContent = str_ireplace("{FirstPageArticlePoint}",
                                "null",
                                $templateContent
                            );
                        }
                    //}


                    $secondNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfNext(
                        $currentNewspaperId,
                        $currentNewspaperPageId,
                        true
                    );

                    //if ($IsAuthorizedUser || self::IsFreeRead($currentNewspaperId,$secondNewspaperPageId)) {

                        //pc 当前第二个版面
                        $secondNewspaperPageMustPay=0;

                        $secondNewspaperPageNo = $newspaperPagePublicData->GetNewspaperPageNo(
                            $secondNewspaperPageId,
                            true
                        );


                        $templateContent = str_ireplace("{SecondNewspaperPageNo}",
                            $secondNewspaperPageNo,
                            $templateContent
                        );

                        $templateContent = str_ireplace("{NewspaperSecondPageId}",
                            $secondNewspaperPageId,
                            $templateContent
                        );
                        $templateContent = str_ireplace("{NextNewspaperPageId}",
                            $secondNewspaperPageId,
                            $templateContent
                        );
                        $secondUploadFilePath = $newspaperPagePublicData->GetUploadFilePath(
                            $secondNewspaperPageId,
                            true
                        );

                        $templateContent = str_ireplace("{UploadFilePath_Second}",
                            $secondUploadFilePath,
                            $templateContent
                        );

                        $secondPdfUploadFilePath = $newspaperPagePublicData->GetPdfUploadFilePath(
                            $secondNewspaperPageId,
                            true
                        );
                        $templateContent = str_ireplace("{SecondPdfUploadFilePath}",
                            $secondPdfUploadFilePath,
                            $templateContent
                        );

                        //生成第二个版面文章位置xy坐标数据
                        $newspaperArticlePublicData = new NewspaperArticlePublicData();
                        $arrSecondPageArticleList = $newspaperArticlePublicData->GetList($secondNewspaperPageId, 100, 0);
                        $arrSecondPageArticlePointList = array();

                        if (!empty($arrSecondPageArticleList)) {
                            foreach ($arrSecondPageArticleList as $value) {
                                $newsPaperArticleId = $value["NewspaperArticleId"];
                                $newsPaperArticleTitle = $value["NewspaperArticleTitle"];
                                $picMapping = $value["PicMapping"];
                                $arrSecondPageArticlePointList[] = self::GenPoint($newsPaperArticleId, $newsPaperArticleTitle, $picMapping);
                            }
                            $templateContent = str_ireplace("{SecondPageArticlePoint}",
                                $arr = Format::FixJsonEncode($arrSecondPageArticlePointList),
                                $templateContent
                            );
                        } else {
                            $templateContent = str_ireplace("{SecondPageArticlePoint}",
                                "null",
                                $templateContent
                            );
                        }
                    //}

                    //给前台是否显示付费信息标志赋值
                    $templateContent = str_ireplace("{FirstNewspaperPageMustPay}",
                        $firstNewspaperPageMustPay,
                        $templateContent
                    );
                    if($firstNewspaperPageMustPay==1){
                        $templateContent = str_ireplace("{FirstPageArticlePoint}",
                            "null",
                            $templateContent
                        );
                    }
                    $templateContent = str_ireplace("{SecondNewspaperPageMustPay}",
                        $secondNewspaperPageMustPay,
                        $templateContent
                    );
                    if($secondNewspaperPageMustPay==1){
                        $templateContent = str_ireplace("{SecondPageArticlePoint}",
                            "null",
                            $templateContent
                        );
                    }


                    //pc 当前第三个版面id
                    $thirdNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfNext(
                        $currentNewspaperId,
                        $secondNewspaperPageId,
                        true
                    );
                    $templateContent = str_ireplace("{ThirdNewspaperPageId}",
                        $thirdNewspaperPageId,
                        $templateContent
                    );

                    $previousNewspaperPageId1 = $newspaperPagePublicData->GetNewspaperPageIdOfPrevious(
                        $currentNewspaperId,
                        $currentNewspaperPageId,
                        true
                    );
                    $previousNewspaperPageId = $newspaperPagePublicData->GetNewspaperPageIdOfPrevious(
                        $currentNewspaperId,
                        $previousNewspaperPageId1,
                        true
                    );

                    if ($previousNewspaperPageId <= 0 && $previousNewspaperPageId1 > 0) {
                        $previousNewspaperPageId = $previousNewspaperPageId1;
                    }

                    $templateContent = str_ireplace("{PreviousNewspaperPageId}",
                        $previousNewspaperPageId,
                        $templateContent
                    );



                    //版面导航
                    $arrNewspaperPages = $newspaperPagePublicData->GetListForSelectPage($currentNewspaperId);
                    $listName = "newspaper_page";

                    if (count($arrNewspaperPages) > 0) {
                        Template::ReplaceList($templateContent, $arrNewspaperPages, $listName);
                    } else {
                        Template::RemoveCustomTag($tempContent, $listName);
                    }

                    $templateContent = parent::ReplaceTemplate($templateContent);
                }
            }

            Template::RemoveCustomTag($templateContent);

            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }

    /**
     * 版面选择
     */

    private function GenSelect()
    {
        $channelId = Control::GetRequest("channel_id", 0);
        $templateContent = "";
        if ($channelId > 0) {
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
            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;

    }


    /**
     * 版面选择列表
     */
    private function GenPageList()
    {
        $channelId = Control::GetRequest("channel_id", 0);
        $newspaperId = Control::GetRequest("newspaper_id", 0);
        $templateContent = "";
        if ($channelId > 0) {
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
            $newspaperPagePublicData = new NewspaperPagePublicData();
            $arrNewspaperPages = $newspaperPagePublicData->GetListForSelectPage($newspaperId);

            if (count($arrNewspaperPages) > 0) {

                //默认只显示已发状态的新闻
                $state = 0;

                $newspaperPageIds = "";
                foreach ($arrNewspaperPages as $page) {
                    $newspaperPageIds .= "," . $page["NewspaperPageId"];
                }

                if (strpos($newspaperPageIds, ',') == 0) {
                    $newspaperPageIds = substr($newspaperPageIds, 1);
                }

                //文章列表数据集
                $newspaperArticlePublicData = new NewspaperArticlePublicData();
                $arrNewspaperArticles = $newspaperArticlePublicData->GetListOfMultiPage($newspaperPageIds, $state);

                $listName = "newspaper_page_and_article";
                $tagName = Template::DEFAULT_TAG_NAME;
                $tableIdName = "NewspaperPageId";
                $parentIdName = "NewspaperPageId";
                Template::ReplaceList($templateContent, $arrNewspaperPages, $listName, $tagName, $arrNewspaperArticles, $tableIdName, $parentIdName);
            } else {
                $listName = "newspaper_page_and_article";
                Template::RemoveCustomTag($templateContent, $listName);
            }
            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;

    }


    private function GetNewspaperIdForImport()
    {
        $result = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n", "", Control::PostRequest("AuthorityCode", "", $removeXSS));
        $publishDate = str_ireplace("\r\n", "", Control::PostRequest("PublishDate", "", $removeXSS));

        if ($authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&

            strlen($publishDate) > 0
        ) {
            $newspaperPublicData = new NewspaperPublicData();

            $result = $newspaperPublicData->GetNewspaperIdByPublishDateForImport(
                $publishDate
            );
        }
        return $result;
    }


    //判断当前页数是否是报纸当前版面的第一页
    function IsFirstPageOnPaper($newspaperPageNo)
    {
        $newspaperPageNo = preg_replace('/[^\d]/', '', $newspaperPageNo);
        $newspaperPageNo = intval($newspaperPageNo);
        if ($newspaperPageNo % 2 == 0) {
            return false;
        } else {
            return true;
        }
    }

    //生成报纸版面div定位数据点
    function GenPoint($newsPaperArticleId, $newsPaperArticleTitle, $picMapping)
    {
        $result = null;
        $arr_x = array();
        $arr_y = array();
        self::GenXAndYSortArray($picMapping, $arr_x, $arr_y);
        if (count($arr_x) > 0 && count($arr_y) > 0) {
            $min_x_point = $arr_x[0];
            $max_x_point = $arr_x[count($arr_x) - 1];
            $min_y_point = $arr_y[0];
            $max_y_point = $arr_y[count($arr_y) - 1];
            //$result = array(array($min_x_point, $min_y_point), array($max_x_point, $min_y_point), array($max_x_point, $max_y_point), array($min_x_point, $max_y_point));
            $result = array($newsPaperArticleId, $newsPaperArticleTitle, $min_x_point, $min_y_point, $max_x_point - $min_x_point, $max_y_point - $min_y_point);
        }
        return $result;
    }

    //生成xy坐标点数组
    function GenXAndYSortArray($pointStr, &$arr_x, &$arr_y)
    {
        $pointStr = str_ireplace("</顶点>", "", $pointStr);
        $arr1 = explode("<顶点>", $pointStr);
        if (count($arr1) > 1) {
            unset($arr1[0]);
            for ($i = 0; $i < count($arr1); $i++) {
                if(isset($arr1[$i])){
                    if(strlen($arr1[$i])>1){
                        $arr_2 = explode(",", $arr1[$i]);
                        if(is_array($arr_2) && !empty($arr_2)){
                            $x_point = str_ireplace("%", "", $arr_2[0]);
                            $y_point = str_ireplace("%", "", $arr_2[1]);
                            if ($x_point > 0) {
                                $arr_x[] = $x_point;
                            }
                            if ($y_point > 0) {
                                $arr_y[] = $y_point;
                            }
                        }

                    }
                }

            }
            sort($arr_x);
            sort($arr_y);
        }
    }
} 