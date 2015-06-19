<?php

/**
 * 客户端 论坛 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list_by_rank":
                $result = self::GenListByRank();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenListByRank(){

        $result = "[{}]";

        $siteId = intval(Control::GetRequest("site_id", 0));
        $rank = intval(Control::GetRequest("rank", 0));

        if($siteId>0){

            $forumClientData = new ForumClientData();
            $arrList = $forumClientData->GetListByForumRank(
                $siteId,
                $rank
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

        return '{"result_code":"'.$resultCode.'","forum":{"forum_list":' . $result . '}}';

    }
} 