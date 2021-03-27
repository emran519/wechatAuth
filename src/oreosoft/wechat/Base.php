<?php


namespace oreosoft\wechat;

/**
 * SDK类基类
 */
abstract class Base
{
    /**
     * 1=>获取Code;
     * 2=>Code兑换OpenId和access_token
     * 3=>Code兑换OpenId
     * 4=>根据access_token和OpenId获取用户信息
     * @var int
     */
    public $type;

    /**
     * 小程序AppId
     * @var string
     */
    public $appId;

    /**.
     * 小程序appSecret
     * @var string
     */
    public $appSecret;

    /**.
     * scope
     * @var string
     */
    public $scope;

    /**
     * 返回地址
     * @var array
     */
    public $redirectUri;

    /**
     * 用户Code
     * @var string
     */
    public $code;

    /**
     * 用户access_token
     * @var string
     */
    public $accessToken;

    /**
     * 最后请求的结果
     * @var mixed
     */
    public $result;

    /**
     * 用户openId
     * @var string
     */
    public $openId;

    /**
     * 获取错误信息
     * @param array $result
     * @return string
     */
    protected abstract function __getError($result);


    /**
     * 获取错误信息
     * @param array $result
     * @return string
     */
    public function getError($result = null)
    {
        return $this->__getError(null === $result ? $this->result : $result);
    }


    /**
     * author : 饼干<609451870@qq.com>
     * date : 2021/3/25 23:12
     *
     * @param int $type
     * @return bool|mixed|string
     */
    public function getHttp($type){
        $obj = $this->url.'?'.http_build_query($this->params);
        if($type==1){
            header('Location:'.$obj);
            exit();
        } else if($type==2||$type==3){
            return json_decode(file_get_contents($obj),true);
        }else{
            $ch = curl_init($obj);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);// 超时设置,以秒为单位
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $data  = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            if($error) return $this->getError('请求发生错误：' . $error);
            return  $data;
        }
    }

}