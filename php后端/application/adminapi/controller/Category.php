<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Category extends BaseApi
{
    /**
     * 商品数据列表
     *
     * @return \think\Response
     */
    public function index()
    {

        // 传入 1 为1级分类
        // 传入 2 为2分类
        // 传入 3 为3分类
        $params= input();
        $validate = $this->validate($params, [
            'type' => 'require',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        if ($params['type']==1) {
            // 查询分类为1的
            $list = \app\adminapi\model\Category::where('cat_level','0')->select();
        }elseif($params['type'] == 2){
            // 查询分类的2
            $list = \app\adminapi\model\Category::where('cat_level', 'in','0,1')->select();
        }else {
        //   默认不写或者3级别 
            $list = \app\adminapi\model\Category::select();
        }
        
        // 转换成数据

        $list = (new \think\Collection($list))->toArray();
        $list = get_tree_list_cate($list);

        $this->ok($list,200,'获取成功');
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
     * 商品分类,添加分类
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 接受数据
        $params = input();
        // 参数检测
        $validate = $this->validate($params, [
            'cat_pid|分类父ID' => 'require|number',
            'cat_name|分类名称' => 'require',
            'cat_level|分类层级' => 'require|number',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        // 写入数据
        $list = \app\adminapi\model\Category::create($params,true);
        // 返回数据
        $this->ok($list,201,'创建成功');
    }

    /**
     * 根据ID查询分类
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        // 接受参数
        $param = input();
        // 参数检测
        $validate = $this->validate($param, [
            'id|分类ID' => 'require|number',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        // 查询数据
        $list = \app\adminapi\model\Category::find($param['id']);
        // 返回结果
        $this->ok($list,200,'获取成功');
    }

    /**
     * 编辑分类
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // 接受参数
        $params = input();
        // 验证参数
        $validate = $this->validate($params, [
            'id|分类ID' => 'require|number',
            'cat_name|分类名称' =>'require'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        // 更新数据
        $list = \app\adminapi\model\Category::update($params,['cat_id'=>$params['id']],true);
        // 返回数据
        $this->ok($list);
    }



    /**
     * 删除商品分类
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        // 接受参数
        $param = request()->param();
        // 验证参数
        $validate = $this->validate($param, [
            'id|分类ID' => 'require|number',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        // 查询数据
        $find = \app\adminapi\model\Category::where('cat_pid',$param['id'])->find();
        // 判断查询下面是否还有这个分类的,
        if ($find !==null) {
            $this->fail('该分类下还有其他商品');
        }
        // 删除数据
        \app\adminapi\model\Category::destroy(['cat_id'=>$param['id']]);
        // 返回响应
        $this->ok(null,200,'删除成功');
    }
}
