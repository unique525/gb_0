<?php

/**
 * 后台管理 会员相册 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 *  @author yin
 */
class UserAlbumManageData extends BaseManageData {

    /**
     * 修改相册(checked)
     * @param array $httpPostData $_POST里的数据
     * @param int $userAlbumId 要修改的相册的ID
     * @return int 影响的行数
     */
    public function Modify($httpPostData, $userAlbumId) {
            if ($userAlbumId > 0) {
                $dataProperty = new DataProperty();
                $sql = parent::GetUpdateSql($httpPostData, self::TableName_UserAlbum, self::TableId_UserAlbum, $userAlbumId, $dataProperty);
                $result = $this->dbOperator->Execute($sql, $dataProperty);
                return $result;
            } else {
                return null;
            }
    }

    /**
     * 修改该相册是否为精华(checked)
     * @param int $userAlbumId 相册ID
     * @param int $isBest 是否为精华 默认为1(是)
     * @return int 影响行数
     */
    public function ModifyIsBest($userAlbumId,$isBest = 1) {
        if ($userAlbumId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbum . " SET IsBest = :IsBest WHERE " . self::TableId_UserAlbum . " = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dataProperty->AddField("IsBest", $isBest);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 修改状态
     * @param int $userAlbumId 相册Id
     * @param int $state 修改后的状态
     * @return int 影响行数
     */
    public function ModifyState($userAlbumId,$state){
        $sql = "UPDATE ".self::TableName_UserAlbum." SET State = :State WHERE ". self::TableId_UserAlbum . " = :UserAlbumId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("State",$state);
        $dataProperty->AddField("UserAlbumId",$userAlbumId);
        $result = $this->dbOperator->Execute($sql,$dataProperty);
        return $result;
    }

    /**
     * 通过相册ID获取用户ID(checked)
     * @param int $userAlbumId 相册ID
     * @return int 用户ID
     */
    public function GetUserId($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserId FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 通过相册ID获取用户名(checked)
     * @param int $userAlbumId 相册ID
     * @param bool $withCache 是否缓存
     * @return string 用户名 
     */
    public function GetUserName($userAlbumId,$withCache) {
        if ($userAlbumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_album_data';
            $cacheFile = 'user_album_get_username.cache_' . $userAlbumId . '';
            $sql = "SELECT UserName FROM " . self::TableName_User . "
                WHERE " . self::TableId_User . " = (SELECT " . self::TableId_User . " FROM " . self::TableName_UserAlbum . " 
                WHERE " . self::TableId_UserAlbum . "=:UserAlbumId);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            return $result;
        } else {
            return 0;
        }
    }

    /**
     * 根据UserAlbumId取得UserAlbumIntro(checked)
     * @param int $userAlbumId 相册ID
     * @param bool $withCache 是否缓存
     * @return string 相册简介
     */
    public function GetUserAlbumIntro($userAlbumId,$withCache) {
        if ($userAlbumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_album_data';
            $cacheFile = 'user_album_get_intro.cache_' . $userAlbumId . '';
            $sql = "SELECT UserAlbumIntro FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . "=:UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            return $result;
        } else {
            return "";
        }
    }

    /**
     * 获取一个相册的信息(checked)
     * @param int $userAlbumId 相册ID
     * @return array 一个相册的数据集
     */
    public function GetOne($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT ua.*,uap.UserAlbumPicThumbnailUrl,ui.NickName,ui.RealName,u.UserName,ui.UserId FROM " . self::TableName_UserAlbum . " ua 
                LEFT JOIN " . self::TableName_UserAlbumPic . " uap ON uap.UserAlbumId=ua.UserAlbumId AND uap.IsCover = 1," . self::TableName_UserInfo . " ui," . self::TableName_User . " u
                WHERE ua.UserAlbumId = :UserAlbumId AND ua.State <= 100 AND ui.UserId = ua.UserId AND u.UserId=ui.UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取站点内的所有相册(checked)
     * @param int $siteId 站点ID
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $state 取状态为state的相册
     * @param string $searchKey 搜索关键词
     * @param int $searchType 检索字段
     * @return array 相册列表的数组
     */
    public function GetList($siteId, $pageBegin, $pageSize, &$allCount, $state,$searchKey = "",$searchType = 0,$userGroupId) {

        $result = -1;
        if ($siteId > 0) {
            $dataProperty = new DataProperty();
            $searchSql="";
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                if ($searchType == 0) { //真实姓名
                    $searchSql = " AND (ui.RealName like :SearchKey) ";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else if ($searchType == 1) { //手机号码
                    $searchSql = " AND (u.UserMobile like :SearchKey) ";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else { //模糊
                    $searchSql = " AND (ui.RealName LIKE :SearchKey1
                                    OR u.UserMobile LIKE :SearchKey2) ";
                    $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                    $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                }
            }

            //CountPic 用于统计相册有多少图片
            $sql = "SELECT ui.RealName,ui.UserId,ui.SchoolName,ui.ClassName,ua.UserAlbumId,ua.UserAlbumIntro,ua.State,ua.SiteId,uf.UploadFilePath,uf.UploadFileId,u.UserMobile
                    FROM cst_user_album ua,cst_user_info ui,cst_upload_file uf,cst_user u,cst_user_role ur
                    WHERE ui.UserId=ua.UserId and u.UserId=ua.UserId and uf.UploadFileId=ua.CoverPicUploadFileId and ua.UserId=ur.UserId and ur.UserGroupId=".$userGroupId." and ua.SiteId=".$siteId." $searchSql
                    ORDER BY ua.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sqlCount = "SELECT count(*) FROM
                        " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua," . self::TableName_UploadFile . " uf, " . self::TableName_UserRole . " ur
                        WHERE ui.UserId=ua.UserId and uf.UploadFileId=ua.CoverPicUploadFileId and ua.UserId=ur.UserId and ur.UserGroupId=".$userGroupId." $searchSql ";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取相册名(checked)
     * @param int $userAlbumId 相册ID
     * @param bool $withCache 是否缓存
     * @return string 相册名
     */
    public function GetUserAlbumName($userAlbumId,$withCache) {
        if ($userAlbumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_album_data';
            $cacheFile = 'user_album_get_name.cache_' . $userAlbumId . '';
            $sql = "SELECT UserAlbumName FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取相册标签(checked)
     * @param int $userAlbumId 相册ID
     * @param bool $withCache 是否缓存
     * @return string 相册类别
     */
    public function GetUserAlbumTag($userAlbumId,$withCache) {
        if ($userAlbumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_album_data';
            $cacheFile = 'user_album_get_tag.cache_' . $userAlbumId . '';
            $sql = "SELECT UserAlbumTag FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取当前相册的状态(checked)
     * @param int $userAlbumId 相册ID
     * @param bool $withCache 是否缓存
     * @return int 相册的当前状态 0为未审核,
     */
    public function GetState($userAlbumId,$withCache) {
        if ($userAlbumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_album_data';
            $cacheFile = 'user_album_get_state.cache_' . $userAlbumId . '';
            $sql = "SELECT State FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
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
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount = 1;";
        } else if ($country == '!china' && $userAlbumTag == '' && $single == 0) { //国外的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount = 1;";
        } else if ($country == 'china' && $userAlbumTag == '' && $single == 1) { //国内的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount > 1;";
        } else if ($country == '!china' && $userAlbumTag == '' && $single == 1) {//国外的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumPicCount > 1;";
        } else if ($country == 'china' && $userAlbumTag != '' && $single == 0) {//国内的带类别的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount = 1;";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        } else if ($country == '!china' && $userAlbumTag != '' && $single == 0) {//国外的带类别的相册数 只包括已编辑 未审核 已审核的 单照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount = 1;";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        } else if ($country == 'china' && $userAlbumTag != '' && $single == 1) {//国内的带类别的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount > 1;";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        } else if ($country == '!china' && $userAlbumTag != '' && $single == 1) {//国外的带类别的相册数 只包括已编辑 未审核 已审核的 组照
            $sql = "SELECT count(ua." . self::TableId_UserAlbum . ") FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserInfo . " ui WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumPicCount > 1;";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        } else{
            $sql = "";
        }
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 按条件搜索所有相册(分页)
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $siteId 站点ID
     * @param string $author 作者
     * @param string $userAlbumName 相册名
     * @param int $indexTop 是否上首页
     * @param int $isBest 是否精华
     * @param string $equipment 拍摄设备
     * @param string $userAlbumTag 相册类别
     * @param string $beginDate 查询从BeginDate之后的作品
     * @param string $endDate 查询EndDate之前的作品
     * @param int $recLevel 推荐级别
     * @param int $state 状态
     * @param string $country 默认为中国,否则为国外
     * @return array 符合查询条件的相册数组
     */
    public function GetListForSearch($pageBegin, $pageSize, &$allCount, $siteId, $author, $userAlbumName, $indexTop, $isBest, $equipment, $userAlbumTag, $beginDate, $endDate, $recLevel, $state, $country = "china") {
        if ($siteId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT
                        ui.NickName,
                        ui.RealName,
                        ui.UserId,ua.*,
                        uap.UserAlbumPicThumbnailUrl
                    FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua
                    LEFT JOIN " . self::TableName_UserAlbumPic . " uap
                    ON ua." . self::TableId_UserAlbum . " = uap.UserAlbumId
                    AND uap.IsCover = 1," . self::TableName_UserRole . " ur
                    WHERE ur.UserId=ui.UserId
                        AND ur.SiteId=:SiteId
                        AND ui." . self::TableId_User . " = ua.UserId
                        AND ua.Country = :Country;";

            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua," . self::TableName_UserRole . " ur  WHERE ur.UserId=ui." . self::TableId_User . " AND ur.SiteId=:SiteId AND  ui." . self::TableId_User . " = ua.UserId AND ua.Country = :Country";

            $dataProperty->AddField("SiteId", $siteId);
            if($country == "china"){
                $dataProperty->AddField("Country", $country);
            }else{
                $dataProperty->AddField("Country", "!china");
            }

            if (isset($author) && !empty($author)) {//作者
                $addUserName = " AND (ui.NickName = :NickName OR ui.RealName = :RealName) ";
                $sql = $sql . $addUserName;
                $sqlCount = $sqlCount . $addUserName;
                $dataProperty->AddField("NickName", $author);
                $dataProperty->AddField("RealName", $author);

            }
            if (isset($userAlbumName) && !empty($userAlbumName)) {//作品名
                $addUserAlbumName = " AND ua.UserAlbumName LIKE :UserAlbumName";
                $sql = $sql . $addUserAlbumName;
                $sqlCount = $sqlCount . $addUserAlbumName;
                $dataProperty->AddField("UserAlbumName", "%" . $userAlbumName . "%");
            }
            if (isset($indexTop) && !empty($indexTop)) {//是否首页
                $addUserAlbumTopIndex = " AND ua.IndexTop = :IndexTop";
                $sql = $sql . $addUserAlbumTopIndex;
                $sqlCount = $sqlCount . $addUserAlbumTopIndex;
                $dataProperty->AddField("IndexTop", $indexTop);
            }
            if (isset($isBest) && !empty($isBest)) {//是否精华
                $addUserAlbumIsBest = " AND ua.IsBest = :IsBest";
                $sql = $sql . $addUserAlbumIsBest;
                $sqlCount = $sqlCount . $addUserAlbumIsBest;
                $dataProperty->AddField("IsBest", $isBest);
            }
            if (isset($equipment) && !empty($equipment)) {//拍摄装备
                $addUserAlbumEquipment = " AND ua.Equipment = :Equipment";
                $sql = $sql . $addUserAlbumEquipment;
                $sqlCount = $sqlCount . $addUserAlbumEquipment;
                $dataProperty->AddField("Equipment", $equipment);
            }
            if (isset($userAlbumTag) && !empty($userAlbumTag)) {//按照分类查询
                $addUserAlbumTag = " AND ua.UserAlbumTag = :UserAlbumTag";
                $sql = $sql . $addUserAlbumTag;
                $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
                $sqlCount = $sqlCount . $addUserAlbumTag;
            }
            if (isset($beginDate) && !empty($beginDate)) {
                if (isset($endDate) && !empty($endDate)) {
                    $addBeginDate = " AND ua.CreateDate < date(:EndDate) AND ua.CreateDate > date(:BeginDate)";
                    $dataProperty->AddField("EndDate", $endDate);
                    $dataProperty->AddField("BeginDate", $beginDate);
                } else {
                    $addBeginDate = " AND ua.CreateDate > date(:BeginDate)";
                    $dataProperty->AddField("BeginDate", $beginDate);
                }
                $sql = $sql . $addBeginDate;
                $sqlCount = $sqlCount . $addBeginDate;
            }
            if (isset($endDate) && !empty($endDate)) {
                if (isset($beginDate) && !empty($beginDate)) {
                    $addEndDate = " AND ua.CreateDate < date(:EndDate) AND ua.CreateDate > date(:BeginDate)";
                    $dataProperty->AddField("EndDate", $endDate);
                    $dataProperty->AddField("BeginDate", $beginDate);
                } else {
                    $addEndDate = " AND ua.CreateDate < date(:EndDate)";
                    $dataProperty->AddField("EndDate", $endDate);
                }
                $sql = $sql . $addEndDate;
                $sqlCount = $sqlCount . $addEndDate;
            }
            if (isset($recLevel) && !empty($recLevel)) {
                $addRecLevel = " AND ua.RecLevel = :RecLevel";
                $sql = $sql . $addRecLevel;
                $sqlCount = $sqlCount . $addRecLevel;
                $dataProperty->AddField("RecLevel", $recLevel);
            }
            if (isset($state) && !empty($state)) {
                $addState = " AND ua.State = :State";
                $sql = $sql . $addState;
                $sqlCount = $sqlCount . $addState;
                $dataProperty->AddField("State", $state);
            }else{
                $addState = " AND ua.State < 100";
                $sql = $sql . $addState;
                $sqlCount = $sqlCount . $addState;
            }
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
            $sql = $sql . " ORDER BY ua.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 通过修改state删除(checked)
     * @param int $userAlbumId 相册ID
     * @return int 影响的行数
     */
    public function DeleteToRecycleBin($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbum . " SET State = 110 WHERE " . self::TableId_UserAlbum . " = :UserAlbumId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 完全删除(包括物理删除图片)(checked)
     * @param int $userAlbumId 相册ID
     * @return int 影响行数 
     */
    public function Delete($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "DELETE FROM " . self::TableName_UserAlbum . " WHERE ".self::TableId_UserAlbum." = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

}

