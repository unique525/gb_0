<?php

/**
 * 前台 在线直播 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Vote
 * @author yanjiuyuan
 */
class LiveTextPublicGen extends BasePublicGen implements IBasePublicGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            default:
                break;
        }

        return $result;
    }
} 