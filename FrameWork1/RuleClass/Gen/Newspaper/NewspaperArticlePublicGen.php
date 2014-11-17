<?php
/**
 * 公开访问 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicGen extends BasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "async_get_list":
                $result = self::AsyncGetList();
                break;

        }
        return $result;
    }
} 