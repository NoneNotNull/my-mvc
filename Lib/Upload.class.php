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
    private $allowed_mime;
    /*
     * 允许上传的mime类型，判断主类型就可以了
     * 不过没什么卵用，抓包拦截就好了
     */
    private $allowed_suffix = array("jpg","jpeg","png","gif");
    /*
     * 允许上传文件的后缀名，数组类型
     */
    private $error_info = array("Upload success",
        "The size was more than upload_max_filesize",
        "The size was more than MAX_FILE_SIZE",
        "Uploaded part not the all",
        "No file was uploaded",
        "Tmp folder is missing",
        "Failed to write to the tmp folder",
        "Failed to write into the tmp folder"
    );
    /*
     * 文件错误信息，来此$_FILE["error"]
     */

    private $ren;
    /*
     * 是否重命名，默认重命名
     */

    public function __construct(){
        $this->setUploadDir("../uploads");
        $this->setAllowedSize(2*1024*1014);
        $this->setAllowedSuffix(array("png","jpg","jpeg"));
        $this->setRen(FALSE);
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
    private function getErrorInfo(){
        /*
         * 返回错误信息数组
         */
        return $this->error_info;
    }
    private function getRen(){
        /*
         * 返回是否重命名
         */
        return $this->ren;
    }

    public function setUploadDir($dir){
        /*
         * 设置文件上传的目录
         */
        $this->upload_dir = $dir;
    }
    public function setAllowedSize($size){
        /*
         * 设置允许文件上传的后缀
         */
        $this->allowed_size = $size;
    }
    public function setAllowedMime($arr){
        /*
         * 设置运行的文件MIME类型
         */
        $this->allowed_mime = $arr;
    }
    public function setAllowedSuffix($arr){
        /*
         * 设置允许上传的文件后缀
         */
        $this->allowed_suffix = $arr;
    }
    public function setRen($ren){
        /*
         * 设置重命名
         */
        $this->ren = $ren;
    }
    public function run(){
        $this->file = $_FILES;
    }
}