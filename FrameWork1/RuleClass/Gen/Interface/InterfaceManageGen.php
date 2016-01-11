<?php

/**
 * Created by PhpStorm.
 * User: a525
 * Date: 15-2-13
 * Time: 下午3:37
 */
class InterfaceManageGen extends BaseManageGen implements IBaseManageGen
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
            case "list":
                $result = self::GenList();
                break;
            case "copy":
                $result = self::GenDeal($method);
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 生成外部接口管理列表页面
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
        $templateContent = Template::Load("interface/interface_content_list.html", "common");
        $jsonType = Control::GetRequest("json_type", "default");

        parent::ReplaceFirst($templateContent);

        if ($channelId > 0) {

            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);


            $publishApiUrl = $channelManageData->GetPublishApiUrl($channelId, TRUE);
            $publishApiType = $channelManageData->GetPublishApiType($channelId, TRUE);

            $tagContent = Language::Load('interface', 3); //此接口找不到数据或解析格式不匹配
            $arrDocumentNews = null;
            switch ($publishApiType) { //外部接口数据格式
                case(0): //json
                    $arrDocumentNews = self::GetArrayOfDocumentFromJson($publishApiUrl, $jsonType);
                    break;
                case(1): //xml
                    $rss = new RSS();
                    $rss->load($publishApiUrl);
                    $arrDocumentNews = $rss->getItems();
                    break;
                default: //json
                    $arrDocumentNews = self::GetArrayOfDocumentFromJson($publishApiUrl, $jsonType);
                    break;
            }

            $tagId = Control::GetRequest("tag_id", "document_news_interface_content_list"); //默认资讯列表
            if (count($arrDocumentNews) > 0) {
                Template::ReplaceList($templateContent, $arrDocumentNews, $tagId);
            } else {
                Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            }
            Template::RemoveCustomTag($templateContent);
        }

        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
        $templateContent = str_ireplace("{JsonType}", $jsonType, $templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }


    /**
     * 复制，移动文档等处理
     * @param string $method 操作类型名称
     * @return string 返回Jsonp修改结果
     */
    private function GenDeal($method)
    {
        $tempContent = Template::Load("interface/interface_content_list_deal.html", "common");
        parent::ReplaceFirst($tempContent);
        $mod = Control::GetRequest("mod", "");
        $channelId = Control::GetRequest("channel_id", 0);
        $toSiteId = Control::GetRequest("to_site_id", 0); //跨站点，站点id
        $jsonType = Control::PostOrGetRequest("json_type", "default"); //默认icms2
        $useOriUrl = Control::PostOrGetRequest("use_ori_url", 0); //是否使用源地址作为直接转向
        $docIdString = $_GET["doc_id_string"]; //GetRequest中的过滤会消去逗号
        $manageUserId = Control::GetManageUserID();
        $manageUserName = Control::GetManageUserName();
        if ($channelId > 0) {
            $channelManageData = new ChannelManageData();
            $documentNewsManageData = new DocumentNewsManageData();
            $publishApiUrl = $channelManageData->GetPublishApiUrl($channelId, TRUE);

            /** 筛选出已选中的文档 **/
            $arrayOfDocumentNewsList = self::GetArrayOfDocumentFromJson($publishApiUrl, $jsonType, $docIdString, $useOriUrl); //取得外部接口数据转为数组

            if (!empty($_POST)) { //提交
                $targetCid = Control::PostRequest("pop_cid", 0); //目标频道ID
                $targetSiteId = $channelManageData->GetSiteId($targetCid, true);

                if ($targetCid > 0) {
                    /**********************************************************************
                     ******************************判断是否有操作权限**********************
                     **********************************************************************/
                    $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                    $can = $manageUserAuthorityManageData->CanChannelCreate(0, $targetCid, $manageUserId);
                    if (!$can) {
                        $result = -10;
                        Control::ShowMessage(Language::Load('document', 26)); //您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
                    } else {


                        $channelType = $channelManageData->GetChannelType($targetCid, true); //频道类型是否匹配
                        $targetChannelType = intval(Control::GetRequest("target_channel_type", "1"));
                        if (count($arrayOfDocumentNewsList) > 0) {
                            if ($channelType == $targetChannelType) {
                                if ($channelType == 1) { //新闻资讯类
                                    $handUploadFileError = self::HandUploadFiles($arrayOfDocumentNewsList, $targetCid, $channelType);

                                    //逆向排序使导入后排序号正确
                                    $arrayForCopy=array();
                                    if(count($arrayOfDocumentNewsList)>0){
                                        for($i=count($arrayOfDocumentNewsList)-1;$i>=0;$i--){
                                            $arrayForCopy[]=$arrayOfDocumentNewsList[$i];
                                        }
                                    }

                                    switch ($method) {
                                        case "copy":
                                            $strResultId = $documentNewsManageData->Copy($targetSiteId, $targetCid, $arrayForCopy, $manageUserId, $manageUserName);
                                            if (strlen($strResultId) > 0) {
                                                $result = 1;
                                                /** 处理DocumentNewsPic */
                                                $uploadFileManageData = new UploadFileManageData();
                                                $documentNewsPicManageData = new DocumentNewsPicManageData();
                                                $arrResultId = explode(",", $strResultId);
                                                foreach ($arrResultId as $oneResultId) {
                                                    $strUploadFiles = $documentNewsManageData->GetUploadFiles($oneResultId);
                                                    $arrayOfUploadFiles = $uploadFileManageData->GetListById(substr($strUploadFiles, 1));
                                                    if (count($arrayOfUploadFiles) > 0) {
                                                        foreach ($arrayOfUploadFiles as $oneArticlePic) {
                                                            $documentNewsPicManageData->Create($oneResultId, $oneArticlePic["UploadFileId"], 0); //加入DocumentNewsPic表
                                                        }
                                                    }
                                                }
                                            } else {
                                                $result = -1;
                                            }
                                            break;
                                        default:
                                            $result = -1;
                                            break;
                                    }

                                    //加入操作日志
                                    $operateContent = 'copy DocumentNews From Interface,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                                    self::CreateManageUserLog($operateContent);


                                    if ($result > 0) {
                                        if ($handUploadFileError == "") {
                                            $jsCode = 'parent.$("#dialog_resultbox").dialog("close");';
                                        } else {
                                            return $handUploadFileError;
                                        }
                                        Control::RunJavascript($jsCode);
                                    } else {
                                        Control::ShowMessage(Language::Load('document', 17));
                                    }
                                }

                            } else {
                                Control::ShowMessage(Language::Load('interface', 4)); //节点类型错误
                            }
                        }
                    }
                }
            }

            $documentList = "";

            //显示操作文档的标题
            //for ($i = 0; $i < count($arrList); $i++) {
            //    $columns = $arrList[$i];
            foreach ($arrayOfDocumentNewsList as $columnValue) {
                $documentList = $documentList . $columnValue["DocumentNewsTitle"] . '<br>';
            }
            //}


            //显示有权限的站点树
            $siteManageData=new SiteManageData();
            $siteList=$siteManageData->GetListForSelect($manageUserId);
            $listName="site_list";
            Template::ReplaceList($tempContent,$siteList,$listName);

            //替换channel type供手动输入目的节点id判断
            $channelType = $channelManageData->GetChannelType($channelId,true);
            $tempContent = str_ireplace("{ChannelType}", $channelType, $tempContent);


            //显示当前站点的节点树
            //显示当前站点的节点树
            if($toSiteId>0){
                $siteId=$toSiteId;
            }else{
                $siteId = $channelManageData->GetSiteID($channelId,true);
            }
            $order = "";
            $arrayChannelTree = $channelManageData->GetListForManageLeft($siteId, $manageUserId, $order);
            $listName = "channel_tree";
            Template::ReplaceList($tempContent, $arrayChannelTree, $listName);

            $methodName = "复制";
            $replaceArr = array(
                "{mod}" => $mod,
                "{method}" => $method,
                "{SiteId}" => $siteId,
                "{ChannelId}" => $channelId,
                "{ChannelName}" => "",
                "{Method}" => $methodName,
                "{MethodName}" => $methodName,
                "{DealType}" => $methodName,
                "{DocumentList}" => $documentList,
                "{DocIdString}" => $docIdString,
                "{JsonType}" => $jsonType,
                "{PicStyleSelector}" => "none"
            );

            $tempContent = strtr($tempContent, $replaceArr);

        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


    /**
     * 取得外部接口json数据并转为可以直接insert的数组
     * @param string $publishApiUrl 外部接口url
     * @param string $jsonType 外部接口json类: icms1,icms2,others
     * @param string $docIdString 筛选其中几条的字符串
     * @param int $useOriUrl 是否使用源地址作为直接转向
     * @return array 数组结果
     */
    private function GetArrayOfDocumentFromJson($publishApiUrl, $jsonType, $docIdString = "", $useOriUrl=0)
    {
        $result = null;
        $arrayOfAllDocumentNewsList = null;

        $interFaceData=new InterfaceData();

        $arrayOfAllDocumentNewsList=$interFaceData->GetList($publishApiUrl,$jsonType,$useOriUrl=0);
        /** 取出选中的条目**/
        if ($docIdString != "") {
            $arraySelect = explode(",", $docIdString);
            $result = Array();
            foreach ($arraySelect as $selectNumber) {
                $result[] = $arrayOfAllDocumentNewsList[$selectNumber];
            }
        } else {
            $result = $arrayOfAllDocumentNewsList;
        }


        return $result;
    }

    /**
     * 处理数据集内的附件(content里面的图片,题图等)
     * @param array $arrayOfItems 数组数据集
     * @param int $channelId 节点id (tableId)
     * @param int $channelType 节点类型: document,activity,information,product
     * @return int 操作结果
     */
    private function HandUploadFiles(&$arrayOfItems, $channelId, $channelType = 1)
    {
        $error = "";
        if (count($arrayOfItems) > 0) {
            switch ($channelType) {
                case 1: //document
                    for ($i = 0; $i < count($arrayOfItems); $i++) {
                        $errorOfOne = "";
                        $uploadFile = new UploadFile();
                        /** 处理题图 **/
                        //题图1
                        $url = $arrayOfItems[$i]["TitlePic1UploadFilePath"];
                        if (strlen($url) > 0) {
                            if ((stripos($url, "http") != 0 || stripos($url, "http") === false)) { //不是绝对地址且不为空  则加上域名
                                $url = $arrayOfItems[$i]["SiteUrl"] . $url;
                            }
                            parent::SaveRemoteImage($url, UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1, $channelId, $uploadFile); //远程上传题图1
                            if ($uploadFile->UploadFileId > 0) {
                                $arrayOfItems[$i]["TitlePic1UploadFileId"] = $uploadFile->UploadFileId;
                            } else {
                                $errorOfOne .= "<div style='margin-left:13px'>TitlePic1:" . Language::Load("interface", 7) . "</div>"; //TitlePic1:抓取题图失败！请手动上传！
                            }
                        }
                        //题图2
                        $url = $arrayOfItems[$i]["TitlePic2UploadFilePath"];
                        if (strlen($url) > 0) {
                            if ((stripos($url, "http") != 0 || stripos($url, "http") === false)) { //不是绝对地址且不为空  则加上域名
                                $url = $arrayOfItems[$i]["SiteUrl"] . $url;
                            }
                            parent::SaveRemoteImage($url, UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2, $channelId, $uploadFile); //远程上传题图2
                            if ($uploadFile->UploadFileId > 0) {
                                $arrayOfItems[$i]["TitlePic2UploadFileId"] = $uploadFile->UploadFileId;
                            } else {
                                $errorOfOne .= "<div style='margin-left:13px'>TitlePic2:" . Language::Load("interface", 7) . "</div>"; //TitlePic2:抓取题图失败！请手动上传！
                            }
                        }
                        //题图3
                        $url = $arrayOfItems[$i]["TitlePic3UploadFilePath"];
                        if (strlen($url) > 0) {
                            if ((stripos($url, "http") != 0 || stripos($url, "http") === false)) { //不是绝对地址且不为空  则加上域名
                                $url = $arrayOfItems[$i]["SiteUrl"] . $url;
                            }
                            parent::SaveRemoteImage($url, UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3, $channelId, $uploadFile); //远程上传题图3
                            if ($uploadFile->UploadFileId > 0) {
                                $arrayOfItems[$i]["TitlePic3UploadFileId"] = $uploadFile->UploadFileId;
                            } else {
                                $errorOfOne .= "<div style='margin-left:13px'>TitlePic3:" . Language::Load("interface", 7) . "</div>"; //TitlePic3:抓取题图失败！请手动上传！
                            }
                        }

                        /** 处理内容图片 **/
                        $strUploadFiles = "";
                        $preg = $preg = "/\<" . "img" . "(.*)\/>/imsU"; //取得所有 <img />的数组
                        preg_match_all($preg, $arrayOfItems[$i]["DocumentNewsContent"], $arrayImages, PREG_PATTERN_ORDER);

                        foreach ($arrayImages[0] as $oneImg) {
                            $oneImg=str_ireplace("&","",$oneImg);//处理’&middot;‘等转义字符中的‘&’被xml报错的问题
                            $oneImgUrl = Template::GetParamValue($oneImg, "src", "img"); //取得所有src
                            /** 远程图片上传 */
                            if ((stripos($oneImgUrl, "http") != 0 || stripos($oneImgUrl, "http") === false) && strlen($oneImgUrl) > 0) { //不是绝对地址且不为空  则加上域名
                                parent::SaveRemoteImage($arrayOfItems[$i]["SiteUrl"] . $oneImgUrl, UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT, $channelId, $uploadFile);
                            } else {
                                parent::SaveRemoteImage($oneImgUrl, UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT, $channelId, $uploadFile);
                            }
                            if ($uploadFile->UploadFileId > 0) { //替换原src
                                $arrayOfItems[$i]["DocumentNewsContent"] = str_ireplace($oneImgUrl, $uploadFile->UploadFilePath, $arrayOfItems[$i]["DocumentNewsContent"]);
                                $strUploadFiles .= "," . $uploadFile->UploadFileId;
                            } else {
                                $errorOfOne .= "<div style='margin-left:13px'>content:" . Language::Load("interface", 8) . "</div>"; //content:抓取内容图片失败！请手动编辑！
                            }
                        }
                        $arrayOfItems[$i]["UploadFiles"] = $strUploadFiles;


                        /** 处理直接转向地址 */
                        $directUrl = $arrayOfItems[$i]["DirectUrl"];
                        if ((stripos($directUrl, "http") != 0 || stripos($directUrl, "http") === false) && strlen($directUrl) > 0) { //不是绝对地址且不为空  则加上域名
                            $arrayOfItems[$i]["DirectUrl"] = $arrayOfItems[$i]["SiteUrl"] . $directUrl;
                        }
                        /** error提示 */
                        if (strlen($errorOfOne) > 0) {
                            $error .= $arrayOfItems[$i]["DocumentNewsTitle"] . ":<br>" . $errorOfOne . "<br>";
                        }
                    }
                    break;

                default:
                    break;
            }
        }
        return $error;
    }
}

?>