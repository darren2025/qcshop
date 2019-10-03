<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class User extends BaseApi
{
    /**
     * 用户列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 接受参数
        $params = input();
        if (empty($params['pagesize'])) {
            $params['pagesize'] = 10;
        }
        if (empty($params['pagenum'])) {
            $params['pagenum'] = 1;
        }
        $where = [];
        if (isset($params['query'])) {
            $keyword = $params['query'];
            $where['mg_name'] = ['like',"%$keyword%"];
        }
        $list = \app\adminapi\model\Manager::with('role')->where($where)->paginate($params['pagesize']);
        // $list = (new \think\Collection($list))->toArray();
        $list= json_encode($list, JSON_UNESCAPED_UNICODE);
        $list = json_decode($list,true);
        

        $list['pagenum'] = $list['current_page'];
        unset($list['current_page']);
        $list['users'] = $list['data'];
        foreach ($list['users'] as $key => &$value) {
            if ($value['role_id'] ==-1) {
                $value['role_name']  ='超级管理员';
            }
            if ($value['mg_state'] ==1) {
                $value['mg_state']  =true;
            }else {
                $value['mg_state']  = false;
            }
            $value['create_time'] = $value['mg_time'];
            $value['email'] = $value['mg_email'];
            $value['id'] =$value['mg_id'];
            $value['mobile'] = $value['mg_mobile'];
            $value['username'] =$value['mg_name'];
            unset($value['mg_time'], $value['mg_email'], $value['mg_id'], $value['mg_mobile'], $value['mg_name']);
        }
        unset($list['data']);
        
        $this->ok($list,200,'获取成功');

    }

   

    /**
     * 添加用户
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 接受参数
        $params = input();
        // 验证参数
        $validate = $this->validate($params,[
            'username' => 'require|unique:manager,mg_name',
            'password' => 'require',
            'email' => 'email',
            'mobile' =>'regex:1[3-9]\d{9}',
        ]);
        if ($validate !==true) {
            $this->fail($validate);
        }

        $id = \app\adminapi\model\Manager::max('mg_id');
        // 转成数据表的需要的字段
        $params['mg_name'] = $params['username'];
        $params['mg_pwd']  = $params['password'];
        $params['mg_mobile']  = $params['mobile'];
        $params['mg_email']  = $params['email'];
        $params['mg_state']  = '';
        $params['role_id']  = -1;
        $params['mg_id'] = $id+1;

        // 添加到数据表
        $user_info=\app\adminapi\model\Manager::create($params,true);
        // 查询数据
        $list= \app\adminapi\model\Manager::where('mg_id',$user_info['mg_id'])->find();
        // 返回数据
        $this->ok($list,201,'用户创建成功');

    }


    public function state()
    {
        // 接受参数
        $params = input();
        $validate = $this->validate($params,[
            'mg_id' => 'require',
            'state'=> 'require'
        ]);
        
        if ($validate !==true) {
            $this->fail($validate);
        }
        // 查询数据
        $user_info = \app\adminapi\model\Manager::where('mg_id',$params['mg_id'])->find();
        if (!$user_info) {
            $this->fail('用户不存在');
        }
        if ($params['state']=='false') {
            $params['mg_state'] =0;
        }else{
            $params['mg_state'] = 1;
        }
        // 修改状态
        $res = \app\adminapi\model\Manager::update(['mg_state'=>$params['mg_state']],['mg_id'=>$params['id']],true);
        // 返回数据
        if (!$res) {
           $this->fail('更新失败',500);
        }
        // 新数据 查询
        $this->ok($res,201, '设置状态成功');
    }

    public function getuser()
    {
        $params = input();
        $validate = $this->validate($params, [
            'id' => 'require|number',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }

        $list = \app\adminapi\model\Manager::where('mg_id',$params['id'])->find();

        $this->ok($list,200,'更新成功');
    }

    public function edit (Request $request) 
    {
        $params = input();
        if (strpos($request->url(),'state')) {
            $this->state();die;
        }
        $validate = $this->validate($params, [
            'id' => 'require|number',
            'email' => 'email',
            'mobile' => 'regex:1[3-9]\d{9}',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        $params['mg_mobile']  = $params['mobile'];
        $params['mg_email']  = $params['email'];
        $params['mg_id']  = $params['id'];
        $list = \app\adminapi\model\Manager::update($params,true);
        
        $this->ok($list,200,'更新成功');
    }

    public function delete()
    {
        $params = input();

        $validate = $this->validate($params, [
            'id' => 'require|number',
        ]);

        if ($validate !== true) {
            $this->fail($validate);
        }

        \app\adminapi\model\Manager::destroy(['mg_id'=>$params['id']]);

        $this->ok([],200,'删除成功');
    }

    public function useRole()
    {
        $params = input();

        $validate = $this->validate($params, [
            'id' => 'require|number',
            'rid' => 'require|number',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        \app\adminapi\model\Manager::where('mg_id',$params['id'])->update(['role_id'=>$params['rid']]);

        $user_info = \app\adminapi\model\Manager::where('mg_id', $params['id'])->find();

        $this->ok($user_info,200, '设置角色成功');
    }

    
}
