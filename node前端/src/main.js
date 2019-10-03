// 导入vue模块,导入app主页组件,导入路由模块
import Vue from 'vue'
import App from './App'
import router from './router'

// 导入vue路由模块
router.beforeEach((to, from, next) => {
  // 判断用户的访问意图
  if ( to.path == "/login"|| to.path == "/Retions") {
    // 如果用户是访问这个那么我就让他访问
      next()
  }else{
    // 如果用户要访问home，那么我就要去判断在他本地的localstorage是否有token
    if (localStorage.getItem("token")==null) {
      //没有登录
      next("/login")
    }else{
      // 有登陆的
      next()
    }
  }
})
// 导入elementUi组件库
import element from 'element-ui/lib'
import 'element-ui/lib/theme-chalk/index.css'

// 插件安装
Vue.use(element)


// 导入ue相关文件
import '../static/ueditor/ueditor.config'  //导入配置文件
import '../static/ueditor/ueditor.all.min'  
import '../static/ueditor/lang/zh-cn/zh-cn'  
import '../static/ueditor/ueditor.parse'  


// 导入一个sass的项目
import "@/assets/styles/index.scss"
Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>',
  create(){
    localStorage.removeItem()
  },
  watch:{
    // 监听路由路径
    //login 欢迎登陆
    "$route.path":function(){
      //欢迎信息
      switch (this.$route.path) {
        case "/login":
          // 欢迎登陆页面
           this.$message({
             showClose: true,
             message: '欢迎登陆',
             type: 'success',
             duration:1000,
           });
          break;
        case "/Retions":
          // 欢迎注册页面
           this.$message({
             showClose: true,
             message: '欢迎注册',
             type: 'success',
            duration: 1000,
           });
          break;
      }

      //错误路径处理
      if(this.$route.name==null){
        this.$router.push("/error")
      }
    }
  }
})
