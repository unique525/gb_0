<?php
/**
 * 客户端 会员收藏 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserFavoriteClientGen extends BaseClientGen implements IBaseClientGen
{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "create":
                $result = self::GenCreate();
                break;

            case "delete":
                $result = self::GenDelete();
                break;

            case "list":
                $result = self::GenList();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate()
    {
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            $tableId = intval(Control::PostOrGetRequest("TableId",0));
            $tableType = intval(Control::PostOrGetRequest("TableType",0));
            $siteId = intval(Control::PostOrGetRequest("site_id",0));
            $userFavoriteTag = Control::PostOrGetRequest("UserFavoriteTag","");

            if(
                $tableId > 0
                && $tableType > 0
                && $siteId > 0
            ){
                $userFavoriteClientData = new UserFavoriteClientData();
                $userFavoriteTitle = "";
                $uploadFileId = 0;
                $userFavoriteUrl = "";

                //判断是否重复
                $canAddFavorite = $userFavoriteClientData->CheckIsExist($tableId,$tableType);
                if($canAddFavorite > 0){
                    $resultCode = -12; //重复
                }else{
                    if($tableType == UserFavoriteData::TABLE_TYPE_PRODUCT){
                        $userFavoriteTitle = Control::PostOrGetRequest("UserFavoriteTitle","");
                        $productClientData = new ProductClientData();
                        $arrProductOne =$productClientData->GetOne($tableId);
                        if (count($arrProductOne)>0){
                            if($userFavoriteTitle == ""){
                                $userFavoriteTitle = $arrProductOne["ProductName"];
                            }
                            $channelId = $arrProductOne["ChannelId"];
                            $uploadFileId = $arrProductOne["TitlePic1UploadFileId"];
                            $userFavoriteUrl = "/default.php?&mod=product&a=detail&channel_id=".$channelId."&product_id=".$tableId;
                        }
                    }elseif($tableType == UserFavoriteData::TABLE_TYPE_DOCUMENT_NEWS){
                        $userFavoriteTitle = Control::PostOrGetRequest("UserFavoriteTitle","");
                        $documentNewsClientData = new DocumentNewsClientData();
                        $arrOne =$documentNewsClientData->GetOne($tableId);
                        if (count($arrOne)>0){
                            if($userFavoriteTitle == ""){
                                $userFavoriteTitle = $arrOne["DocumentNewsTitle"];
                            }
                            $channelId = $arrOne["ChannelId"];
                            $uploadFileId = $arrOne["TitlePic1UploadFileId"];
                            $userFavoriteUrl = "/default.php?&mod=document_news&a=detail&channel_id=".$channelId."&document_news_id=".$tableId;
                        }
                    }elseif($tableType == UserFavoriteData::TABLE_TYPE_NEWSPAPER_ARTICLE){
                        $userFavoriteTitle = Control::PostOrGetRequest("UserFavoriteTitle","");
                        $newspaperArticleClientData = new NewspaperArticleClientData();
                        $arrOne =$newspaperArticleClientData->GetOne($tableId);
                        if (count($arrOne)>0){
                            if($userFavoriteTitle == ""){
                                $userFavoriteTitle = $arrOne["NewspaperArticleTitle"];
                            }

                            $uploadFileId = intval(Control::PostOrGetRequest("UserFavoriteUploadFileId",0));
                            //$uploadFileId = 0;//$arrOne["TitlePic1UploadFileId"];
                            $userFavoriteUrl = "/default.php?&mod=newspaper_article&a=detail&newspaper_article_id=".$tableId;
                        }
                    }elseif($tableType == UserFavoriteData::TABLE_TYPE_ACTIVITY){
                        $userFavoriteTitle = Control::PostOrGetRequest("UserFavoriteTitle","");
                        $activityClientData = new ActivityClientData();
                        $arrOne =$activityClientData->GetOne($tableId);
                        if (count($arrOne)>0){
                            if($userFavoriteTitle == ""){
                                $userFavoriteTitle = $arrOne["ActivityTitle"];
                            }

                            $channelId = $arrOne["ChannelId"];
                            $uploadFileId = $arrOne["TitlePic1UploadFileId"];
                            $userFavoriteUrl = "/default.php?&mod=activity&a=detail&channel_id=".$channelId."&activity_id=".$tableId;
                        }
                    }elseif($tableType == UserFavoriteData::TABLE_TYPE_FORUM_TOPIC){
                        $userFavoriteTitle = Control::PostOrGetRequest("UserFavoriteTitle","");
                        $forumTopicClientData = new ForumTopicClientData();
                        $forumTopicTitle =$forumTopicClientData->GetForumTopicTitle($tableId, true);
                        if (strlen($forumTopicTitle)>0){
                            if($userFavoriteTitle == ""){
                                $userFavoriteTitle = $forumTopicTitle;
                            }

                            $uploadFileId = 0;//$arrOne["TitlePic1UploadFileId"];
                            $userFavoriteUrl = "/default.php?mod=forum_post&a=list&forum_topic_id=".$tableId;
                        }
                    }

                    if($userFavoriteTitle != ""){
                        $userFavoriteClientData = new UserFavoriteClientData();
                        $newUserFavoriteId = $userFavoriteClientData->Create($userId,$tableId,$tableType,$siteId,$userFavoriteTitle,$userFavoriteUrl,$uploadFileId,$userFavoriteTag);
                        if($newUserFavoriteId > 0){
                            $resultCode = 1;

                            $arrOne = $userFavoriteClientData->GetOne($userId, $newUserFavoriteId, false);

                            $result = Format::FixJsonEncode($arrOne);


                        }else{
                            $resultCode = -5; //数据库操作错误;
                        }
                    }else{
                        $resultCode = -4; //对应表数据错误;
                    }
                }

            }else{
                $resultCode = -6; //参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_favorite":' . $result . '}';
    }

    private function GenDelete()
    {
        $result = "[{}]";
        $userId = parent::GetUserId();
        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $userFavoriteId = Control::PostOrGetRequest("UserFavoriteId", 0);
            if ($userFavoriteId > 0) {
                $userFavoriteClientData = new UserFavoriteClientData();
                $result = $userFavoriteClientData->Delete($userFavoriteId, $userId);
                if ($result > 0) {
                    $resultCode = 1; //删除成功
                } else {
                    $resultCode = -5; //删除失败,数据库原因
                }

            } else {
                $resultCode = -6; //加入失败,参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_favorite_delete":' . $result . '}';
    }

    private function GenList()
    {

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId;
        } else {
            $siteId = intval(Control::GetRequest("site_id", 0));
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $userFavoriteClientData = new UserFavoriteClientData();

            $arrList = $userFavoriteClientData->GetList($userId, $siteId, $pageBegin, $pageSize);

            if (count($arrList) > 0) {
                $resultCode = 1;


                $result = Format::FixJsonEncode($arrList);

            } else {
                $resultCode = -1;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_favorite":{"user_favorite_list":' . $result . '}}';

    }

} 