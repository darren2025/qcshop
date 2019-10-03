<?php

namespace app\adminapi\model;

use think\Model;

class Order extends Model
{
    // 关联订单商品表
    public function goods()
    {
        return $this->hasMany('OrderGoods','id','order_id');
    }

    public function getcreateTimeAttr($value)
    {
        return $value*1;
    }
    public function getupdateTimeAttr($value)
    {
        return $value*1;
    }
}
