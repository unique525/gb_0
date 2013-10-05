<?php

/**
 * Description of UserAlbum
 *
 * @author yin
 */
class UserAlbumData extends BaseFrontData {

    const tableid = "UserAlbumID";
    const tablename = "cst_useralbum";

    
    /**
     * 新增
     * @param int $userId 用户ID
     * @return int 最后插入的ID
     */
    public function Create($userId = 0) {
        $dataProperty = new DataProperty();
        $sql = "";
        if ($userId === 0) {
            $sql = parent::GetInsertSql(self::tablename, $dataProperty);
        } else {
            $sql = parent::GetInsertSql(self::tablename, $dataProperty, "userid", $userId);
        }

        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 新增相册
     * @param int $userId 用户ID
     * @param string $userAlbumName 相册名
     * @param string $userAlbumIntro 相册简介
     * @param string $equipment 拍摄装备
     * @param string $region 拍摄地点
     * @param string $userAlbumTag 相册类别
     * @param int $siteId 站点ID
     * @param int $state 相册状态
     * @return int 最后插入的相册ID
     */
    public function CreateAlbum($userId, $userAlbumName, $userAlbumIntro, $equipment, $region, $userAlbumTag, $siteId, $state) {
        $sql = "insert into " . self::tablename . " (userid,useralbumname,useralbumintro,equipment,region,useralbumtag,createdate,siteid,state) values (:userid,".$userAlbumName.",:useralbumintro,:equipment,:region,:useralbumtag,now(),:siteid,:state)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("userid", $userId);
        $dataProperty->AddField("useralbumintro", $userAlbumIntro);
        $dataProperty->AddField("equipment", $equipment);
        $dataProperty->AddField("region", $region);
        $dataProperty->AddField("useralbumtag", $userAlbumTag);
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("state", $state);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改相册
     * @param int $tableIdValue 要修改的相册的ID
     * @return int 影响的行数
     */
    public function Modify($tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tablename, self::tableid, $tableIdValue, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取相册根据用户ID和站点ID 前台用
     * @param int $userId 用户ID
     * @param int $siteId 站点ID
     * @return array 相册数组
     */
    public function GetFrontAlbum($userId, $siteId) {
        $sql = "select * from " . self::tablename . " where userid = :userid and siteid = :siteid and state <= 100 and state != 40";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("userid", $userId);
        $dataProperty->AddField("siteid", $siteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个相册的信息 前台用
     * @param int $albumId 相册ID
     * @param int $userId 用户ID
     * @return array 一个相册的2维数组
     */
    public function GetFrontOneAlbum($albumId, $userId) {
        $sql = "select * from " . self::tablename . " where useralbumid = :useralbumid and userid = :userid and state <= 100 and state != 40";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $albumId);
        $dataProperty->AddField("userid", $userId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个相册的信息 后台用
     * @param int $albumId 相册ID
     * @return array 一个相册的2维数组
     */
    public function GetOneAlbum($albumId) {
        $sql = "select ua.*,uap.UserAlbumPicThumbnailUrl,ui.nickname,ui.userid from " . self::tablename . " ua 
            left join cst_useralbumpic uap on uap.useralbumid=ua.useralbumid and uap.iscover = 1,cst_userinfo ui 
            where ua.useralbumid = :useralbumid and ua.state <= 100 and ui.userid = ua.userid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $albumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 通过相册ID获取用户ID
     * @param int $userAlbumId 相册ID
     * @return int 用户ID
     */
    public function GetAlbumUserID($userAlbumId) {
        $sql = "select userid from " . self::tablename . " where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取相册列表 分页 前台
     * @param int $pageBegin 从pageBegin页起
     * @param int $pageSize 取pageSize条
     * @param int $siteId 站点ID
     * @param int $allCount 一共有allCount条
     * @param int $userId 用户ID
     * @param string $order 排序方式
     * @return array 相册列表的数组
     */
    public function GetFrontPagerAlbum($pageBegin, $pageSize, $siteId, &$allCount, $userId, $order = "useralbumid") {
        $sql = "select u.*,(select UserAlbumPicThumbnailUrl from cst_useralbumpic where UserAlbumPicID=
                    (select useralbumpicid from cst_useralbumpic where useralbumid=u.useralbumid and iscover = 1 limit 1)) as picurl
                    ,(select count(useralbumpicid) from cst_useralbumpic where useralbumid=u.useralbumid) as piccount
                    from cst_useralbum u where u.UserID=:userid and u.state <= 100 and u.state != 40 and u.siteid=:siteid order by " . $order . " desc limit " . $pageBegin . "," . $pageSize . "";
        $sqlCount = "select count(*) from cst_useralbum u where u.UserID=:userid 
            and u.state <= 100 and u.state != 40 and u.siteid=:siteid order by " . $order . " desc";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("userid", $userId);
        $dataProperty->AddField("siteid", $siteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param type $pageBegin
     * @param type $pageSize
     * @param type $allCount
     * @param type $siteId
     * @param type $region
     * @param type $userId
     * @param type $tag
     * @param type $reclevel
     * @param type $indexTop
     * @param type $isBest
     * @param type $order
     * @param type $state
     * @param type $searchKey
     * @return type
     */
    public function GetFrontPagerAlbumUnion($pageBegin, $pageSize, &$allCount, $siteId, $region, $userId, $tag, $reclevel, $indexTop, $isBest, $order, $state, $searchKey = "") {
        $dataProperty = new DataProperty();
        $sqlWhere = "where";
        $sql = "select u.*,us.username,us.userid,ui.nickname,ui.nickname as showusername,(select useralbumpicthumbnailurl from cst_useralbumpic up where up.iscover=1 and up.UserAlbumID=u.UserAlbumID limit 1) as picurl
                    ,(select count(useralbumpicid) from cst_useralbumpic where useralbumid=u.useralbumid) as piccount 
                    from cst_useralbum u inner join cst_user us on u.userid=us.userid left join cst_userinfo ui on u.userid=ui.userid {[sqlWhere]} order by " . $order . " limit " . $pageBegin . "," . $pageSize . "";
        $sqlCount = "select count(*) from cst_useralbum u left join cst_user us on u.userid=us.userid left join cst_userinfo ui on u.userid=ui.userid {[sqlWhere]}";
        /* if ($state != "") {
          $sqlwhere .=" u.state=:state and";
          $dataProperty->AddField("state", $state);
          }
          else
          { */
        $sqlWhere .=" u.state<40 and";
        //}
        if ($userId != "") {
            if (strpos($userId, ',') > 0) {
                $sqlWhere .=" u.userid in (" . $userId . ") and";
            } else {
                $sqlWhere .=" u.userid =:userid and";
                $dataProperty->AddField("userid", $userId);
            }
        }
        if ($region != "") {
            $sqlWhere .=" u.region=:region and";
            $dataProperty->AddField("region", $region);
        }
        if ($siteId != "") {
            $sqlWhere .=" u.siteid=:siteid and";
            $dataProperty->AddField("siteid", $siteId);
        }
        if ($tag != "") {
            $sqlWhere .=" u.useralbumtag=:useralbumtag and";
            $dataProperty->AddField("useralbumtag", $tag);
        }
        if ($reclevel != "") {
            $sqlWhere .=" u.reclevel>=:reclevel and";
            $dataProperty->AddField("reclevel", $reclevel);
        }
        if ($indexTop != "") {
            $sqlWhere .=" u.indextop>=:indextop and";
            $dataProperty->AddField("indextop", $indexTop);
        }
        if ($isBest != "") {
            $sqlWhere .=" u.isbest=:isbest and";
            $dataProperty->AddField("isbest", $isBest);
        }
        if ($searchKey != "undefined" && strlen($searchKey) > 0) {
            $sqlWhere .= " (u.UserAlbumName like :searchkey1 or us.UserName like :searchkey2 or ui.NickName like :searchkey3) and";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        if (strlen($sqlWhere) > 5)
            $sqlWhere = substr($sqlWhere, 0, strlen($sqlWhere) - 3);
        else
            $sqlWhere = "";
        $sql = str_replace("{[sqlWhere]}", $sqlWhere, $sql);
        $sqlCount = str_replace("{[sqlWhere]}", $sqlWhere, $sqlCount);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 通过修改state删除
     * @param int $userAlbumId 相册ID
     * @return int 影响的行数
     */
    public function DeleteAlbumByModifyState($userAlbumId) {
        $sql = "update " . self::tablename . " set state = 110 where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 完全删除(包括物理删除图片)
     * @param int $userAlbumId 相册ID
     * @return int 影响行数 
     */
    public function DeleteAlbum($userAlbumId) {
        $userAlbumpicData = new UserAlbumPicData();
        $picArr = $userAlbumpicData->GetOneAlbumPicList($userAlbumId);
        $uploadFileData = new UploadFileData();
        $tableType = 11;
        for ($i = 0; $i < count($picArr); $i++) {
            $uploadFileData->DeleteByAlbumPic($tableType, $picArr[$i]["UserAlbumPicID"]);
            $userAlbumpicData->DeletePic($picArr[$i]["UserAlbumPicID"]);
        }
        $dirPath = $uploadFileData->GetAlbumPath($tableType, $picArr[0]["UserAlbumPicID"]);
        $sql = "delete from " . self::tablename . " where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        //关联删除cst_ActivityAlbum 表中的活动相册记录L
        if ($result > 0) {
            $activityAlbumData = new ActivityAlbumData();
            $activityAlbumData->DeleteActivityAlbumByUserAlbumID($userAlbumId);
        }
        return $result;
    }

    /**
     * 根据useralbumid取得userid
     * @param int $userAlbumId 相册ID
     * @return int 用户ID
     */
    public function GetUserID($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT userid FROM " . self::tablename . " WHERE useralbumid=:useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnInt($sql, $dataProperty);
            return $result;
        } else {
            return 0;
        }
    }

    /**
     * 根据useralbumid取得UserAlbumIntro
     * @param int $userAlbumId 相册ID
     * @return string 相册简介
     */
    public function GetUserAlbumIntro($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumIntro FROM " . self::tablename . " WHERE useralbumid=:useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnString($sql, $dataProperty);
            return $result;
        } else {
            return "";
        }
    }

    /**
     * 中摄协用
     * @param type $userNames
     * @return type
     */
    public function GetListForSearch($userNames) {
        $sql = "select ua.*,u.username from cst_user u,cst_useralbum ua where u.userid = ua.userid and u.username in ('01-00023-00034-Z')";
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->RetrunArray(trim($sql), $dataProperty);
        return $result;
    }

    /**
     * 获取国外的所有相册
     * @param int $pageBegin 
     * @param int $pageSize 
     * @param int $allCount
     * @param int $state
     * @param int $siteId
     * @return type
     */
    public function GetAllAlbumForForeign($pageBegin, $pageSize, &$allCount, $state, $siteId) {
        if ($state == 100) {
            //countpic 用于统计相册有多少图片
            $sql = "select ui.nickname as nickname,ui.realname as realname,ui.userid,ui.country,ua.useralbumid,ua.useralbumname,ua.state,ua.supportcount,ua.HitCount,
                (select count(*) from cst_useralbumpic uap where uap.UserAlbumID = ua.UserAlbumID) as countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.useralbumpicthumbnailurl 
                from cst_userinfo ui,cst_useralbum ua left join cst_useralbumpic uap on ua.useralbumid = uap.useralbumid and uap.iscover = 1 
                where ua.state <= 100 and ua.userid = ui.userid and ui.country != 'china' and ui.country != ''  order by createdate desc limit " . $pageBegin . "," . $pageSize;
            //$dataProperty = new DataProperty();
            //$dataProperty->AddField("SiteId", $siteId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnArray($sql, null);

            $sqlCount = "select count(*) from cst_userinfo ui,cst_useralbum ua where ua.userid = ui.userid and ua.state <= 100 and ui.country != 'china' and ui.country != '' ";
            $allCount = $dbOperator->ReturnInt($sqlCount, null);
        } else {
            //countpic 用于统计相册有多少图片
            $sql = "select ui.nickname as nickname,ui.realname as realname,ui.userid,ui.country,ua.useralbumid,ua.useralbumname,ua.state,ua.supportcount,ua.HitCount,
                (select count(*) from cst_useralbumpic uap where uap.UserAlbumID = ua.UserAlbumID) as countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.useralbumpicthumbnailurl 
                from cst_userinfo ui,cst_useralbum ua left join cst_useralbumpic uap on ua.useralbumid = uap.useralbumid and uap.iscover = 1 
                where ua.state = :state and ua.userid = ui.userid and ua.siteid = :siteid and ui.country != 'china' and ui.country != ''  order by createdate desc limit " . $pageBegin . "," . $pageSize;
            $dataProperty = new DataProperty();
            $dbOperator = DBOperator::getInstance();
            $dataProperty->AddField("state", $state);
            $dataProperty->AddField("siteid", $siteId);
            $result = $dbOperator->ReturnArray($sql, $dataProperty);

            $sqlCount = "select count(*) from cst_userinfo ui,cst_useralbum ua where ua.userid = ui.userid and ua.siteid = :siteid and ua.state = :state and ui.country != 'china' and ui.country != '' ";
            $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        }
        return $result;
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
    public function GetAllAlbum($pageBegin, $pageSize, &$allCount, $state, $siteId) {
        if ($state == 100) {
            //countpic 用于统计相册有多少图片
            $sql = "select ui.nickname as nickname,ui.realname as realname,ui.userid,ua.useralbumid,ua.useralbumname,ua.state,ua.supportcount,ua.HitCount,
                (select count(*) from cst_useralbumpic uap where uap.UserAlbumID = ua.UserAlbumID) as countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.useralbumpicthumbnailurl 
                from cst_userinfo ui,cst_useralbum ua left join cst_useralbumpic uap on ua.useralbumid = uap.useralbumid and uap.iscover = 1 
                where ua.state <= 100 and ua.userid = ui.userid order by createdate desc limit " . $pageBegin . "," . $pageSize;
            //$dataProperty = new DataProperty();
            //$dataProperty->AddField("SiteId", $siteId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnArray($sql, null);

            $sqlCount = "select count(*) from cst_userinfo ui,cst_useralbum ua where ua.userid = ui.userid and ua.state <= 100";
            $allCount = $dbOperator->ReturnInt($sqlCount, null);
        } else {
            //countpic 用于统计相册有多少图片
            $sql = "select ui.nickname as nickname,ui.realname as realname,ui.userid,ua.useralbumid,ua.useralbumname,ua.state,ua.supportcount,ua.HitCount,
                (select count(*) from cst_useralbumpic uap where uap.UserAlbumID = ua.UserAlbumID) as countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.useralbumpicthumbnailurl 
                from cst_userinfo ui,cst_useralbum ua left join cst_useralbumpic uap on ua.useralbumid = uap.useralbumid and uap.iscover = 1 
                where ua.state = :state and ua.userid = ui.userid and ua.siteid = :siteid order by createdate desc limit " . $pageBegin . "," . $pageSize;
            $dataProperty = new DataProperty();
            $dbOperator = DBOperator::getInstance();
            $dataProperty->AddField("state", $state);
            $dataProperty->AddField("siteid", $siteId);
            $result = $dbOperator->ReturnArray($sql, $dataProperty);

            $sqlCount = "select count(*) from cst_userinfo ui,cst_useralbum ua where ua.userid = ui.userid and ua.siteid = :siteid and ua.state = :state";
            $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        }
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
    public function GetAllAlbumForSearch($pageBegin, $pageSize, &$allCount, $siteId) {
        $dataProperty = new DataProperty();
        $sql = "select ui.nickname as nickname,ui.realname as realname,ui.userid,ua.*,uap.useralbumpicthumbnailurl  from cst_userinfo ui,cst_useralbum ua left join cst_useralbumpic uap on ua.useralbumid = uap.useralbumid and uap.iscover = 1,cst_userrole ur where ur.userid=ui.userid AND ur.SiteId=:SiteId AND ui.userid = ua.userid and ua.state < 100 ";
        $sqlCount = "select count(*) from cst_userinfo ui,cst_useralbum ua,cst_userrole ur  where ur.userid=ui.userid AND ur.SiteId=:SiteId AND  ui.userid = ua.userid and ua.state < 100 ";

        $dataProperty->AddField("SiteId", $siteId);
//        $front_addsql = true;
        if (isset($_POST["author"]) && !empty($_POST["author"])) {//作者
//            if ($front_addsql) {
//                $add_username = " where u.username = :username";
//                $front_addsql = false;
//            } else {
            $addUsername = " and (ui.nickname = :nickname or ui.realname = :realname) ";
//            }
            $sql = $sql . $addUsername;
            $sqlCount = $sqlCount . $addUsername;
            $dataProperty->AddField("nickname", $_POST["author"]);
            $dataProperty->AddField("realname", $_POST["author"]);
//            $frontAddSql = false;
        }
        if (isset($_POST["albumname"]) && !empty($_POST["albumname"])) {//作品名
//            if ($front_addsql) {
//                $add_useralbumname = " where ua.useralbumname = :useralbumname";
//                $front_addsql = false;
//            } else {
            $addUserAlbumName = " and ua.useralbumname like :useralbumname";
//            }
            $sql = $sql . $addUserAlbumName;
            $sqlCount = $sqlCount . $addUserAlbumName;
            $dataProperty->AddField("useralbumname", "%" . $_POST["albumname"] . "%");
        }
        if (isset($_POST["indextop"]) && !empty($_POST["indextop"])) {//是否首页
            $addUserAlbumTopIndex = " and ua.indextop = :indextop";
            $sql = $sql . $addUserAlbumTopIndex;
            $sqlCount = $sqlCount . $addUserAlbumTopIndex;
            $dataProperty->AddField("indextop", $_POST["indextop"]);
        }
        if (isset($_POST["isbest"]) && !empty($_POST["isbest"])) {//是否精华
            $addUserAlbumIsBest = " and ua.isbest = :isbest";
            $sql = $sql . $addUserAlbumIsBest;
            $sqlCount = $sqlCount . $addUserAlbumIsBest;
            $dataProperty->AddField("isbest", $_POST["isbest"]);
        }
        if (isset($_POST["equipment"]) && !empty($_POST["equipment"])) {//拍摄装备
            $addUserAlbumEquipment = " and ua.equipment = :equipment";
            $sql = $sql . $addUserAlbumEquipment;
            $sqlCount = $sqlCount . $addUserAlbumEquipment;
            $dataProperty->AddField("equipment", $_POST["equipment"]);
        }
        if (isset($_POST["albumtag"]) && !empty($_POST["albumtag"])) {//按照分类查询
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
            $addUserAlbumTag = " and ua.useralbumtag = :useralbumtag";
            $sql = $sql . $addUserAlbumTag;
            $dataProperty->AddField("useralbumtag", $_POST["albumtag"]);
            $sqlCount = $sqlCount . $addUserAlbumTag;
        }
        if (isset($_POST["begindate"]) && !empty($_POST["begindate"])) {
            if (isset($_POST["enddate"]) && !empty($_POST["enddate"])) {
                $addBeginDate = " and ua.createdate < date(:enddate) and ua.createdate > date(:begindate)";
                $dataProperty->AddField("enddate", $_POST["enddate"]);
                $dataProperty->AddField("begindate", $_POST["begindate"]);
            } else {
                $addBeginDate = " and ua.createdate > date(:begindate)";
                $dataProperty->AddField("begindate", $_POST["begindate"]);
            }
            $sql = $sql . $addBeginDate;
            $sqlCount = $sqlCount . $addBeginDate;
        }
        if (isset($_POST["enddate"]) && !empty($_POST["enddate"])) {
            if (isset($_POST["begindate"]) && !empty($_POST["begindate"])) {
                $addEndDate = " and ua.createdate < date(:enddate) and ua.createdate > date(:begindate)";
                $dataProperty->AddField("enddate", $_POST["enddate"]);
                $dataProperty->AddField("begindate", $_POST["begindate"]);
            } else {
                $addEndDate = " and ua.createdate < date(:enddate)";
                $dataProperty->AddField("enddate", $_POST["enddate"]);
            }
            $sql = $sql . $addEndDate;
            $sqlCount = $sqlCount . $addEndDate;
        }
        if (isset($_POST["reclevel"]) && $_POST["reclevel"] != "") {
            $addReclevel = " and ua.reclevel = :reclevel";
            $sql = $sql . $addReclevel;
            $sqlCount = $sqlCount . $addReclevel;
            $dataProperty->AddField("reclevel", $_POST["reclevel"]);
        }
        if (isset($_POST["state"]) && $_POST["state"] != "") {
            $addState = " and ua.state = :state";
            $sql = $sql . $addState;
            $sqlCount = $sqlCount . $addState;
            $dataProperty->AddField("state", $_POST["state"]);
        }
        $dbOperator = DBOperator::getInstance();
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        $sql = $sql . " order by ua.createdate desc limit " . $pageBegin . "," . $pageSize;

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 给中摄协用的查询国外的
     * @param type $pageBegin
     * @param type $pageSize
     * @param type $allCount
     * @param type $siteId
     * @return type
     */
    public function GetAllAlbumForSearchAndForeign($pageBegin, $pageSize, &$allCount, $siteId) {
        $dataProperty = new DataProperty();
        $sql = "select ui.nickname as nickname,ui.realname as realname,ui.userid,ui.country,ua.*,uap.useralbumpicthumbnailurl  from cst_userinfo ui,cst_useralbum ua left join cst_useralbumpic uap on ua.useralbumid = uap.useralbumid and uap.iscover = 1,cst_userrole ur where ur.userid=ui.userid AND ur.SiteId=:SiteId AND ui.userid = ua.userid and ua.state < 100 and ui.country != 'china' ";
        $sqlCount = "select count(*) from cst_userinfo ui,cst_useralbum ua,cst_userrole ur  where ur.userid=ui.userid AND ur.SiteId=:SiteId AND  ui.userid = ua.userid and ua.state < 100 and ui.country != 'china' ";

        $dataProperty->AddField("SiteId", $siteId);
//        $front_addsql = true;
        if (isset($_POST["author"]) && !empty($_POST["author"])) {//作者
//            if ($front_addsql) {
//                $add_username = " where u.username = :username";
//                $front_addsql = false;
//            } else {
            $addUsername = " and (ui.nickname = :nickname or ui.realname = :realname) ";
//            }
            $sql = $sql . $addUsername;
            $sqlCount = $sqlCount . $addUsername;
            $dataProperty->AddField("nickname", $_POST["author"]);
            $dataProperty->AddField("realname", $_POST["author"]);
//            $frontAddSql = false;
        }
        if (isset($_POST["albumname"]) && !empty($_POST["albumname"])) {//作品名
//            if ($front_addsql) {
//                $add_useralbumname = " where ua.useralbumname = :useralbumname";
//                $front_addsql = false;
//            } else {
            $addUserAlbumName = " and ua.useralbumname like :useralbumname";
//            }
            $sql = $sql . $addUserAlbumName;
            $sqlCount = $sqlCount . $addUserAlbumName;
            $dataProperty->AddField("useralbumname", "%" . $_POST["albumname"] . "%");
        }
        if (isset($_POST["indextop"]) && !empty($_POST["indextop"])) {//是否首页
            $addUserAlbumTopIndex = " and ua.indextop = :indextop";
            $sql = $sql . $addUserAlbumTopIndex;
            $sqlCount = $sqlCount . $addUserAlbumTopIndex;
            $dataProperty->AddField("indextop", $_POST["indextop"]);
        }
        if (isset($_POST["isbest"]) && !empty($_POST["isbest"])) {//是否精华
            $addUserAlbumIsBest = " and ua.isbest = :isbest";
            $sql = $sql . $addUserAlbumIsBest;
            $sqlCount = $sqlCount . $addUserAlbumIsBest;
            $dataProperty->AddField("isbest", $_POST["isbest"]);
        }
        if (isset($_POST["equipment"]) && !empty($_POST["equipment"])) {//拍摄装备
            $addUserAlbumEquipment = " and ua.equipment = :equipment";
            $sql = $sql . $addUserAlbumEquipment;
            $sqlCount = $sqlCount . $addUserAlbumEquipment;
            $dataProperty->AddField("equipment", $_POST["equipment"]);
        }
        if (isset($_POST["albumtag"]) && !empty($_POST["albumtag"])) {//按照分类查询
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
            $addUserAlbumTag = " and ua.useralbumtag = :useralbumtag";
            $sql = $sql . $addUserAlbumTag;
            $dataProperty->AddField("useralbumtag", $_POST["albumtag"]);
            $sqlCount = $sqlCount . $addUserAlbumTag;
        }
        if (isset($_POST["begindate"]) && !empty($_POST["begindate"])) {
            if (isset($_POST["enddate"]) && !empty($_POST["enddate"])) {
                $addBeginDate = " and ua.createdate < date(:enddate) and ua.createdate > date(:begindate)";
                $dataProperty->AddField("enddate", $_POST["enddate"]);
                $dataProperty->AddField("begindate", $_POST["begindate"]);
            } else {
                $addBeginDate = " and ua.createdate > date(:begindate)";
                $dataProperty->AddField("begindate", $_POST["begindate"]);
            }
            $sql = $sql . $addBeginDate;
            $sqlCount = $sqlCount . $addBeginDate;
        }
        if (isset($_POST["enddate"]) && !empty($_POST["enddate"])) {
            if (isset($_POST["begindate"]) && !empty($_POST["begindate"])) {
                $addEndDate = " and ua.createdate < date(:enddate) and ua.createdate > date(:begindate)";
                $dataProperty->AddField("enddate", $_POST["enddate"]);
                $dataProperty->AddField("begindate", $_POST["begindate"]);
            } else {
                $addEndDate = " and ua.createdate < date(:enddate)";
                $dataProperty->AddField("enddate", $_POST["enddate"]);
            }
            $sql = $sql . $addEndDate;
            $sqlCount = $sqlCount . $addEndDate;
        }
        if (isset($_POST["reclevel"]) && $_POST["reclevel"] != "") {
            $addReclevel = " and ua.reclevel = :reclevel";
            $sql = $sql . $addReclevel;
            $sqlCount = $sqlCount . $addReclevel;
            $dataProperty->AddField("reclevel", $_POST["reclevel"]);
        }
        if (isset($_POST["state"]) && $_POST["state"] != "") {
            $addState = " and ua.state = :state";
            $sql = $sql . $addState;
            $sqlCount = $sqlCount . $addState;
            $dataProperty->AddField("state", $_POST["state"]);
        }
        $dbOperator = DBOperator::getInstance();
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        $sql = $sql . " order by ua.createdate desc limit " . $pageBegin . "," . $pageSize;

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改状态
     * @param int $userAlbumId 相册ID
     * @param int $state 相册状态
     * @return int 影响行数
     */
    public function ModiftState($userAlbumId, $state) {
        $sql = "update " . self::tablename . " set state = :state where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("state", $state);
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 给相册增加推荐分
     * @param type $userAlbumId
     * @param type $recCount
     * @return type 
     */
    public function AddRecCount($userAlbumId, $recCount) {
        $sql = "update " . self::tablename . " set RecCount = RecCount+:RecCount where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("RecCount", $recCount);
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param type $userAlbumId
     * @param type $recCount
     * @return type
     */
    public function SetRecCount($userAlbumId, $recCount) {
        $sql = "update " . self::tablename . " set RecCount = :RecCount where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("RecCount", $recCount);
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
        
    /**
     * 获取某个相册的推荐分
     * @param int $userAlbumId 相册ID
     * @return int 相册的推荐分
     */
    public function GetRecCount($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT RecCount FROM " . self::tablename . " WHERE useralbumid=:useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnInt($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 给相册增加点击数
     * @param type $userAlbumId
     * @param type $hitCount
     * @return type 
     */
    public function AddHitCount($userAlbumId, $hitCount) {
        $sql = "update " . self::tablename . " set HitCount = HitCount+:HitCount where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("HitCount", $hitCount);
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function GetRegion($siteId, $topCount, $state) {
        $sql = "select region from " . self::tablename . " where region != '' and region is not null and siteid = :siteid and state < 40 group by region order by count(*) desc limit " . $topCount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        //$dataProperty->AddField("state", $state);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 重计相册是否为精华相册
     * @param int $siteId 站点ID
     */
    public function RecountBest($siteId) {
        $result = 0;
        $tableType = 1; //相册
        $commentType = 2; //简单投票

        $siteConfigData = new SiteConfigData($siteId);
        $userAlbumToBestMustVoteCount = $siteConfigData->UserAlbumToBestMustVoteCount;
        if ($userAlbumToBestMustVoteCount > 0) {
            $sql = "update cst_useralbum set isbest=1 where (select count(*) from cst_comment where tabletype=$tableType and commenttype=$commentType and tableid=cst_useralbum.UserAlbumID)>$userAlbumToBestMustVoteCount";
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, null);
        }
        return $result;
    }

    /**
     * 删除该用户的所有相册(物理删除)
     * @param int $userId 用户ID
     */
    public function DeleteUserAlbumByUserID($userId) {
        $sql = "select useralbumid from " . self::tablename . " where userid = :userid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("userid", $userId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        for ($i = 0; $i < count($result); $i++) {
            $userAlbumId = $result[$i]["useralbumid"];
            self::DeleteAlbum($userAlbumId);
        }
    }

    /**
     * 修改该相册是否为精华
     * @param int $userAlbumId 相册ID
     * @return int 影响行数
     */
    public function ModifyIsBest($userAlbumId) {
        $sql = "update " . self::tablename . " set isbest = 1 where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 支持数+1
     * @param type $userAlbumId
     * @return type 
     */
    public function AddSupportCountOne($userAlbumId) {
        $sql = "update " . self::tablename . " set supportcount = (select count(*) from cst_comment where tableid = :tableid and tabletype = 1 and commenttype = 2) where useralbumid = :useralbumid ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dataProperty->AddField("tableid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 评论数+1
     * @param type $userAlbumId
     * @return type 
     */
    public function AddCommentCountOne($userAlbumId) {
        $sql = "update " . self::tablename . " set commentcount = (select count(*) from cst_comment where tableid = :tableid and tabletype = 1 and commenttype = 0) where useralbumid = :useralbumid ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dataProperty->AddField("tableid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 增加评论数
     * @param int $userAlbumId 相册ID
     * @param int $commentCount 评论数
     * @return int 影响行数
     */
    public function AddCommentCount($userAlbumId, $commentCount) {
        $sql = "UPDATE " . self::tablename . " SET CommentCount=CommentCount+$commentCount WHERE " . self::tableid . "=:" . self::tableid;
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::tableid, $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取相册名
     * @param int $userAlbumId 相册ID
     * @return string 相册名
     */
    public function GetUserAlbumName($userAlbumId) {
        $sql = "select useralbumname from cst_useralbum where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取当前相册的状态
     * @param int $userAlbumId 相册ID
     * @return int 相册的当前状态 0为未审核,
     */
    public function GetNowAlbumState($userAlbumId) {
        $sql = "select state from " . self::tablename . " where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param int $userAlbumId 相册ID
     * @return type
     */
    public function CheckUserAlbum($userAlbumId) {
        $sql = "select count(*) from " . self::tablename . " where (state = 40 or state = 110) and useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 检查是否有封面图
     * @param int $userAlbumId
     * @return int 返回1或0 1为有封面图 0为没有封面图
     */
    public function CheckIsCreateMainPic($userAlbumId) {
        $sql = "select count(*) from cst_useralbumpic where useralbumid = :useralbumid and iscover = 1";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbopertor = DBOperator::getInstance();
        $result = $dbopertor->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取某个用户的总相册数(不包括相册里面没相片的)
     * @param int $userId
     * @return int 某个用户的总相册数
     */
    public function GetAlbumCountByUserId($userId){
        $sql = "select count(*) from cst_useralbum where userid = :userid and 
            (select count(*) from cst_useralbumpic where useralbumid = cst_useralbum.useralbumid)>0";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("userid", $userId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql,$dataProperty);
        return $result;
    }
    
    /**
     * 
     * @param type $siteId
     * @return type
     */
    public function GetAllUserAlbumTag($siteId){
        $sql = "select useralbumtag from ".self::tablename." where siteid = :siteid group by useralbumtag";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql,$dataProperty);
        return $result;
    }
    
    /**
     * 
     * @param type $userAlbumId
     * @param type $supportCount
     * @return type
     */
    public function SetSupportCount($userAlbumId,$supportCount){
        $sql = "update ".self::tablename." set supportcount = :supportcount where useralbumid = :useralbumid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("supportcount", $supportCount);
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql,$dataProperty);
        return $result;
    }
    
     /**
     * 获取相册列表 分页 前台
     * @param int $pageBegin 从pageBegin页起
     * @param int $pageSize 取pageSize条
     * @param int $siteId 站点ID
     * @param int $allCount 一共有allCount条
     * @param int $userId 用户ID
     * @param string $order 排序方式
     * @return array 相册列表的数组
     */
    public function GetFrontPagerAlbumForPushOut($pageBegin, $pageSize, $siteId, &$allCount, $userId, $order = "useralbumid") {
        $searchSqlStr = "";
        $dataProperty = new DataProperty();
        if ($siteId > 0) {
            $searchSqlStr .= " and u.siteid=:siteid";
            $dataProperty->AddField("siteid", $siteId);
        }
        if ($userId > 0) {
            $searchSqlStr .= " and u.userid=:userid";
            $dataProperty->AddField("userid", $userId);
        }
        $sql = "select u.UserAlbumID,u.UserAlbumName,(select UserAlbumPicThumbnailUrl from cst_useralbumpic where UserAlbumPicID=
                    (select useralbumpicid from cst_useralbumpic where useralbumid=u.useralbumid and iscover = 1 limit 1)) as picurl
                    ,(select count(useralbumpicid) from cst_useralbumpic where useralbumid=u.useralbumid) as piccount
                    from cst_useralbum u where u.pushout=1 and u.state <= 100 and u.state != 40" .$searchSqlStr. " order by " . $order . " desc limit " . $pageBegin . "," . $pageSize . "";
        $sqlCount = "select count(*) from cst_useralbum u where u.pushout=1 and u.state <= 100 and u.state != 40" .$searchSqlStr;
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }
    
    public function GetFrontListForClient($userAlbumTag,$limit){
        $sql = "select ua.*,uap.useralbumpicthumbnailurl as UserAlbumPicUrlThumbnailUrl,ui.nickname as NickName from cst_useralbum ua,cst_userinfo ui,cst_useralbumpic uap 
            where ua.state = 20 and ua.useralbumtag = :useralbumtag and ua.userid = ui.userid and uap.useralbumid = ua.useralbumid and uap.iscover = 1
            and ua.isbest = 1 order by ua.createdate desc limit ".$limit;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumtag", $userAlbumTag);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql,$dataProperty);
        return $result;
    }
    
    public function GetFrontOneForClient($userAlbumId){
        $sql = "select ua.*,uap.useralbumpicthumbnailurl as UserAlbumPicUrlThumbnailUrl,ui.nickname as NickName from cst_useralbum ua,cst_userinfo ui,cst_useralbumpic uap 
            where ua.state = 20 and ua.useralbumid = :useralbumid and ua.userid = ui.userid and uap.useralbumid = ua.useralbumid and uap.iscover = 1
            and ua.isbest = 1 order by ua.createdate desc ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("useralbumid", $userAlbumId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnRow($sql,$dataProperty);
        return $result;
    }
    
    public function CheckSameUserAlbumNameForSYJ($userAlbumName){
        $sql = "select count(*) from ".self::tablename." where useralbumname = ".$userAlbumName;
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql,$dataProperty);
        return $result;
    }
}

?>
