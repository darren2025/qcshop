<?php

namespace app\adminapi\model;

use think\Model;

class Manager extends Model
{
    //
    protected $createTime = 'mg_time';// 默认create_time

    public function setMgPwdAttr($value)
    {
        return encrypt_password($value);
    }
    // 禁止tp拿数据后,自动转换成日期
    public function getmgTimeAttr($value)
    {
        return $value*1;
    }

    // 连表查询,根据管理员查询角色名
    public function role()
    {
        return $this->hasOne('Role','role_id', 'role_id')->bind('role_name');
    }
}
