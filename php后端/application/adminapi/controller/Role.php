<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Role extends BaseApi
{
    /**
     * 角色列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 接受参数
        $params = input();
        
        if (isset($params['id'])) {
            // 查询数据
            $list = \app\adminapi\model\Role::where('role_id',$params['id'])->find();
            $this->ok($list, 200, '获取成功');
            
        }else {
            $role = \app\adminapi\model\Role::select();
            $role = (new \think\Collection($role))->toArray();
            foreach ($role as  &$v) {
                $v['roleDesc'] = $v['role_desc'];
                $v['roleName'] = $v['role_name'];
                $v['id'] = $v['role_id'];
                unset($v['role_desc'], $v['role_name'], $v['role_id']);
                $v['children'] = explode(',', $v['ps_ids']);
                $list = \app\adminapi\model\Permission::where('ps_id', 'in', $v['children'])->select();
                $list = (new \think\Collection($list))->toArray();
                $v['children'] = get_tree_list($list);

            }

            $this->ok($role, 200, "获取成功");
        }


    }

    // 添加角色
    public function save()
    {
        // 接受参数
        $params = input();
        // 验证参数
        $validate = $this->validate($params,[
            'roleName|角色名称' => 'require',
        ]);
        if ($validate !==true) {
            $this->fail($validate);
        }
        $params['role_name'] = $params['roleName'];
        $params['role_desc'] = $params['roleDesc'];
        // 写入数据库
        $list = \app\adminapi\model\Role::create($params,true);
        // 返回响应
        $this->ok($list,200, "创建成功");

    }

    // 编辑用户
    public function edit()
    {
        // 接受参数
        $params = input();
        // 验证参数
        $validate = $this->validate($params, [
            'roleName|角色名称' => 'require',
            'id' =>'require',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        
        // 改变值
        $params['role_name'] = $params['roleName'];
        $params['role_desc'] = $params['roleDesc'];
        $params['role_id']  = $params['id'];
        $list = \app\adminapi\model\Role::update($params,['role_id'=>$params['role_id']],true);

        $this->ok($list,200,'获取成功');
        
    }

    // 删除角色
    public function delete()
    {
        // 接受参数
        $param = input();   

        \app\adminapi\model\Role::destroy(['role_id'=>$param['id']]);
        $this->ok([],200,'删除成功');
    }

    // 角色授权
    public function role()
    {
        // 接受参数
        $param = input();
        // 验证参数
        $validate = $this->validate($param, [
            'roleId|角色id' => 'require',
            'rids|权限列表' => 'require',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        $param['ps_ids'] = $param['rids'];
        $param['role_id'] = $param['roleId'];

        $list =\app\adminapi\model\Role::update($param,['role_id'=>$param['ps_ids']],true);

        $this->ok([], 200, '更新成功');
    }

    // 角色权限删除
    public function roledelete()
    {
        // 接受参数
        $param = input();
        // 验证参数
        $validate = $this->validate($param, [
            'roleId|角色id' => 'require',
            'rightId|权限列表' => 'require',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        $param['ps_ids'] = $param['rightId'];
        $param['role_id'] = $param['roleId'];

        $list = \app\adminapi\model\Role::update($param, ['role_id' => $param['ps_ids']], true);

        $array = $list['ps_ids'];

        $list = \app\adminapi\model\Permission::where('ps_id','in',$array)->select();

        $list = (new \think\Collection($list))->toArray();

        $list = get_tree_list($list);

        $this->ok($list,200,'取消权限成功');



    }
}
