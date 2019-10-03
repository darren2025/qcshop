<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Right extends BaseApi
{
    /**
     * 权限列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 判断类型数据
        $params = input();

       $data= \app\adminapi\model\Permission::select();
       $data = (new \think\Collection($data))->toArray();
       foreach ($data as  &$value) {
           $value['id'] = $value['ps_id'];
           $value['authName']  =$value['ps_name'];
           $value['level']  =$value['ps_level'];
           $value['path'] = $value['ps_c'];
           $value['pid'] = $value['ps_pid'];
        
       }
        if ($params['type']=='list' ) {
            // 无限极分类
           $list= get_cate_list($data);
        }else {
            // 树状结构
           $list = get_tree_list($data);
        }
        $this->ok($list,200, "获取权限列表成功");

    }

  
}
