<?php
/**
 * 客户端 会员订单电子报 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserOrderNewspaperClientData extends BaseClientData {

    /**
     * @param $userOrderId
     * @param $siteId
     * @param $channelId
     * @param $userId
     * @param $newspaperId
     * @param $newspaperArticleId
     * @param $salePrice
     * @param $beginDate
     * @param $endDate
     * @return int
     */
    public function Create(
        $userOrderId,
        $siteId,
        $channelId,
        $userId,
        $newspaperId,
        $newspaperArticleId,
        $salePrice,
        $beginDate,
        $endDate
    ){
        $result = -1;
        if(
            $userOrderId>0
        ){

            $sql = "INSERT INTO ".self::TableName_UserOrderNewspaper."
                    (
                        UserOrderId,
                        SiteId,
                        ChannelId,
                        UserId,
                        NewspaperId,
                        NewspaperArticleId,
                        SalePrice,
                        CreateDate,
                        BeginDate,
                        EndDate
                    )
                    VALUES
                    (
                        :UserOrderId,
                        :SiteId,
                        :ChannelId,
                        :UserId,
                        :NewspaperId,
                        :NewspaperArticleId,
                        :SalePrice,
                        now(),
                        :BeginDate,
                        :EndDate
                    );

            ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("ChannelId",$channelId);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("NewspaperId",$newspaperId);
            $dataProperty->AddField("NewspaperArticleId",$newspaperArticleId);
            $dataProperty->AddField("SalePrice",$salePrice);
            $dataProperty->AddField("BeginDate",$beginDate);
            $dataProperty->AddField("EndDate",$endDate);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);

        }


        return $result;
    }

    /**
     * 获取一个会员订单电子报的详细信息
     * @param int $userOrderNewspaperId 订单电子报Id
     * @return array|null 会员订单电子报的数组
     */
    public function GetOne($userOrderNewspaperId){
        $result = null;
        if($userOrderNewspaperId > 0){
            $sql = "SELECT *
                    FROM " .self::TableName_UserOrderNewspaper."
                    WHERE UserOrderNewspaperId = :UserOrderNewspaperId; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderNewspaperId",$userOrderNewspaperId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 检查用户是否购买了当前报纸
     * @param int $userId 用户id
     * @param int $channelId 报纸频道id
     * @param String $publishDate 报纸出版日期
     * @return bool
     */
    public function CheckIsBoughtInTimeByChannelId($userId,$channelId,$publishDate){
        $result = -1;
        if($userId > 0 && $channelId > 0){
            $sql = "SELECT
                        count(*)
                    FROM ".self::TableName_UserOrderNewspaper." uon,
                         ".self::TableName_UserOrder." uo
                    WHERE
                        uo.UserId = :UserId
                        AND uon.UserId = uo.UserId
                        AND uon.UserOrderId = uo.UserOrderId
                        AND uon.ChannelId = :ChannelId
                        AND uon.EndDate>=:NowTime

                        AND (      uo.State = ".UserOrderData::STATE_DONE."
                                OR uo.State = ".UserOrderData::STATE_PAYMENT." )

                        ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ChannelId",$channelId);
            $dataProperty->AddField("NowTime",$publishDate);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }
} 