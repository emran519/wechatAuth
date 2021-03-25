# WechatAuth
PHP 微信公众号或小程序获取OpenId，获取Code，获取access_token和userInfo类



## 【注】公共参数说明

### 调用函数
| 函数名称  | 类型 | 传参值 | 说明                                                         |
| --------- | ---- | ------ | ------------------------------------------------------------ |
| authIndex | Int  | 默认 1 | 1=>获取Code； 2=>Code兑换OpenId和access_token；3=>小程序Code兑换OpenId；4=>根据access_token和OpenId获取用户信息； |



## 一、【公众号】获取用户Code

### 类名
`\oreo\wechat\auth\WechatAuth`

### 参数
|   名称  |    类型 | 说明 |
| --- | --- | --- |
|  appId  | String | 公众号的唯一标识 |
|  scope | String | 应用授权作用域，snsapi\_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi\_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息） |
|  redirectUri | String | 授权后重定向的回调链接地址 |


### 调用方法（实例）
```php
$wx = new \oreo\wechat\auth\WechatAuth(); //初始化类
$wx->appId = 'wxc123457898'; //公众号appId
$wx->scope = 'snsapi_userinfo'; //这里因业务需要而变
$wx->redirectUri = 'https://www.xxx.com/api/callBack'; //微信授权成功后回调Code的链接
return $wx->authIndex(1);  //调用函数
//完成以上操作后回调地址会收到code (get)
```



## 二、【公众号】Code兑换OpenId和access_token

### 类名
`\oreo\wechat\auth\WechatAuth`

### 参数

|   名称  |    类型 | 说明 |
| --- | --- | --- |
|  appId  | String | 公众号的唯一标识 |
|  appSecret | String | 公众号的SecretKey |
|  code | String | 用户Code(上一步骤获取的用户Code) |
### 调用方法（实例）
```php
$wx = new \oreo\wechat\auth\WechatAuth(); //初始化类
$wx->appId = 'wxc123457898'; //公众号appId
$wx->appSecret = '05c139ee123456abcdef'; //公众号appSecret
$wx->code = $_GET['code']; //第一步获取的code参数
$res = $wx->authIndex(2); //调用函数
/*
var_dump($res);
exit();
*/
//将会获取到
//$access_token = $res['access_token']; //用户access_token
//$openid = $res['openid']; //用户openId
```



## 三、【公众号】access_token和openid兑换用户详细UserInfo

### 类名
`\oreo\wechat\auth\WechatAuth`

### 参数

|   名称  |    类型 | 说明 |
| --- | --- | --- |
|  access_token | String | 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同，这里可以设置上一步骤获取到的用户access_token  |
|  openid | String | 用户的唯一标识，上一步骤获取到的用户openid  |
### 调用方法（实例）
```php
$wx = new \oreo\wechat\auth\WechatAuth(); //初始化类
$wx->accessToken = $access_token; //用户access_token
$wx->openId = $openid; //用户openId
$res = $wx->authIndex(3); //调用函数
/*
var_dump($res);
exit();
*/
//将会获取到
$openid = $res['openid']; //用户openId
$nickname = $res['nickname']; //用户微信名称
$sex = $res['sex']; //用户微信性别 1=>男; 2=>女; 3=>保密
$city = $res['city']; //用户微信设置的城市
$province = $res['province']; //用户微信设置的省份
$country = $res['country']; //用户微信设置的国家
$headimgurl = $res['headimgurl']; //用户微信头像
```


# 四、【小程序】Code兑换OpenId

### 类名
`\oreo\wechat\auth\WechatAuth`

### 参数

|   名称  |    类型 | 说明 |
| --- | --- | --- |
|  appId  | String | 小程序的唯一标识 |
|  appSecret | String | 小程序的SecretKey |
|  code | String | 用户Code(微信小程序登录时获取的code) |
### 调用方法（实例）
```php
$wx = new \oreo\wechat\auth\WechatAuth(); //初始化类
$wx->appId = 'wxc123457898'; //公众号appId
$wx->appSecret = '05c139ee123456abcdef'; //公众号appSecret
$wx->code = $_GET['code']; //微信小程序登录时获取的code
$arr = $wx->authIndex(4);//调用函数
/*
var_dump($arr);
exit();
*/
$arr = json_decode($arr,true);
//将会获取到
$openid = $arr['openid']; //用户openId
$session_key = $arr['session_key'];//用户session_key
```
