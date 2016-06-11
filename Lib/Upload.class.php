<?php
/**
 * Created by PhpStorm.
 * User: Cain
 * Date: 2016/6/6
 * Time: 20:23
 */

class Upload{
    /*
     * Upload
     * 文件上传类,用于文件上传等等。。。
     */

    private $File;

    private $upload_dir;
    /*
     *上传目录
     */
    private $allowed_size;
    /*
     * 允许上传的文件大小
     */
    private $allowed_mimetype = array("application/pdf",
        "image/bmp",
        "image/gif",
        "image/jpeg",
        "image/png"
        );
    /*
     * 允许上传的mime类型，判断主类型就可以了
     * 不过没什么卵用，抓包拦截就好了
     */
    private $allowed_suffix = array();
    /*
     * 允许上传文件的后缀名，数组类型
     */
    private $filesize;
    /*
     * 上传文件的大小，字节为单位
     */
    private $mimetype;
    /*
     * 上传文件mime类型
     */
    private $suffix;
    /*
     * 上传文件后缀名
     */
    private $tmp_name;
    /*
     * 临时文件名
     */
    private $name;
    /*
     * 客户端文件的原名称
     */
    private $inner_error = array("Upload success",
        "The size was more than upload_max_filesize",
        "The size was more than MAX_FILE_SIZE",
        "Uploaded part not the all",
        "No file was uploaded",
        "Tmp folder is missing",
        "Failed to write to the tmp folder",
        "Failed to write into the tmp folder",
    );
    /*
     * 文件上传内部错误信息
     */
    private $outer_error = array(
        "size" => "Uploaded file's size is too big",
        "suffix" => "Uploaded file's suffix is not allowed",
        "mimetype" => "Uploaded file's mimetype is not allowed"
    );
    /*
     * 文件上传自定义错误信息
     */


    /*
     * 文件错误信息，来此$_FILE["error"]
     */
    private $ren;
    /*
     * 是否重命名，默认重命名
     */
    public function __construct($upload_file,
                                $upload_dir = "../uploads",
                                $allowed_size = 2097152,
                                $rename = False,
                                $allowed_suffix = array("png","jpg","jpeg","gif")){
        $this->File = $upload_file;
        $this->setUploadDir("$upload_dir");
        $this->setAllowedSize($allowed_size);
        $this->setAllowedSuffix($allowed_suffix);
        $this->setRen($rename);
        $this->getMimeType();
        $this->getSuffix();
        $this->getFileSize();
        $this->getTmpName();
        $this->getName();
    }

    private function getUploadDir(){
        /*
         * 获取上传目录
         */
        return $this->upload_dir;
    }
    private function getAllowedSize(){
        /*
         * 返回允许上传的文件大小
         */
        return $this->allowed_size;
    }
    private function getAllowedMime(){
        /*
         * 返回允许上传的文件MIME类型
         */
        return $this->allowed_mime;
    }
    private function getAllowedSuffix(){
        /*
         * 获取允许上传的文件后缀
         */
        return $this->allowed_suffix;
    }
    private function getRen(){
        /*
         * 返回是否重命名
         */
        return $this->ren;
    }
    private function getMimeType(){
        /*
         * 获取文件的mimetype
         */
        $this->mimetype = $this->File["type"];
    }
    private function getSuffix(){
        /*
         * 获取文件的后缀名
         */
        $this->suffix = pathinfo($this->File["name"],PATHINFO_EXTENSION);
    }
    private function getFileSize(){
        /*
         * 获取文件上传文件的大小，字节为单位
         */
        $this->filesize = $this->File["size"];
    }
    private function getTmpName(){
        /*
         * 获取文件的临时名
         */
        $this->tmp_name = $this->File["tmp_name"];
    }
    private function getName(){
        $this->name = $this->File["name"];
    }

    private function setUploadDir($dir){
        /*
         * 设置文件上传的目录
         */
        $this->upload_dir = $dir;
    }
    private function setAllowedSize($size){
        /*
         * 设置允许文件上传的后缀
         */
        $this->allowed_size = $size;
    }
    private function setAllowedMime($arr){
        /*
         * 设置运行的文件MIME类型
         */
        $this->allowed_mimetype = $arr;
    }
    private function setAllowedSuffix($allowed_suffix){
        /*
         * 设置允许上传的文件后缀
         */
        $this->allowed_suffix = $allowed_suffix;
    }
    private function setRen($ren){
        /*
         * 设置重命名
         */
        $this->ren = $ren;
    }
    private function setName(){
        /*
         * 设置文件名，如果重命名那么以(文件名+时间戳)摘要的形式进行重命名
         * *************************************************************************************************************
         * 否则不进行处理,上传的文件会被覆盖！！！
         */
        if ($this->ren) {
            /*
             * 重命名
             */
            $this->name = $this->upload_dir."/".md5($this->File["name"].time()).".".$this->suffix;
        }else{
            $this->name = $this->upload_dir."/".$this->File["name"];
        }
    }

    public function run(){
        if($this->filesize>$this->allowed_size){
            throw new Exception($this->outer_error["size"]);
        }
        if(!in_array($this->mimetype,$this->allowed_mimetype)){
            echo $this->mimetype;
            var_dump($this->allowed_mimetype);
            throw new Exception($this->outer_error["mimetype"]);
        }
        if(!in_array($this->suffix,$this->allowed_suffix)){
            throw new Exception($this->outer_error["suffix"]);
        }
        if ($this->File["error"] > 0){
            throw new Exception($this->inner_error[$this->File["error"] ]);
        }
        $this->setName();
        if (is_uploaded_file($this->tmp_name)){
            if(move_uploaded_file($this->tmp_name,$this->name)){
                return $this->name;
            }else{
                throw new Exception("Move uploaded file error");
            }
        }else{
            throw new Exception("$this->tmp_name is not uploaded file");
        }
    }
}

$test = new Upload($_FILES["file"]);
$test->run();