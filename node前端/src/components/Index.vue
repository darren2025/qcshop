<template>
    <div>
        <el-row :gutter="20">
            <div class="fl"><el-tag>操作系统:{{this.system}}</el-tag></div>
            <div  class="fl"></div><el-tag type="warning">已不间断运行:{{time}}</el-tag>
            <div  class="fl"></div><el-tag type="info">cpu核心数:{{cpuNum}}</el-tag>
            <div  class="fl"></div><el-tag type="info">内存总量:{{memTotal}}MB</el-tag>
            <div class="fl"><el-tag type="success">面板版本:{{this.version}}</el-tag></div>
        </el-row>
        <el-row :gutter="20" class="el-row1">
        <el-link type="primary">CPU使用率</el-link>
          <el-progress type="circle" :percentage="cpuRealUsed"></el-progress>
          <el-link type="primary">内存使用率</el-link>
          <el-progress type="circle" :percentage="memRealUsed"></el-progress>
        </el-row>

    </div>
</template>

<script>
import {api_bt} from '@/api/Bt-api'
export default {
    data() { 
        return {
          // 操作系统信息
          system:"",
          // 面板版本
          version:"",
          // 上次开机到现在时间
          time:"",
          // cpu核心数
          cpuNum:"",
          // cpu使用率
          cpuRealUsed:0,
          // 物理内存容量
          memTotal:0,
          // 已经使用内存内容
          memRealUsed:0,
          // 可用的物理内容
          memFree:"",
          // 缓存化内存
          memCached:"",
          // 系统缓存
          memBuffers:"",
        }
    },
    created() {
      api_bt().then(res=>{
        this.system=res.system
        this.version=res.version
        this.time=res.time
        this.cpuNum=res.cpuNum
        this.cpuRealUsed=res.cpuRealUsed
        this.memTotal=res.memTotal
        this.memRealUsed=res.memRealUsed/10
        this.memFree=res.memFree
        this.memCached=res.memCached
        this.memBuffers=res.memBuffers
      })
    },
}
</script>

<style lang="scss" scoped>
.fl{
  float: left;
}
.el-row1{
  margin-top:20px;
}
</style>