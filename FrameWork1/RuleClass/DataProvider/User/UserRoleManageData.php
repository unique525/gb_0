<?php
/**
 * 后台管理 会员角色 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserRoleManageData extends BaseManageData {

    /**
     * 会员角色列表
     * @param int $siteId
     * @param int $pageBegin
     * @param int $pageSize
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @return array
     */
    public function GetList($siteId, $pageBegin, $pageSize, $searchKey = "", $searchType = 0) {
        $dataProperty = new DataProperty();
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 1) { //会员名
                $searchSql = " AND (".self::TableName_User.".UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }elseif ($searchType == 2) { //IP
                $searchSql = " AND (".self::TableName_User.".RegIp like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }elseif ($searchType == 3) { //手机号码
                $searchSql = " AND (".self::TableName_User.".UserMobile like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        $fieldOfList = "".self::TableName_User.".UserId,
                    ".self::TableName_User.".UserName,
                    ".self::TableName_User.".UserMobile,
                    ".self::TableName_User.".UserEmail,
                    ".self::TableName_User.".CreateDate,
                    ".self::TableName_User.".State,
                    ".self::TableName_UserInfo.".NickName,
                    ".self::TableName_UserInfo.".RealName,
                    ".self::TableName_UserGroup.".UserGroupName,
                    ".self::TableName_UserRole.".State as RoleState
        ";

        $sql = "SELECT

                    $fieldOfList

                    FROM ".self::TableName_User."
                INNER JOIN ".self::TableName_UserInfo."
                    ON (".self::TableName_User.".UserId = ".self::TableName_UserInfo.".UserId)
                INNER JOIN ".self::TableName_UserRole."
                    ON (".self::TableName_User.".UserId = ".self::TableName_UserRole.".UserId)
                INNER JOIN ".self::TableName_UserGroup."
                    ON (".self::TableName_UserRole.".UserGroupId = ".self::TableName_UserGroup.".UserGroupId)
                    AND ".self::TableName_UserRole.".SiteId = :SiteId ";


        $dataProperty->AddField("SiteId", $siteId);

        $sql .=" $searchSql ORDER BY ".self::TableName_UserRole.".State DESC
                    ,".self::TableName_User.".CreateDate DESC LIMIT "
            . $pageBegin . "," . $pageSize . "";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }

    public function GetCount($siteId, $searchKey = "", $searchType = 0){

        $dataProperty = new DataProperty();

        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 1) { //会员名
                $searchSql = " AND (".self::TableName_User.".UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }elseif ($searchType == 2) { //IP
                $searchSql = " AND (".self::TableName_User.".RegIp like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }elseif ($searchType == 3) { //手机号码
                $searchSql = " AND (".self::TableName_User.".UserMobile like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        $fieldOfList = " count(".self::TableName_User.".UserId)";

        $sql = "SELECT

                    $fieldOfList

                    FROM ".self::TableName_User."
                INNER JOIN ".self::TableName_UserInfo."
                    ON (".self::TableName_User.".UserId = ".self::TableName_UserInfo.".UserId)
                INNER JOIN ".self::TableName_UserRole."
                    ON (".self::TableName_User.".UserId = ".self::TableName_UserRole.".UserId)
                INNER JOIN ".self::TableName_UserGroup."
                    ON (".self::TableName_UserRole.".UserGroupId = ".self::TableName_UserGroup.".UserGroupId)
                    AND ".self::TableName_UserRole.".SiteId = :SiteId $searchSql";


        $dataProperty->AddField("SiteId", $siteId);



        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }


    public function GetUserGroupId($userId,$siteId = 0,$channelId = 0){
        if ($siteId <= 0 && $channelId <= 0) {
            return null;
        } elseif ($channelId <= 0 && $siteId > 0) {
            $sql = "SELECT UserGroupId from ".self::TableName_UserRole." WHERE UserId=:UserId AND SiteId=:SiteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            return $this->dbOperator->GetInt($sql, $dataProperty);
        } elseif ($channelId > 0) {
            $sql = "SELECT UserGroupId from ".self::TableName_UserRole." WHERE UserId=:UserId AND ChannelId=:ChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("ChannelId", $channelId);
            return $this->dbOperator->GetInt($sql, $dataProperty);
        }else{
            return null;
        }
    }

    /**
     * 增加或修改会员到角色表中
     * @param int $userId 会员ID
     * @param int $userGroupId　会员组ID
     * @param int $siteId　站点ID
     * @return int 返回影响的行数
     */
    public function CreateOrModify($userId, $userGroupId, $siteId) {
        $sql = "SELECT Count(*) From ".self::TableName_UserRole." WHERE UserID=:UserID AND SiteID=:SiteID;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteID", $siteId);
        $dataProperty->AddField("UserID", $userId);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);
        if ($hasCount > 0) { //modify
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $sql = "UPDATE ".self::TableName_UserRole." SET UserGroupId=:UserGroupId WHERE UserID=:UserID AND SiteID=:SiteID;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else { //INSERT
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $sql = "INSERT INTO ".self::TableName_UserRole." (UserGroupId,UserID,SiteID) VALUES (:UserGroupId,:UserID,:SiteID);";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


}