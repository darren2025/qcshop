// 这个是所有api设置
// 导入axios模块、
import axios from 'axios'
// 添加请求拦截器
// 设置默认基准地址
axios.defaults.baseURL = 'http://www.ecshop.com/'

//载入接口函数库时设置token信息 此时浏览器未获得token信息
// axios.defaults.headers.common['Authorization'] = localStorage.getItem("token")

//发起请求时设置token信息 登录之后的所有接口都能获得token信息
axios.interceptors.request.use(function (config) {
  // 在发送请求之前做些什么
  config.headers.Authorization = localStorage.getItem("token")
  return config;
}, function (error) {
  // 对请求错误做些什么
  return Promise.reject(error);
});

// 添加响应拦截器 自己写的
// axios.interceptors.response.use(function (response) {
//   // 对响应数据做点什么
//   if (response.data.meta.msg=="无效token") {
//     // 清除本地所有的token缓存
//     // localStorage.clear()
//     console.log(response.data.meta.msg)
//     // 然后跳转会登录页面
//     // location.href="#/login"
//   }

//   return response;
// },function(error){
//   return Promise.reject(error)
// });
// 设置响应拦截器 当响应返回时 拦截下来 对token是否过期进行检查 老公的
axios.interceptors.response.use(function (response) {
  // Do something with response data
  // console.log(response)

  if (response.config.url != "http://www.321dz.com/bt-api.php") {
    if (response.data.meta.msg == "无效token") {
      //清除已有token信息
      localStorage.clear()
      //强制跳转到login页面
      location.href = "#/login"
    }
  }
  return response;
}, function (error) {
  // Do something with response error
  return Promise.reject(error);
});

// 导出登录的post请求
export var login = function(data){
    return axios.post("/login",data).then(function(res){
        return res.data
    })
}

// 获取get菜单权限列表
export var menus = function () {
    return axios.get('/menus').then(function (res) {
      return res.data
    })
}

// 获取用户数据的模块
export var Users =function(data){
    return axios.get('/users', {params:data}).then(function (res) {
        return res.data
      })
}

// 添加用户的数据的模块
export var addUser = function (data) {
  return axios.post("/users", data).then(function (res) {
    return res.data
  })
}

// 删除用户的模块

export var delUser = function (data) {
  return axios.delete(`/users/${data.id}`).then(function (res) {
    return res.data
  })
}

// 编辑写入用户的修改模块
export var EditUser = function (data) {
  return axios.put(`/users/${data.id}`,data).then(function (res) {
    return res.data
  })
}

// 获取目录角色列表
export var selroles = function () {
  return axios.get('/roles').then(function (res) {
    return res.data
  })
}

// 获取当前点击用户的rid
export var getUserById = function (id) {
  return axios.get(`/users/${id}`).then(function (res) {
    return res.data
  })
}

// 用户权限分配

export var Assignrole = function (data) {
  return axios.put(`/users/${data.id}/role`, data).then(function (res) {
    return res.data
  })
}

// 修改用户状态
export var status = function (data) {
  return axios.put(`/users/${data.id}/state/${data.mg_state}`).then(function (res) {
    return res.data
  })
}

// 获取所有权限列表
export var rights = function (type) {
  return axios.get(`/rights/${type}`).then(function (res) {
    return res.data
  })
}

// 删除权限模块中角色
export var delroles = function (data) {
  return axios.delete(`/roles/${data.id}`).then(function (res) {
    return res.data
  })
}
// 删除角色权限模块
export var deleteRoles = function (rid,id) {
  return axios.delete(`/roles/${id}/rights/${rid}`).then(function (res) {
    return res.data
  })
}

// 修改角色的描述与修改角色的名称
export var EditRolesForm = function (data) {
  return axios.put(`/roles/${data.id}`,data).then(function (res) {
    return res.data
  })
}

// 新增角色
export var addRoles = function (data) {
  return axios.post("/roles", data).then(function (res) {
    return res.data
  })
}
// 角色授权的
export var RoleAuthorization = function (roleId, rids) {
  return axios.post(`/roles/${roleId}/rights`, {rids}).then(function (res) {
    return res.data
  })
}

// 获取商品分类数据
export var Categorieslist = function (type) {
  return axios.get(`/categories`,{
    params:{
      type:type
    }
  }).then(function (res) {
    return res.data
  })
}
// 添加商品分类
export var addcategories = function (data) {
  return axios.post("/categories", data).then(function (res) {
    return res.data
  })
}

// 删除分类的模块

export var deletecategories = function (cid) {
  return axios.delete(`/categories/${cid}`).then(function (res) {
    return res.data
  })
}

// 编辑分类
export var EditcategoriesForm = function (data) {
  return axios.put(`/categories/${data.id}`, data).then(function (res) {
    return res.data
  })
}

// 获取商品列表数据
export var Goodslist = function (data) {
  return axios.get(`/goods`, {
    params: data
  }).then(function (res) {
    return res.data
  })
}

// 添加商品
export var addGoods = function (data) {
  return axios.post("/goods", data).then(function (res) {
    return res.data
  })
}

// 删除商品
export var delGoods = function (data) {
  return axios.delete(`/goods/${data}`).then(function (res) {
    return res.data
  })
}

// 请求订单数据
export var getorders = function (data) {
  return axios.get(`/orders`, {
    params: data
  }).then(function (res) {
    return res.data
  })
}

// 修改订单
export var getEditorders = function (data) {
  return axios.put(`/orders/${data.id}`, data).then(function (res) {
    return res.data
  })
}

// 获取数据统计的图标的的数据
export var getreports = function (data) {
  return axios.get(`/reports/${data}`).then(function (res) {
    return res.data
  })
}

// 查询相关商品的id方法
export var getgoods = function (data) {
  return axios.get(`/goods/${data}`).then(function (res) {
    return res.data
  })
}

// 提交编辑商品的方法
export var getEditgoods = function (data) {
  return axios.put(`/goods/${data.goods_id}`, data).then(function (res) {
    return res.data
  })
}