<?php

/**
 * 后台管理 资讯内容图片 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author zhangchi
 */
class DocumentNewsPicManageGen extends BaseManageGen implements IBaseManageGen
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
            case "async_modify_showing_state":
                $result = self::AsyncModifyShowingState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 生成资讯管理列表页面
     */
    private function GenList()
    {
        $resultJavaScript="";
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
        $canModify = $manageUserAuthorityManageData->CanChannelModify($siteId, $channelId, $manageUserId);
        if (!$canModify) {
            die(Language::Load('channel', 4));
        }

        $documentNewsId=Control::GetRequest("document_news_id",0);
        $pageIndex=Control::GetRequest("p",1);
        $tabIndex=Control::GetRequest("tab_index",0);
        if($documentNewsId>0){
            //load template
            $templateContent = Template::Load("document/document_news_pic_list.html", "common");
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            $templateContent = str_ireplace("{DocumentNewsId}", $documentNewsId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            $templateContent = str_ireplace("{PageIndex}", $pageIndex, $templateContent);

            parent::ReplaceFirst($templateContent);

            //document news pic
            $documentNewsPicManageData = new DocumentNewsPicManageData();
            $tagId = "document_news_pic";
            $arrPicList = $documentNewsPicManageData->GetList($documentNewsId);
            if (count($arrPicList) > 0) {
                Template::ReplaceList($templateContent, $arrPicList, $tagId);
            } else {
                Template::ReplaceCustomTag($templateContent, $tagId, Language::Load('document', 37));//此文档没有图片
            }
            if(!empty($_POST)){
                //内容图片处理(DocumentNewsPic)
                $documentNewsPicManageData=new DocumentNewsPicManageData();
                //处理修改
                $strModifyPicList=$_POST["modify_pic_list"];  // 格式: 内容图ID1_组图显示1,内容图ID2_组图显示2....
                $arrModifyPicList=explode(",",$strModifyPicList);
                foreach($arrModifyPicList as $strModifyPic){
                    $picDocumentNewsPicId=substr($strModifyPic, 0,strpos($strModifyPic, "_"));
                    $picShowInPicSlider=substr($strModifyPic, strpos($strModifyPic, "_")+1);
                    $documentNewsPicManageData->ChangeShowingState($picDocumentNewsPicId,$picShowInPicSlider);
                }
                //处理删除
                $strDeletePicIdList=$_POST["delete_pic_list"];  // 格式: 内容图ID1_组图显示1,内容图ID2_组图显示2....
                $arrDeletePicIdList=explode(",",$strDeletePicIdList);
                foreach($arrDeletePicIdList as $strDeletePicId){
                    $documentNewsPicManageData->Delete($strDeletePicId);
                }


                //加入操作日志
                $operateContent = 'Modify DocumentNewsPic,POST FORM:' . implode('|', $_POST) . ';\r\n';
                self::CreateManageUserLog($operateContent);

                //javascript 处理

                $closeTab = Control::PostRequest("CloseTab", 1);//默认跳到列表页
                if ($closeTab == 1) {
                    $resultJavaScript .= Control::GetCloseTab();
                    //Control::GoUrl("/default.php?secu=manage&mod=document_news&m=list&channel_id=$channelId&tab_index=$tabIndex&p=$pageIndex");
                } else {
                    Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                }
            }


        }
        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }


    /**
     * 异步修改图片状态:是否显示在组图控件中
     */
    private function AsyncModifyShowingState(){
        $result = -1;
        $documentNewsPicId = Control::GetRequest("document_news_pic_id", 0);
        $state = Control::GetRequest("state", 0);
        $documentNewsPicManageData= new DocumentNewsPicManageData();

        if($documentNewsPicId>0){
            ///////////////判断是否有操作权限///////////////////
            $manageUserId=Control::GetManageUserId();
            $documentNewsManageData = new DocumentNewsManageData();
            $channelManageData=new ChannelManageData();
            $documentNewsId=$documentNewsPicManageData->GetDocumentNewsId($documentNewsPicId);
            $channelId=$documentNewsManageData->GetChannelId($documentNewsId,true);
            $siteId=$channelManageData->GetSiteId($channelId,true);
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $canModify = $manageUserAuthorityManageData->CanChannelModify($siteId, $channelId, $manageUserId);
            if (!$canModify) {
                die(Language::Load('channel', 4));
            }

            $result=$documentNewsPicManageData->ChangeShowingState($documentNewsPicId,$state);

            //加入操作日志
            $operateContent = 'Modify DocumentNewsPic,Get PARAM:' . implode('|', $_GET) . ';\r\n';
            self::CreateManageUserLog($operateContent);


        }


        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';


    }

}

?>
