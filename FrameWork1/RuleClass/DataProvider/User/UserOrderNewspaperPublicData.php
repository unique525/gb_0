<?php
/**
 * 前台 会员订单电子报 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserOrderNewspaperPublicData extends BasePublicData {


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
            && $newspaperId>0
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
     * @param $userId
     * @param $newspaperId
     * @return int
     */
    public function CheckIsBought($userId,$newspaperId){
        $result = -1;
        if($userId > 0 && $newspaperId > 0){
            $sql = "SELECT
                        count(*)
                    FROM ".self::TableName_UserOrderNewspaper." uon,
                         ".self::TableName_UserOrder." uo
                    WHERE
                        uo.UserId = :UserId
                        AND uon.UserId = uo.UserId
                        AND uon.UserOrderId = uo.UserOrderId
                        AND uon.NewspaperId = :NewspaperId

                        AND (      uo.State = ".UserOrderData::STATE_DONE."
                                OR uo.State = ".UserOrderData::STATE_PAYMENT." )

                        ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("NewspaperId",$newspaperId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**检查用户是否购买了当前报纸
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

    /**
     * 获取订单中的电子报列表
     * @param int $userOrderId 会员订单Id
     * @param int $userId 会员Id
     * @param int $siteId 站点Id
     * @return array 数组
     */
    public function GetList($userOrderId,$userId,$siteId)
    {
        $result = null;
        if ($userOrderId > 0 && $userId > 0 && $siteId > 0) {
            $sql = "SELECT
                        uon.*,n.*
                FROM " . self::TableName_UserOrderNewspaper . " uon, "
                . self::TableName_Newspaper . " n,"
                . self::TableName_UserOrder." uo
                WHERE uon.NewspaperId = n.NewspaperId
                AND uon.State < :State
                AND uon.UserOrderId = uo.UserOrderId
                AND uo.UserId = :UserId
                AND uo.UserOrderId = :UserOrderId
                AND uon.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $dataProperty->AddField("State", UserOrderNewspaperData::STATE_REMOVED);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得电子报id
     * @param int $userOrderId 订单编号
     * @param bool $withCache 是否从缓冲中取
     * @return float 电子报id
     */
    public function GetNewspaperId($userOrderId, $withCache){
        $result =-1;
        if($userOrderId>0){

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_newspaper_get_newspaper_id.cache_' . $userOrderId . '';
            $sql = "SELECT NewspaperId FROM " . self::TableName_UserOrderNewspaper . "
                    WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }

        return $result;
    }

    /**
     * 取得电子报文章id
     * @param int $userOrderId 订单编号
     * @param bool $withCache 是否从缓冲中取
     * @return float 电子报id
     */
    public function GetNewspaperArticleId($userOrderId, $withCache){
        $result =-1;
        if($userOrderId>0){

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_newspaper_get_newspaper_article_id.cache_' . $userOrderId . '';
            $sql = "SELECT NewspaperArticleId FROM " . self::TableName_UserOrderNewspaper . "
                    WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }

        return $result;
    }
} 