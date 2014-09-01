<?php

/**
 * 后台管理 产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author zhangchi
 */
class ProductManageGen extends BaseManageGen implements IBaseManageGen
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
        $tempContent = "";
        $channelId = Control::GetRequest("channel_id", 0);
        $manageUserId = Control::GetManageUserId();
        $resultJavaScript = "";

        if ($channelId > 0 && $manageUserId > 0) {
            $tempContent = Template::Load("product/product_deal.html", "common");
            parent::ReplaceFirst($tempContent);
            $productManageData = new ProductManageData();
            $productParamManageData = new ProductParamManageData();
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);

            if (!empty($_POST)) {
                $httpPostData = $_POST;

                //产品新增
                $productId = $productManageData->Create($httpPostData);
                //加入操作日志
                $operateContent = 'Create Product,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:productId:' . $productId;
                self::CreateManageUserLog($operateContent);

                if ($productId > 0) {

                    //产品参数新增
                    $productParamManageData->CreateProductParam($httpPostData, $productId);
                    if( !empty($_FILES)){
                        //title pic1
                        $fileElementName = "file_title_pic_1";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_1;
                        $tableId = $channelId;
                        $uploadFile1 = new UploadFile();
                        $uploadFileId1 = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile1,
                            $uploadFileId1
                        );

                        if (intval($titlePic1Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }

                        //title pic2
                        $fileElementName = "file_title_pic_2";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_2;
                        $uploadFileId2 = 0;
                        $uploadFile2 = new UploadFile();
                        $titlePic2Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile2,
                            $uploadFileId2
                        );
                        if (intval($titlePic2Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }
                        //title pic3
                        $fileElementName = "file_title_pic_3";

                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_3;
                        $uploadFileId3 = 0;

                        $uploadFile3 = new UploadFile();

                        $titlePic3Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile3,
                            $uploadFileId3)
                        ;
                        if (intval($titlePic3Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }
                        //title pic4
                        $fileElementName = "file_title_pic_4";

                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_4;
                        $uploadFileId4 = 0;

                        $uploadFile4 = new UploadFile();

                        $titlePic4Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile4,
                            $uploadFileId4)
                        ;
                        if (intval($titlePic4Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }

                        if($uploadFileId1>0 || $uploadFileId2>0 || $uploadFileId3>0 || $uploadFileId4>0){
                            $productManageData->ModifyTitlePic($productId, $uploadFileId1, $uploadFileId2, $uploadFileId3, $uploadFileId4);
                        }

                        $siteConfigManageData = new SiteConfigManageData($siteId);
                        if($uploadFileId1>0){
                            $productTitlePic1MobileWidth = $siteConfigManageData->ProductTitlePic1MobileWidth;
                            if($productTitlePic1MobileWidth<=0){
                                $productTitlePic1MobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$productTitlePic1MobileWidth);

                            $productTitlePic1PadWidth = $siteConfigManageData->ProductTitlePic1PadWidth;
                            if($productTitlePic1PadWidth<=0){
                                $productTitlePic1PadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$productTitlePic1PadWidth);
                        }
                    }

                    //授权给创建人
                    if ($manageUserId > 1) { //只有非ADMIN的要授权
                        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                        $manageUserAuthorityManageData->CreateForChannel($siteId, $productId, $manageUserId);
                    }

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/product_data');

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //Control::CloseTab();
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 1)); //新增失败！
                }

            }

            $tempContent = str_ireplace("{CreateDate}", strval(date('Y-m-d', time())), $tempContent);
            $tempContent = str_ireplace("{ChannelId}", strval($channelId), $tempContent);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);

            //生成产品新增界面
            $fieldsOfChannel = $productManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

            //生成产品参数新增界面
            self::SubGenProductParamTypeClass($tempContent);
            //把对应ID的CMS标记替换成指定内容
            //替换子循环里的<![CDATA[标记
            $tempContent = str_ireplace("<icms_child", "<icms", $tempContent);
            $tempContent = str_ireplace("</icms_child>", "</icms>", $tempContent);
            $tempContent = str_ireplace("<item_child", "<item", $tempContent);
            $tempContent = str_ireplace("</item_child>", "</item>", $tempContent);
            $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
            $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
            $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
            $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
            self::SubGenProductParamType($tempContent);
            self::SubGenProductParamTypeControl($tempContent);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        }

        return $tempContent;
    }


    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify(){
        $tempContent = Template::Load("product/product_deal.html", "common");
        $productId = Control::GetRequest("product_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($productId >0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);
            $productManageData = new ProductManageData();

            $tempContent = str_ireplace("{ProductId}", $productId, $tempContent);

            $siteId = $productManageData->GetSiteId($productId, false);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);


            //加载产品表数据
            $arrOne = $productManageData->GetOne($productId);
            Template::ReplaceOne($tempContent, $arrOne);

            //生成产品参数界面并加载产品参数数据
            self::SubGenProductParamTypeClass($tempContent);
            //把对应ID的CMS标记替换成指定内容
            //替换子循环里的<![CDATA[标记
            $tempContent = str_ireplace("<icms_child", "<icms", $tempContent);
            $tempContent = str_ireplace("</icms_child>", "</icms>", $tempContent);
            $tempContent = str_ireplace("<item_child", "<item", $tempContent);
            $tempContent = str_ireplace("</item_child>", "</item>", $tempContent);
            $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
            $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
            $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
            $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
            self::SubGenProductParamType($tempContent);
            //取产品参数表数据
            $productParamManageData = new ProductParamManageData();
            $arrProductParam = $productParamManageData->GetList($productId);
            self::SubGenProductParamTypeControl($tempContent,$arrProductParam);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                    //产品表数据修改
                    $result = $productManageData->Modify($httpPostData, $productId);

                    //加入操作日志
                    $operateContent = 'Modify Product,POST FORM:'.implode('|',$_POST).';\r\nResult:ProductId:'.$result;
                    self::CreateManageUserLog($operateContent);

                    if ($result > 0) {
                        //产品参数表数据修改
                        $productParamManageData->ModifyProductParam($httpPostData, $productId);
                        //题图操作
                        if( !empty($_FILES)){
                            //title pic1
                            $fileElementName = "file_title_pic_1";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_1;
                            $tableId = $productId;
                            $uploadFile1 = new UploadFile();
                            $uploadFileId1 = 0;
                            $titlePic1Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile1,
                                $uploadFileId1
                            );

                            if (intval($titlePic1Result) <=0 && $uploadFileId1<=0){
                                //上传出错或没有选择文件上传
                            }else{
                                //删除原有题图
                                $oldUploadFileId1 = $productManageData->GetTitlePic1UploadFileId($productId, false);
                                parent::DeleteUploadFile($oldUploadFileId1);

                                //修改题图
                                $productManageData->ModifyTitlePic1UploadFileId($productId, $uploadFileId1);
                            }

                            //title pic2
                            $fileElementName = "file_title_pic_2";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_2;
                            $uploadFileId2 = 0;
                            $uploadFile2 = new UploadFile();
                            $titlePic2Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile2,
                                $uploadFileId2
                            );
                            if (intval($titlePic2Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{
                                //删除原有题图
                                $oldUploadFileId2 = $productManageData->GetTitlePic2UploadFileId($productId, false);
                                parent::DeleteUploadFile($oldUploadFileId2);

                                //修改题图
                                $productManageData->ModifyTitlePic2UploadFileId($productId, $uploadFileId2);
                            }
                            //title pic3
                            $fileElementName = "file_title_pic_3";

                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_3;
                            $uploadFileId3 = 0;

                            $uploadFile3 = new UploadFile();

                            $titlePic3Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile3,
                                $uploadFileId3
                            );
                            if (intval($titlePic3Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{
                                //删除原有题图
                                $oldUploadFileId3 = $productManageData->GetTitlePic3UploadFileId($productId, false);
                                parent::DeleteUploadFile($oldUploadFileId3);

                                //修改题图
                                $productManageData->ModifyTitlePic3UploadFileId($productId, $uploadFileId3);
                            }
                            //title pic4
                            $fileElementName = "file_title_pic_4";

                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_4;
                            $uploadFileId4 = 0;

                            $uploadFile4 = new UploadFile();

                            $titlePic4Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile4,
                                $uploadFileId4
                            );
                            if (intval($titlePic4Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{
                                //删除原有题图
                                $oldUploadFileId4 = $productManageData->GetTitlePic4UploadFileId($productId, false);
                                parent::DeleteUploadFile($oldUploadFileId4);

                                //修改题图
                                $productManageData->ModifyTitlePic4UploadFileId($productId, $uploadFileId4);
                            }

                            //重新制作题图1的相关图片
                            $siteConfigManageData = new SiteConfigManageData($siteId);
                            if($uploadFileId1>0){
                                $productTitlePic1MobileWidth = $siteConfigManageData->ProductTitlePic1MobileWidth;
                                if($productTitlePic1MobileWidth<=0){
                                    $productTitlePic1MobileWidth  = 320; //默认320宽
                                }
                                self::GenUploadFileMobile($uploadFileId1,$productTitlePic1MobileWidth);

                                $productTitlePic1PadWidth = $siteConfigManageData->ProductTitlePic1PadWidth;
                                if($productTitlePic1PadWidth<=0){
                                    $productTitlePic1PadWidth  = 1024; //默认1024宽
                                }
                                self::GenUploadFilePad($uploadFileId1,$productTitlePic1PadWidth);
                            }
                        }

                        //删除缓冲
                        DataCache::RemoveDir(CACHE_PATH . '/product_data');

                        //javascript 处理
                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }

                    } else {
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 4)); //编辑失败！
                    }

            }

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 生成资讯管理列表页面
     */
    private function GenList() {
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if($siteId<=0){
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return Language::Load('channel', 4);
        }

        //load template
        $tempContent = Template::Load("product/product_list.html", "common");

        parent::ReplaceFirst($tempContent);

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);
        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                return Language::Load('channel', 4);
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "product_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $productManageData = new ProductManageData();
            $arrProductList = $productManageData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType,
                $isSelf,
                $manageUserId
            );
            if (count($arrProductList) > 0) {
                Template::ReplaceList($tempContent, $arrProductList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=product&m=list&channel_id=$channelId&p={0}&ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load('channel', 5), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    ///////////////////////////// 以下为产品参数表处理方法/////////////////////////////////

    /**
     * 替换模板中的产品参数类别标记生成产品参数类别列表
     * @param string $tempContent 模板字符串
     */
    public function SubGenProductParamTypeClass(&$tempContent)
    {
        $keyName = "icms";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $productParamTypeClassData = new ProductParamTypeClassManageData();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $channelId = Template::GetParamValue($content, "id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_param_type_class_list') {
                            $arrProductParamTypeClassList = $productParamTypeClassData->GetList($channelId);
                            Template::ReplaceList($content, $arrProductParamTypeClassList, $channelId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $channelId, $content, $keyName);
                        }
                    }
                }
            }
        }
    }

    /**
     * 替换模板中的产品参数类别标记生成产品参数类别列表
     * @param string $tempContent 模板字符串
     */
    public function SubGenProductParamType(&$tempContent)
    {
        $keyName = "icms";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $productParamTypeData = new ProductParamTypeManageData();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $productParamTypeClassId = Template::GetParamValue($content, "id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_param_type_list') {
                            $arrProductParamTypeList = $productParamTypeData->GetList($productParamTypeClassId);
                            Template::ReplaceList($content, $arrProductParamTypeList, $productParamTypeClassId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $productParamTypeClassId, $content, $keyName);
                        }
                    }
                }
            }
        }
    }

    /**
     * 替换模板中的控件标记按类别生成控件
     * @param string $tempContent 模板字符串
     * @param array $arrProductParam 产品参数值数组
     */
    public function SubGenProductParamTypeControl(&$tempContent,$arrProductParam=null)
    {
        $keyName = "icms_control";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $productParamTypeId = Template::GetParamValue($content, "id", $keyName);
                        $paramValueType = Template::GetParamValue($content, "type", $keyName);
                        $inputClass = Template::GetParamValue($content, "input_class", $keyName);
                        $selectClass = Template::GetParamValue($content, "select_class", $keyName);
                        $content = self::GenControl($productParamTypeId, $paramValueType, $inputClass, $selectClass ,$arrProductParam);
                        $tempContent = Template::ReplaceCustomTag($tempContent, $productParamTypeId, $content, $keyName);
                    }
                }
            }
        }
    }

    /**
     * 根据参数类型生成控件
     * @param string $productParamTypeId 参数类型Id
     * @param string $paramValueType 参数类型值
     * @param string $inputClass 文本输入框控件样式名称
     * @param string $selectClass 下拉控件控件样式名称
     * @param array $arrProductParam 产品参数值数组
     * @return string 输入框html
     */
    public function GenControl($productParamTypeId, $paramValueType, $inputClass, $selectClass,$arrProductParam)
    {
        switch ($paramValueType) {
            case "0":
                $columnName="ShortStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;
            case "1":
                $columnName="LongStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;
            case "2":
                $columnName="MaxStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;
            case "3":
                $columnName="FloatValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;
            case "4":
                $columnName="MoneyValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;
            case "5":
                $columnName="UrlValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;
            case "6":
                $columnName="ShortStringValue";
                $controlName = "pps_".$columnName."_" . $productParamTypeId;
                $controlId = "pps_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
                $arrProductParamTypeOption = $productParamTypeOptionManageData->GetList($productParamTypeId);
                $result = self::GenSelectControl($controlId, $controlName, $selectClass, $arrProductParamTypeOption,$controlValue);
                break;
            default:
                $columnName="ShortStringValue";
                $controlName = "pps_".$columnName."_" . $productParamTypeId;
                $controlId = "pps_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenInputControl($controlId, $controlName, $inputClass,$controlValue);
                break;

        }
        return $result;
    }

    /**
     * 生成产品参数输入框
     * @param string $name 输入框name
     * @param string $id 输入框id
     * @param string $inputClass 输入框样式类名称
     * @param string $value 输入框的值
     * @return string 输入框html
     */
    public function GenInputControl($id, $name, $inputClass, $value = "")
    {
        $result = '<input type="text" name="' . $name . '" id="' . $id . '" class="' . $inputClass . '" value="' . $value . '" />';
        return $result;
    }

    /**
     * 生成产品参数选项下拉框
     * @param string $name 下拉控件name
     * @param string $id 下拉控件id
     * @param string $selectClass 下拉控件样式类名称
     * @param array $arrProductParamTypeOption 产品参数选项数组
     * @param string $value 产品参数下拉控件选中值
     * @return string 下拉框html
     */
    public function GenSelectControl($id, $name, $selectClass, $arrProductParamTypeOption, $value = "")
    {
        $result = '<select name="' . $name . '" id="' . $id . '" class="' . $selectClass . '">';
        for ($i = 0; $i < count($arrProductParamTypeOption); $i++) {
            if (!empty($value) && $value === $arrProductParamTypeOption[$i]["OptionName"])
                $result = $result . '<option value="' . $arrProductParamTypeOption[$i]["OptionName"] . '" selected>' . $arrProductParamTypeOption[$i]["OptionName"] . '</option>';
            else $result = $result . '<option value="' . $arrProductParamTypeOption[$i]["OptionName"] . '">' . $arrProductParamTypeOption[$i]["OptionName"] . '</option>';
        }
        $result = $result . "</select>";
        return $result;
    }

    /**
     * 根据产品参数类型ID和产品参数类型对应值字段名称得到产品参数值
     * @param array $arrProductParam 产品参数数组
     * @param string $productParamTypeId 产品参数类型Id
     * @param string $productParamColumnName 产品参数类型对应值字段名称
     * @return string 产品参数值
     */
    public function GetProductParamValue($arrProductParam,$productParamTypeId,$productParamColumnName){
        $productParamValue="";
        for ($i = 0; $i < count($arrProductParam); $i++) {
            if($productParamTypeId===$arrProductParam[$i]["ProductParamTypeId"])
                $productParamValue=$arrProductParam[$i][$productParamColumnName];
        }
        return $productParamValue;
    }

} 