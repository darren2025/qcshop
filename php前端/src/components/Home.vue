<template>
  <div class="Home" v-cloak>
    <el-container>
      <el-aside width="auto">
        <div class="logo"></div>
        <el-menu
          default-active="2"
          class="el-menu-vertical-demo"
          background-color="#545c64"
          text-color="#fff"
          active-text-color="#ffd04b"
          :collapse="isCollapse"
          :router="true"
        >
          <el-submenu  v-for="item in menus" :key="item.ps_id" :index="item.ps_id.toString()">
            <template slot="title">
              <i :class="icons[item.ps_id]"></i>
              <span>{{item.ps_name}}</span>
            </template>
            <el-menu-item-group>
              <el-menu-item :index="item2.ps_c" v-for="item2 in item.children" :key="item2.ps_id">{{item2.ps_name}}</el-menu-item>
            </el-menu-item-group>
          </el-submenu>
        </el-menu>
      </el-aside>
      <el-container>
        <el-header>
          <!-- 顶部的面包导航 -->
            <i class="el-icon-menu toggle-btn" @click="isCollapse = !isCollapse"></i>
            <!-- 顶部的title信息 -->
            <div class="system-title">后台管理系统</div>
            <!-- 欢迎信息 -->
            <div class="welcome">欢迎{{username}}管理员</div>
            <div class="welcome">今天是{{timeNow()}}</div>
            <!-- 退出按钮 -->
            <el-button type="danger" @click="loginout">退　出</el-button>
        </el-header>
        <el-main>
          <router-view></router-view>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import {menus} from '@/api'
import moment from 'moment'
export default {
  data() {
    return {
        isCollapse:false,
        username:localStorage.getItem("username"),
        handleOpen:"",
        handleClose:"",
        menus:[],
        // 所有图标的管理数组
        icons:[],
    };
  },
  methods: {
    loginout(){
      this.$router.push('/login')
      localStorage.clear()
    },
    // 获取当前时间
    timeNow () {
      var arr = ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]
      return moment().format('YYYY年MM月DD日') + '' + arr[moment().format('d')]
    }
  },
  created() {
    menus().then(res=>{
      // 页面加载完成去获取菜单列表
        this.menus=res.data
        console.log(res);
    }),
        // 初始化图标集
    this.icons["125"]="el-icon-service"
    this.icons["103"]="el-icon-setting"
    this.icons["101"]="el-icon-goods"
    this.icons["102"]="el-icon-tickets"
    this.icons["145"]="el-icon-loading"
  },
};
</script>

<style lang="scss" scoped>
.Home {
  height: 100%;
  .el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 400px;
  }
  .el-container {
    height: 100%;
  }
  .el-aside {
    background-color: #545c64;
  }
  .el-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #545c64;
  }
  .logo {
    height: 60px;
    background: url(../assets/images/logo.png);
    background-size: cover;
    background-color: #989898;
  }
  .toggle-btn {
    font-size: 36px;
    color: #989898;
    cursor: pointer;
    line-height: 60px;
  }
  .system-title {
    font-size: 28px;
    color: white;
  }
  .welcome {
    color: white;
  }
}
</style>