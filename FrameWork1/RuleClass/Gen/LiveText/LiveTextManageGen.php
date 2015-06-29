<?php
/**
 * 后台管理 在线直播 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_PicSlider
 * @author zhangchi
 */
class LiveTextManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_sort":
                $result = self::AsyncModifySort();
                break;
            case "async_modify_sort_by_drag":
                $result = self::AsyncModifySortByDrag();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }
} 