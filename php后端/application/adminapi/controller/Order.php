<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Order extends BaseApi
{
    /**
     * 订单显示
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
        // 查询订单编号
        if (isset($params['query'])&&(!empty($params['query']))) {
            $query = $params['query'];
            $where['order_number'] = [$query];
        }
        
        // 查询用户的id
        if (isset($params['user_id']) && (!empty($params['query']))) {
            $user_id = $params['user_id'];
            $where['user_id'] = [$user_id];
        }
        // 查询支付状态
        if (isset($params['pay_status']) && (!empty($params['query']))) {
            $pay_status = $params['pay_status'];
            $where['pay_status'] = [$pay_status];
        }
        
        // 查询支付状态
        if (isset($params['is_send']) && (!empty($params['query']))) {
            $is_send = $params['is_send'];
            $where['is_send'] = [$is_send];
        }
        
        // 查询发票类型
        if (isset($params['order_fapiao_title']) && (!empty($params['query']))) {
            $order_fapiao_title = $params['order_fapiao_title'];
            $where['order_fapiao_title'] = [$order_fapiao_title];
        }
        
        // 查询公司名称
        if (isset($params['order_fapiao_company']) && (!empty($params['query']))) {
            $order_fapiao_company = $params['order_fapiao_company'];
            $where['order_fapiao_company'] = [$order_fapiao_company];
        }

        // 查询发票内容
        if (isset($params['order_fapiao_content']) && (!empty($params['query']))) {
            $order_fapiao_content = $params['order_fapiao_content'];
            $where['order_fapiao_content'] = [$order_fapiao_content];
        }
        
        // 查询发货地址
        if (isset($params['consignee_addr']) && (!empty($params['query']))) {
            $consignee_addr = $params['consignee_addr'];
            $where['consignee_addr'] = [$consignee_addr];
        }
        $list = \app\adminapi\model\Order::where($where)->paginate($params['pagesize']);
        $list = json_encode($list, JSON_UNESCAPED_UNICODE);
        $list = json_decode($list, true);
        $list['pagenum'] = $list['per_page'];
        unset($list['per_page']);
        $list['orders'] = $list['data'];
        unset($list['data']);
        $this->ok($list);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 查询订单信息
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $list = \app\adminapi\model\Order::with('goods')->where('order_id',$id)->find();
        $this->ok($list,200,'获取成功');
    }

    /**
     * 修改订单
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //接受参数
        $params = input();
        // 验证参数
        $validate =$this->validate($params,[
            'id' =>'require', //订单id
            'order_price' =>'require|number',
            'pay_status' =>'require|number',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        unset($params['order_number']);
        // 参数处理
        $params['is_send']=1 ? $params['is_send'] = '是' : $params['is_send'] = '否';
        // 更新数据
        $list = \app\adminapi\model\Order::update($params,['order_id'=>$id],true);
        // 返回
        $this->ok($list,201,'获取成功');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
