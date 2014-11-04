<?php

class UserInfo
{
    private $nickName = "";
    private $realName = "";
    private $avatarUploadFileId = 0;
    private $userScore = 0;
    private $userMoney = 0;
    private $userCharm = 0;
    private $userExp = 0;
    private $userPoint = 0;
    private $question = "";
    private $answer = "";
    private $sign = "";
    private $lastVisitIp = "";
    private $lastVisitTime = "0000-00-00";
    private $email = "";
    private $qq = "";
    private $comeFrom = "";
    private $honor = "";
    private $birthday = "0000-00-00";
    private $gender = 0;
    private $fansCount = 0;
    private $idCard = "";
    private $postCode = "";
    private $address = "";
    private $tel = "";
    private $mobile = "";
    private $country = "";
    private $province = "";
    private $occupational = "";
    private $city = "";
    private $relationship = "";
    private $hit = 0;
    private $messageCount = 0;
    private $userPostCount = 0;
    private $userPostBestCount = 0;
    private $userActivityCount = 0;
    private $userAlbumCount = 0;
    private $userBestAlbumCount = 0;
    private $userRecAlbumCount = 0;
    private $userAllAlbumCount = 0;

    private $userLevelId = 0;
    private $userGroupId = 0;

    function __construct(
        $userId = 0,
        $siteId = 0,
        $nickName = "",
        $realName = "",
        $avatarUploadFileId = 0,
        $userScore = 0,
        $userMoney = 0,
        $userCharm = 0,
        $userExp = 0,
        $userPoint = 0,
        $question = "",
        $answer = "",
        $sign = "",
        $lastVisitIp = "",
        $lastVisitTime = "0000-00-00",
        $email = "",
        $qq = "",
        $comeFrom = "",
        $honor = "",
        $birthday = "0000-00-00",
        $gender = 0,
        $fansCount = 0,
        $idCard = "",
        $postCode = "",
        $address = "",
        $tel = "",
        $mobile = "",
        $country = "",
        $province = "",
        $occupational = "",
        $city = "",
        $relationship = "",
        $hit = 0,
        $messageCount = 0,
        $userPostCount = 0,
        $userPostBestCount = 0,
        $userActivityCount = 0,
        $userAlbumCount = 0,
        $userBestAlbumCount = 0,
        $userRecAlbumCount = 0,
        $userAllAlbumCount = 0
    )
    {
        if ($userId > 0 && $siteId > 0) {
            $userInfoData = new UserInfoData();
            $arrUserInfoOne = $userInfoData->GetOne($userId, $siteId);
            $this->address = $arrUserInfoOne["Address"];
            $this->answer = $arrUserInfoOne["Answer"];
            $this->avatarUploadFileId = $arrUserInfoOne["AvatarUploadFileId"];
            $this->bankCount = $arrUserInfoOne["BankCount"];
            $this->bankName = $arrUserInfoOne["BankName"];
            $this->bankOpenAddress = $arrUserInfoOne["BankOpenAddress"];
            $this->bankUserName = $arrUserInfoOne["BankUserName"];
            $this->birthday = $arrUserInfoOne["Birthday"];
            $this->city = $arrUserInfoOne["City"];
            $this->comeFrom = $arrUserInfoOne["ComeFrom"];
            $this->country = $arrUserInfoOne["Country"];
            $this->email = $arrUserInfoOne["Email"];
            $this->fansCount = $arrUserInfoOne["FansCount"];
            $this->gender = $arrUserInfoOne["Gender"];
            $this->hit = $arrUserInfoOne["Hit"];
            $this->honor = $arrUserInfoOne["Honor"];
            $this->idCard = $arrUserInfoOne["IdCard"];
            $this->lastVisitIp = $arrUserInfoOne["LastVisitIp"];
            $this->lastVisitTime = $arrUserInfoOne["LastVisitTime"];
            $this->messageCount = $arrUserInfoOne["MessageCount"];
            $this->mobile = $arrUserInfoOne["Mobile"];
            $this->nickName = $arrUserInfoOne["NickName"];
            $this->occupational = $arrUserInfoOne["Occupational"];
            $this->postCode = $arrUserInfoOne["PostCode"];
            $this->province = $arrUserInfoOne["Province"];
            $this->qq = $arrUserInfoOne["QQ"];
            $this->question = $arrUserInfoOne["Question"];
            $this->realName = $arrUserInfoOne["RealName"];
            $this->relationship = $arrUserInfoOne["Relationship"];
            $this->sign = $arrUserInfoOne["Sign"];
            $this->tel = $arrUserInfoOne["Tel"];
            $this->userActivityCount = $arrUserInfoOne["UserActivityCount"];
            $this->userAlbumCount = $arrUserInfoOne["UserAlbumCount"];
            $this->userAllAlbumCount = $arrUserInfoOne["UserAllAlbumCount"];
            $this->userBestAlbumCount = $arrUserInfoOne["UserBestAlbumCount"];
            $this->userCharm = $arrUserInfoOne["UserCharm"];
            $this->userCommissionChild = $arrUserInfoOne["UserCommissionChild"];
            $this->userCommissionGrandson = $arrUserInfoOne["UserCommissionGrandson"];
            $this->userCommissionOwn = $arrUserInfoOne["UserCommissionOwn"];
            $this->userExp = $arrUserInfoOne["UserExp"];
            $this->userGroupId = $arrUserInfoOne["UserGroupId"];
            $this->userLevelId = $arrUserInfoOne["UserLevelId"];
            $this->userMoney = $arrUserInfoOne["UserMoney"];
            $this->userPoint = $arrUserInfoOne["UserPoint"];
            $this->userPostBestCount = $arrUserInfoOne["UserPostBestCount"];
            $this->userPostCount = $arrUserInfoOne["UserPostCount"];
            $this->userRecAlbumCount = $arrUserInfoOne["UserRecAlbumCount"];
            $this->userScore = $arrUserInfoOne["UserScore"];
        } else {
            $this->address = $address;
            $this->answer = $answer;
            $this->avatarUploadFileId = $avatarUploadFileId;
            $this->bankCount = $bankCount;
            $this->bankName = $bankName;
            $this->bankOpenAddress = $bankOpenAddress;
            $this->bankUserName = $bankUserName;
            $this->birthday = $birthday;
            $this->city = $city;
            $this->comeFrom = $comeFrom;
            $this->country = $country;
            $this->email = $email;
            $this->fansCount = $fansCount;
            $this->gender = $gender;
            $this->hit = $hit;
            $this->honor = $honor;
            $this->idCard = $idCard;
            $this->lastVisitIp = $lastVisitIp;
            $this->lastVisitTime = $lastVisitTime;
            $this->messageCount = $messageCount;
            $this->mobile = $mobile;
            $this->nickName = $nickName;
            $this->occupational = $occupational;
            $this->postCode = $postCode;
            $this->province = $province;
            $this->qq = $qq;
            $this->question = $question;
            $this->realName = $realName;
            $this->relationship = $relationship;
            $this->sign = $sign;
            $this->tel = $tel;
            $this->userActivityCount = $userActivityCount;
            $this->userAlbumCount = $userAlbumCount;
            $this->userAllAlbumCount = $userAllAlbumCount;
            $this->userBestAlbumCount = $userBestAlbumCount;
            $this->userCharm = $userCharm;
            $this->userCommissionChild = $userCommissionChild;
            $this->userCommissionGrandson = $userCommissionGrandson;
            $this->userCommissionOwn = $userCommissionOwn;
            $this->userExp = $userExp;
            $this->userGroupId = $userGroupId;
            $this->userLevelId = $userLevelId;
            $this->userMoney = $userMoney;
            $this->userPoint = $userPoint;
            $this->userPostBestCount = $userPostBestCount;
            $this->userPostCount = $userPostCount;
            $this->userRecAlbumCount = $userRecAlbumCount;
            $this->userScore = $userScore;
        }
    }


