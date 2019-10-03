<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Report extends BaseApi
{
    
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = \app\adminapi\model\Report_1::select();
        $list = (new \think\Collection($list))->toArray();
        $diqu = [];
        $diqucount = [];
        $result = [];
        $riqi = [];
        foreach ($list as  $value) {
            if (!in_array($value['rp1_area'],$diqu)) {
                array_push($diqu,$value['rp1_area']);
                
            }
            if (in_array($value['rp1_area'], $diqu)) {
                $diqucount[$value['rp1_area']][] = $value['rp1_user_count'];
            }
            if (!in_array($value['rp1_date'], $riqi)) {
                array_push($riqi,$value['rp1_date']);
            }
        }
        foreach ($diqucount as $key => $value) {
            $result[] = [
                'name' => $key,
                'type' => 'bar',
                'stack' => '总量',
                'data' => $value,
            ];
        }
        $data = [
            'legend' =>[
                'data'=>$diqu,
            ],
            'yAxis' =>[
                'type'=>'value',
            ],
            'xAxis'=>[
                'data'=>$riqi,
            ],
            'series'=> $result,
            'title'=>[
                'text'=>'全国销量统计',
            ],
        ];

        $this->ok($data,200,'获取成功');
    }

    
}
