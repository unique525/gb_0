<?php

/**
 * 公共访问 活动产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author yin
 */
class ActivityProductPublicData extends BasePublicData{

    /**
     * 取得活动产品的打折数
     * @param int $activityProductId 活动产品id
     * @param bool $withCache 是否缓存
     * @return float|int 活动产品的打折数
     */
    public function GetDiscount($activityProductId, $withCache){
        $result = 0;
        if ($activityProductId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'activity_product_data';
            $cacheFile = 'activity_product_get_activity_product_discount.cache_' . $activityProductId . '';
            $sql = "SELECT Discount FROM " . self::TableName_ActivityProduct . " WHERE ActivityProductId=:ActivityProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityProductId",$activityProductId);
            $result = $this->GetInfoOfFloatValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }
} 