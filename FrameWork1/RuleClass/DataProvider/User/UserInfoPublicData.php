<?php

/**
 * 公共访问 会员信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserInfoPublicData extends BasePublicData
{

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

    public function Create($userId, $realName = '', $nickName = '',$avatarUploadFileId="", $userScore = 0, $userMoney = 0, $userCharm = 0, $userExp = 0, $userPoint = 0, $question = '', $answer = '', $sign = '', $lastVisitIP = '', $lastVisitTime = '', $email = '', $qq = '', $country = '', $comeFrom = '', $honor = '', $birthday = '', $gender = 0, $fansCount = 0, $idCard = '', $postCode = '', $address = '', $tel = '', $mobile = '', $province = '', $occupational = '', $city = '', $district = '', $hit = 0, $messageCount = 0, $userPostCount = 0, $userPostBestCount = 0, $userActivityCount = 0, $userAlbumCount = 0, $userBestAlbumCount = 0, $userRecAlbumCount = 0, $userAlbumCommentCount = 0, $userCommissionOwn = 0, $userCommissionChild = 0, $userCommissionGrandson = 0)
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
            $dataProperty->AddField("District", $district);
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
                $sql = "INSERT INTO " . self::TableName_UserInfo. " (UserId, RealName, NickName, AvatarUploadFileId, UserScore, UserMoney, UserCharm, UserExp, UserPoint, Question, Answer, Sign, LastVisitIP, LastVisitTime, Email, QQ,Country, ComeFrom, Honor, Birthday, Gender, FansCount, IDCard,PostCode, Address,
            Tel, Mobile, Province, Occupational, City, District, Hit, MessageCount, UserPostCount, UserPostBestCount, UserActivityCount, UserAlbumCount, UserBestAlbumCount, UserRecAlbumCount, UserAlbumCommentCount,UserCommissionOwn,UserCommissionChild,UserCommissionGrandson) VALUES (:UserId, :RealName, :NickName,
            :AvatarUploadFileId, :UserScore, :UserMoney, :UserCharm, :UserExp, :UserPoint, :Question, :Answer, :Sign, :LastVisitIP, :LastVisitTime, :Email, :QQ,:Country, :ComeFrom, :Honor, :Birthday, :Gender, :FansCount, :IDCard, :PostCode, :Address, :Tel, :Mobile, :Province, :Occupational, :City, :District, :Hit, :MessageCount, :UserPostCount, :UserPostBestCount, :UserActivityCount, :UserAlbumCount, :UserBestAlbumCount, :UserRecAlbumCount, :UserAlbumCommentCount,:UserCommissionOwn,:UserCommissionChild,:UserCommissionGrandson);";

                $result = $this->dbOperator->Execute($sql, $dataProperty);
            } else { //修改
                $sql = "UPDATE " . self::TableName_UserInfo. " SET RealName=:RealName, NickName=:NickName, AvatarUploadFileId=:AvatarUploadFileId, UserScore=:UserScore, UserMoney=:UserMoney, UserCharm=:UserCharm, UserExp=:UserExp, UserPoint=:UserPoint, Question=:Question, Answer=:Answer,
            Sign=:Sign, LastVisitIP=:LastVisitIP, LastVisitTime=:LastVisitTime, Email=:Email, QQ=:QQ,Country=:Country, ComeFrom=:ComeFrom, Honor=:Honor, Birthday=:Birthday, Gender=:Gender, FansCount=:FansCount, IDCard=:IDCard,PostCode=:PostCode, Address=:Address, Tel=:Tel, Mobile=:Mobile, Province=:Province,
            Occupational=:Occupational, City=:City, District=:District, Hit=:Hit, MessageCount=:MessageCount, UserPostCount=:UserPostCount, UserPostBestCount=:UserPostBestCount, UserActivityCount=:UserActivityCount, UserAlbumCount=:UserAlbumCount, UserBestAlbumCount=:UserBestAlbumCount, UserRecAlbumCount=:UserRecAlbumCount, UserAlbumCommentCount=:UserAlbumCommentCount,UserCommissionOwn=:UserCommissionOwn,UserCommissionChild=:UserCommissionChild,UserCommissionGrandson=:UserCommissionGrandson WHERE UserId=:UserId;";

                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }
}

?>
