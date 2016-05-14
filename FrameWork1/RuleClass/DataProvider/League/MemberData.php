<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:16
 */
class MemberData {
    /** 队员状态定义 */

    /**
     * 启用 0
     */
    const STATE_NORMAL = 0;

    /**
     * 停用 100
     */
    const STATE_REMOVED = 100;

    /**
     * 比赛首发
     */
    const STATE_OF_MATCH_STARTING =0;
    /**
     * 比赛替补登场
     */
    const STATE_OF_MATCH_ALTERNATE =1;
    /**
     * 比赛管饮水机
     */
    const STATE_OF_MATCH_DRINK_MACHINE =2;
    /**
     * 未进大名单
     */
    const STATE_OF_MATCH_NONE =100;

}