<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Login extends BaseApi
{
    // 登录的方法
    public function login()
    {
        // 接受参数
        $params  = input();
        // 验证参数
        $validate= $this->validate($params,[
            'username' => 'require',
            'password' => 'require',
        ]);
        if ($validate !==true) {
            $this->fail($validate,500,[]);
        };
        $params['password'] = encrypt_password($params['password']);
        // 查询数据库
       $user_id= \app\adminapi\model\Manager::where('mg_name',$params['username'])->where('mg_pwd',$params['password'])->find();
        if (!$user_id) {
        //    不存在
            $this->fail('登录失败',500,[]);
        }
        $user_id = $user_id->toArray();
        $user_id = ljsql($user_id);
        $user_id['token'] = \tools\jwt\Token::getToken($user_id['id']);
        // 返回结果
        $this->ok($user_id,200, '登录成功');
    }

    public function up()
    {
       $list= \app\adminapi\model\Manager::select();
        $list = (new \think\Collection($list))->toArray();
        $list = ljsql($list);
        $list = \tools\jwt\Token::getToken($list[0]['id']);
        dump($list);
    }

    public function logout()
    {
        //清空token  将需清空的token存入缓存，再次使用时，会读取缓存进行判断
        $token = \tools\jwt\Token::getRequestToken();
        $delete_token = cache('delete_token') ?: [];
        $delete_token[] = $token;
        cache('delete_token', $delete_token, 86400);
        $this->ok();
    }
}
