<?php
/**
 * Created by PhpStorm.
 * User: Cain
 * Date: 2016/6/24
 * Time: 17:55
 */

include_once "../func/encrypt.php";

class Session{
    /*
     * Session类
     */

    public function __construct(){

        ini_set("session.cookie_lifetime", "0");    # 告知浏览器是否持久化存储cookie数据
        ini_set("session.use_trans_sid", "1");      # 开启自动跨页传递会话ID，以URL的形式
        ini_set("session.use_cookies", "1");        # 允许使用cookie传递会话ID
        ini_set("session.use_only_cookies", "0");   # 只允许使用cookie传递会话ID
        ini_set("session.use_strict_mode", "1");    # 严格模式，不接受由用户提供的未经初始化的会话ID
        ini_set("session.cookie_httponly", "1");    # 禁止Javascript访问cookie
        session_start();

    }


    public function isExist($key){

    }

    public function getByKey($key){

    }

    public function setSession($key,$value){

    }

    public function delByKey($key){

    }

    public function delAll(){

    }

}