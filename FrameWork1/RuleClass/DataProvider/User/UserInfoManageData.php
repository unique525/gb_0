<?php
/**
 * 后台管理 会员信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */

class UserInfoManageData extends BaseManageData {

    public function GetFields($tableName = self::TableName_User){
        return parent::GetFields(self::TableName_User);
    }

    public function Create($userId){
        $result = -1;
        if($userId > 0){
            $sql = "INSERT INTO ".self::TableName_UserInfo." (UserId) VALUES (:UserId);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 新增用户信息
     * @param int $userId 用户Id
     * @param int $realName 真实姓名
     * @return int 运行结果
     */
    public function CreateForOfflineOrder($userId,$realName){
        $result = -1;
        if($userId > 0){
            $sql = "INSERT INTO ".self::TableName_UserInfo." (UserId,RealName) VALUES (:UserId,:RealName);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("RealName",$realName);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    public function Modify($httpPostData,$userId){
        $result = -1;
        if(!empty($httpPostData) && $userId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_UserInfo,self::TableId_UserInfo,$userId,$dataProperty);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个用户的信息
     * @param int $userId 用户Id
     * @param int $siteId 站点Id
     * @return array 一个用户的所有信息
     */
    public function GetOne($userId,$siteId){
        $dataProperty = new DataProperty();
        $sql = "SELECT ".self::TableName_User.".UserName,
            ".self::TableName_User.".UserId,
            ".self::TableName_User.".State,
            ".self::TableName_UserInfo.".NickName,
            ".self::TableName_UserInfo.".RealName,
            ".self::TableName_UploadFile.".UploadFilePath,
            ".self::TableName_UserInfo.".Email,
            ".self::TableName_UserInfo.".QQ,
            ".self::TableName_UserInfo.".IDCard,
            ".self::TableName_UserInfo.".Address,
            ".self::TableName_UserInfo.".Birthday,
            ".self::TableName_UserInfo.".PostCode,
            ".self::TableName_UserInfo.".Mobile,
            ".self::TableName_UserInfo.".Tel,
            ".self::TableName_UserInfo.".UserScore,
            ".self::TableName_UserInfo.".UserMoney,
            ".self::TableName_UserInfo.".UserCharm,
            ".self::TableName_UserInfo.".UserExp,
            ".self::TableName_UserInfo.".UserPoint,
            ".self::TableName_UserInfo.".Question,
            ".self::TableName_UserInfo.".Answer,
            ".self::TableName_UserInfo.".Sign,
            ".self::TableName_UserInfo.".ComeFrom,
            ".self::TableName_UserInfo.".Honor,
            ".self::TableName_UserInfo.".FansCount,
            ".self::TableName_UserInfo.".Gender,
            ".self::TableName_UserInfo.".Occupational,
            ".self::TableName_UserInfo.".Province,
            ".self::TableName_UserInfo.".Country,
            ".self::TableName_UserInfo.".City,
            ".self::TableName_UserInfo.".Hit,
            ".self::TableName_UserInfo.".MessageCount,
            ".self::TableName_UserInfo.".UserPostCount,
            ".self::TableName_UserInfo.".UserPostBestCount,
            ".self::TableName_UserInfo.".UserActivityCount,
            ".self::TableName_UserInfo.".UserAlbumCount,
            ".self::TableName_UserInfo.".UserBestAlbumCount,
            ".self::TableName_UserInfo.".UserRecAlbumCount,
            ".self::TableName_UserInfo.".UserAlbumCommentCount,
            ".self::TableName_UserInfo.".BankName,
            ".self::TableName_UserInfo.".BankOpenAddress,
            ".self::TableName_UserInfo.".BankUserName,
            ".self::TableName_UserInfo.".BankAccount,
            ".self::TableName_UserLevel.".UserLevelName,
            ".self::TableName_UserLevel.".UserLevelPic,
            ".self::TableName_UserLevel.".UserLevel,
            ".self::TableName_UserRole.".UserGroupID,
            ".self::TableName_UserPlugins.".WxOpenId
            FROM ".self::TableName_User."
            INNER JOIN ".self::TableName_UserInfo." ON (".self::TableName_User.".UserID = ".self::TableName_UserInfo.".UserID)
            LEFT JOIN ".self::TableName_UploadFile." ON ".self::TableName_UserInfo.".AvatarUploadFileId = ".self::TableName_UploadFile.".UploadFileId
            LEFT JOIN ".self::TableName_UserPlugins." ON ".self::TableName_UserInfo.".UserID = ".self::TableName_UserPlugins.".UserID
            LEFT JOIN ".self::TableName_UserSiteLevel." ON (".self::TableName_User.".UserID = ".self::TableName_UserSiteLevel.".UserId) AND ".self::TableName_UserSiteLevel.".SiteId=:SiteId
            LEFT JOIN ".self::TableName_UserRole." ON (".self::TableName_User.".UserID = ".self::TableName_UserRole.".UserID) AND ".self::TableName_UserRole.".SiteID = :SiteId2
            LEFT JOIN ".self::TableName_UserLevel." ON (".self::TableName_UserSiteLevel.".SiteId = ".self::TableName_UserLevel.".SiteID) AND (".self::TableName_UserSiteLevel.".UserLevelId = ".self::TableName_UserLevel.".UserLevelID)
            LEFT JOIN ".self::TableName_ManageUserGroup." ON (".self::TableName_UserRole.".UserGroupID = ".self::TableName_ManageUserGroup.".ManageUserGroupID) AND (".self::TableName_UserRole.".SiteID = ".self::TableName_UserRole.".SiteID)
            WHERE ".self::TableName_User.".UserId=:UserId AND ".self::TableName_User.".State<100;";
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("SiteId2", $siteId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 重计会员相册数
     * @return int 影响行数
     */
    public function ReCountUserAlbumCount() {
        $sql = "UPDATE ".self::TableName_UserInfo." SET UserAlbumCount=(SELECT count(*) FROM ".self::TableName_UserAlbum." WHERE State<40 AND UserId=".self::TableName_UserInfo.".UserId);";
        $result = $this->dbOperator->Execute($sql, null);
        return $result;
    }

    /**
     * 减少一个会员相册数
     * @param int $userId 会员Id
     * @return int 影响行数
     */
    public function MinusUserAlbumCount($userId){
        $sql = "UPDATE " . self::TableName_UserInfo . " SET UserAlbumCount=UserAlbumCount-1 WHERE UserId=:UserId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个会员的真实姓名
     * @param int $userId 会员Id
     * @return string 会员的真实姓名
     */
    public function GetRealName($userId){
        $result = "";
        if($userId > 0){
            $sql = "SELECT RealName FROM ".self::TableName_UserInfo." WHERE UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetString($sql,$dataProperty);
        }
        return $result;
    }

    public function CheckIsExist($userId,$siteId){
        $result = -1;
        if($userId > 0){
            $sql = "SELECT count(*) FROM ".self::TableName_UserInfo." WHERE UserId = :UserId AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }
}