<?php

/**
 * 后台管理 会员相册 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserAlbumManageData extends BaseManageData {

    /**
     * 修改相册
     * @param int $tableIdValue 要修改的相册的ID
     * @return int 影响的行数
     */
    public function Modify($httpPostData,$userAlbumId) {
        if ($userAlbumId > 0) {
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_UserAlbum, self::TableId_UserAlbum, $userAlbumId, $dataProperty);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 修改该相册是否为精华
     * @param int $userAlbumId 相册ID
     * @return int 影响行数
     */
    public function ModifyIsBest($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbum . " SET IsBest = 1 WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 通过相册ID获取用户ID
     * @param int $userAlbumId 相册ID
     * @return int 用户ID
     */
    public function GetUserID($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserId FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetInt($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
    
    /**
     * 通过相册ID获取用户名
     * @param int $userAlbumId 相册ID
     * @return int
     */
    public function GetUserName($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserName FROM ".self::TableName_User." WHERE ".self::TableId_User." = (SELECT ".self::TableId_User." FROM " . self::TableName_UserAlbum . " WHERE ".self::TableId_UserAlbum."=:UserAlbumId);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return 0;
        }
    }
    
    /**
     * 根据UserAlbumId取得UserAlbumIntro
     * @param int $userAlbumId 相册ID
     * @return string 相册简介
     */
    public function GetIntro($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumIntro FROM " . self::TableName_UserAlbum . " WHERE ".self::TableId_UserAlbum."=:UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return "";
        }
    }

    /**
     * 获取一个相册的信息
     * @param int $userAlbumId 相册ID
     * @return array 一个相册的数据集
     */
    public function GetOne($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT ua.*,uap.UserAlbumPicThumbnailUrl,ui.NickName,ui.RealName,u.UserName,ui.UserId FROM " . self::TableName_UserAlbum . " ua 
                LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON uap.UserAlbumId=ua.UserAlbumId AND uap.IsCover = 1," . self::TableName_UserInfo . " ui," . self::TableName_User . " u
                WHERE ua.UserAlbumId = :UserAlbumId AND ua.State <= 100 AND ui.UserId = ua.UserId AND u.UserId=ui.UserId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取站点内的所有相册
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $state 取状态为state的相册
     * @param int $siteId 站点ID
     * @return array 相册列表的数组
     */
    public function GetAll($pageBegin, $pageSize, &$allCount, $state, $siteId) {
        if ($state == 100) {
            //countpic 用于统计相册有多少图片
            $sql = "SELECT ui.NickName AS nickname,ui.RealName AS realname,ui.UserId,ua.UserAlbumId,ua.UserAlbumName,ua.State,ua.SupportCount,ua.HitCount,
                (SELECT count(*) FROM " . self::TableName_UserAlbumPicture . " uap WHERE uap.UserAlbumId = ua.UserAlbumId) AS countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.UserAlbumPicThumbnailUrl 
                FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON ua.UserAlbumId = uap.UserAlbumId AND uap.IsCover = 1 
                WHERE ua.State <= 100 AND ua.UserId = ui.UserId ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;
            //$dataProperty = new DataProperty();
            //$dataProperty->AddField("SiteId", $siteId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetArrayList($sql, null);

            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua WHERE ua.UserId = ui.UserId AND ua.State <= 100";
            $allCount = $dbOperator->GetInt($sqlCount, null);
        } else {
            if ($siteId > 0) {
                //countpic 用于统计相册有多少图片
                $sql = "SELECT ui.NickName AS NickName,ui.RealName AS RealName,ui.UserId,ua.UserAlbumId,ua.UserAlbumName,ua.State,ua.SupportCount,ua.HitCount,
                    (SELECT count(*) FROM " . self::TableName_UserAlbunPicture . " uap WHERE uap.UserAlbumID = ua.UserAlbumID) AS countpic,
                    ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.UserAlbumPicThumbnailUrl 
                    FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON ua.UserAlbumId = uap.UserAlbumId AND uap.IsCover = 1 
                    WHERE ua.State = :State AND ua.UserId = ui.UserId AND ua.SiteId = :SiteId ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;
                $dataProperty = new DataProperty();
                $dbOperator = DBOperator::getInstance();
                $dataProperty->AddField("State", $state);
                $dataProperty->AddField("SiteId", $siteId);
                $result = $dbOperator->GetArrayList($sql, $dataProperty);

                $sqlCount = "SELECT count(*) FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua where ua.UserId = ui.UserId AND ua.SiteId = :siteid AND ua.state = :state";
                $allCount = $dbOperator->GetInt($sqlCount, $dataProperty);
            } else {
                return null;
            }
        }
        return $result;
    }
    
    /**
     * 获取相册名
     * @param int $userAlbumId 相册ID
     * @return string 相册名
     */
    public function GetName($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumName FROM " . self::TableName_UserAlbum . " WHERE " . TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取相册类别
     * @param int $userAlbumId 相册ID
     * @return string 相册类别
     */
    public function GetTag($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumTag FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取当前相册的状态
     * @param int $userAlbumId 相册ID
     * @return int 相册的当前状态 0为未审核,
     */
    public function GetState($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT State FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetInt($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 统计相册数
     * @param string $country 国家
     * @param string $userAlbumTag 相册类别
     * @param int $single 单照或组照  0(默认)为单照 1为组照
     * @return int 不同统计方式的值
     */
    public function GetCountForStatistics($country = '', $userAlbumTag = '', $single = 0) {
        $dataProperty = new DataProperty();
        if ($userAlbumTag == '' && $country == '') {//所有的相册数 只包括已编辑 未审核 已审核的
            $sql = "SELECT count(" . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " WHERE State < 40";
        } else if ($country == 'china' && $userAlbumTag == '' && $single == 0) { //国内的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount = 1";
        } else if ($country == '!china' && $userAlbumTag == '' && $single == 0) { //国外的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount = 1";
        } else if ($country == 'china' && $userAlbumTag == '' && $single == 1) { //国内的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount > 1";
        } else if ($country == '!china' && $userAlbumTag == '' && $single == 1) {//国外的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount > 1";
        } else if ($country == 'china' && $userAlbumTag != '' && $single == 0) {//国内的带类别的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount = 1";
            $dataProperty->AddField("useralbumtag", $userAlbumTag);
        } else if ($country == '!china' && $userAlbumTag != '' && $single == 0) {//国外的带类别的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount = 1";
            $dataProperty->AddField("useralbumtag", $userAlbumTag);
        } else if ($country == 'china' && $userAlbumTag != '' && $single == 1) {//国外的带类别的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount > 1";
            $dataProperty->AddField("useralbumtag", $userAlbumTag);
        } else if ($country == '!china' && $userAlbumTag != '' && $single == 1) {//国外的带类别的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount > 1";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        }
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 按条件搜索所有相册(分页)
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $siteId 站点ID
     * @return array 符合查询条件的相册数组
     */
    public function GetAllForSearch($pageBegin, $pageSize, &$allCount, $siteId,$arrSearchKey) {
        if ($siteId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT ui.NickName AS NickName,ui.RealName AS RealName,ui.UserId,ua.*,uap.UserAlbumPicThumbnailUrl  FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON ua." . self::TableId_UserAlbum . " = uap.UserAlbumId AND uap.IsCover = 1," . self::TableName_UserRole . " ur WHERE ur.UserId=ui.UserId AND ur.SiteId=:SiteId AND ui." . self::TableId_UserInfo . " = ua.UserId AND ua.State < 100 ";
            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua," . self::TableName_UserRole . " ur  WHERE ur.UserId=ui." . self::TableId_UserInfo . " AND ur.SiteId=:SiteId AND  ui." . self::TableId_UserInfo . " = ua.UserId AND ua.State < 100 ";

            $dataProperty->AddField("SiteId", $siteId);
//        $front_addsql = true;
            if (isset($arrSearchKey["author"]) && !empty($arrSearchKey["author"])) {//作者
//            if ($front_addsql) {
//                $add_username = " where u.username = :username";
//                $front_addsql = false;
//            } else {
                $addUsername = " AND (ui.NickName = :NickName OR ui.RealName = :RealName) ";
//            }
                $sql = $sql . $addUsername;
                $sqlCount = $sqlCount . $addUsername;
                $dataProperty->AddField("NickName", $arrSearchKey["author"]);
                $dataProperty->AddField("RealName", $arrSearchKey["author"]);
//            $frontAddSql = false;
            }
            if (isset($arrSearchKey["albumname"]) && !empty($arrSearchKey["albumname"])) {//作品名
//            if ($front_addsql) {
//                $add_useralbumname = " where ua.useralbumname = :useralbumname";
//                $front_addsql = false;
//            } else {
                $addUserAlbumName = " AND ua.UserAlbumName LIKE :UserAlbumName";
//            }
                $sql = $sql . $addUserAlbumName;
                $sqlCount = $sqlCount . $addUserAlbumName;
                $dataProperty->AddField("UserAlbumName", "%" . $arrSearchKey["albumname"] . "%");
            }
            if (isset($arrSearchKey["indextop"]) && !empty($arrSearchKey["indextop"])) {//是否首页
                $addUserAlbumTopIndex = " AND ua.IndexTop = :IndexTop";
                $sql = $sql . $addUserAlbumTopIndex;
                $sqlCount = $sqlCount . $addUserAlbumTopIndex;
                $dataProperty->AddField("IndexTop", $arrSearchKey["indextop"]);
            }
            if (isset($arrSearchKey["isbest"]) && !empty($arrSearchKey["isbest"])) {//是否精华
                $addUserAlbumIsBest = " AND ua.IsBest = :IsBest";
                $sql = $sql . $addUserAlbumIsBest;
                $sqlCount = $sqlCount . $addUserAlbumIsBest;
                $dataProperty->AddField("IsBest", $arrSearchKey["isbest"]);
            }
            if (isset($arrSearchKey["equipment"]) && !empty($arrSearchKey["equipment"])) {//拍摄装备
                $addUserAlbumEquipment = " AND ua.Equipment = :Equipment";
                $sql = $sql . $addUserAlbumEquipment;
                $sqlCount = $sqlCount . $addUserAlbumEquipment;
                $dataProperty->AddField("Equipment", $arrSearchKey["equipment"]);
            }
            if (isset($arrSearchKey["albumtag"]) && !empty($arrSearchKey["albumtag"])) {//按照分类查询
//            $add_oneuseralbumtag = "";
//            for($i=0;$i<count($_POST["albumtag"]);$i++){
//                if($i < count($_POST["albumtag"])-1){
//                    $add_oneuseralbumtag = $add_oneuseralbumtag . "ua.useralbumtag = :useralbumtag".$i." or ";
//                    $dataProperty->AddField("useralbumtag".$i, $_POST["albumtag"][$i]);
//                }else{
//                    $add_oneuseralbumtag = $add_oneuseralbumtag . "ua.useralbumtag = :useralbumtag".$i;
//                    $dataProperty->AddField("useralbumtag".$i, $_POST["albumtag"][$i]);
//                }
//            }
                $addUserAlbumTag = " AND ua.UserAlbumTag = :UserAlbumTag";
                $sql = $sql . $addUserAlbumTag;
                $dataProperty->AddField("UserAlbumTag", $arrSearchKey["albumtag"]);
                $sqlCount = $sqlCount . $addUserAlbumTag;
            }
            if (isset($arrSearchKey["begindate"]) && !empty($arrSearchKey["begindate"])) {
                if (isset($arrSearchKey["enddate"]) && !empty($arrSearchKey["enddate"])) {
                    $addBeginDate = " AND ua.CreateDate < date(:EndDate) AND ua.CreateDate > date(:BeginDate)";
                    $dataProperty->AddField("EndDate", $arrSearchKey["enddate"]);
                    $dataProperty->AddField("BeginDate", $arrSearchKey["begindate"]);
                } else {
                    $addBeginDate = " AND ua.CreateDate > date(:BeginDate)";
                    $dataProperty->AddField("BeginDate", $arrSearchKey["begindate"]);
                }
                $sql = $sql . $addBeginDate;
                $sqlCount = $sqlCount . $addBeginDate;
            }
            if (isset($arrSearchKey["enddate"]) && !empty($arrSearchKey["enddate"])) {
                if (isset($arrSearchKey["begindate"]) && !empty($arrSearchKey["begindate"])) {
                    $addEndDate = " AND ua.CreateDate < date(:EndDate) AND ua.CreateDate > date(:BeginDate)";
                    $dataProperty->AddField("EndDate", $arrSearchKey["enddate"]);
                    $dataProperty->AddField("BeginDate", $arrSearchKey["begindate"]);
                } else {
                    $addEndDate = " AND ua.CreateDate < date(:EndDate)";
                    $dataProperty->AddField("EndDate", $arrSearchKey["enddate"]);
                }
                $sql = $sql . $addEndDate;
                $sqlCount = $sqlCount . $addEndDate;
            }
            if (isset($arrSearchKey["reclevel"]) && $arrSearchKey["reclevel"] != "") {
                $addReclevel = " AND ua.RecLevel = :RecLevel";
                $sql = $sql . $addReclevel;
                $sqlCount = $sqlCount . $addReclevel;
                $dataProperty->AddField("RecLevel", $arrSearchKey["reclevel"]);
            }
            if (isset($arrSearchKey["state"]) && $arrSearchKey["state"] != "") {
                $addState = " AND ua.State = :State";
                $sql = $sql . $addState;
                $sqlCount = $sqlCount . $addState;
                $dataProperty->AddField("State", $arrSearchKey["state"]);
            }
            $dbOperator = DBOperator::getInstance();
            $allCount = $dbOperator->GetInt($sqlCount, $dataProperty);
            $sql = $sql . " ORDER BY ua.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;

            $result = $dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 给中摄协用的查询国外的
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $siteId 站点ID
     * @return array 符合查询条件的相册数组
     */
    public function GetAllForSearchAndForeign($pageBegin, $pageSize, &$allCount, $siteId,$arrSearchKey) {
        $dataProperty = new DataProperty();
        $sql = "SELECT ui.NickName AS nickname,ui.RealName AS realname,ui.UserId,ui.Country,ua.*,uap.UserAlbumPicThumbnailUrl  FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON ua." . self::TableId_UserAlbum . " = uap.UserAlbumId AND uap.IsCover = 1," . self::TableName_UserRole . " ur WHERE ur.UserId=ui." . self::TableName_UserInfo . " AND ur.SiteId=:SiteId AND ui." . self::TableName_UserInfo . " = ua.UserId AND ua.State < 100 AND ui.Country != 'china' ";
        $sqlCount = "SELECT count(*) FROM " . self::TableId_UserInfo . " ui," . self::TableName_UserAlbum . " ua," . self::TableName_UserRole . " ur  WHERE ur.UserId=ui.UserId AND ur.SiteId=:SiteId AND  ui.UserId = ua.UserId AND ua.State < 100 AND ui.Country != 'china' ";

        $dataProperty->AddField("SiteId", $siteId);
//        $front_addsql = true;
        if (isset($arrSearchKey["author"]) && !empty($arrSearchKey["author"])) {//作者
//            if ($front_addsql) {
//                $add_username = " where u.username = :username";
//                $front_addsql = false;
//            } else {
            $addUsername = " AND (ui.NickName = :NickName OR ui.RealName = :RealName) ";
//            }
            $sql = $sql . $addUsername;
            $sqlCount = $sqlCount . $addUsername;
            $dataProperty->AddField("NickName", $arrSearchKey["author"]);
            $dataProperty->AddField("RealName", $arrSearchKey["author"]);
//            $frontAddSql = false;
        }
        if (isset($arrSearchKey["albumname"]) && !empty($arrSearchKey["albumname"])) {//作品名
//            if ($front_addsql) {
//                $add_useralbumname = " where ua.useralbumname = :useralbumname";
//                $front_addsql = false;
//            } else {
            $addUserAlbumName = " AND ua.UserAlbumName LIKE :UserAlbumName";
//            }
            $sql = $sql . $addUserAlbumName;
            $sqlCount = $sqlCount . $addUserAlbumName;
            $dataProperty->AddField("UserAlbumName", "%" . $arrSearchKey["albumname"] . "%");
        }
        if (isset($arrSearchKey["indextop"]) && !empty($arrSearchKey["indextop"])) {//是否首页
            $addUserAlbumTopIndex = " AND ua.IndexTop = :IndexTop";
            $sql = $sql . $addUserAlbumTopIndex;
            $sqlCount = $sqlCount . $addUserAlbumTopIndex;
            $dataProperty->AddField("IndexTop", $arrSearchKey["indextop"]);
        }
        if (isset($arrSearchKey["isbest"]) && !empty($arrSearchKey["isbest"])) {//是否精华
            $addUserAlbumIsBest = " AND ua.IsBest = :IsBest";
            $sql = $sql . $addUserAlbumIsBest;
            $sqlCount = $sqlCount . $addUserAlbumIsBest;
            $dataProperty->AddField("IsBest", $arrSearchKey["isbest"]);
        }
        if (isset($arrSearchKey["equipment"]) && !empty($arrSearchKey["equipment"])) {//拍摄装备
            $addUserAlbumEquipment = " AND ua.Equipment = :Equipment";
            $sql = $sql . $addUserAlbumEquipment;
            $sqlCount = $sqlCount . $addUserAlbumEquipment;
            $dataProperty->AddField("Equipment", $arrSearchKey["equipment"]);
        }
        if (isset($arrSearchKey["albumtag"]) && !empty($arrSearchKey["albumtag"])) {//按照分类查询
//            $add_oneuseralbumtag = "";
//            for($i=0;$i<count($_POST["albumtag"]);$i++){
//                if($i < count($_POST["albumtag"])-1){
//                    $add_oneuseralbumtag = $add_oneuseralbumtag . "ua.useralbumtag = :useralbumtag".$i." or ";
//                    $dataProperty->AddField("useralbumtag".$i, $_POST["albumtag"][$i]);
//                }else{
//                    $add_oneuseralbumtag = $add_oneuseralbumtag . "ua.useralbumtag = :useralbumtag".$i;
//                    $dataProperty->AddField("useralbumtag".$i, $_POST["albumtag"][$i]);
//                }
//            }
            $addUserAlbumTag = " AND ua.UserAlbumTag = :UserAlbumTag";
            $sql = $sql . $addUserAlbumTag;
            $dataProperty->AddField("UserAlbumTag", $arrSearchKey["albumtag"]);
            $sqlCount = $sqlCount . $addUserAlbumTag;
        }
        if (isset($arrSearchKey["begindate"]) && !empty($arrSearchKey["begindate"])) {
            if (isset($arrSearchKey["enddate"]) && !empty($arrSearchKey["enddate"])) {
                $addBeginDate = " AND ua.CreateDate < date(:EndDate) AND ua.CreateDate > date(:BeginDate)";
                $dataProperty->AddField("EndDate", $arrSearchKey["enddate"]);
                $dataProperty->AddField("BeginDate", $arrSearchKey["begindate"]);
            } else {
                $addBeginDate = " AND ua.CreateDate > date(:BeginDate)";
                $dataProperty->AddField("BeginDate", $arrSearchKey["begindate"]);
            }
            $sql = $sql . $addBeginDate;
            $sqlCount = $sqlCount . $addBeginDate;
        }
        if (isset($arrSearchKey["enddate"]) && !empty($arrSearchKey["enddate"])) {
            if (isset($arrSearchKey["begindate"]) && !empty($arrSearchKey["begindate"])) {
                $addEndDate = " AND ua.CreateDate < date(:EndDate) AND ua.CreateDate > date(:BeginDate)";
                $dataProperty->AddField("EndDate", $arrSearchKey["enddate"]);
                $dataProperty->AddField("BeginDate", $arrSearchKey["begindate"]);
            } else {
                $addEndDate = " AND ua.CreateDate < date(:EndDate)";
                $dataProperty->AddField("EndDate", $arrSearchKey["enddate"]);
            }
            $sql = $sql . $addEndDate;
            $sqlCount = $sqlCount . $addEndDate;
        }
        if (isset($arrSearchKey["reclevel"]) && $arrSearchKey["reclevel"] != "") {
            $addReclevel = " AND ua.RecLevel = :RecLevel";
            $sql = $sql . $addReclevel;
            $sqlCount = $sqlCount . $addReclevel;
            $dataProperty->AddField("RecLevel", $arrSearchKey["reclevel"]);
        }
        if (isset($arrSearchKey["state"]) && $arrSearchKey["state"] != "") {
            $addState = " AND ua.State = :State";
            $sql = $sql . $addState;
            $sqlCount = $sqlCount . $addState;
            $dataProperty->AddField("State", $arrSearchKey["state"]);
        }
        $dbOperator = DBOperator::getInstance();
        $allCount = $dbOperator->GetInt($sqlCount, $dataProperty);
        $sql = $sql . " ORDER BY ua.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;

        $result = $dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 通过修改state删除
     * @param int $userAlbumId 相册ID
     * @return int 影响的行数
     */
    public function DeleteByModifyState($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbum . " SET State = 110 WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 完全删除(包括物理删除图片)
     * @param int $userAlbumId 相册ID
     * @return int 影响行数 
     */
    public function Delete($userAlbumId) {
        if ($userAlbumId > 0) {
            $userAlbumpicData = new UserAlbumPicData();
            $picArr = $userAlbumpicData->GetOneAlbumPicList($userAlbumId);
            $uploadFileData = new UploadFileData();
            $tableType = 11;
            for ($i = 0; $i < count($picArr); $i++) {
                $uploadFileData->DeleteByAlbumPic($tableType, $picArr[$i]["UserAlbumPicID"]);
                $userAlbumpicData->DeletePic($picArr[$i]["UserAlbumPicID"]);
            }
            $dirPath = $uploadFileData->GetAlbumPath($tableType, $picArr[0]["UserAlbumPicID"]);
            $sql = "DELETE FROM " . self::TableName_UserAlbum . " WHERE UserAlbumId = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
            //关联删除cst_ActivityAlbum 表中的活动相册记录L
            if ($result > 0) {
                $activityAlbumData = new ActivityAlbumData();
                $activityAlbumData->DeleteActivityAlbumByUserAlbumID($userAlbumId);
            }
            return $result;
        } else {
            return null;
        }
    }

}

