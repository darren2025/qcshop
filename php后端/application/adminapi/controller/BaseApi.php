<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class BaseApi extends Controller
{
    protected $no_login = ['login/login', 'login/logout'];

   public function __construct()
   {
       parent::__construct();

        //允许的源域名
        header("Access-Control-Allow-Origin: *");
        //允许的请求头信息
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        //允许的请求类型
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        // 验证登录
        $this->checkLogin();
   }

    /**
     * 通用方法
     * @param int    $code 返回的状态码
     * @param string $msg 返回的信息
     * @param array  $data 返回的数据
     */
    public function reponse(int $code = 200, string $msg = 'success', $data = [])
    {
        // 设置返回的数据
        $res = [
            'meta' => [
                'status' => $code,
                'msg' => $msg,
            ],
            'data' => $data,
        ];
        // 返回数据,并且结束执行 send结束执行
        json($res)->send();
        die;
    }
    /**
     * 封装成功的方法
     * @param array $data 要返回的数据,其他可以不填写
     */
    public function ok($data = [], $code = 200, $msg = 'success')
    {
        return $this->reponse($code, $msg, $data);
    }

    /**
     * @param string $msg 要提示的错误信息,其他可以去填写
     */
    public function fail(string $msg, int $code = 500, $data = [])
    {
        return $this->reponse($code, $msg, $data);
    }


    public function checkLogin()
    {
        try {
            $path = strtolower($this->request->controller()) . '/' . $this->request->action();
            if (!in_array($path, $this->no_login)) {
                $mg_id = \tools\jwt\Token::getUserId();
                //登录验证
                if (empty($mg_id)) {
                    $this->fail('未登录或Token无效', 403);
                }
                //将获取的用户id 设置到请求信息中
                $this->request->get(['mg_id' => $mg_id]);
                $this->request->post(['mg_id' => $mg_id]);
            }
        } catch (\Exception $e) {
            $this->fail('服务异常，请检查token令牌', 403);
        }
    }
}
