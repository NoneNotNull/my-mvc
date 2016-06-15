<?php
/**
 * Created by PhpStorm.
 * User: Cain
 * Date: 2016/6/15
 * Time: 13:42
 */

$config = array(
    "dbms" => "mysql",
    "host" => "127.0.0.1",
    "port" => 3306,
    "user" => "root",
    "pass" => "",
    "dbname" => "test_data"
    );


class Mysql{
    /*
     * 数据库类，基于PDO完成
     * 核心使用链式操作
     */

    public $db = Null;
    public $statement = Null;
    public $sql;                # 数据库语句

    private $tb_name = "";       # 表名
    private $where = "";         # where条件
    private $fields = "";        # fileds条件
    private $order = "";         # order语句
    private $limit = "";         # limit限制
    private $data = "";          # insert或者update时用到的数据，关联数组

    public function __construct($config){
        /*
         * 用于数据库连接初始化
         * $config:数据库配置信息
         */
        $dsn = "${config['dbms']}:host=${config['host']};port=${config['port']};dbname=${config['dbname']}";
        try{
            $this->db = new PDO($dsn,$config["user"],$config["pass"],array(
                PDO::ATTR_PERSISTENT => true    # 持久化连接
            ));
        }catch (PDOException $e){
            die("Error:" . $e->getMessage() . "<br>");
        }
    }

    public function data($data){
        /*
         * 处理insert或者update的数据
         */
        $data_array = array();
        if (is_array($data)){
            foreach($data as $key => $value){
                $data_array[] = " $key = '$value'";
            }
            $this->data = implode(",",$data_array);
            return $this;
        }
    }

    public function where($where,$logic="AND"){
        /*
         * 改变语句的where条件,$where必须是字符串或者数组，并且不能为空
         * 目前只实现了简单的 $where = "id=1 and tid>1" 以及
         * $where = array("id=1","tid>1") 这两种简单的类型，并且逻辑关系为AND 或者 OR
         */
        if (empty($where)){
            $this->where = " ";
        }elseif(is_array($where)){
            $where_array = array();
                foreach($where as $key => $value){
                    $where_array[] = "$key=$value";
                }
            if(preg_match("/^\s.*and\s.*$|^\s.*or\s.*$/i",$logic)){
                $this->where = " where " .implode(" AND ",$where_array);
            }else{
                $this->where = " where " .implode(" AND ",$where_array);
            }
        }elseif(is_string($where)){
            $this->where = " where " .$where;
        }else{
            $this->where = " ";
        }
        return $this;
    }

    public function fields($fields=""){
        /*
         * 处理fields信息，目前支持数组，字符串类型
         * 最好可以支持这种，field('id,title,content')，如果是数组的话，比较麻烦
         */
        if (empty($fields)){
            $this->fields = " * ";
        }elseif(is_string($fields)){
            $this->fields = " ". $fields ." ";
        }elseif(is_array($fields)){
            $this->fields = " " . implode(",",$fields) . "";
        }else{
            $this->fields = " * ";
        }
        return $this;
    }

    public function limit($start=0,$length=10){
        /*
         * 处理limit信息，只支持符串类型
         */
        if(empty($start) && empty(($length))){
            $this->limit = " limit 0,10 ";
        }elseif(empty($start) && !empty($length)){
            $this->limit = " limit 0," . $length;
        }else{
            $this->limit = " limit " . $start . " , " . " 10 ";
        }
        return $this;
    }

    public function order(){
        /*
         * 处理fields信息，目前支持数组，字符串类型
         */
        if (empty($fields)){
            $this->fields = "  ";
        }elseif(is_string($fields)){
            $this->fields = " ORDER BY " . " ". $fields ." ";
        }elseif(is_array($fields)){
            $this->fields = " ORDER BY " ." " . implode(",",$fields) . "";
        }else{
            $this->fields = "  ";
        }
        return $this;
    }

    public function table($tb_name){
        /*
         * 设置表名
         */
        $this->tb_name = $tb_name;
        return $this;
    }

    public function query(){
        /*
         * 进行数据库查询
         * 返回所有数据<关联数组>
         */
        $this->sql = "SELECT " . $this->fields . " FROM " . $this->tb_name . $this->where . $this->order . $this->limit;
        $this->statement = $this->db->prepare($this->sql);
        $this->statement->execute();
        $this->statement-> setFetchMode ( PDO::FETCH_ASSOC );
        return $this->statement->fetchAll();
    }

    public function insert(){
        /*
         * 插入数据
         * 返回最后插入的ID
         */
        $this->sql = "INSERT INTO " . $this->tb_name . " SET " . $this->data;
        print $this->sql;
        $this->statement = $this->db->prepare($this->sql);
        $this->statement->execute();
        if($this->errno() >0 ) {
            var_dump($this->errorInfo());
        }
        return $this->insertLastID();
    }

    public function update(){
        /*
         * 更新数据
         * 返回影响的行数
         */
        $this->sql = "UPDATE " . $this->tb_name . " SET " . $this->data . $this->where;
        print $this->sql;
        $this->statement = $this->db->prepare($this->sql);
        $this->statement->execute();
        if($this->errno() >0 ) {
            var_dump($this->errorInfo());
        }
        return $this->statement->rowCount();
    }

    public function delete(){
        /*
         * 删除数据
         * 返回影响的行数
         */
        $this->sql = "DELETE FROM " . $this->tb_name . $this->where;
        print $this->sql;
        $this->statement = $this->db->prepare($this->sql);
        $this->statement->execute();
        if($this->errno() >0 ) {
            var_dump($this->errorInfo());
        }
        return $this->statement->rowCount();

    }

    public function queryCount(){
        /*
         * 返回查询数据的条数
         */
        return $this->statement->columnCount();
    }

    public function affectedRows(){
        /*
         * 返回影响行数
         */
        return $this->statement->rowCount();
    }

    public function insertLastID(){
        /*
         * 返回最后插入的ID
         */
        return $this->db->lastInsertId();
    }

    public function errorInfo(){
        /*
         * 返回错误信息
         */
        return $this->statement->errorInfo();
    }

    public function errno(){
        return $this->statement->errorCode();
    }
    public function __destruct(){
        // TODO: Implement __destruct() method.
        $this->db = Null;
    }

}

$test = new Mysql($config);
$test->table("a");
// var_dump($test->fields("*")->where("id=31 or id=32")->query());
// echo $test->data(array("tid"=>12,"name"=>"test"))->insert();
// echo $test->data(array("tid"=>111111111,"name"=>"update"))->where("id=32")->update();

// echo $test->delete();