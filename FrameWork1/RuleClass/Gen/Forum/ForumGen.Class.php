<?php

/**
 * 前台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumGen extends BaseFrontGen implements IBaseFrontGen {
    
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";
        
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "login":
                $result = self::Login();
                break;
            case "logout":
                self::Logout();
                break;
            default:
                $result = self::GenDefault();
                break;
        }
        
        return $result;
    }
    
    /**
     * 生成论坛首页
     * @return string
     */
    private function GenDefault(){
        $templateFileUrl = "forum/forum_default.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl,$templateName,$templatePath);
        
        parent::ReplaceFirstForForum($tempContent);
        
        $templateForumRecTopicFileUrl = "forum/forum_rec_1.html";
        $templateForumRecTopic = Template::Load($templateForumRecTopicFileUrl,$templateName,$templatePath);
        
        $tempContent = str_ireplace("{forum_rec_1}", $templateForumRecTopic, $tempContent);
        return $tempContent;
    }
}

?>
