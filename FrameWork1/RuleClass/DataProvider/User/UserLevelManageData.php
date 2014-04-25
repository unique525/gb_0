<?php
/**
 * 后台管理 会员等级 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserLevelManageData extends BaseManageData {
    public function Update($userId,$siteId){
        $result = 0;
        if ($userId > 0 && $siteId > 0) {
            //取得当前的会员等级
            $userSiteLevelManageData = new UserSiteLevelManageData();
            $nowUserLevelId = $userSiteLevelManageData->GetUserLevelId($userId, $siteId);
            $isLock = self::GetIsLock($nowUserLevelId);
            if ($isLock > 0) {
                return -20; //等级锁定
            }
            if ($nowUserLevelId < 0) {
                $nowUserLevelId = 0;
            }
            //当前级别数字
            $nowUserLevel = self::GetUserLevel($nowUserLevelId);

            if ($nowUserLevel < 0) {
                $nowUserLevel = 0;
            }

            //********是否降级*******//
            $sql = "SELECT * FROM " . self::TableName_UserLevel . " WHERE UserLevel=:UserLevel AND SiteId=:SiteId Limit 1;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserLevel", $nowUserLevel);
            $dataProperty->AddField("SiteId", $siteId);
            $arrNowUserLevel = $this->dbOperator->GetOne($sql, $dataProperty);
            $canDegrade = FALSE;
            if (count($arrNowUserLevel) > 0) {
                $limitForumPost = intval($arrNowUserLevel["LimitForumPost"]);
                $limitBestForumPost = intval($arrNowUserLevel["LimitBestForumPost"]);
                $limitScore = intval($arrNowUserLevel["LimitScore"]);
                $limitMoney = intval($arrNowUserLevel["LimitMoney"]);
                $limitCharm = intval($arrNowUserLevel["LimitCharm"]);
                $limitExp = intval($arrNowUserLevel["LimitExp"]);
                $limitPoint = intval($arrNowUserLevel["LimitPoint"]);
                $limitUserAlbum = intval($arrNowUserLevel["LimitUserAlbum"]);
                $limitBestUserAlbum = intval($arrNowUserLevel["LimitBestUserAlbum"]);
                $limitRecUserAlbum = intval($arrNowUserLevel["LimitRecUserAlbum"]);
                $limitUserAlbumCommentCount = intval($arrNowUserLevel["LimitUserAlbumCommentCount"]);

                $userInfoManageData = new UserInfoManageData();
                $arrUserInfo = $userInfoManageData->GetOne($userId, $siteId);

                $userPostCount = intval($arrUserInfo["UserPostCount"]);
                $userPostBestCount = intval($arrUserInfo["UserPostBestCount"]);
                $userScore = intval($arrUserInfo["UserScore"]);
                $userMoney = intval($arrUserInfo["UserMoney"]);
                $userCharm = intval($arrUserInfo["UserCharm"]);
                $userExp = intval($arrUserInfo["UserExp"]);
                $userPoint = intval($arrUserInfo["UserPoint"]);
                $userAlbumCount = intval($arrUserInfo["UserAlbumCount"]);
                $userBestAlbumCount = intval($arrUserInfo["UserBestAlbumCount"]);
                $userRecAlbumCount = intval($arrUserInfo["UserRecAlbumCount"]);
                $userAlbumCommentCount = intval($arrUserInfo["UserAlbumCommentCount"]);


                if (!$canDegrade && $limitForumPost > 0) { //设置了发帖数升级条件
                    if ($userPostCount < $limitForumPost) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitBestForumPost > 0) { //设置了精华发帖数升级条件
                    if ($userPostBestCount < $limitBestForumPost) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitScore > 0) { //设置了积分升级条件
                    if ($userScore < $limitScore) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitMoney > 0) { //设置了金钱升级条件
                    if ($userMoney < $limitMoney) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitCharm > 0) { //设置了金钱升级条件
                    if ($userCharm < $limitCharm) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitExp > 0) { //设置了经验升级条件
                    if ($userExp < $limitExp) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitPoint > 0) { //设置了点券升级条件
                    if ($userPoint < $limitPoint) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitUserAlbum > 0) { //设置了相册数升级条件
                    if ($userAlbumCount < $limitUserAlbum) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitBestUserAlbum > 0) { //设置了精华相册数升级条件
                    if ($userBestAlbumCount < $limitBestUserAlbum) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitRecUserAlbum > 0) { //设置了相册专家推荐分数升级条件
                    if ($userRecAlbumCount < $limitRecUserAlbum) {
                        $canDegrade = TRUE; //需要降级
                    }
                }
                if (!$canDegrade && $limitUserAlbumCommentCount > 0) { //设置了相册评论支持票数升级条件
                    if ($userAlbumCommentCount < $limitUserAlbumCommentCount) {
                        $canDegrade = TRUE; //需要降级
                    }
                }

                if ($canDegrade) {
                    //进行降级
                    $sql = "SELECT * FROM " . self::TableName_UserLevel . " WHERE UserLevel<:UserLevel AND SiteId=:SiteId ORDER BY UserLevel DESC LIMIT 1;";
                    $arrPreUserLevel = $dbOperator->GetArray($sql, $dataProperty);
                    if (count($arrPreUserLevel) > 0) { //有上一级
                        $preUserLevelId = intval($arrPreUserLevel["UserLevelID"]);
                        $preUserGroupId = intval($arrPreUserLevel["UpdateToUserGroupId"]);
                        $userSiteLevelManageData = new UserSiteLevelManageData();
                        $result = $userSiteLevelManageData->ModifyUserLevelId($userId, $siteId, $preUserLevelId);
                        if ($result > 0) {
                            //修改会员用户组
                            $userRoleManageData = new UserRoleManageData();
                            $nowUserGroupId = $userRoleManageData->GetUserGroupId($userId, $siteId);
                            $userGroupManageData = new UserGroupManageData();
                            $isLock = $userGroupManageData->GetIsLock($nowUserGroupId);

                            if ($isLock <= 0 && $preUserGroupId > 0 && $siteId > 0) { //当前身份未锁定时才操作等级
                                $userRoleManageData->CreateOrModify($userId, $preUserGroupId, $siteId);
                            }
                        }
                    }
                }
            }

            if (!$canDegrade) { //不降级时，才计算升级条件
                //********升级********//
                //查询下一级的各种限制条件参数
                $sql = "SELECT * FROM " . self::TableName_UserLevel . " WHERE UserLevel>:UserLevel AND SiteId=:SiteId ORDER BY UserLevel LIMIT 1;";
                $arrNextUserLevel = $dbOperator->ReturnRow($sql, $dataProperty);

                if (count($arrNextUserLevel) > 0) {//还有下一级，可以升级
                    $limitForumPost = intval($arrNextUserLevel["LimitForumPost"]);
                    $limitBestForumPost = intval($arrNextUserLevel["LimitBestForumPost"]);
                    $limitScore = intval($arrNextUserLevel["LimitScore"]);
                    $limitMoney = intval($arrNextUserLevel["LimitMoney"]);
                    $limitCharm = intval($arrNextUserLevel["LimitCharm"]);
                    $limitExp = intval($arrNextUserLevel["LimitExp"]);
                    $limitPoint = intval($arrNextUserLevel["LimitPoint"]);
                    $limitUserAlbum = intval($arrNextUserLevel["LimitUserAlbum"]);
                    $limitBestUserAlbum = intval($arrNextUserLevel["LimitBestUserAlbum"]);
                    $limitRecUserAlbum = intval($arrNextUserLevel["LimitRecUserAlbum"]);
                    $limitUserAlbumCommentCount = intval($arrNextUserLevel["LimitUserAlbumCommentCount"]);
                    $nextUserLevelId = intval($arrNextUserLevel["UserLevelID"]);
                    $nextUserGroupId = intval($arrNextUserLevel["UpdateToUserGroupId"]);

                    $userInfoManageData = new UserInfoManageData();
                    $arrUserInfo = $userInfoManageData->GetOne($userId, $siteId);

                    $userPostCount = intval($arrUserInfo["UserPostCount"]);
                    $userPostBestCount = intval($arrUserInfo["UserPostBestCount"]);
                    $userScore = intval($arrUserInfo["UserScore"]);
                    $userMoney = intval($arrUserInfo["UserMoney"]);
                    $userCharm = intval($arrUserInfo["UserCharm"]);
                    $userExp = intval($arrUserInfo["UserExp"]);
                    $userPoint = intval($arrUserInfo["UserPoint"]);
                    $userAlbumCount = intval($arrUserInfo["UserAlbumCount"]);
                    $userBestAlbumCount = intval($arrUserInfo["UserBestAlbumCount"]);
                    $userRecAlbumCount = intval($arrUserInfo["UserRecAlbumCount"]);
                    $userAlbumCommentCount = intval($arrUserInfo["UserAlbumCommentCount"]);

                    $canUpgrade = true;
                    if ($canUpgrade && $limitForumPost > 0) { //设置了发帖数升级条件
                        if ($userPostCount < $limitForumPost) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitBestForumPost > 0) { //设置了精华发帖数升级条件
                        if ($userPostBestCount < $limitBestForumPost) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitScore > 0) { //设置了积分升级条件
                        if ($userScore < $limitScore) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitMoney > 0) { //设置了金钱升级条件
                        if ($userMoney < $limitMoney) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitCharm > 0) { //设置了金钱升级条件
                        if ($userCharm < $limitCharm) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitExp > 0) { //设置了经验升级条件
                        if ($userExp < $limitExp) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitPoint > 0) { //设置了点券升级条件
                        if ($userPoint < $limitPoint) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitUserAlbum > 0) { //设置了相册数升级条件
                        if ($userAlbumCount < $limitUserAlbum) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitBestUserAlbum > 0) { //设置了精华相册数升级条件
                        if ($userBestAlbumCount < $limitBestUserAlbum) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitRecUserAlbum > 0) { //设置了相册专家推荐分数升级条件
                        if ($userRecAlbumCount < $limitRecUserAlbum) {
                            $canUpgrade = false; //不能升级
                        }
                    }
                    if ($canUpgrade && $limitUserAlbumCommentCount > 0) { //设置了相册评论支持票数升级条件
                        if ($userAlbumCommentCount < $limitUserAlbumCommentCount) {
                            $canUpgrade = false; //不能升级
                        }
                    }

                    if ($canUpgrade) {
                        //进行升级
                        $userSiteLevelManageData = new UserSiteLevelManageData();
                        $result = $userSiteLevelManageData->ModifyUserLevelId($userId, $siteId, $nextUserLevelId);
                        if ($result > 0) {
                            //修改会员用户组
                            $userRoleManageData = new UserRoleManageData();
                            $nowUserGroupId = $userRoleManageData->GetUserGroupID($userId, $siteId);
                            $userGroupManageData = new UserGroupManageData();
                            $isLock = $userGroupManageData->GetIsLock($nowUserGroupId);

                            if ($isLock <= 0 && $nextUserGroupId>0 && $siteId>0) { //当前身份未锁定时才操作等级
                                $userRoleManageData->CreateOrModify($userId, $nextUserGroupId, $siteId);
                            }
                        }
                    }
                } else {
                    //已经最高级了
                    $result = -10;
                }
            }
        }
        return $result;
    }

    public function GetIsLock($userLevelId){
        $sql = "SELECT IsLock FROM " . self::TableName_UserLevel . " WHERE UserLevelId=:UserLevelId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserLevelId", $userLevelId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得等级数字
     * @param int $userLevelId 等级Id
     * @return  等级数字
     */
    public function GetUserLevel($userLevelId){
        $sql = "SELECT UserLevel FROM " . self::TableName_UserLevel . " WHERE UserLevelId=:UserLevelId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserLevelId", $userLevelId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }
}