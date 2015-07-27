<?php
/**
 * 前台 会员第三方插件 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserPluginsPublicData extends BasePublicData {

    public function Create(
        $userId,
        $wxOpenId,
        $siteId
    ){
        $result = -1;
        if (
            strlen($wxOpenId)>0 &&
            $userId>0 &&
            $siteId > 0
        ) {
            $sql = "INSERT INTO " . self::TableName_UserPlugins ."

                    (
                    UserId,
                    WxOpenId,
                    SiteId
                    )
                    VALUES
                    (
                    :UserId,
                    :WxOpenId,
                    :SiteId
                    );
            ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("WxOpenId",$wxOpenId);
            $dataProperty->AddField("SiteId",$siteId);


            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);

        }
        return $result;
    }

    public function Modify(
        $userId,
        $wxNickName,
        $wxSex,
        $wxProvince,
        $wxCity,
        $wxCountry,
        $wxHeadImgUrl,
        $wxUnionId,
        $wxSubscribe,
        $wxSubscribeTime,
        $wxRemark,
        $wxGroupId

    )
    {

        $sql = "UPDATE ".self::TableName_UserPlugins."
	            SET

	                WxNickName = :WxNickName ,
	                WxSex = :WxSex ,
	                WxProvince = :WxProvince ,
	                WxCity = :WxCity ,
	                WxCountry = :WxCountry ,
	                WxHeadImgUrl = :WxHeadImgUrl ,
	                WxUnionId = :WxUnionId ,
	                WxSubscribe = :WxSubscribe ,
	                WxSubscribeTime = :WxSubscribeTime ,
	                WxRemark = :WxRemark ,
	                WxGroupId = :WxGroupId

	            WHERE
	                UserId = :UserId ;";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("WxNickName",$wxNickName);
        $dataProperty->AddField("WxSex",$wxSex);
        $dataProperty->AddField("WxProvince",$wxProvince);
        $dataProperty->AddField("WxCity",$wxCity);
        $dataProperty->AddField("WxCountry",$wxCountry);
        $dataProperty->AddField("WxHeadImgUrl",$wxHeadImgUrl);
        $dataProperty->AddField("WxUnionId",$wxUnionId);
        $dataProperty->AddField("WxSubscribe",$wxSubscribe);
        $dataProperty->AddField("WxSubscribeTime",$wxSubscribeTime);
        $dataProperty->AddField("WxRemark",$wxRemark);
        $dataProperty->AddField("WxGroupId",$wxGroupId);
        $dataProperty->AddField("UserId",$userId);
        return $this->dbOperator->Execute($sql,$dataProperty);

    }


    public function GetOne($userId)
    {

    }

    public function GetUserId($wxOpenId, $withCache)
    {
        $result = -1;
        if (strlen($wxOpenId)>0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_plugins_data'
                . DIRECTORY_SEPARATOR .$wxOpenId;
            $cacheFile = 'user_plugins_get_user_id.cache_' . $wxOpenId . '';
            $sql = "SELECT UserId FROM " . self::TableName_UserPlugins . " WHERE WxOpenId=:WxOpenId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("WxOpenId", $wxOpenId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    public function GetWxOpenId($userId, $withCache)
    {
        $result = "";
        if ($userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_plugins_data'
                . DIRECTORY_SEPARATOR .$userId;
            $cacheFile = 'user_plugins_get_user_wx_open_id.cache_' . $userId . '';
            $sql = "SELECT WxOpenId FROM " . self::TableName_UserPlugins . " WHERE UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    public function GetWxSubscribe($userId, $withCache)
    {
        $result = 0;
        if ($userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_plugins_data'
                . DIRECTORY_SEPARATOR .$userId;
            $cacheFile = 'user_plugins_get_user_wx_subscribe.cache_' . $userId . '';
            $sql = "SELECT WxSubscribe FROM " . self::TableName_UserPlugins . " WHERE UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }




} 