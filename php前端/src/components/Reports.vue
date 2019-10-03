<template>
    <div>
  <el-button type="primary" plain @click="zhu">柱状图</el-button>
  <el-button type="success" plain @click="zexian">折线图</el-button>
  <el-button type="info" plain @click="bing">饼图</el-button>
            <!-- 数据统计 -->
            <div ref="main" style="width: 1100px;height:600px;"></div>


    </div>
</template>

<script>
import echarts from 'echarts'
import {getreports} from '@/api'
export default {
    data() { 
        return {
            // 用于储存服务器取得的数据
            option:[],
            echartsdom:null
        }
    },
    mounted() {
        // 获取相关的数据
        getreports("1").then(res=>{
            
        this.option=res.data
        this.option.title={
            text:"全国销量统计"
        }
        // 获取dom元素
        this.echartsdom=echarts.init(this.$refs.main)

        // 实施配置
        this.option.series.forEach(item => {
            item.type='bar'
        });
        this.echartsdom.setOption(this.option)
        })

    },
    methods: {
        zhu(){
            // 获取dom元素
            this.echartsdom=echarts.init(this.$refs.main)
            // 实施配置
            this.option.series.forEach(item => {
                item.type='bar'
            });
            this.echartsdom.setOption(this.option)
        },
        zexian(){
            getreports("1").then(res=>{
            this.option=res.data
            this.option.title={
                text:"全国销量统计"
            }
            // 获取dom元素
            this.echartsdom=echarts.init(this.$refs.main)

            this.echartsdom.setOption(this.option)
            })
        },
        bing(){
            // 获取dom元素
            this.echartsdom=echarts.init(this.$refs.main)
            // 实施配置
            this.option.series.forEach(item => {
                item.type='pie'
            });
            this.echartsdom.setOption(this.option)
         
        }
    },
    
}
</script>

<style lang="css" scoped>
</style>