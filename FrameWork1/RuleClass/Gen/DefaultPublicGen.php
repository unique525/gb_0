<?php

/**
 * 前台Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DefaultPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $module = Control::GetRequest("mod", "");
        switch ($module) {
            case "manage":                
                $manageLoginGen = new ManageLoginPublicGen();
                $result = $manageLoginGen->GenPublic();
                break;
            case "common":
                $commonFrontGen = new CommonPublicGen();
                $result = $commonFrontGen->GenPublic();
                break;
            case "forum":
                $forumGen = new ForumPublicGen();
                $result = $forumGen->GenPublic();
                break;
            case "forumtopic":
                $forumTopicGen = new ForumTopicPublicGen();
                $result = $forumTopicGen->GenPublic();
                break;
        }
        return $result;
    }

}

?>
