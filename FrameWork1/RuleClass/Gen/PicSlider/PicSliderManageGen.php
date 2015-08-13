<?php

/**
 * 后台管理 图片轮换 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_PicSlider
 * @author zhangchi
 */
class PicSliderManageGen extends BaseManageGen implements IBaseManageGen
{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_sort":
                $result = self::AsyncModifySort();
                break;
            case "async_modify_sort_by_drag":
                $result = self::AsyncModifySortByDrag();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $templateContent = "";
        $channelId = Control::GetRequest("channel_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if ($siteId <= 0) {
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $can = $manageUserAuthorityManageData->CanChannelCreate($siteId, $channelId, $manageUserId);
        if (!$can) {
            die(Language::Load('channel', 4));
        }

        if ($channelId > 0 && $manageUserId > 0) {
            $templateContent = Template::Load("pic_slider/pic_slider_deal.html", "common");
            parent::ReplaceFirst($templateContent);
            $picSliderManageData = new PicSliderManageData();

            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);


            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $picSliderId = $picSliderManageData->Create($httpPostData, $manageUserId);
                //加入操作日志
                $operateContent = 'Create PicSlider,POST FORM:' . implode('|', $_POST) . ';\r\nResult:PicSliderId:' . $picSliderId;
                self::CreateManageUserLog($operateContent);

                if ($picSliderId > 0) {

                    //新增文档时修改排序号到当前频道的最大排序
                    $picSliderManageData->ModifySortWhenCreate($channelId, $picSliderId);

                    if (!empty($_FILES)) {
                        //title pic1
                        $fileElementName = "file_upload_file";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PIC_SLIDER;
                        $tableId = $picSliderId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $uploadFileResult = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($uploadFileResult) <= 0) {
                            //上传出错或没有选择文件上传
                        } else {

                        }

                        if ($uploadFileId > 0) {
                            $picSliderManageData->ModifyUploadFileId($picSliderId, $uploadFileId);
                        }

                    }

                    //删除缓冲
                    parent::DelAllCache();

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //Control::CloseTab();
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('pic_slider', 2)); //新增失败！
                }

            }

            $fields = $picSliderManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);

            parent::ReplaceEnd($templateContent);

            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);

        }


        return $templateContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $templateContent = "";
        $picSliderId = Control::GetRequest("pic_slider_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($picSliderId > 0 && $manageUserId > 0) {

            $templateContent = Template::Load("pic_slider/pic_slider_deal.html", "common");

            $picSliderManageData = new PicSliderManageData();
            //加载原有数据
            $arrOne = $picSliderManageData->GetOne($picSliderId);
            Template::ReplaceOne($templateContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $picSliderManageData->Modify($httpPostData, $picSliderId);
                //加入操作日志
                $operateContent = 'Modify Channel,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //题图操作
                    if (!empty($_FILES)) {
                        //upload file
                        $fileElementName = "file_upload_file";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PIC_SLIDER;
                        $tableId = $picSliderId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $uploadResult = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($uploadResult) <= 0 && $uploadFileId <= 0) {
                            //上传出错或没有选择文件上传
                        } else {
                            //删除原有题图
                            $oldUploadFileId = $picSliderManageData->GetUploadFileId($picSliderId, false);
                            parent::DeleteUploadFile($oldUploadFileId);

                            //修改题图
                            $picSliderManageData->ModifyUploadFileId($picSliderId, $uploadFileId);
                        }

                    }

                    //删除缓冲
                    parent::DelAllCache();

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }

                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('pic_slider', 5)); //编辑失败！
                }

            }

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);
            parent::ReplaceEnd($templateContent);
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);

        }
        return $templateContent;
    }

    /**
     * 删除到回收站
     * @return string 模板内容页面
     */
    private function GenRemoveToBin()
    {
        $tempContent = Template::Load("channel/channel_remove_to_bin.html", "common");
        $channelId = Control::GetRequest("channel_id", 0);
        $channelManageData = new ChannelManageData();
        $channelManageData->UpdateParentChildrenChannelId($channelId);

        return $tempContent;
    }


    /**
     * 生成列表页面
     */
    private function GenList()
    {
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if ($siteId <= 0) {
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanChannelExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }

        //load template
        $tempContent = Template::Load("pic_slider/pic_slider_list.html", "common");

        parent::ReplaceFirst($tempContent);


        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanChannelSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                die(Language::Load('channel', 4));
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {

            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "pic_slider_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $picSliderManageData = new PicSliderManageData();
            $arrPicSliderList = $picSliderManageData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType,
                $isSelf,
                $manageUserId
            );
            if (count($arrPicSliderList) > 0) {
                Template::ReplaceList($tempContent, $arrPicSliderList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=pic_slider&m=list&channel_id=$channelId&p={0}
                            &ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("pic_slider", 4), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


    /**
     * 修改排序号
     * @return int 修改结果
     */
    private function AsyncModifySort(){
        $result = -1;
        $picSliderId = Control::GetRequest("pic_slider_id", 0);
        $sort = Control::GetRequest("sort", 0);
        if($picSliderId>0){
            $picSliderManageData = new PicSliderManageData();
            $result = $picSliderManageData->ModifySort($sort, $picSliderId);
        }
        return $result;
    }


    /**
     * 批量修改排序号
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifySortByDrag() {
        $arrPicSliderId = Control::GetRequest("sort", null);
        if(!empty($arrPicSliderId)){
            $picSliderManageData = new PicSliderManageData();
            $result = $picSliderManageData->ModifySortForDrag($arrPicSliderId);
            return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
        }  else{
            return "";
        }
    }
} 