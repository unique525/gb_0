<?php
/**
 * 后台管理 获奖用户 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotteryAwardUserManageData extends BaseManageData{
    /**
     * 获取抽奖活动分页列表
     * @param int $lotterySetId 奖项id
     * @return array 活动数据集
     */
    public function GetList($lotterySetId) {
        $result=null;
        if($lotterySetId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotterySetId", $lotterySetId);
            $sql = "
                SELECT
                au.*,
                u.UserName,
                u.UserEmail,
                u.UserMobile,
                ui.RealName,
                ui.IdCard
                FROM
                " . self::TableName_LotteryAwardUser . " au
                LEFT OUTER JOIN " .self::TableName_User." u on au.UserId = u.UserId
                LEFT OUTER JOIN " .self::TableName_UserInfo." ui on au.UserId = ui.UserId
                WHERE LotterySetId=:LotterySetId ORDER BY CreateDate DESC;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 