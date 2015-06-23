<?php
/**
 * 客户端 论坛帖子 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumPostClientGen extends BaseClientGen implements IBaseClientGen {

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

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfForumTopic(){

        $result = "[{}]";

        $forumId = intval(Control::GetRequest("forum_topic_id", 0));

        if($forumId>0){
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
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","forum_post":{"forum_post_list":' . $result . '}}';
    }
} 