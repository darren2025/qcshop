<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 封装公共加密函数
if (!function_exists('encrypt_password')) {
    /**
     * @param string $password 要加密的密码
     */
    function encrypt_password($password)
    {
        // 盐值读取配置文件
        $salt = config('salt');
        return md5(md5($salt . $password));
    }
}

function ljsql($array=[])
{
   if (isset($array['mg_id'])) {
       //   一条的
        $array['id'] = $array['mg_id'];
        unset($array['mg_id']);
        $array['name'] = $array['mg_name'];
        unset($array['mg_name']);
        $array['mobile'] = $array['mg_mobile'];
        unset($array['mg_mobile']);
        $array['email'] = $array['mg_email'];
        unset($array['mg_email']);
        $array['rid'] = $array['role_id'];
        unset($array['role_id']);

    
   }else {
        dump('ok');
        //    多条数据
        foreach ($array as  &$value) {
            if (isset($value['mg_id'])) {
                $value['id'] = $value['mg_id'];
                unset($value['mg_id']);
            }
            if (isset($value['mg_name'])) {
                $value['username'] = $value['mg_name'];
                unset($value['mg_name']);
            }
            if (isset($value['mg_mobile'])) {
                $value['mobile'] = $value['mg_mobile'];
                unset($value['mg_mobile']);
            }
            if (isset($value['mg_email'])) {
                $value['email'] = $value['mg_email'];
                unset($value['mg_email']);
            }
            if (isset($value['role_id'])) {
                $value['rid'] = $value['role_id'];
                unset($value['role_id']);
            }
        }
   }
    unset($value);
    return $array;
}
if (!function_exists('get_cate_list')) {
    //递归函数 实现无限级分类列表
    function get_cate_list($list, $pid = 0, $level = 0)
    {
        static $tree = array();
        foreach ($list as $row) {
            if ($row['ps_pid'] == $pid) {
                $row['ps_level'] = $level;
                $tree[] = $row;
                get_cate_list($list, $row['ps_id'], $level + 1);
            }
        }
        return $tree;
    }
}

if (!function_exists('get_tree_list')) {
    //引用方式实现 父子级树状结构
    function get_tree_list($list)
    {
        //将每条数据中的id值作为其下标
        $temp = [];
        foreach ($list as $v) {
            $v['children'] = [];
            $temp[$v['ps_id']] = $v;
        }
        //获取分类树
        foreach ($temp as $k => $v) {
            $temp[$v['ps_pid']]['children'][] = &$temp[$v['ps_id']];
        }
        return isset($temp[0]['children']) ? $temp[0]['children'] : [];
    }
}
if (!function_exists('get_tree_list_cat')) {
    //引用方式实现 父子级树状结构
    function get_tree_list_cate($list)
    {
        //将每条数据中的id值作为其下标
        $temp = [];
        foreach ($list as $v) {
            $v['children'] = [];
            $temp[$v['cat_id']] = $v;
        }
        //获取分类树
        foreach ($temp as $k => $v) {
            $temp[$v['cat_pid']]['children'][] = &$temp[$v['cat_id']];
        }
        return isset($temp[0]['children']) ? $temp[0]['children'] : [];
    }
}
