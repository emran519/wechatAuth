<?php
namespace Oreo\Wechat\Auth;

header("Content-Type: text/html; charset=utf-8");
class WechatAuth{

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
     * 用户openId
     * @var string
     */
    public $openId;


    /**
     * @param int $type
     * @return bool|mixed|string
     */
    public function authIndex(int $type)
    {
        /**
         * 1=>获取Code;
         * 2=>Code兑换OpenId和access_token
         * 3=>Code兑换OpenId
         * 4=>根据access_token和OpenId获取用户信息
         */
        $url = $this->getUrl($type);
        $params = $this->getParams($type);
        $arr = $this->getHttp($type,$url,$params);
        return $arr;
    }


    /**
     * 获参对应Url
     * @param int $type 获取方式
     */
    private function getUrl(int $type){
        if($type==1){
            //获取Code的Url
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
        } else if($type==2){
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token";//Code兑换OpenId和access_token //PC
        } else if($type==3){
            $url = "https://api.weixin.qq.com/sns/jscode2session";//Code兑换OpenId  //小程序
        } else if($type==4){
            $url = "https://api.weixin.qq.com/sns/userinfo";//根据access_token和OpenId获取用户信息
        }
        return $url;
    }

    /**
     * 生成Url参数
     * @param int $type
     * @return mixed
     */
    private  function getParams(int $type){
        if($type==1){
            $params['appid'] = $this->appId;
            $params['redirect_uri'] = $this->redirectUri;
            $params['response_type'] = 'code';
            $params['scope'] = $this->scope;
            $params['state'] = 1;
        } else if($type==2){
            $params['appid'] = $this->appId;
            $params['secret'] = $this->appSecret;
            $params['code'] = $this->code;
            $params['grant_type'] = 'authorization_code';
        } else if($type==3){
            $params['appid'] = $this->appId;
            $params['secret'] = $this->appSecret;
            $params['js_code'] = $this->code;
            $params['grant_type'] = 'authorization_code';
        } else if($type==4){
            $params['access_token'] = $this->accessToken;
            $params['openid'] = $this->openId;
            $params['lang'] = 'zh_CN';
        }
        return $params;
    }

    /**
     * @param int $type
     * @param string $url
     * @param array $params
     * @return bool|mixed|string
     */
    private function getHttp(int $type,string $url,array $params){
        $obj = $url.'?'.http_build_query($params);
        if($type==1){
            header('Location:'.$obj);
            exit();
        } else if($type==2||$type==4){
            $res = json_decode(file_get_contents($obj),true);
            return $res;
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
            if($error) throw new Exception('请求发生错误：' . $error);
            return  $data;
        }
    }
}