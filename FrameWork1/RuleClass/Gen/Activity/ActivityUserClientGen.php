<?php
/**
 * 客户端 活动会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author zhangchi
 */
class ActivityUserClientGen extends BaseClientGen implements IBaseClientGen  {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list":
                $result = self::GenList();
                break;

            case "create":
                $result = self::GenCreate();
                break;
        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate()
    {
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            $siteId = Control::PostOrGetRequest("site_id", 0);
            $activityId = Control::PostOrGetRequest("activity_id", 0);
            if ($activityId > 0 && $siteId > 0) {

                //检查是否已经参加
                $activityUserClientData = new ActivityUserClientData();
                $hasCount = $activityUserClientData->IsRepeat($userId, $siteId);
                if ($hasCount > 0) {
                    $resultCode = -10; //已经参加
                } else {

                    $result = $activityUserClientData->Create($userId, $activityId);

                    if ($result > 0) {

                        $resultCode = 1; //加入成功
                    } else {
                        $resultCode = -5; //加入失败,数据库原因
                    }
                }
            } else {
                $resultCode = -15; //加入失败,参数错误;
            }

        }
        return '{"result_code":"' . $resultCode . '","activity_user_create":' . $result . '}';
    }

    private function GenList()
    {
        $result = "[{}]";
        $activityId = Control::PostOrGetRequest("activity_id", 0);

        if ($activityId <= 0) {
            $resultCode = -1;
        } else {

            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $pageBegin = ($pageIndex - 1) * $pageSize;

            $activityUserClientData = new ActivityUserClientData();


            $arrList = $activityUserClientData->GetListByActivityId(
                $activityId, $pageBegin, $pageSize);

            if (count($arrList) > 0) {
                $resultCode = 1;

                $result = Format::FixJsonEncode($arrList);

            } else {
                $resultCode = -1;
            }

        }
        return '{"result_code":"' . $resultCode . '","activity_user":{"activity_user_list":' . $result . '}}';
    }
} 