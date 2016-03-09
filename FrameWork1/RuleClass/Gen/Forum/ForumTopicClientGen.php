<?php

/**
 * 客户端 论坛主题 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumTopicClientGen extends BaseClientGen implements IBaseClientGen
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

            case "list_of_forum":
                $result = self::GenListOfForum();
                break;

            case "list_of_user":
                $result = self::GenListOfUser();
                break;
            case "list_of_user_post_forum"://根据参与发帖的用户进行搜索
                $result = self::GenListOfUserPostForum();
                break;

            case "create":
                $result = self::GenCreate();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfForum()
    {

        $result = "[{}]";

        $forumId = intval(Control::GetRequest("forum_id", 0));

        if ($forumId > 0) {
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $forumTopicClientData = new ForumTopicClientData();
            $arrList = $forumTopicClientData->GetList(
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

        return '{"result_code":"' . $resultCode . '","forum_topic":{"forum_topic_list":' . $result . '}}';
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfUserPostForum()
    {

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            if ($userId > 0) {
                $pageSize = intval(Control::GetRequest("ps", 20));
                $pageIndex = intval(Control::GetRequest("p", 1));

                $pageBegin = ($pageIndex - 1) * $pageSize;
                $forumTopicClientData = new ForumTopicClientData();
                $arrList = $forumTopicClientData->GetListOfUserPostForum(
                    $userId,
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
        }

        return '{"result_code":"' . $resultCode . '","forum_topic":{"forum_topic_list":' . $result . '}}';
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfUser()
    {

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            if ($userId > 0) {
                $pageSize = intval(Control::GetRequest("ps", 20));
                $pageIndex = intval(Control::GetRequest("p", 1));

                $pageBegin = ($pageIndex - 1) * $pageSize;
                $forumTopicClientData = new ForumTopicClientData();
                $arrList = $forumTopicClientData->GetListOfUser(
                    $userId,
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


        }

        return '{"result_code":"' . $resultCode . '","forum_topic":{"forum_topic_list":' . $result . '}}';
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
            $forumId = intval(Control::PostOrGetRequest("ForumId", ""));
            $forumTopicTitle = Control::PostOrGetRequest("ForumTopicTitle", "", false);

            if (
                $forumId > 0
                && $siteId > 0
                && $userId > 0
                && strlen($forumTopicTitle) > 0
            ) {

                $forumTopicTypeId = intval(Control::PostOrGetRequest("ForumTopicTypeId", "0"));
                $forumTopicTypeName = Control::PostOrGetRequest("ForumTopicTypeName", "");
                $forumTopicAudit = intval(Control::PostOrGetRequest("ForumTopicAudit", "0"));
                $forumTopicAccess = intval(Control::PostOrGetRequest("ForumTopicAccess", "0"));
                $postTime = strval(date('Y-m-d H:i:s', time()));
                $userClientData = new UserClientData();
                $userName = $userClientData->GetUserName($userId, true);
                $forumTopicMood = intval(Control::PostOrGetRequest("ForumTopicMood", "0"));
                $forumTopicAttach = intval(Control::PostOrGetRequest("ForumTopicAttach", "0"));
                $titleBold = intval(Control::PostOrGetRequest("TitleBold", "0"));
                $titleColor = intval(Control::PostOrGetRequest("TitleColor", "0"));
                $titleBgImage = intval(Control::PostOrGetRequest("TitleBgImage", "0"));


                $forumTopicClientData = new ForumTopicClientData();

                $forumTopicId = $forumTopicClientData->Create(
                    $siteId,
                    $forumId,
                    $forumTopicTitle,
                    $forumTopicTypeId,
                    $forumTopicTypeName,
                    $forumTopicAudit,
                    $forumTopicAccess,
                    $postTime,
                    $userId,
                    $userName,
                    $forumTopicMood,
                    $forumTopicAttach,
                    $titleBold,
                    $titleColor,
                    $titleBgImage
                );

                if ($forumTopicId > 0) {
                    $result = Format::FixJsonEncode($forumTopicClientData->GetOne($forumTopicId));

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
        return '{"result_code":"' . $resultCode . '","forum_topic_create":' . $result . '}';
    }
} 