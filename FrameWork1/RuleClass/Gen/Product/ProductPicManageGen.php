<?php

/**
 * 产品价格后台管理生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_ProductPic
 * @author yanjiuyuan
 */
class ProductPicManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 牵引生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成产品图片管理新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $channelId = Control::GetRequest("channel_id", 0);
        $productId = Control::GetRequest("product_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        $tempContent = Template::Load("product/product_pic_deal.html", "common");
        $resultJavaScript = "";
        if ($productId > 0 && $manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);
            $productPicManageData = new ProductPicManageData();
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $ProductPicId = $productPicManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create ProductPic,POST FORM:' . implode('|', $_POST) . ';\r\nResult:ProductPicId:' . $ProductPicId;
                self::CreateManageUserLog($operateContent);

                if ($ProductPicId > 0) {
                    if( !empty($_FILES)){
                        //file pic
                        $fileElementName = "file_pic";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PIC;
                        $tableId = $productId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $titlePicResult = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($titlePicResult) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }
                        if($uploadFileId>0){
                            $productPicManageData->ModifyUploadFileId($ProductPicId, $uploadFileId);
                        }
                        $siteConfigData = new SiteConfigData($siteId);
                        if($uploadFileId>0){
                            $productPicThumb1Width = $siteConfigData->ProductPicThumb1Width;
                            if($productPicThumb1Width<=0){
                                $productPicThumb1Width  = 350; //默认350宽
                            }
                            parent::GenUploadFileThumb1($uploadFileId,$productPicThumb1Width);

                            $productPicThumb2Width = $siteConfigData->ProductPicThumb2Width;
                            if($productPicThumb2Width<=0){
                                $productPicThumb2Width  = 200; //默认200宽
                            }
                            parent::GenUploadFileThumb2($uploadFileId,$productPicThumb2Width);

                            $productPicThumb3Width = $siteConfigData->ProductPicThumb3Width;
                            if($productPicThumb3Width<=0){
                                $productPicThumb3Width  = 100; //默认50宽
                            }
                            parent::GenUploadFileThumb3($uploadFileId,$productPicThumb3Width);

                            $productPicMobileWidth = $siteConfigData->ProductPicMobileWidth;
                            if($productPicMobileWidth<=0){
                                $productPicMobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId,$productPicMobileWidth);

                            $productPicPadWidth = $siteConfigData->ProductPicPadWidth;
                            if($productPicPadWidth<=0){
                                $productPicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId,$productPicPadWidth);

                        }
                    }
                    //javascript 处理
                    //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 1));
                    $resultJavaScript .= '<' . 'script type="text/javascript">parent.location.href=parent.location.href;</script>';
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 2)); //新增失败！
                }
            }
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);
            $tempContent = str_ireplace("{CreateDate}",date("Y-m-d H:i:s"), $tempContent);
            $tempContent = str_ireplace("{ManageUserId}", strval($manageUserId), $tempContent);
            $tempContent = str_ireplace("{ProductId}", strval($productId), $tempContent);
            $fieldsOfProductPic = $productPicManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfProductPic);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

        }
        return $tempContent;
    }

    /**
     * 修改状态
     * @return string 修改结果
     */
    private function AsyncModifyState()
    {
        //$result = -1;
        $ProductPicId = Control::GetRequest("product_pic_id", 0);
        $state = Control::GetRequest("state",0);
        if ($ProductPicId > 0) {
            $productData = new ProductPicManageData();
            $result = $productData->ModifyState($ProductPicId,$state);
            //加入操作日志
            $operateContent = 'ModifyState ProductPic,Get FORM:' . implode('|', $_GET) . ';\r\nResult:ProductPicId:' . $ProductPicId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }

    /**
     * 生成产品图片修改页面
     * @return mixed|string
     */
    private function GenModify()
    {
        $manageUserId = Control::GetManageUserId();
        $tempContent = Template::Load("product/product_pic_deal.html", "common");
        $resultJavaScript="";
        $ProductPicId = Control::GetRequest("product_pic_id", 0);
        $pageIndex = Control::GetRequest("p", 1);
        $channelId = Control::GetRequest("channel_id", 0);
        $productId = Control::GetRequest("product_id", 0);
        parent::ReplaceFirst($tempContent);
        $productPicManageData = new ProductPicManageData();
        if ($ProductPicId >0 && $manageUserId > 0) {
            $channelManageData = new ChannelManageData();
            $withCache = FALSE;
            $siteId = $channelManageData->GetSiteId($channelId, $withCache);
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $productPicManageData->Modify($httpPostData, $ProductPicId);

                //加入操作日志
                $operateContent = 'Modify ProductPic,POST FORM:' . implode('|', $_POST) . ';\r\nResult:ProductPicId:' . $ProductPicId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    if( !empty($_FILES)){
                        //file pic
                        $fileElementName = "file_pic";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PIC;
                        $tableId = $productId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($titlePic1Result) <=0 && $uploadFileId<=0){
                            //上传出错或没有选择文件上传
                        }else{
                            //删除原有题图
                            $oldUploadFileId = $productPicManageData->GetUploadFileId($ProductPicId, false);
                            parent::DeleteUploadFile($oldUploadFileId);

                            //修改题图
                            $productPicManageData->ModifyUploadFileId($ProductPicId, $uploadFileId);
                        }

                        $siteConfigData = new SiteConfigData($siteId);
                        if($uploadFileId>0){
                            $productPicThumb1Width = $siteConfigData->ProductPicThumb1Width;
                            if($productPicThumb1Width<=0){
                                $productPicThumb1Width  = 350; //默认350宽
                            }
                            parent::GenUploadFileThumb1($uploadFileId,$productPicThumb1Width);

                            $productPicThumb2Width = $siteConfigData->ProductPicThumb2Width;
                            if($productPicThumb2Width<=0){
                                $productPicThumb2Width  = 200; //默认200宽
                            }
                            parent::GenUploadFileThumb2($uploadFileId,$productPicThumb2Width);

                            $productPicThumb3Width = $siteConfigData->ProductPicThumb3Width;
                            if($productPicThumb3Width<=0){
                                $productPicThumb3Width  = 100; //默认100宽
                            }
                            parent::GenUploadFileThumb3($uploadFileId,$productPicThumb3Width);

                            $productPicMobileWidth = $siteConfigData->ProductPicMobileWidth;
                            if($productPicMobileWidth<=0){
                                $productPicMobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId,$productPicMobileWidth);

                            $productPicPadWidth = $siteConfigData->ProductPicPadWidth;
                            if($productPicPadWidth<=0){
                                $productPicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId,$productPicPadWidth);
                        }
                    }
                    //javascript 处理
                    $resultJavaScript .= '<' . 'script type="text/javascript">parent.location.href=parent.location.href;</script>';
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 4)); //修改失败！
                }
            }
            $arrList = $productPicManageData->GetOne($ProductPicId);
            Template::ReplaceOne($tempContent, $arrList, false);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
        }
        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 产品图片管理列表页面
     * @return mixed|string
     */
    private function GenList() {
        $templateContent = Template::Load("product/product_pic_list.html", "common");
        $ProductId = Control::GetRequest("product_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $tag = Control::GetRequest("tag", "");
        $tag = urldecode($tag);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);

        if ($pageIndex > 0 && $ProductId > 0) {
            //生成产品图片类别下拉列表
            $templateContent = str_ireplace("{ProductId}", $ProductId, $templateContent);
            $productPicManageData = new ProductPicManageData();
            $tagId = "product_pic_tag_list";
            $arrProductPicTagList = $productPicManageData->GetPicTagList($ProductId);
            Template::ReplaceList($templateContent, $arrProductPicTagList, $tagId, "icms");
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            //生成产品图片列表
            $tagId = "product_pic_list";
            $productPicManageData = new ProductPicManageData();
            $arrList = $productPicManageData->GetListForPager($ProductId, $pageBegin, $pageSize, $allCount, $tag, $searchKey);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=product_pic&m=list&product_id=".$ProductId."&"."tag=".urlencode($tag)."&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrList,$tagId);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("product", 101), $templateContent);
            }
        }
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

}

?>
