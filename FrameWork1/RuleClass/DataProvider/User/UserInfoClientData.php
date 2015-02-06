<?php
/**
 * 客户端 会员信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserInfoClientData extends BaseClientData {


    /**
     * 初始化
     * @param int $userId 会员id
     * @return int
     */
    public function Init($userId){
        $result = -1;
        if($userId > 0){
            $sqlSelect = "SELECT count(*) FROM " . self::TableName_UserInfo . " WHERE UserId=:UserId";
            $selectDataProperty = new DataProperty();
            $selectDataProperty->AddField("UserId", $userId);
            $selectResult = $this->dbOperator->GetInt($sqlSelect, $selectDataProperty);
            if($selectResult == 0){
                $sqlInsert = "INSERT INTO ".self::TableName_UserInfo." (UserId) VALUES (:UserId);";
                $insertDataProperty = new DataProperty();
                $insertDataProperty->AddField("UserId",$userId);
                $result = $this->dbOperator->Execute($sqlInsert, $insertDataProperty);
            }else{
                $result = $userId;
            }
        }
        return $result;
    }

    /**
     * @param $userId
     * @param string $realName
     * @param string $nickName
     * @param int $avatarUploadFileId
     * @param int $userScore
     * @param int $userMoney
     * @param int $userCharm
     * @param int $userExp
     * @param int $userPoint
     * @param string $question
     * @param string $answer
     * @param string $sign
     * @param string $lastVisitIP
     * @param string $lastVisitTime
     * @param string $email
     * @param string $qq
     * @param string $country
     * @param string $comeFrom
     * @param string $honor
     * @param string $birthday
     * @param int $gender
     * @param int $fansCount
     * @param string $idCard
     * @param string $postCode
     * @param string $address
     * @param string $tel
     * @param string $mobile
     * @param string $province
     * @param string $occupational
     * @param string $city
     * @param int $relationship
     * @param int $hit
     * @param int $messageCount
     * @param int $userPostCount
     * @param int $userPostBestCount
     * @param int $userActivityCount
     * @param int $userAlbumCount
     * @param int $userBestAlbumCount
     * @param int $userRecAlbumCount
     * @param int $userAlbumCommentCount
     * @param int $userCommissionOwn
     * @param int $userCommissionChild
     * @param int $userCommissionGrandson
     * @return int
     */
    public function Create($userId, $realName = '', $nickName = '',$avatarUploadFileId=0, $userScore = 0, $userMoney = 0, $userCharm = 0, $userExp = 0, $userPoint = 0, $question = '', $answer = '', $sign = '', $lastVisitIP = '', $lastVisitTime = '', $email = '', $qq = '', $country = '', $comeFrom = '', $honor = '', $birthday = '', $gender = 0, $fansCount = 0, $idCard = '', $postCode = '', $address = '', $tel = '', $mobile = '', $province = '', $occupational = '', $city = '', $relationship = 0, $hit = 0, $messageCount = 0, $userPostCount = 0, $userPostBestCount = 0, $userActivityCount = 0, $userAlbumCount = 0, $userBestAlbumCount = 0, $userRecAlbumCount = 0, $userAlbumCommentCount = 0, $userCommissionOwn = 0, $userCommissionChild = 0, $userCommissionGrandson = 0)
    {
        $result = -1;
        if ($userId > 0) {
            $sql = "SELECT count(*) FROM " . self::TableName_UserInfo. " WHERE UserId=:UserId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);

            $result = $this->dbOperator->GetInt($sql, $dataProperty);

            $dataProperty->AddField("RealName", $realName);
            $dataProperty->AddField("NickName", $nickName);
            $dataProperty->AddField("AvatarUploadFileId",$avatarUploadFileId);
            $dataProperty->AddField("UserScore", $userScore);
            $dataProperty->AddField("UserMoney", $userMoney);
            $dataProperty->AddField("UserCharm", $userCharm);
            $dataProperty->AddField("UserExp", $userExp);
            $dataProperty->AddField("UserPoint", $userPoint);
            $dataProperty->AddField("Question", $question);
            $dataProperty->AddField("Answer", $answer);
            $dataProperty->AddField("Sign", $sign);
            $dataProperty->AddField("LastVisitIP", $lastVisitIP);
            $dataProperty->AddField("LastVisitTime", $lastVisitTime);
            $dataProperty->AddField("Email", $email);
            $dataProperty->AddField("QQ", $qq);
            $dataProperty->AddField("Country", $country);
            $dataProperty->AddField("ComeFrom", $comeFrom);
            $dataProperty->AddField("Honor", $honor);
            $dataProperty->AddField("Birthday", $birthday);
            $dataProperty->AddField("Gender", $gender);
            $dataProperty->AddField("FansCount", $fansCount);
            $dataProperty->AddField("IDCard", $idCard);
            $dataProperty->AddField("PostCode", $postCode);
            $dataProperty->AddField("Address", $address);
            $dataProperty->AddField("Tel", $tel);
            $dataProperty->AddField("Mobile", $mobile);
            $dataProperty->AddField("Province", $province);
            $dataProperty->AddField("Occupational", $occupational);
            $dataProperty->AddField("City", $city);
            $dataProperty->AddField("Relationship", $relationship);
            $dataProperty->AddField("Hit", $hit);
            $dataProperty->AddField("MessageCount", $messageCount);
            $dataProperty->AddField("UserPostCount", $userPostCount);
            $dataProperty->AddField("UserPostBestCount", $userPostBestCount);
            $dataProperty->AddField("UserActivityCount", $userActivityCount);
            $dataProperty->AddField("UserAlbumCount", $userAlbumCount);
            $dataProperty->AddField("UserBestAlbumCount", $userBestAlbumCount);
            $dataProperty->AddField("UserRecAlbumCount", $userRecAlbumCount);
            $dataProperty->AddField("UserAlbumCommentCount", $userAlbumCommentCount);
            $dataProperty->AddField("UserCommissionOwn", $userCommissionOwn);
            $dataProperty->AddField("UserCommissionChild", $userCommissionChild);
            $dataProperty->AddField("UserCommissionGrandson", $userCommissionGrandson);
            if ($result <= 0) { //没有找到记录，初始化一条
                $sql = "INSERT INTO "
                    . self::TableName_UserInfo. " (
                     UserId, RealName, NickName, AvatarUploadFileId,
                     UserScore, UserMoney, UserCharm, UserExp, UserPoint,
                     Question, Answer, Sign, LastVisitIP, LastVisitTime,
                     Email, QQ, Country, ComeFrom, Honor, Birthday, Gender,
                     FansCount, IDCard, PostCode, Address, Tel, Mobile,
                     Province, Occupational, City, Relationship, Hit, MessageCount,
                     UserPostCount, UserPostBestCount, UserActivityCount, UserAlbumCount,
                     UserBestAlbumCount, UserRecAlbumCount, UserAlbumCommentCount,
                     UserCommissionOwn, UserCommissionChild, UserCommissionGrandson
                    ) VALUES (
                    :UserId,:RealName,:NickName,:AvatarUploadFileId,
                    :UserScore,:UserMoney,:UserCharm,:UserExp,:UserPoint,
                    :Question,:Answer,:Sign,:LastVisitIP,:LastVisitTime,
                    :Email,:QQ,:Country,:ComeFrom,:Honor,:Birthday,:Gender,
                    :FansCount,:IDCard,:PostCode,:Address,:Tel,:Mobile,
                    :Province,:Occupational,:City,:Relationship,:Hit,:MessageCount,
                    :UserPostCount,:UserPostBestCount,:UserActivityCount,:UserAlbumCount,
                    :UserBestAlbumCount,:UserRecAlbumCount,:UserAlbumCommentCount,
                    :UserCommissionOwn,:UserCommissionChild,:UserCommissionGrandson
                    );";
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            } else { //修改
                $sql = "UPDATE " . self::TableName_UserInfo. " SET RealName=:RealName, NickName=:NickName, AvatarUploadFileId=:AvatarUploadFileId, UserScore=:UserScore, UserMoney=:UserMoney, UserCharm=:UserCharm, UserExp=:UserExp, UserPoint=:UserPoint, Question=:Question, Answer=:Answer,
            Sign=:Sign, LastVisitIP=:LastVisitIP, LastVisitTime=:LastVisitTime, Email=:Email, QQ=:QQ,Country=:Country, ComeFrom=:ComeFrom, Honor=:Honor, Birthday=:Birthday, Gender=:Gender, FansCount=:FansCount, IDCard=:IDCard,PostCode=:PostCode, Address=:Address, Tel=:Tel, Mobile=:Mobile, Province=:Province,
            Occupational=:Occupational, City=:City, Relationship=:Relationship, Hit=:Hit, MessageCount=:MessageCount, UserPostCount=:UserPostCount, UserPostBestCount=:UserPostBestCount, UserActivityCount=:UserActivityCount, UserAlbumCount=:UserAlbumCount, UserBestAlbumCount=:UserBestAlbumCount, UserRecAlbumCount=:UserRecAlbumCount, UserAlbumCommentCount=:UserAlbumCommentCount,UserCommissionOwn=:UserCommissionOwn,UserCommissionChild=:UserCommissionChild,UserCommissionGrandson=:UserCommissionGrandson WHERE UserId=:UserId;";


                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }
} 