<?php

/**
 * 前台Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DefaultFrontGen extends BaseFrontGen implements IBaseFrontGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";
        $module = Control::GetRequest("mod", "");
        switch ($module) {
            case "manage":                
                $manageGen = new ManageGen();
                $result = $manageGen->GenFront();
                break;
            case "common":
                $commonFrontGen = new CommonFrontGen();
                $result = $commonFrontGen->GenFront();
                break;
            case "forum":
                $forumGen = new ForumGen();
                $result = $forumGen->GenFront();
                break;
            case "forumtopic":
                $forumTopicGen = new ForumTopicGen();
                $result = $forumTopicGen->GenFront();
                break;
        }
        return $result;
    }

}

?>
