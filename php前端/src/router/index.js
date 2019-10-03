import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import Retions from '@/components/Retions'
// 首页
import Home from '@/components/Home'
// 用户管理
import Users from '@/components/Users'
import Rights from '@/components/Rights'
// 错误404页面
import error from '@/components/error'
import Index from '@/components/Index'
// 角色分配与管理
import Roles from '@/components/Roles'
// 商品分类
import Categories from '@/components/Categories'

// 商品列表
import Goods from '@/components/Goods'
// 添加商品
import Goodadd from '@/components/Goodadd'

// 订单模块
import orders from '@/components/orders'
// 数据统计模块
import Reports from '@/components/Reports'

// 编辑商品的页面

import Editgoods from '@/components/Editgoods'
// 分类参数处理
import params from '@/components/params'



Vue.use(Router)

export default new Router({
  routes: [
    {path: '/Login',name: 'Login',component: Login},
    {path: '/Retions',name: 'Retions',component: Retions},
    {path: '/',name: 'Home',component: Home,children:[
      {path:'Users',name:'Users',component:Users},
      {path:'Rights',name:'Rights',component:Rights},
      {path:'Roles',name: 'Roles',component: Roles},
      {path:'Categories',name: 'Categories',component: Categories},
      {path:'Goods',name: 'Goods',component: Goods},
      {path:'Goodadd',name: 'Goodadd',component: Goodadd},
      {path:'Index',name: 'Index',component: Index},
      {path:'orders',name:'orders',component:orders},
      {path:'Reports',name:'Reports',component:Reports},
      {path:'Editgoods',name:'Editgoods',component:Editgoods},
      {path:'params',name:'params',component:params},
      {path:'',name:'default',redirect:"/index"},
    ]},,
    // {path: '/',name: 'defull',component: Home},
    {path: '/error',name: 'error',component: error},
  ]
})