    /**
     * @param int $userGroupId
     */
    public function setUserGroupId($userGroupId)
    {
        $this->userGroupId = $userGroupId;
    }

    /**
     * @return int
     */
    public function getUserGroupId()
    {
        return $this->userGroupId;
    }

    /**
     * @param int $userLevelId
     */
    public function setUserLevelId($userLevelId)
    {
        $this->userLevelId = $userLevelId;
    }

    /**
     * @return int
     */
    public function getUserLevelId()
    {
        return $this->userLevelId;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param int $avatarUploadFileId
     */
    public function setAvatarUploadFileId($avatarUploadFileId)
    {
        $this->avatarUploadFileId = $avatarUploadFileId;
    }

    /**
     * @return int
     */
    public function getAvatarUploadFileId()
    {
        return $this->avatarUploadFileId;
    }

    /**
     * @param int $bankCount
     */
    public function setBankCount($bankCount)
    {
        $this->bankCount = $bankCount;
    }

    /**
     * @return int
     */
    public function getBankCount()
    {
        return $this->bankCount;
    }

    /**
     * @param string $bankName
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @param string $bankOpenAddress
     */
    public function setBankOpenAddress($bankOpenAddress)
    {
        $this->bankOpenAddress = $bankOpenAddress;
    }

    /**
     * @return string
     */
    public function getBankOpenAddress()
    {
        return $this->bankOpenAddress;
    }

    /**
     * @param string $bankUserName
     */
    public function setBankUserName($bankUserName)
    {
        $this->bankUserName = $bankUserName;
    }

    /**
     * @return string
     */
    public function getBankUserName()
    {
        return $this->bankUserName;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $comeFrom
     */
    public function setComeFrom($comeFrom)
    {
        $this->comeFrom = $comeFrom;
    }

    /**
     * @return string
     */
    public function getComeFrom()
    {
        return $this->comeFrom;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $fansCount
     */
    public function setFansCount($fansCount)
    {
        $this->fansCount = $fansCount;
    }

    /**
     * @return int
     */
    public function getFansCount()
    {
        return $this->fansCount;
    }

    /**
     * @param int $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $hit
     */
    public function setHit($hit)
    {
        $this->hit = $hit;
    }

    /**
     * @return int
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * @param string $honor
     */
    public function setHonor($honor)
    {
        $this->honor = $honor;
    }

    /**
     * @return string
     */
    public function getHonor()
    {
        return $this->honor;
    }

    /**
     * @param string $idCard
     */
    public function setIdCard($idCard)
    {
        $this->idCard = $idCard;
    }

    /**
     * @return string
     */
    public function getIdCard()
    {
        return $this->idCard;
    }

    /**
     * @param string $lastVisitIp
     */
    public function setLastVisitIp($lastVisitIp)
    {
        $this->lastVisitIp = $lastVisitIp;
    }

    /**
     * @return string
     */
    public function getLastVisitIp()
    {
        return $this->lastVisitIp;
    }

    /**
     * @param string $lastVisitTime
     */
    public function setLastVisitTime($lastVisitTime)
    {
        $this->lastVisitTime = $lastVisitTime;
    }

    /**
     * @return string
     */
    public function getLastVisitTime()
    {
        return $this->lastVisitTime;
    }

    /**
     * @param int $messageCount
     */
    public function setMessageCount($messageCount)
    {
        $this->messageCount = $messageCount;
    }

    /**
     * @return int
     */
    public function getMessageCount()
    {
        return $this->messageCount;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $nickName
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @param string $occupational
     */
    public function setOccupational($occupational)
    {
        $this->occupational = $occupational;
    }

    /**
     * @return string
     */
    public function getOccupational()
    {
        return $this->occupational;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $qq
     */
    public function setQq($qq)
    {
        $this->qq = $qq;
    }

    /**
     * @return string
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $realName
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @param string $relationship
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * @return string
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @param string $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param int $userActivityCount
     */
    public function setUserActivityCount($userActivityCount)
    {
        $this->userActivityCount = $userActivityCount;
    }

    /**
     * @return int
     */
    public function getUserActivityCount()
    {
        return $this->userActivityCount;
    }

    /**
     * @param int $userAlbumCount
     */
    public function setUserAlbumCount($userAlbumCount)
    {
        $this->userAlbumCount = $userAlbumCount;
    }

    /**
     * @return int
     */
    public function getUserAlbumCount()
    {
        return $this->userAlbumCount;
    }

    /**
     * @param int $userAllAlbumCount
     */
    public function setUserAllAlbumCount($userAllAlbumCount)
    {
        $this->userAllAlbumCount = $userAllAlbumCount;
    }

    /**
     * @return int
     */
    public function getUserAllAlbumCount()
    {
        return $this->userAllAlbumCount;
    }

    /**
     * @param int $userBestAlbumCount
     */
    public function setUserBestAlbumCount($userBestAlbumCount)
    {
        $this->userBestAlbumCount = $userBestAlbumCount;
    }

    /**
     * @return int
     */
    public function getUserBestAlbumCount()
    {
        return $this->userBestAlbumCount;
    }

    /**
     * @param int $userCharm
     */
    public function setUserCharm($userCharm)
    {
        $this->userCharm = $userCharm;
    }

    /**
     * @return int
     */
    public function getUserCharm()
    {
        return $this->userCharm;
    }

    /**
     * @param int $userCommissionChild
     */
    public function setUserCommissionChild($userCommissionChild)
    {
        $this->userCommissionChild = $userCommissionChild;
    }

    /**
     * @return int
     */
    public function getUserCommissionChild()
    {
        return $this->userCommissionChild;
    }

    /**
     * @param mixed $userCommissionGrandson
     */
    public function setUserCommissionGrandson($userCommissionGrandson)
    {
        $this->userCommissionGrandson = $userCommissionGrandson;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionGrandson()
    {
        return $this->userCommissionGrandson;
    }

    /**
     * @param int $userCommissionOwn
     */
    public function setUserCommissionOwn($userCommissionOwn)
    {
        $this->userCommissionOwn = $userCommissionOwn;
    }

    /**
     * @return int
     */
    public function getUserCommissionOwn()
    {
        return $this->userCommissionOwn;
    }

    /**
     * @param int $userExp
     */
    public function setUserExp($userExp)
    {
        $this->userExp = $userExp;
    }

    /**
     * @return int
     */
    public function getUserExp()
    {
        return $this->userExp;
    }

    /**
     * @param int $userMoney
     */
    public function setUserMoney($userMoney)
    {
        $this->userMoney = $userMoney;
    }

    /**
     * @return int
     */
    public function getUserMoney()
    {
        return $this->userMoney;
    }

    /**
     * @param int $userPoint
     */
    public function setUserPoint($userPoint)
    {
        $this->userPoint = $userPoint;
    }

    /**
     * @return int
     */
    public function getUserPoint()
    {
        return $this->userPoint;
    }

    /**
     * @param int $userPostBestCount
     */
    public function setUserPostBestCount($userPostBestCount)
    {
        $this->userPostBestCount = $userPostBestCount;
    }

    /**
     * @return int
     */
    public function getUserPostBestCount()
    {
        return $this->userPostBestCount;
    }

    /**
     * @param int $userPostCount
     */
    public function setUserPostCount($userPostCount)
    {
        $this->userPostCount = $userPostCount;
    }

    /**
     * @return int
     */
    public function getUserPostCount()
    {
        return $this->userPostCount;
    }

    /**
     * @param int $userRecAlbumCount
     */
    public function setUserRecAlbumCount($userRecAlbumCount)
    {
        $this->userRecAlbumCount = $userRecAlbumCount;
    }

    /**
     * @return int
     */
    public function getUserRecAlbumCount()
    {
        return $this->userRecAlbumCount;
    }

    /**
     * @param int $userScore
     */
    public function setUserScore($userScore)
    {
        $this->userScore = $userScore;
    }

    /**
     * @return int
     */
    public function getUserScore()
    {
        return $this->userScore;
    }

    private $userCommissionOwn = 0;
    private $userCommissionChild = 0;
    private $userCommissionGrandson;
    private $bankName = "";
    private $bankOpenAddress = "";
    private $bankUserName = "";
    private $bankCount = 0;
}