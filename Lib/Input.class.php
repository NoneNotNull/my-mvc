<?php
/**
 * Created by PhpStorm.
 * User: Cain
 * Date: 2016/6/3
 * Time: 16:06
 */
class Input{
    /*
     * 控制器名,string
     */
    private $controller_name;
    /*
     * 操作名,string
     */
    private $action_name;

    /*
     * 参数
     */
    private $args;

    /*
     * 客户端IP
     */
    private $client_ip;

    /*
     * 客户端请求头信息
     */
    private $client_info;

    public function __construct(){
        $this -> Init();
    }

    private function Init(){
        /*
         * Init
         * 合并数组，过滤参数等等
         */
        $REQUEST = array_merge($_GET,$_POST);
        $this->args = $this->deepAddslashes($REQUEST);
        $c = isset($this->args["c"]) && is_string($this->args["c"]) ?$this->args["c"]:"index";
        $this -> setControllerName($c);
        $a = isset($this->args["a"]) && is_string($this->args["a"]) ?$this->args["a"]:"index";
        $this -> setActionName($a);
    }

    private function deepAddslashes($args){
        /*
         * deepAddslashes
         * 深度addslashes，不过get_matic_quotes_gpc()在PHP5.4之后就取消了，需要重写这部分
         */
        if (get_magic_quotes_gpc()){
            return $args;
        }
        else{
            if (is_array($args)){
                foreach($args as $key => $value){
                    $args[$key] = $this->deepAddslashes($value);
                }
            }
            else{
                return addslashes($args);
            }
        }
        return $args;
    }

    private function setControllerName($controller_name){
        /**
         * setControllerName
         * 用于设置控制器名
         */
        $this->controller_name = $controller_name;
    }
    public function getControllerName(){
        /*
         * getControllerName
         * 获取控制器的名称并返回
         */
        return $this->controller_name;
    }


    private function setActionName($action_name){
        /*
         * setActionName
         * 设置操作名
         */
        $this->action_name = $action_name;
    }

    public function getActionName(){
        /*
         * getActionName
         * 获取操作名
         */
        return $this->action_name;
    }

    public function getArgs(){
        return $this->args;
    }

    public function getByName($name){
        if (array_key_exists($name, $this->args)){
            return $this->args[$name];
        }
        else{
            return Null;
        }
    }

    public function getClientIP(){
        /*
         * 获取客户端IP信息
         */
        $this->client_ip = "Unknown";
    }

    public function getClientInfo(){
        /*
         * 获取客户端请求头信息
         */
        $this->client_info = "Unknown";
    }

}