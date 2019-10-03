<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;
use think\Validate;
class Goods extends BaseApi
{
    /**
     * 商品列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 接受参数
        $params = input();
        $where = [];
        // 验证参数
        $validate = $this->validate($params,[
            'pagenum|当前页码' => 'require',
            'pagesize|每页显示条数' =>'require',
        ]);
        if ($validate !==true) {
            $this->fail($validate);
        }
        // 查询数据
        if (isset($params['query'])) {
            // 存在
            $keyword = $params['query'];
            $where['goods_name'] =['like',"%$keyword%"];
        }
        $list = \app\adminapi\model\Goods::where($where)->paginate($params['pagesize']);
        // $list = (new \think\Collection($list))->toArray();
        $list = json_encode($list, JSON_UNESCAPED_UNICODE);
        $list = json_decode($list, true);
        $list['pagenum'] = $list['per_page'];
        unset($list['per_page']);
        $list['goods'] = $list['data'];
        unset($list['data']);
        $this->ok($list);
        // 返回数据
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
     * 新增商品
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 接受参数
        $params = input();
        // 验证参数
        $validate = $this->validate($params, [
            'goods_name' => 'require',  //商品名
            'goods_cat' => 'require', //提交是字符串 商品分类
            'goods_price' => 'require',  //商品价格
            'goods_number' => 'require', //商品库存
            'goods_weight' => 'require',    //商品重量
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        // 处理分类的数据
        $array = explode(',',$params['goods_cat']);
        $params['cat_id'] = $array[2];
        $params['cat_one_id'] = $array[0];
        $params['cat_two_id'] = $array[1];
        $params['cat_three_id'] = $array[2];

        // 开启事务
         \think\Db::startTrans();
         try {
            // 开始写入数据
            $addGoods = \app\adminapi\model\Goods::create($params, true);
            // 先判断,不然会出错,遍历空数组会出错
            if (!empty($params['pics'])) {
                // 图片的数据是数组
                $adddata = [];
                foreach ($params['pics'] as $key => $value) {
                    $adddata[$key] = [
                        'pics_big' => $value['pic'],
                        'pics_mid' => $value['pic'],
                        'pics_sma' => $value['pic'],
                        'goods_id' => $addGoods['goods_id'],
                    ];
                }
                $addimages = new \app\adminapi\model\GoodsPics();
                $images = $addimages->allowField(true)->saveAll($adddata);
            }
            // 暂时不做
            if (!empty($params['attrs'])) {
                $attr = [];
                foreach ($params['attrs'] as $key => $value) {
                    $attr[$key] =[
                        'goods_id' => $addGoods['goods_id'],
                        'attr_id'  =>$value['attr_id'],
                    ];
                }

            }

            // 执行事务;
            \think\Db::commit();
            $list = \app\adminapi\model\Goods::with('pics', 'attrs')->where('goods_id', $addGoods['goods_id'])->find();
            $this->ok($list,201,'创建商品成功');
         } catch (\Exception $e) {
            //  回滚
             \think\Db::rollback();
            //  获取错误信息
            $msg = $e->getMessage();

            $this->fail($msg);
            
         }
      

        
    }

    /**
     * 根据商品的id获取指定数据
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $list = \app\adminapi\model\Goods::with('pics', 'attrs')->where('goods_id', $id)->find();
        $this->ok($list,201,'创建商品成功');
    }

    /**
     * 编辑商品
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
            'goods_name' => 'require',  //商品名
            'goods_cat' => 'require', //提交是字符串 商品分类
            'goods_price' => 'require',  //商品价格
            'goods_number' => 'require', //商品库存
            'goods_weight' => 'require',    //商品重量
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        // 处理分类的数据
        $array = explode(',', $params['goods_cat']);
        $params['cat_id'] = $array[2];
        $params['cat_one_id'] = $array[0];
        $params['cat_two_id'] = $array[1];
        $params['cat_three_id'] = $array[2];

        // 开启事务
        \think\Db::startTrans();
        try {
            // 开始写入数据
            $addGoods = \app\adminapi\model\Goods::update($params, true);
            // 先判断,不然会出错,遍历空数组会出错
            if (!empty($params['pics'])) {
                // 图片的数据是数组
                $adddata = [];
                foreach ($params['pics'] as $key => $value) {
                    $adddata[$key] = [
                        'pics_big' => $value['pic'],
                        'pics_mid' => $value['pic'],
                        'pics_sma' => $value['pic'],
                        'goods_id' => $addGoods['goods_id'],
                    ];
                }
                $addimages = new \app\adminapi\model\GoodsPics();
                $addimages->delete(['goods_id'=> $addGoods['goods_id']]);
                $images = $addimages->allowField(true)->saveAll($adddata);
            }
            // 暂时不做
            if (!empty($params['attrs'])) {
                $attr = [];
                foreach ($params['attrs'] as $key => $value) {
                    $attr[$key] = [
                        'goods_id' => $addGoods['goods_id'],
                        'attr_id'  => $value['attr_id'],
                    ];
                }
            }

            // 执行事务;
            \think\Db::commit();
            $list = \app\adminapi\model\Goods::with('pics', 'attrs')->where('goods_id', $addGoods['goods_id'])->find();
            $this->ok($list, 201, '创建商品成功');
        } catch (\Exception $e) {
            //  回滚
            \think\Db::rollback();
            //  获取错误信息
            $msg = $e->getMessage();

            $this->fail($msg);
        }
      
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
     * 删除商品
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        // 开启事务
        \think\Db::startTrans();
        try {
            // 先删除商品的数据
            \app\adminapi\model\Goods::destroy(['goods_id' => $id]);
            // 删除图片表
            // 先查询数据
            $goods_images = \app\adminapi\model\GoodsPics::where(['goods_id' => $id])->select();
            $goods_images = (new \think\Collection($goods_images))->toArray();
            foreach ($goods_images as  $value) {
                // 删除pics_big
                if (file_exists($value['pics_big'])) {
                    unlink('.' . $value['pics_big']);
                }
                // 删除pics_mid
                if (file_exists($value['pics_mid'])) {
                    unlink('.' . $value['pics_mid']);
                }
                // 删除pics_sma
                if (file_exists($value['pics_sma'])) {
                    unlink('.' . $value['pics_sma']);
                }
            }
            \app\adminapi\model\GoodsPics::destroy(['goods_id' => $id]);
            // // 删除属性表
            \app\adminapi\model\GoodsAttr::destroy(['goods_id' => $id]);
            $this->ok(null,200,'删除成功');
            // 写入数据
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollback();
            $msg = $e->getMessage();
            $this->fail($msg);
        }
    }


    // 图片上传接口
    public function upload()
    {
        // 接受数据
        $params = input(true);

        // 拼接路径
        $dir = ROOT_PATH .DS. 'public' .DS. 'Uploads'.DS;

        // 判断类型
        $file = $params['file'];

        $info = $file->validate(['size'=>10*1024*1024,'ext'=>'jpg,png,gif,jpeg','type'=>'image/jpeg,image/png,image/gif'])->save($dir);

        // 判断图片是否上传成功
        if (!$info) $this->fail($file->getError());


        // 拼接路径
        $temp_path = DS . 'uploads' . DS . $params['file'] . $info->getSaveName();
        $url = request()->domain(). $temp_path;
        $res = [
            'temp_path'=> $temp_path,
            'url' => $url,
        ];
        // 返回结果
        $this->ok($res);
    }
}
