<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Menu extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 获取用户的id
        $params = input('mg_id');
        if ($params==0) {
            // 超级管理员
        }else {
            // 然后查询用户的对应rid
            $user_id = \app\adminapi\model\Manager::field('role_id')->where('mg_id', $params)->find();
            $user_id = $user_id->toArray();
            // 根据rid,去查询他是那种权限,炸开里面的数据,批量查询里面的东西,最后进行树状的排序,返回
            $rid = \app\adminapi\model\Role::where('role_id', $user_id['role_id'])->find();
            $array = explode(',', $rid['ps_ids']);
            // 一定要数组
            $list = \app\adminapi\model\Permission::where('ps_id', 'in', $array)->select();
            $list = (new \think\Collection($list))->toArray();

        }

        $list = get_tree_list($list);
        $this->ok($list,200, '获取菜单列表成功');
        
    }

}
