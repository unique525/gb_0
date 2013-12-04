<?php

/**
 * 后台资讯数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsData extends BaseFrontData {
    /**
     * 表名
     */
    const tableName = "cst_documentnews";

    /**
     * 表关键字段名
     */
    const tableIdName = "documentnewsid";

    /**
     * 新增
     * @param string $titlePicPath 题图1
     * @param string $titlePicPath2 题图2
     * @param string $titlePicPath3 题图3
     * @param string $titlePicMobile 生成移动题图（移动客户端）
     * @param string $titlePicPad 生成移动题图（平板电脑）
     * @return int 返回新增的编号
     */
    public function Create($titlePicPath = "", $titlePicPath2 = "", $titlePicPath3 = "", $titlePicMobile = "", $titlePicPad = "") {
        $dataProperty = new DataProperty();
        $addFieldValues = array($titlePicPath, $titlePicPath2, $titlePicPath3, $titlePicMobile, $titlePicPad);
        $addFieldNames = array("TitlePic", "TitlePic2", "TitlePic3", "TitlePicMobile", "TitlePicPad");

        $sql = parent::GetInsertSql(self::tableName, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sql, $dataProperty);
        if ($result > 0) {
            
        }
        return $result;
    }

    /**
     * 新闻类投稿入库
     * @param <type> $userId
     * @param <type> $userName
     * @param <type> $siteId
     * @param <type> $documentChannelId
     * @param <type> $documentNewsTitle
     * @param <type> $documentNewsContent
     * @param <type> $createdate
     * @return int
     */
    public function CreateTg($userId, $userName, $siteId, $documentChannelId, $documentNewsTitle, $documentNewsContent, $createDate) {
        $sql = "INSERT INTO " . self::tableName . " (userid,username,siteid,documentchannelid,documentnewstitle,documentnewscontent,createdate) values (:userid,:username,:siteid,:documentchannelid,:documentnewstitle,:documentnewscontent,:createdate)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("userid", $userId);
        $dataProperty->AddField("username", $userName);
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("documentnewstitle", $documentNewsTitle);
        $dataProperty->AddField("documentnewscontent", $documentNewsContent);
        $dataProperty->AddField("createdate", $createDate);
        $dboperator = DBOperator::getInstance();
        $newUserId = $dboperator->LastInsertId($sql, $dataProperty);
        if ($newUserId > 0) {
            
        } else {
            $newUserId = 0;          //数据INSERT失败
        }
        return $newUserId;
    }

    /**
     * 修改
     * @param int $documentNewsId 资讯编号
     * @param string $titlePicPath 题图1
     * @param string $titlePicPath2 题图2
     * @param string $titlePicPath3 题图3
     * @return int 返回执行结果(影响的行数)
     */
    public function Modify($documentNewsId, $titlePicPath = "", $titlePicPath2 = "", $titlePicPath3 = "", $titlePicMobile = "", $titlePicPad = "") {
        $dataProperty = new DataProperty();
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($titlePicPath)) {
            $addFieldNames[] = "TitlePic";
            $addFieldValues[] = $titlePicPath;
        }
        if (!empty($titlePicPath2)) {
            $addFieldNames[] = "TitlePic2";
            $addFieldValues[] = $titlePicPath2;
        }
        if (!empty($titlePicPath3)) {
            $addFieldNames[] = "TitlePic3";
            $addFieldValues[] = $titlePicPath3;
        }
        if (!empty($titlePicMobile)) {
            $addFieldNames[] = "TitlePicMobile";
            $addFieldValues[] = $titlePicMobile;
        }
        if (!empty($titlePicPad)) {
            $addFieldNames[] = "TitlePicPad";
            $addFieldValues[] = $titlePicPad;
        }
        $sql = parent::GetUpdateSql(self::tableName, self::tableIdName, $documentNewsId, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);

        if ($result > 0) {
            if (isset($_POST['c_ShowPicMethod']) && $_POST['c_ShowPicMethod'] == 'on') {
                $tableType = 1; //DOCNEWS
                $isBatchUpload = 1;
                $uploadFileData = new UploadFileData();
                $uploadFileData->ModifyIsBatchUpload($tableType, $documentNewsId, $isBatchUpload);
            }
        }

        return $result;
    }

    /**
     * 修改发布时间
     * @param string $publishDate 新的发布时间
     * @param int $documentNewsId 资讯编号
     * @return int 返回执行结果(影响的行数)
     */
    public function ModifyPublishDate($publishDate, $documentNewsId) {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `PublishDate`=:publishdate Where documentnewsid=:documentnewsid";
            $dataProperty->AddField("publishdate", $publishDate);
            $dataProperty->AddField("documentnewsid", $documentNewsId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改创建时间
     * @param string $createDate 新的创建时间
     * @param int $documentNewsId 资讯编号
     * @return int 返回执行结果(影响的行数)
     */
    /*
    public function ModifyCreateDate($createDate, $documentNewsId) {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `CreateDate`=:CreateDate Where documentnewsid=:documentnewsid";
            $dataProperty->AddField("CreateDate", $createDate);
            $dataProperty->AddField("documentnewsid", $documentNewsId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
    */

    /**
     * 修改锁定状态和时间
     * @param type $lockEdit
     * @param type $documentNewsId
     * @param type $adminUserId
     * @return type
     */
    public function ModifyLockEdit($lockEdit, $documentNewsId, $adminUserId) {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `LockEdit`=:LockEdit,LockEditDate=now(),LockEditadminuserid=:LockEditadminuserid Where documentnewsid=:documentnewsid";
            $dataProperty->AddField("LockEdit", $lockEdit);
            $dataProperty->AddField("documentnewsid", $documentNewsId);
            $dataProperty->AddField("LockEditadminuserid", $adminUserId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 增加一个点击数
     * @param type $documentNewsId
     * @return type 
     */
    public function AddHit($documentNewsId) {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `Hit`=`Hit`+1 Where documentnewsid=:documentnewsid";
            $dataProperty->AddField("documentnewsid", $documentNewsId);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取点击数
     * @param type $documentNewsId
     * @return type 
     */
    public function GetHit($documentNewsId) {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "select Hit from " . self::tableName . " Where documentnewsid=:documentnewsid";
            $dataProperty->AddField("documentnewsid", $documentNewsId);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改sort
     * @param type $value
     * @param type $documentNewsId
     * @return type 
     */
    public function ModifySort($value, $documentNewsId) {
        $result = 0;
        if ($documentNewsId > 0) {

            $documentchannelid = $this->GetDocumentChannelID($documentNewsId);

            $nowsort = $this->GetSort($documentNewsId);

            if ($value > 0) { //向上移动
                $dataProperty = new DataProperty();
                $sql = "SELECT sort FROM " . self::tableName . " where documentchannelid=:documentchannelid and documentnewsid<>:documentnewsid and sort>=:nowsort order by sort desc limit 1";
                $dataProperty->AddField("documentchannelid", $documentchannelid);
                $dataProperty->AddField("documentnewsid", $documentNewsId);
                $dataProperty->AddField("nowsort", $nowsort);
                $dboperator = DBOperator::getInstance();
                $newsort = $dboperator->ReturnInt($sql, $dataProperty);
            } else if ($value < 0) {//向下移动
                $dataProperty = new DataProperty();
                $sql = "SELECT sort FROM " . self::tableName . " where documentchannelid=:documentchannelid and documentnewsid<>:documentnewsid and sort<=:nowsort order by sort limit 1";
                $dataProperty->AddField("documentchannelid", $documentchannelid);
                $dataProperty->AddField("documentnewsid", $documentNewsId);
                $dataProperty->AddField("nowsort", $nowsort);
                $dboperator = DBOperator::getInstance();
                $newsort = $dboperator->ReturnInt($sql, $dataProperty);
            }

            if ($newsort < 0) {
                $newsort = 0;
            }

            $newsort = $newsort + $value;


            //2011.12.8 zc 排序号禁止负数
            if ($newsort < 0) {
                $newsort = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `Sort`=:value Where documentnewsid=:documentnewsid";
            $dataProperty->AddField("value", $newsort);
            $dataProperty->AddField("documentnewsid", $documentNewsId);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 拖动排序
     * @param type $value
     * @param type $tableidvalue
     * @return type 
     */
    public function UpdateSort($arr_docnewsid) {
        if (count($arr_docnewsid) > 1) { //大于1条时排序才有意义
            $str_docnewsid = join(',', $arr_docnewsid);

            $max_sort = 0;
            $sql = "select max(sort) from " . self::tableName . " where documentnewsid in (" . $str_docnewsid . ")";
            $dboperator = DBOperator::getInstance();
            $max_sort = $dboperator->ReturnInt($sql, null);

            for ($i = 0; $i < count($arr_docnewsid); $i++) {
                $newsort = $max_sort - $i;
                if ($newsort < 0) {
                    $newsort = 0;
                }
                $sql = "UPDATE " . self::tableName . " SET sort=" . $newsort . " WHERE documentnewsid=" . $arr_docnewsid[$i];
                $dboperator->Execute($sql, null);
            }
        }
    }

    /**
     * 新增文档时修改排序号到当前频道的最大排序
     * @param type $documentchannelid
     * @param type $documentNewsId 
     */
    public function UpdateSortWhenAdd($documentchannelid, $documentNewsId) {
        $max_sort = 0;
        $sql = "select max(sort) from " . self::tableName . " where documentchannelid=" . $documentchannelid . "";
        $dboperator = DBOperator::getInstance();
        $max_sort = $dboperator->ReturnInt($sql, null);
        $newsort = $max_sort + 1;
        $sql = "UPDATE " . self::tableName . " SET sort=" . $newsort . " WHERE documentnewsid=" . $documentNewsId;
        $dboperator->Execute($sql, null);
    }

    /**
     * 批量修改频道ID,移动功能
     * @param <type> $documentChannelId
     * @param <type> $ids
     * @return <type>
     */
    public function Move($documentChannelId, $ids) {
        $maxSort = self::GetMaxSort($documentChannelId);

        if ($maxSort < 0) {
            $maxSort = 0;
        } else {
            $maxSort++;
        }

        $sql = "UPDATE " . self::tableName . " SET `documentchannelid`=:documentchannelid,Sort=" . $maxSort . " where documentnewsid in (" . $ids . ")";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);







        return $result;
    }

    /**
     * 批量复制文档
     * @param type $documentChannelId
     * @param type $ids
     * @return type 
     */
    public function Copy($documentChannelId, $ids, $adminUserId, $adminUserName) {

        $arr_newsid = Format::ToSplit($ids, ',');

        if (!empty($arr_newsid)) {

            $maxSort = self::GetMaxSort($documentChannelId);

            if ($maxSort < 0) {
                $maxSort = 0;
            } else {
                $maxSort++;
            }

            for ($i = 0; $i < count($arr_newsid); $i++) {

                if (!empty($arr_newsid[$i])) {
                    $sql = "
                    INSERT INTO cst_documentnews (
                    	SiteID, 
                    	DocumentChannelID, 
                    	DocumentNewsTitle, 
                    	DocumentNewsSubTitle, 
                    	DocumentNewsCiteTitle, 
                    	DocumentNewsShortTitle, 
                    	DocumentNewsIntro, 
                    	CreateDate, 
                    	AdminUserID, 
                    	AdminUserName, 
                    	UserID, 
                    	UserName, 
                    	Author, 
                    	State, 
                    	DocumentNewsType, 
                    	DirectUrl, 
                    	DocumentNewsContent, 
                    	PublishDate, 
                    	ShowDate, 
                    	SourceName, 
                    	DocumentNewsMainTag, 
                    	DocumentNewsTag, 
                    	Sort, 
                    	TitlePic, 
                    	DocumentNewsTitleColor, 
                    	DocumentNewsTitleBold, 
                    	OpenComment, 
                    	ShowHour, 
                    	ShowMinute, 
                    	ShowSecond, 
                    	UploadFiles, 
                    	IsHot, 
                    	RecLevel, 
                    	WaitPublish, 
                    	ShowPicMethod,
                    	IsCopy
                    	)

                        SELECT  
                    	SiteID, 
                    	" . $documentChannelId . ", 
                    	DocumentNewsTitle, 
                    	DocumentNewsSubTitle, 
                    	DocumentNewsCiteTitle, 
                    	DocumentNewsShortTitle, 
                    	DocumentNewsIntro, 
                    	now(), 
                    	" . $adminUserId . ", 
                    	'" . $adminUserName . "', 
                    	UserID, 
                    	UserName, 
                    	Author, 
                    	0, 
                    	DocumentNewsType, 
                    	DirectUrl, 
                    	DocumentNewsContent, 
                    	'', 
                    	CURRENT_DATE(), 
                    	SourceName, 
                    	DocumentNewsMainTag, 
                    	DocumentNewsTag, 
                    	" . $maxSort . ", 
                    	TitlePic, 
                    	DocumentNewsTitleColor, 
                    	DocumentNewsTitleBold, 
                    	OpenComment, 
                    	hour(now()), 
                    	minute(now()), 
                    	second(now()), 
                    	UploadFiles, 
                    	IsHot, 
                    	RecLevel, 
                    	WaitPublish, 
                    	ShowPicMethod,
                    	1
	 
                        FROM cst_documentnews WHERE DocumentNewsID = " . $arr_newsid[$i] . ";            

                    ";

                    $dboperator = DBOperator::getInstance();
                    $new_newsid = $dboperator->LastInsertId($sql, null);
                    $result = $new_newsid;
                    if ($new_newsid > 0) {
                        //操作上传的附件
                        $sql_uploadfiles = "select uploadfiles from cst_documentnews where DocumentNewsID=" . $arr_newsid[$i] . "";
                        $uploadfiles = $dboperator->ReturnString($sql_uploadfiles, null);

                        if (!empty($uploadfiles)) {
                            $arr_fileid = Format::ToSplit($uploadfiles, ',');
                            $new_uploadfiles = "";
                            for ($j = 0; $j < count($arr_fileid); $j++) {
                                if (!empty($arr_fileid[$j])) {
                                    //复制uploadfile
                                    $sql_copyfile = "
                                    INSERT INTO cst_uploadfile 
                                    ( 
                                    	UploadFileName, 
                                    	UploadFileSize, 
                                    	UploadFileType, 
                                    	UploadFileOrgName, 
                                    	UploadFilePath, 
                                    	UploadFileTitle, 
                                    	UploadFileInfo, 
                                    	TableType, 
                                    	TableID, 
                                    	UploadYear, 
                                    	UploadMonth, 
                                    	UploadDay, 
                                    	AdminUserID, 
                                    	UserID, 
                                    	CreateDate, 
                                    	IsBatchUpload
                                    )
	
                                    SELECT 

                                    UploadFileName, 
                                    	UploadFileSize, 
                                    	UploadFileType, 
                                    	UploadFileOrgName, 
                                    	UploadFilePath, 
                                    	UploadFileTitle, 
                                    	UploadFileInfo, 
                                    	TableType, 
                                    	" . $new_newsid . ", 
                                    	UploadYear, 
                                    	UploadMonth, 
                                    	UploadDay, 
                                    	AdminUserID, 
                                    	UserID, 
                                    	CreateDate, 
                                    	IsBatchUpload
        
                                    FROM cst_uploadfile WHERE UploadFileID=" . $arr_fileid[$j] . ";
    
                                    ";
                                    $new_fileid = $dboperator->LastInsertId($sql_copyfile, null);

                                    $new_uploadfiles = $new_uploadfiles . "," . $new_fileid;
                                }
                            }

                            //修改news表的uploadfiles
                            $sql_modify_uploadfiles = "UPDATE cst_documentnews SET uploadfiles='" . $new_uploadfiles . "' WHERE documentnewsid=" . $new_newsid . "";
                            $dboperator->Execute($sql_modify_uploadfiles, null);
                        }
                    }
                }
            }
        }





        return $result;
    }

    /**
     * 取得一条数据
     * @param <type> $documentNewsId
     * @return <type>
     */
    public function GetOne($documentNewsId) {
        $sql = "select * from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得列表，根据频道ID
     * @param <type> $documentchannelid
     * @return <type>
     */
    public function GetList($documentchannelid) {
        $sql = "select dn.*,s.siteurl from " . self::tableName . " dn,cst_documentchannel dc,cst_site s where dn.documentchannelid=dc.documentchannelid and dc.siteid=s.siteid and dn.documentchannelid=:documentchannelid and dn.state<100 order by dn.createdate desc";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 未发布的所有文档id列表,批量发布用
     * @param <type> $state
     * @param <type> $limit
     * @param <type> $siteid 默认全部
     * @return <type>
     */
    public function GetIDListAll($state, $limit, $siteid = 0) {
        if ($siteid <= 0) {
            $sql = "select documentnewsid from " . self::tableName . " where state=:state and waitpublish=1 order by createdate desc limit 0," . $limit . "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
        } else {
            $sql = "select documentnewsid from " . self::tableName . " where siteid=:siteid AND state=:state and waitpublish=1 order by createdate desc limit 0," . $limit . "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("siteid", $siteid);
            $dataProperty->AddField("state", $state);
        }
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得等待发布的文档数量
     * @param type $state
     * @param type $siteid
     * @return type 
     */
    public function GetIDListCountAll($state, $siteid = 0) {
        if ($siteid <= 0) {
            $sql = "select count(*) from " . self::tableName . " where state=:state and waitpublish=1";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
        } else {
            $sql = "select count(*) from " . self::tableName . " where siteid=:siteid AND state=:state and waitpublish=1";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("siteid", $siteid);
            $dataProperty->AddField("state", $state);
        }
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据ID字条串取得标题数组
     * @param <type> $ids
     * @return <type>
     */
    public function GetTitleListByIDs($ids) {
        $sql = "select documentnewstitle from " . self::tableName . " where documentnewsid in (" . $ids . ") and state<100 order by createdate desc";
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, null);
        return $result;
    }

    /**
     * 取得最新文档列表
     * @param <type> $documentchannelid
     * @param int $topcount
     * @param <type> $state
     * @return <type>
     */
    public function GetNewList($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsContent,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where documentchannelid=:documentchannelid and state=:state order by sort desc, createdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    public function GetOldList($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where documentchannelid=:documentchannelid and state=:state order by sort, createdate limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    public function GetListByHit($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where documentchannelid=:documentchannelid and state=:state order by hit desc, createdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得当前频道下一级子频道的最新文档列表,按排序号，创建时间排
     * @param type $documentchannelid
     * @param type $topcount
     * @param type $state
     * @return type 
     */
    public function GetSubNewList($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where (documentchannelid=:documentchannelid or documentchannelid in (select documentchannelid from cst_documentchannel where parentid=:documentchannelid)) and state=:state order by sort desc, createdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    public function GetSubListByHit($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where (documentchannelid=:documentchannelid or documentchannelid in (select documentchannelid from cst_documentchannel where parentid=:documentchannelid)) and state=:state order by hit desc, createdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得当前频道的最新文档列表,按创建时间排
     * @param type $documentchannelid
     * @param type $topcount
     * @param type $state
     * @return type 
     */
    public function GetNewListByCreateDate($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where (documentchannelid=:documentchannelid or documentchannelid in (select documentchannelid from cst_documentchannel where parentid=:documentchannelid)) and state=:state order by createdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得当前频道下一级子频道的最新文档列表,按创建时间排
     * @param type $documentchannelid
     * @param type $topcount
     * @param type $state
     * @return type 
     */
    public function GetSubNewListByCreateDate($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where (documentchannelid=:documentchannelid or documentchannelid in (select documentchannelid from cst_documentchannel where parentid=:documentchannelid)) and state=:state order by createdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得最新文档列表
     * @param <type> $documentchannelid
     * @param int $topcount
     * @param <type> $state
     * @return <type>
     */
    public function GetCodeList($documentchannelid, $topcount, $state) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        $sql = "select $selectColumn from " . self::tableName . " where documentchannelid=:documentchannelid and state=:state order by documentnewscitetitle asc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据主关键字取得相关文档列表
     * @param type $topcount
     * @param type $state
     * @param type $aboutnewsid
     * @return type 
     */
    public function GetAboutList($topcount, $state, $aboutnewsid) {
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        $siteid = self::GetSiteID($aboutnewsid);
        $documentnewsmaintag = self::GetDocumentNewsMainTag($aboutnewsid);
        $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

        if ($siteid > 0 && strlen($documentnewsmaintag) > 0) {

            $sql = "select $selectColumn from " . self::tableName . " where documentnewsid<>:aboutnewsid and siteid=:siteid and state=:state and documentnewsmaintag like '%" . $documentnewsmaintag . "%' order by createdate desc limit " . $topcount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("aboutnewsid", $aboutnewsid);
            $dataProperty->AddField("siteid", $siteid);
            $dataProperty->AddField("state", $state);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnArray($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据关键字取得文档列表
     * @param type $topcount
     * @param type $state
     * @param type $aboutnewsid
     * @return type 
     */
    public function GetBySearchKeyList($topcount, $state, $searchkey) {

        $searchkey = mysql_escape_string($searchkey);
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        if (strlen($searchkey) > 0) {


            $sql = "select dn.*,s.siteurl from " . self::tableName . " dn,cst_documentchannel dc,cst_site s WHERE dn.documentchannelid=dc.documentchannelid AND dc.siteid=s.siteid AND dn.state=:state AND dn.documentnewstag like '%" . $searchkey . "%' order by dn.createdate desc limit " . $topcount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnArray($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据推荐级别取得文档列表
     * @param type $topcount
     * @param type $state
     * @param type $between
     * @return type 
     */
    public function GetListByRecLevel($documentchannelid, $topcount, $state, $between) {

        $between = mysql_escape_string($between);
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        //转换documentchannelid到siteid
        $siteid = 0;
        if (strlen(strval($documentchannelid)) > 0) {
            $pos = stripos(strtolower($documentchannelid), "sid_");
            if ($pos !== false) {
                $siteid = intval(str_ireplace("sid_", "", strval($documentchannelid)));
            }
        }


        if (strlen($between) > 0) {
            $arrBetween = Format::ToSplit($between, ',');
            $condition = '';
            $recLevel_0 = false;
            if (count($arrBetween) == 2) {
                $condition = $arrBetween[0] . ' and ' . $arrBetween[1];
                if ($arrBetween[0] == 0 && $arrBetween[1] == 0) {
                    $recLevel_0 = true;
                }
            }

            if ($siteid > 0) {
                $condition = $condition . ' and siteid=' . $siteid;
            } else {
                $condition = $condition . ' and documentchannelid=' . $documentchannelid;
            }
            $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

            if ($recLevel_0) { //推荐数字是0的，还要按sort排序
                $sql = "select $selectColumn from " . self::tableName . " where state=:state and reclevel between " . $condition . " order by reclevel desc,sort desc, createdate desc limit " . $topcount;
            } else {
                $sql = "select $selectColumn from " . self::tableName . " where state=:state and reclevel between " . $condition . " order by reclevel desc, createdate desc limit " . $topcount;
            }
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnArray($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据推荐级别取得文档列表以及文档对应频道名
     * @param type $topcount
     * @param type $state
     * @param type $between
     * @return type 
     */
    public function GetDocAndChannelnameListByRecLevel($documentchannelid, $topcount, $state, $between) {
        $between = mysql_escape_string($between);
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        //转换documentchannelid到siteid
        $siteid = 0;
        if (strlen(strval($documentchannelid)) > 0) {
            $pos = stripos(strtolower($documentchannelid), "sid_");
            if ($pos !== false) {
                $siteid = intval(str_ireplace("sid_", "", strval($documentchannelid)));
            }
        }


        if (strlen($between) > 0) {
            $arrBetween = Format::ToSplit($between, ',');
            $condition = '';
            $recLevel_0 = false;
            if (count($arrBetween) == 2) {
                $condition = $arrBetween[0] . ' and ' . $arrBetween[1];
                if ($arrBetween[0] == 0 && $arrBetween[1] == 0) {
                    $recLevel_0 = true;
                }
            }

            if ($siteid > 0) {
                $condition = $condition . ' and n.siteid=' . $siteid;
            } else {
                $condition = $condition . ' and n.documentchannelid=' . $documentchannelid;
            }
            $selectColumn = 'n.DocumentNewsID,
                             n.SiteID,
                             n.DocumentChannelID,
                             n.DocumentNewsTitle,
                             n.DocumentNewsSubTitle,
                             n.DocumentNewsCiteTitle,
                             n.DocumentNewsShortTitle,
                             n.DocumentNewsIntro,
                             n.CreateDate,
                             n.AdminUserID,
                             n.AdminUserName,
                             n.UserID,
                             n.UserName,
                             n.Author,
                             n.State,
                             n.DocumentNewsType,
                             n.DirectUrl,
                             n.PublishDate,
                             n.ShowDate,
                             n.SourceName,
                             n.DocumentNewsMainTag,
                             n.DocumentNewsTag,
                             n.Sort,
                             n.TitlePic,
                             n.TitlePic2,
                             n.TitlePic3,
                             n.DocumentNewsTitleColor,
                             n.DocumentNewsTitleBold,
                             n.OpenComment,
                             n.ShowHour,
                             n.ShowMinute,
                             n.ShowSecond,
                             n.UploadFiles,
                             n.IsHot,
                             n.RecLevel,
                             n.WaitPublish,
                             n.ShowPicMethod,
                             n.IsCopy,
                             n.IsAddToFullText,
                             n.ClosePosition,
                             n.Hit,
                             n.LockEdit,
                             n.LockEditDate,
                             n.LockEditAdminUserId,
			     c.DocumentChannelname';

            if ($recLevel_0) { //推荐数字是0的，还要按sort排序
                $sql = "select $selectColumn from " . self::tableName . " n, cst_documentchannel c where n.state=:state and n.reclevel between " . $condition . " and c.documentchannelid=n.documentchannelid order by n.reclevel desc,n.sort desc, n.createdate desc limit " . $topcount;
            } else {
                $sql = "select $selectColumn from " . self::tableName . " n, cst_documentchannel c where n.state=:state and n.reclevel between " . $condition . " and c.documentchannelid=n.documentchannelid order by n.reclevel desc, n.createdate desc limit " . $topcount;
            }
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnArray($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据推荐级别取得文档列表（单频道使用）
     * @param type $documentchannelid
     * @param type $topcount
     * @param type $state
     * @param type $between
     * @return type 
     */
    public function GetListByRecLevelOneChannel($documentchannelid, $topcount, $state, $between) {

        $between = mysql_escape_string($between);
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        if (strlen($between) > 0) {
            $arrBetween = Format::ToSplit($between, ',');
            $condition = '';
            if (count($arrBetween) == 2) {
                $condition = $arrBetween[0] . ' and ' . $arrBetween[1];
            }

            if ($documentchannelid > 0) {
                $condition = $condition . ' and documentchannelid=' . $documentchannelid;
            }
            $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

            $sql = "select $selectColumn from " . self::tableName . " where state=:state and reclevel between " . $condition . " order by reclevel desc, createdate desc limit " . $topcount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnArray($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据推荐级别取得子频道文档列表
     * @param type $documentchannelid
     * @param type $topcount
     * @param type $state
     * @param type $between
     * @return type 
     */
    public function GetSubListByRecLevelOneChannel($documentchannelid, $topcount, $state, $between) {

        $between = mysql_escape_string($between);
        if ($topcount == '') {
            $topcount = '10';
        }

        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }

        if (strlen($between) > 0) {
            $arrBetween = Format::ToSplit($between, ',');
            $condition = '';
            if (count($arrBetween) == 2) {
                $condition = $arrBetween[0] . ' and ' . $arrBetween[1];
            }

            if ($documentchannelid > 0) {
                $condition = $condition . ' and documentchannelid in (select documentchannelid from cst_documentchannel where parentid=' . $documentchannelid . ') ';
            }
            $selectColumn = 'DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,AdminUserID,AdminUserName,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,IsAddToFullText,ClosePosition,Hit,LockEdit,LockEditDate,LockEditAdminUserId';

            $sql = "select $selectColumn from " . self::tableName . " where state=:state and reclevel between " . $condition . " order by reclevel desc, createdate desc limit " . $topcount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnArray($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 取得分页列表
     * @param <type> $documentchannelid
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $searchkey
     * @return <type>
     */
    public function GetListPager($documentchannelid, $pagebegin, $pagesize, &$allcount, $searchkey, $searchTypeBox = "", $type = '') {

        $searchsql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            if ($searchTypeBox == "source") {
                $searchsql = " and (sourcename like :searchkey)";
                $dataProperty->AddField("searchkey", "%" . $searchkey . "%");
            } else {
                $searchsql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3 or documentnewstag like :searchkey4)";
                $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
                $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
                $dataProperty->AddField("searchkey3", "%" . $searchkey . "%");
                $dataProperty->AddField("searchkey4", "%" . $searchkey . "%");
            }
        }
        if ($type === 'self') {
            $adminuserid = Control::GetAdminUserID();
            $condition_adminuserid = ' and adminuserid=' . $adminuserid;
        }

        $sql = "
            SELECT
            documentnewsid,documentnewstype,documentnewstitle,state,sort,documentchannelid,publishdate,
            createdate,adminuserid,adminusername,username,documentnewstitlecolor,documentnewstitlebold,titlepic,reclevel,hit
            FROM
            " . self::tableName . "
            WHERE documentchannelid=:documentchannelid and state<100 " . $searchsql . " " . $condition_adminuserid . " order by sort desc, CreateDate desc limit " . $pagebegin . "," . $pagesize . "";



        $dboperator = DBOperator::getInstance();

        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchkey . "%");
        }

        $sql = "SELECT count(*) FROM cst_documentnews WHERE documentchannelid=:documentchannelid and state<100 " . $condition_adminuserid . " " . $searchsql;
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 前台用的分页JSON数据表
     * @param <type> $documentchannelid
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $searchkey
     * @return <type>
     */
    public function GetFrontListPager($documentchannelid, $pagebegin, $pagesize, &$allcount, $searchkey) {

        $state = 30; //只显示已发布的
        $searchsql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3 or author like :searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey4", "%" . $searchkey . "%");
        }

        $sql = "
            SELECT
            documentnewsid,documentnewstype,documentnewstitle,publishdate,titlepic,titlepic2,showdate,documentnewssubtitle,documentnewsshorttitle,documentnewsintro,documentchannelid,documentnewstag,showhour,showminute,documentnewscontent 
            FROM
            " . self::tableName . "
            WHERE documentchannelid=:documentchannelid and state=" . $state . " " . $searchsql . " order by sort desc, PublishDate desc limit " . $pagebegin . "," . $pagesize . "";


        $dboperator = DBOperator::getInstance();

        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3 or author like :searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey4", "%" . $searchkey . "%");
        }

        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE documentchannelid=:documentchannelid and state=" . $state . " " . $searchsql;
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 网友投稿列表
     * @param int $documentChannelId 频道ID号
     * @param int $pageBegin 开始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param int $userId 用户ID
     * @return arr 返回数据结果集 
     */
    public function GetTgFrontListPager($documentChannelId, $pageBegin, $pageSize, &$allCount, $userId = 0) {
        $dataProperty = new DataProperty();
        if ($userId > 0) {
            $searchSql = " and userid=:userid";
            $dataProperty->AddField("userid", $userId);
        }
        $sql = "SELECT documentnewsid,documentnewstitle,createdate,state,publishdate,documentchannelid FROM " . self::tableName . " WHERE documentchannelid=:documentchannelid  " . $searchSql . " order by sort desc, createdate desc limit " . $pageBegin . "," . $pageSize . "";
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE documentchannelid=:documentchannelid  " . $searchSql;
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $allCount = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 前台用的分页JSON数据表
     * @param <type> $documentchannelid
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $searchkey
     * @return <type>
     */
    public function GetFrontSubChannelDocListPager($documentchannelid, $pagebegin, $pagesize, &$allcount, $searchkey) {

        $state = 30; //只显示已发布的
        $searchsql = "";
        $dataProperty = new DataProperty();



        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchkey . "%");
        }

        if ($documentchannelid > 0) {
            $searchsql .= ' and documentchannelid in (select documentchannelid from cst_documentchannel where parentid=' . $documentchannelid . ') ';
        } else {
            $searchsql .= ' and documentchannelid = 0 ';
        }

        $sql = "
            SELECT
            documentnewsid,documentnewstype,documentnewstitle,publishdate,titlepic,titlepic2,showdate,documentnewssubtitle,documentnewsshorttitle,documentnewsintro,documentchannelid,documentnewstag,showhour,showminute,documentnewscontent,adminusername 
            FROM
            " . self::tableName . "
            WHERE state=" . $state . " " . $searchsql . " order by sort desc, PublishDate desc limit " . $pagebegin . "," . $pagesize . "";


        $dboperator = DBOperator::getInstance();

        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchkey . "%");
        }

        if ($documentchannelid > 0) {
            $searchsql .= ' and documentchannelid in (select documentchannelid from cst_documentchannel where parentid=' . $documentchannelid . ') ';
        } else {
            $searchsql .= ' and documentchannelid = 0 ';
        }

        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE state=" . $state . " " . $searchsql;
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);

        return $result;
    }

    public function GetFrontListForRSS($documentChannelId = 0, $createDate = "", $documentNewsType = -1) {
        $state = 30; //只显示已发布的
        $dataProperty = new DataProperty();

        $sqlWhere = "";
        if ($documentChannelId > 0) {
            $dataProperty->AddField("documentchannelid", $documentChannelId);
            $sqlWhere.= " documentchannelid=:documentchannelid AND ";
        }
        if (strlen($createDate) > 0) {
            $dataProperty->AddField("createdate", $createDate);
            $sqlWhere.= " createdate>=:createdate AND ";
        }
        if ($documentNewsType >= 0) {
            $dataProperty->AddField("documentnewstype", $documentNewsType);
            $sqlWhere.= " documentnewstype=:documentnewstype AND ";
        }

        $sql = "
            SELECT
            documentnewsid,documentnewstype,documentnewstitle,publishdate,titlepic,titlepic2,author,showdate,documentnewssubtitle,documentnewsshorttitle,documentnewsintro,documentchannelid,documentnewstag,showhour,showminute,showsecond,documentnewscontent 
            FROM
            " . self::tableName . "
            WHERE $sqlWhere state=$state order by sort desc, PublishDate desc";

        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);

        return $result;
    }

    /**
     * 返回前台数据列表（外部推送使用）
     * @param int $documentChannelId
     * @param string $createDate
     * @param int $documentNewsType
     * @param int $topCount
     * @param int $hasSub 是否调用子频道数据
     * @return array 数据列表
     */
    public function GetFrontListForPush($documentChannelId = 0, $createDate = "", $documentNewsType = -1, $topCount = 0, $hasSub = 0) {
        $state = 30; //只显示已发布的
        $dataProperty = new DataProperty();
        $documentChannelId = intval($documentChannelId);
        $sqlWhere = "";

        Control::GetRequest("sub", 0);

        if ($documentChannelId > 0) {
            $dataProperty->AddField("documentchannelid", $documentChannelId);
            $sqlWhere = " documentchannelid=:documentchannelid AND ";
            if ($hasSub > 0) {
                $sqlWhere = " (documentchannelid=:documentchannelid OR documentchannelid in (select documentchannelid from cst_documentchannel where parentid=$documentChannelId)) AND ";
            }
        }
        if (strlen($createDate) > 0) {
            $dataProperty->AddField("createdate", $createDate);
            $sqlWhere.= " createdate>=:createdate AND ";
        }
        if ($documentNewsType >= 0) {
            $dataProperty->AddField("documentnewstype", $documentNewsType);
            $sqlWhere.= " documentnewstype=:documentnewstype AND ";
        }



        if (intval($topCount) > 0) {
            $sqlLimit = " LIMIT $topCount";
        }

        $sql = "
            SELECT
            DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,DocumentNewsContent,CreateDate,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,TitlePicMobile,TitlePicPad,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,ClosePosition,Hit 
            FROM
            " . self::tableName . "
            WHERE $sqlWhere state=$state order by PublishDate desc $sqlLimit";

        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);

        return $result;
    }

    public function GetFrontOne($documentNewsId) {
        $sql = "select DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,UserID,UserName,Author,State,DocumentNewsType,DirectUrl,PublishDate,ShowDate,SourceName,DocumentNewsMainTag,DocumentNewsTag,Sort,TitlePic,TitlePic2,TitlePic3,TitlePicMobile,TitlePicPad,DocumentNewsTitleColor,DocumentNewsTitleBold,OpenComment,ShowHour,ShowMinute,ShowSecond,UploadFiles,IsHot,RecLevel,WaitPublish,ShowPicMethod,IsCopy,ClosePosition,DocumentNewsContent from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 前台tag搜索用的分页JSON数据表
     * @param <type> $documentchannelid
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $searchkey
     * @return <type>
     */
    public function GetFrontListPagerOfTag($documentchannelid = 0, $pagebegin, $pagesize, &$allcount, $searchkey) {
        $state = 30; //只显示已发布的
        $searchsql = "";
        $dataProperty = new DataProperty();
        //$dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstag like :searchkey1 or documentnewsmaintag like :searchkey2)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
        }
        if ($documentchannelid > 0) {
            $searchsql .= " and documentchannelid = :documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentchannelid);
        }

        $sql = "
            SELECT
            documentnewsid,documentnewstype,documentnewstitle,publishdate,titlepic,titlepic2,showdate,documentnewssubtitle,documentnewsshorttitle,documentnewsintro,documentchannelid,documentnewstag,showhour,showminute,documentnewscontent
            FROM
            " . self::tableName . "
            WHERE state=" . $state . " " . $searchsql . " order by sort desc, PublishDate desc limit " . $pagebegin . "," . $pagesize . "";


        $dboperator = DBOperator::getInstance();

        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $dataProperty = new DataProperty();
        //$dataProperty->AddField("documentchannelid", $documentchannelid);
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql = " and (documentnewstag like :searchkey1 or documentnewsmaintag like :searchkey2)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
        }
        if ($documentchannelid > 0) {
            $searchsql .= " and documentchannelid = :documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentchannelid);
        }
        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE state=" . $state . " " . $searchsql;
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 取得频道ID
     * @param <type> $documentNewsId
     * @return <type>
     */
    public function GetDocumentChannelID($documentNewsId) {
        $sql = "select documentchannelid from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得adminuserid
     * @param <type> $documentNewsId
     * @return <type>
     */
    public function GetAdminUserID($documentNewsId) {
        $sql = "select adminuserid from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得当前状态
     * @param type $documentNewsId
     * @return type 
     */
    public function GetState($documentNewsId) {
        $sql = "select state from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得文档标题
     * @param type $documentNewsId
     * @return type 
     */
    public function GetDocumentNewsTitle($documentNewsId) {
        $sql = "select DocumentNewsTitle from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 是否锁定了编辑
     * @param type $documentNewsId
     * @return type 
     */
    public function GetLockEdit($documentNewsId) {
        $sql = "select LockEdit from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 返回锁定编辑的时间
     * @param type $documentNewsId
     * @return type 
     */
    public function GetLockEditDate($documentNewsId) {
        $sql = "select LockEditDate from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    public function GetLockEditAdminUserId($documentNewsId) {
        $sql = "select LockEditAdminUserId from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得文档简介
     * @param type $documentNewsId
     * @return type 
     */
    public function GetDocumentNewsIntro($documentNewsId) {
        $sql = "select DocumentNewsIntro from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点id
     * @param type $documentNewsId
     * @return type 
     */
    public function GetSiteID($documentNewsId) {
        $sql = "select SiteID from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得主关键字
     * @param type $documentNewsId
     * @return type 
     */
    public function GetDocumentNewsMainTag($documentNewsId) {
        $sql = "select DocumentNewsMainTag from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得发布时间
     * @param type $documentNewsId
     * @return type 
     */
    public function GetPublishDate($documentNewsId) {
        $sql = "select publishdate from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得评论开启状态
     * @param type $documentNewsId
     * @return type 
     */
    public function GetOpenComment($documentNewsId) {
        $sql = "select opencomment from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改文档状态
     * @param <type> $documentNewsId
     * @param <type> $state
     * @return <type>
     */
    public function UpdateState($documentNewsId, $state) {
        $sql = "update " . self::tableName . " set state=:state where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dataProperty->AddField("state", $state);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改文档等待发布状态 0:退出等待发布队列 1:进入等待发布队列
     * @param type $documentNewsId
     * @param type $waitpublish
     * @return type 
     */
    public function UpdateWaitPublish($documentNewsId, $waitpublish) {
        $sql = "update " . self::tableName . " set waitpublish=:waitpublish where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dataProperty->AddField("waitpublish", $waitpublish);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改整个站点的文档等待发布状态
     * @param type $siteid
     * @param type $waitpublish
     * @return type 
     */
    public function UpdateWaitPublishForSite($siteid, $waitpublish) {
        $dataProperty = new DataProperty();
        if ($siteid <= 0) {
            $sql = "update " . self::tableName . " set waitpublish=:waitpublish";
            $dataProperty->AddField("waitpublish", $waitpublish);
        } else {
            $sql = "update " . self::tableName . " set waitpublish=:waitpublish where siteid=:siteid";
            $dataProperty->AddField("siteid", $siteid);
            $dataProperty->AddField("waitpublish", $waitpublish);
        }
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改整个服务器的文档等待发布状态

     * @param type $waitpublish
     * @return type
     */
    public function UpdateWaitPublishForServer($waitpublish) {
        $sql = "update " . self::tableName . " set waitpublish=:waitpublish";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("waitpublish", $waitpublish);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得排序号
     * @param type $documentNewsId 
     */
    public function GetSort($documentNewsId) {
        $sql = "select sort from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        return $dboperator->ReturnInt($sql, $dataProperty);
    }

    public function GetMaxSort($documentChannelId) {
        $sql = "select max(sort) as maxsort from " . self::tableName . " where DocumentChannelId=:DocumentChannelId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentChannelId", $documentChannelId);
        $dboperator = DBOperator::getInstance();
        return $dboperator->ReturnInt($sql, $dataProperty);
    }

    /**
     * 取得资讯内容
     * @param type $documentNewsId 
     */
    public function GetDocumentNewsContent($documentNewsId) {
        $sql = "select documentnewscontent from " . self::tableName . " where documentnewsid=:documentnewsid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $documentNewsId);
        $dboperator = DBOperator::getInstance();
        return $dboperator->ReturnString($sql, $dataProperty);
    }

    /**
     * 按月,按userid,$siteid 统计发稿量
     * @param <type> $siteid
     * @param <type> $adminuserid
     * @param <type> $createdate
     * @param <type> $counttype     0表示按adminuserid统计 否则按用户名进行统计
     * @param <type> $state         0表示新稿,30表示已发
     * @return <type>
     */
    public function GetCount($siteid, $adminuserid, $beginDate = 0, $endDate = 0, $counttype = 0, $state = -1) {
        $dataProperty = new DataProperty();
        if ($counttype == 0) {
            if ($adminuserid == -1) {
                $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE 1=1 ";
            } else {
                $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE  adminuserid=:adminuserid ";
                $dataProperty->AddField("adminuserid", $adminuserid);
            }
        } else {
            $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE  adminusername=:adminusername ";
            $dataProperty->AddField("adminusername", $adminuserid);
        }

        if ($siteid != -1) {
            $sql .= " AND siteid=:siteid";
            $dataProperty->AddField("siteid", $siteid);
        }

        if ($state >= 0) {
            $sql = $sql . " AND state=:state";
            $dataProperty->AddField("state", $state);
        }

        if (strlen($beginDate) > 1) {
            $sql .= " AND createdate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 1) {
            $sql .= " AND createdate<'" . $endDate . "'";
        }

        $dboperator = DBOperator::getInstance();
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);
        return $allcount;
    }

    /*
     * 
     * 按月返回频道下稿件的作者,稿名,日期
     * 
     * 
     * 
     */

    public function GetDocumentListTable($siteid, $createdate, $enddate, $documentchannelid) {
        $dataProperty = new DataProperty();
        $sql = "SELECT AdminUserID,AdminUserName,DocumentNewsTitle,ShowDate,state  FROM " . self::tableName . " WHERE siteid=:siteid AND documentchannelid=:documentchannelid AND createdate between :searchkey1 and :searchkey2 ORDER BY AdminUserName,ShowDate";
        $dataProperty->AddField("siteid", $siteid);
        $dataProperty->AddField("searchkey1", $createdate);
        $dataProperty->AddField("searchkey2", $enddate);
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dboperator = DBOperator::getInstance();
        $DocumentInfor = $dboperator->ReturnArray($sql, $dataProperty); //AdminUserName,DocumentNewsTitle,PublishDate
        return $DocumentInfor;
    }

    /* public function GetCountState($siteid, $adminuserid, $createdate, $counttype = 0, $state = 0) {
      $dataProperty = new DataProperty();
      if ($counttype == 0) {
      $sql = "SELECT  count(*) FROM " . self::tablename . "  WHERE siteid=:siteid AND adminuserid=:adminuserid AND state=:state AND createdate like :searchkey1";
      $dataProperty->AddField("adminuserid", $adminuserid);
      } else {
      $sql = "SELECT  count(*) FROM " . self::tablename . "  WHERE siteid=:siteid AND adminusername=:adminusername AND state=:state AND createdate like :searchkey1";
      $dataProperty->AddField("adminusername", $adminuserid);
      }
      $dataProperty->AddField("siteid", $siteid);
      $dataProperty->AddField("searchkey1", "%" . $createdate . "%");
      $dataProperty->AddField("state", $state);
      $dboperator = DBOperator::getInstance();
      $allcount = $dboperator->ReturnInt($sql, $dataProperty);
      return $allcount;
      } */

    /**
     * 记者文集调用,根据用户名
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetListByAdminUserPager($pagebegin, $pagesize, &$allcount, $adminusername) {
        $state = 30; //只显示已发布的
        $searchsql = "";
        $dataProperty = new DataProperty();
        $searchsql = " and (n.adminusername=:searchkey1 or n.author like :searchkey2)";
        $dataProperty->AddField("searchkey1", $adminusername);
        $dataProperty->AddField("searchkey2", "%" . $adminusername . "%");

        $sql = "
            SELECT
            n.documentnewsid,n.documentnewstype,n.documentnewstitle,n.publishdate,n.titlepic,n.documentnewsintro,n.documentchannelid,
            s.siteurl
            FROM
            " . self::tableName . " n, cst_site s, cst_documentchannel c
            WHERE n.documentchannelid=c.documentchannelid AND c.siteid=s.siteid AND n.state=" . $state . " " . $searchsql . " order by n.sort desc, n.publishdate desc limit " . $pagebegin . "," . $pagesize . "";

        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $sql = "
            SELECT
            count(n.documentnewsid)
            FROM
            " . self::tableName . " n
            WHERE n.state=" . $state . " " . $searchsql . "";
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 按author查询
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetListByAuthor($pagebegin, $pagesize, &$allcount, $adminusername) {
        $state = 30; //只显示已发布的
        $searchsql = "";
        $dataProperty = new DataProperty();
        $searchsql = " and n.author like :searchkey1";
        $dataProperty->AddField("searchkey1", "%" . $adminusername . "%");

        $sql = "
            SELECT
            n.documentnewsid,n.documentnewstype,n.documentnewstitle,n.publishdate,n.titlepic,n.documentnewsintro,n.documentchannelid,
            s.siteurl
            FROM
            " . self::tableName . " n, cst_site s, cst_documentchannel c
            WHERE n.documentchannelid=c.documentchannelid AND c.siteid=s.siteid AND n.state=" . $state . " " . $searchsql . " order by n.sort desc, n.publishdate desc limit " . $pagebegin . "," . $pagesize . "";

        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);

        $sql = "
            SELECT
            count(n.documentnewsid)
            FROM
            " . self::tableName . " n
            WHERE n.state=" . $state . " " . $searchsql . "";
        $allcount = $dboperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    public function GetNewTags($lastId) {
        if (!$lastId)
            $lastId = 0;
        $sql = "select documentnewstag,documentnewsmaintag,documentnewsid,documentchannelid,siteid from " . self::tableName . " where documentnewsid > :documentnewsid and (documentnewstag != :documentnewstag or documentnewsmaintag !=:documentnewsmaintag)";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentnewsid", $lastId);
        $dataProperty->AddField("documentnewstag", "");
        $dataProperty->AddField("documentnewsmaintag", "");
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 频道文档调用,根据频道名
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $channelname
     * @return <type>
     */
    public function GetListByChannelName($topcount, $channelname) {
        if ($topcount == '') {
            $topcount = '10';
        }
        if (is_numeric($topcount)) {
            if (intval($topcount) <= 0) {
                $topcount = '10';
            }
        }
        $channelstate = 0; //只显示可用频道
        $state = 30; //只显示已发布的
        $sql = "
            SELECT
            cs.subdomain,cs.siteid,t1.documentnewsid,t1.documentnewstype,t1.documentnewstitle,t1.publishdate,t1.titlepic,t1.documentnewsintro,t1.documentchannelid 
            FROM
            cst_site cs inner join cst_documentchannel t on cs.SiteID=t.SiteID inner join " . self::tableName . " t1 on t.DocumentChannelID=t1.DocumentChannelID and t.DocumentChannelName=:channelname 
            WHERE t.state=" . $channelstate . " and t1.state=" . $state . " order by t1.publishdate desc limit " . $topcount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("channelname", $channelname);
        //echo $sql;
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

}

?>
