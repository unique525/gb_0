<?php

/**
 * 客户端 论坛帖子 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumPostClientGen extends BaseClientGen implements IBaseClientGen
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

            case "list_of_forum_topic":
                $result = self::GenListOfForumTopic();
                break;

            case "create":
                $result = self::GenCreate();
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

            //验证数据
            $siteId = intval(Control::PostOrGetRequest("SiteId", ""));
            $forumTopicId = intval(Control::PostOrGetRequest("ForumTopicId", "0"));
            $forumPostContent = Control::PostOrGetRequest("ForumPostContent", "", false);

            if (
                $forumTopicId > 0
                && $siteId > 0
                && $userId > 0
                && strlen($forumPostContent) > 0
            ) {

                $forumId = intval(Control::PostOrGetRequest("ForumId", "0"));

                $isTopic = intval(Control::PostOrGetRequest("IsTopic", "0"));
                $userClientData = new UserClientData();
                $userName = $userClientData->GetUserName($userId, true);
                $forumPostTitle = Control::PostOrGetRequest("ForumPostTitle", "", false);
                $postTime = strval(date('Y-m-d H:i:s', time()));
                $forumTopicAudit = intval(Control::PostOrGetRequest("ForumTopicAudit", "0"));
                $forumTopicAccess = intval(Control::PostOrGetRequest("ForumTopicAccess", "0"));
                $accessLimitNumber = intval(Control::PostOrGetRequest("AccessLimitNumber", "0"));
                $accessLimitContent = Control::PostOrGetRequest("AccessLimitContent", "", false);
                $showSign = intval(Control::PostOrGetRequest("ShowSign", "0"));
                $postIp = Control::GetIp();
                $isOneSale = intval(Control::PostOrGetRequest("IsOneSale", "0"));
                $addMoney = intval(Control::PostOrGetRequest("AddMoney", "0"));
                $addScore = intval(Control::PostOrGetRequest("AddScore", "0"));
                $addCharm = intval(Control::PostOrGetRequest("AddCharm", "0"));
                $addExp = intval(Control::PostOrGetRequest("AddExp", "0"));
                $showBoughtUser = intval(Control::PostOrGetRequest("ShowBoughtUser", "0"));
                $sort = 0;
                $state = 0;
                $uploadFiles = Control::PostOrGetRequest("UploadFiles", "", false);


                $forumPostClientData = new ForumPostClientData();

                $forumPostId = $forumPostClientData->Create(
                    $siteId,
                    $forumId,
                    $forumTopicId,
                    $isTopic,
                    $userId,
                    $userName,
                    $forumPostTitle,
                    $forumPostContent,
                    $postTime,
                    $forumTopicAudit,
                    $forumTopicAccess,
                    $accessLimitNumber,
                    $accessLimitContent,
                    $showSign,
                    $postIp,
                    $isOneSale,
                    $addMoney,
                    $addScore,
                    $addCharm,
                    $addExp,
                    $showBoughtUser,
                    $sort,
                    $state,
                    $uploadFiles
                );

                if ($forumPostId > 0) {
                    $result = Format::FixJsonEncode($forumPostClientData->GetOne($forumPostId));

                    $resultCode = 1;

                } else {
                    //编号出错
                    $resultCode = -4;
                }
            } else {
                $resultCode = -5;
                //出错，返回
            }

        }
        return '{"result_code":"' . $resultCode . '","forum_post_create":' . $result . '}';
    }


    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfForumTopic()
    {

        $result = "[{}]";

        $forumId = intval(Control::GetRequest("forum_topic_id", 0));

        if ($forumId > 0) {
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $forumPostClientData = new ForumPostClientData();
            $arrList = $forumPostClientData->GetList(
                $forumId,
                $pageBegin,
                $pageSize
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            } else {
                $resultCode = -2;
            }
        } else {
            $resultCode = -1;
        }

        return '{"result_code":"' . $resultCode . '","forum_post":{"forum_post_list":' . $result . '}}';
    }
} 