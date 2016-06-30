<?php
/**
 * Created by PhpStorm.
 * User: Cain
 * Date: 2016/6/24
 * Time: 16:54
 */


include_once "../func/encrypt.php";


class Cookie{
    /*
     * Cookie类
     */
    private $encrypted_key;
    /*
     * 加密cookie所用的key
     */
    private $expire;
    /*
     * cookie过期时间
     * 过期后cookie还是存在的
     * 但是一旦客户端的cookie过期就不能再使用了
     */
    private $path;
    /*
     * "/" 代表的是根目录，如果多个目录，最好还是设置正确的path
     */
    public function __construct($encrypted_key="",$expire=3600,$path="/"){  #
        if (is_string($encrypted_key)) {
            $this->encrypted_key = $encrypted_key;
        } else {
            $this->encrypted_key = "";
        }
        $this->expire = $expire;
        $this->path = $path;
    }
    public function isExist($key){
        /*
         * 判断cookie中某个键是否存在
         * @param   key is string
         * @return  return  True or False
         */
        if (isset($_COOKIE[$key])) {
            return True;
        }else{
            return False;
        }
    }

    public function getByKey($key){
        /*
         * 获取cookie中的某个键
         * @param   key is string
         * @return  return  the value or "";
         */
        if ($this->isExist($key)){
            return encrypt($_COOKIE[$key], "D", $key = $this->encrypted_key);
        }else{
            return "";
        }
    }

    public function setCookie($key,$value){
        /*
         * 设置cookie
         */
        $value = encrypt($value, "E",$this->encrypted_key);
        setcookie($key,$value,time()+$this->expire,$this->path);
    }

    public function delByKey($key){
        /*
         * 删除某个键的cookie
         */
        if($this->isExist($key)){
            setcookie($key, "", time()-3600,$this->path);
        }
    }

    public function delAll(){
        /*
         * 删除所有cookie
         */
        foreach ($_COOKIE as $key => $value){
            setcookie($key, "", time()-3600,$this->path);
        }
    }
}

//$cookie = new Cookie();
//$cookie->setCookie("user","cain");
//$cookie->delAll();
//echo $cookie->getByKey("user");