<?php

namespace app\adminapi\model;

use think\Model;

class Goods extends Model
{
    protected $createTime ='add_time';
    protected $updateTime ='upd_time';
    protected $deleteTime ='delete_time';

    // 建立图片关联模型
    public function pics()
    {
        return $this->hasMany('GoodsPics','pics_id','goods_id');
    }
    // 建立商品的属性关联模型
    public function attrs()
    {
        return $this->hasMany('GoodsAttr','id','goods_id');
    }
}
