<?php
/**
 * 客户端 论坛主题 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumTopicClientGen extends BaseClientGen implements IBaseClientGen {

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

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 返回列表数据集
     * @return string
     */
    public function GenListOfForum(){

        $result = "[{}]";

        $forumId = intval(Control::GetRequest("forum_id", 0));

        if($forumId>0){
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
            }
            else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","forum_topic":{"forum_topic_list":' . $result . '}}';
    }
} 