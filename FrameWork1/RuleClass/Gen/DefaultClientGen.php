<?php
/**
 * Client Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DefaultClientGen extends BaseClientGen implements IBaseClientGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient() {
        $module = Control::GetRequest("mod", "");

        $authKey = Control::GetRequest("auth_key","");

        switch ($module) {
            case "pic_slider":
                $picSliderClientGen = new PicSliderClientGen();
                $result = $picSliderClientGen->GenClient();
                break;
            default:
                $result = "";
                break;
        }
        return $result;
    }
} 