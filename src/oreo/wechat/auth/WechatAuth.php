<?php

namespace oreo\wechat\auth;


use oreo\wechat\Base;

class WechatAuth extends Base
{

    /**
     * @var array
     */
    public $params = [];

    /**
     * @var string
     */
    public $url;

    /**
     * author : 饼干<609451870@qq.com>
     * date : 2021/3/25 23:12
     *
     * @param int $type
     * @return bool|mixed|string
     */
    public function authIndex($type = 1)
    {
        $params['appid'] = $this->appId;
        $params['secret'] = $this->appSecret;
        $params['redirect_uri'] = $this->redirectUri;
        $params['response_type'] = 'code';
        $params['scope'] = $this->scope;
        $params['state'] = 1;
        $params['code'] = $this->code;
        $params['grant_type'] = 'authorization_code';
        $params['access_token'] = $this->accessToken;
        $params['openid'] = $this->openId;
        $params['lang'] = 'zh_CN';
        $params['js_code'] = $this->code;
        switch ($type){
            case 1:
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
                break;
            case 2:
                $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
                break;
            case 3:
                $url = "https://api.weixin.qq.com/sns/userinfo";
                break;
            case 4:
                $url = "https://api.weixin.qq.com/sns/jscode2session";
                break;
        }
        $this->url = $url;
        $this->params = $params;
        return $this->getHttp($type);
    }

    protected function __getError($result)
    {
        // TODO: Implement __getError() method.
    }
}