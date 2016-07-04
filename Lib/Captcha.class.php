<?php
/**
 * Created by PhpStorm.
 * User: Cain
 * Date: 2016/7/4
 * Time: 20:30
 */

class Captcha{
    /*
     * Captcha类
     * 利用GD库生成验证码
     */
    private $img_width = 100;   # 验证码宽度
    private $img_height = 30;   # 验证码高度
    private $verify_code = "";  # 验证码
    private $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";    # 字符集

    public function __construct(){
        $this->setCode();
    }

    public function getCode(){
        /*
         * 返回验证码
         */
        return $this->verify_code;
    }

    private function setCode(){
        /*
         * 生成四位的随机验证码
         */
        for($i = 0; $i < 4; $i++){
            $this->verify_code .= $this->charset[rand(0,61)];
        }
    }

    public function createCaptcha(){
        $img = imagecreate($this->img_width,$this->img_height);
        // imagecolorallocate(),第一次调用作将颜色填充到图像，设置背景色
        $white = imagecolorallocate($img, 255, 255, 255);
        $green = imagecolorallocate($img, 0, 255, 0);
        $red = imagecolorallocate($img, 255, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 255);

        $path = '../font/stocky.ttf';

        for ($i = 0; $i < 4; $i++){
            //为字体添加随机颜色
            $text_color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
            //写入图像，设置随机旋转
            imagettftext($img, 15, rand(-30,+30), 10 + $i * 20, 20, $text_color, $path, substr($this->verify_code,$i,1));
        }
        //红色绿蓝分别两个像素，中间间隔两个白色像素
        $style = array($red, $red, $white, $white, $green, $green, $white, $white, $blue, $blue);
        //添加style
        imagesetstyle($img, $style);
        //分别画两条直线，并添加了随机位置
        imageline($img, rand(0,10), rand(0,30), rand(80,100), rand(0,30), IMG_COLOR_STYLED);
        imageline($img, rand(0,10), rand(0,30), rand(80,100), rand(0,25), IMG_COLOR_STYLED);
        //输出图像；
        imagepng($img);
        imagedestroy($img);
    }

}

$test = new Captcha();
header("Content-type: image/png");
echo $test->createCaptcha();