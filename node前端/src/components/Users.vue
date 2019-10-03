<template>
    <div class="user">
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>用户管理</el-breadcrumb-item>
            <el-breadcrumb-item>用户列表</el-breadcrumb-item>
        </el-breadcrumb>
          <el-input placeholder="请输入内容" v-model="input3" class=" input-with-select search-input" @keydown.native.enter="search">
            <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
        </el-input>
          <el-button type="primary" @click="addUserForm=true">新增用户</el-button>
        <el-table
            :data="tableData"
            border
            style="width: 100%">
            <el-table-column
            prop="id"
            label="I D"
            width="50">
            </el-table-column>
            <el-table-column
            prop="username"
            label="用户名"
            width="">
            </el-table-column>
            <el-table-column
            prop="role_name"
            label="权限"
            width="">
            </el-table-column>
            <el-table-column
            prop="mobile"
            label="电话"
            width="">
            </el-table-column>
            <el-table-column
            prop="email"
            label="邮箱"
            width="">
            </el-table-column>
            <el-table-column
            label="账号启用"
            width="">
            <template slot-scope="users">
            <!-- 这里的数据是从这个当前这条数据渲染中插值表达式中获取 -->
            <el-switch
            v-model="users.row.mg_state"
            @change="status_no_off(users.row)"
            active-color="#13ce66"
            inactive-color="#ff4949">
            </el-switch>
            </template>
            </el-table-column>
            <el-table-column
            prop="create_time"
            label="创建时间"
            width="147">
            <template slot-scope="usern">
                {{usern.row.create_time |timeFormat}}
            </template>
            </el-table-column>
            <el-table-column
            label="操作"
            width="200">
            <template slot-scope="Usess">
                    <el-button type="primary" icon="el-icon-edit" circle @click="EditUserbtton(Usess.row)"></el-button>
                    <el-button type="success" icon="el-icon-setting" circle @click="rolesbtton(Usess.row)"></el-button>
                    <el-button type="danger" icon="el-icon-delete" circle @click="del(Usess.row)"></el-button>
            </template>
            </el-table-column>
        </el-table>
         <el-pagination
         class="page"
            @size-change="handleSizeChange"    
            @current-change="handleCurrentChange"
            :current-page="pagenum"
            :page-sizes="[5, 10, 20, 30]"
            :page-size="pagesize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total">
            
        </el-pagination>
        <!-- 添加用户的弹窗层 -->
        <el-dialog title="添加用户" :visible.sync="addUserForm" width="30%">
        <el-form :model="addUser" label-width="80px">
            <el-form-item label="用户名称">
            <el-input v-model="addUser.username" ></el-input>
            </el-form-item>
            <el-form-item label="用户密码">
            <el-input v-model="addUser.password" show-password></el-input>
            </el-form-item>
            <el-form-item label="邮　　箱">
            <el-input v-model="addUser.email" ></el-input>
            </el-form-item>
            <el-form-item label="手机号">
            <el-input v-model="addUser.mobile" ></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="addUserForm = false">取 消</el-button>
            <el-button type="primary" @click="addUserfn">添 加</el-button>
        </div>
        </el-dialog>
    <!-- 编辑用户的弹出层 -->
        <el-dialog title="编辑用户" :visible.sync="EditUserForm" width="30%">
        <el-form :model="EditUser" label-width="80px">
            <el-form-item label="I D">
            <el-input :value="EditUser.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="用户名">
            <el-input :value="EditUser.username" disabled></el-input>
            </el-form-item>
            <el-form-item label="邮　　箱">
            <el-input v-model="EditUser.email" ></el-input>
            </el-form-item>
            <el-form-item label="手机号">
            <el-input v-model="EditUser.mobile" ></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="EditUserForm = false">取 消</el-button>
            <el-button type="primary" @click="EditUserfn">修 改</el-button>
        </div>
        </el-dialog>
    <!-- 配置用户组的弹出层 -->
        <el-dialog title="设置权限" :visible.sync="rolesForm" width="30%">
        <el-form :model="roles" label-width="80px">
            <el-form-item label="I D">
            <el-input :value="roles.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="用户名">
            <el-input :value="roles.username" disabled></el-input>
            </el-form-item>
            <el-form-item label="选择角色">
              <el-select v-model="roles.rid" placeholder="请选择">
                <el-option
                v-for="item in options"
                :disabled="item.id<=0"
                :key="item.id"
                :label="item.roleName"
                :value="item.id">
                </el-option>
            </el-select>
              </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="rolesForm = false">取 消</el-button>
            <el-button type="primary" @click="rolesfn">修 改</el-button>
        </div>
        </el-dialog>
    </div>
</template>

<script>
// 导入获取用户数据的请求模块
import {Users,addUser,delUser,EditUser,selroles,getUserById,Assignrole,status} from '@/api'
export default {
    filters:{
       timeFormat:function (time){
           var time= new Date(time*1000)
        //获取年月日
        var y = time.getFullYear()
        var m = (time.getMonth()+1).toString().padStart(2,0) //0~11数值
        var d = time.getDate().toString().padStart(2,0)
          return `${y}-${m}-${d}`
       }
    },
    data() { 
        return {
        // 遍历数组数据
           tableData:[], 
        //    搜索框数据
           input3:"",
        //    当前页面
            pagenum:1,
            // 每页数据量
            pagesize:5,
            // 总数据量
            total:0,
            // 添加用户表单显示
            addUserForm:false,
            // 添加用户的表单
            addUser:{
                username:"",
                password:"",
                email:"",
                mobile:"",

            },
            // 修改用户数据的参数
            EditUserForm:false,
            EditUser:{
                id:"",
                email:"",
                mobile:"",
                username:"",
            },
            // 配置用户角色的参数
            roles:{
                id:"",
                username:"",
                rid:"",
            },
            // 打开角色配置的对话框
            rolesForm:false,
            // 获取服务器的所有角色权限后的返回数据保存区域
            options:[],


        }
    },

    // 页面加载完成马上去获取用户数据
    created() {
        this.search()
    },
    // 监听事件,如果搜索框内容没有,那么我就恢复内容
    watch: {
        input3:function() {
            if (this.input3=="") {
                // console.log("开始了")
                this.search()
            }
        }
    },
    methods: {
        // 账号状态启用与关闭事件
        status_no_off(data){
            status(data).then(res=>{
                console.log(res)
               if (res.meta.msg=="设置状态成功") {
                  this.$message({
                    showClose: true,
                    message: res.meta.msg,
                    type: 'success',
                    duration:1000,
                   });
                    // 成功后刷新数据
                    this.search()
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                    data.mg_state=!data.mg_state
                }
            })
        },
        // 用户角色配置完成相关操作后往后端请求修改数据
        rolesfn(){
            // 修改数据,然后发送到后端处理完成后返回处理
            Assignrole(this.roles).then(res=>{
                if (res.meta.msg=="设置角色成功") {
                  this.$message({
                    showClose: true,
                    message: res.meta.msg,
                    type: 'success',
                    duration:1000,
                   });
                    // 关闭对话框
                    this.rolesForm=false;
                    // 成功后刷新数据
                    this.search()
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                }
            })
        },
        // 配置用户角色事件
        rolesbtton(data){
            this.rolesForm=true
            this.roles.id=data.id
            this.roles.username=data.username
            // 这个是所有的后台角色名单
            selroles().then(res=>{
              this.options=res.data
              this.options.push({id:0,roleName:"超级管理员"})
              this.options.push({id:-1,roleName:"空闲人员"})
            })
            // 获取当前点击用户的rid
            getUserById(this.roles.id).then(res=>{
              this.roles.rid=res.data.rid
              this.options.value=this.roles.rid
            })

        },
        // 修改用户数据的事件
        EditUserbtton(data){
            // 首先先打开对话框
            this.EditUserForm=true
            // 将表单按钮获取的参数取得回来
            this.EditUser.id=data.id
            this.EditUser.email=data.email
            this.EditUser.mobile=data.mobile
            this.EditUser.username=data.username
        },
        // 修改用户数据写入后端
        EditUserfn(){
            EditUser(this.EditUser).then(res=>{
                if (res.meta.msg=="更新成功") {
                    this.$message({
                        showClose: true,
                        message: res.meta.msg,
                        type: 'success',
                        duration:1000,
                    });
                    // 关闭对话框
                    this.EditUserForm=false;
                    // 成功后刷新数据
                    this.search()
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                }
            })
        },
        // 新增用户表单添加事件
        addUserfn(){
            addUser(this.addUser).then(res=>{
                // 判断添加以后的请求结果,满足执行以下 
                if (res.meta.status==201) {
                    this.$message({
                        showClose: true,
                        message: res.meta.msg,
                        type: 'success',
                        duration:1000,
                    });
                    // 关闭对话框
                    this.addUserForm=false;
                    // 成功后刷新数据
                    this.input3=this.addUser.username

                    this.search()
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                }
            })
        },
        del(data){
            this.$confirm(`你确定要删除${data.username}吗`, '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
                }).then(() => {
                delUser(data).then(res=>{
                    if (res.meta.msg=="删除成功") {
                        this.$message({
                            showClose: true,
                            message: res.meta.msg,
                            type: 'success',
                            duration:1000,
                        });
                        this.search()
                        
                    }else{
                        this.$message.error(res.meta.msg);
                    }
                })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                     });          
            });
        },
        // 搜索的方法
        search(){
            Users({query:this.input3,pagenum:this.pagenum,pagesize:this.pagesize}).then(res=>{
                console.log(res);
                // 保存分页的相关数据
                this.pagenum=res.data.pagenum*1
                this.total=res.data.total*1
                    // 判断搜索返回的结果是否为空,而且用户的是不是在第一页
                    if (res.data.users.length==0&&this.pagenum!=1) {
                        // 如果满足以上条件,就给用户所在页码赋值为1,让他到第一页
                        this.pagenum=1
                        // 重新执行搜索
                        this.search()
                    }
                // 保存用户列表数据
            this.tableData=res.data.users
            })
        },
        // 每页数据量改变事件
        handleSizeChange(size){
            // 保存新每页数据量
            this.pagesize=size;
            // 重新获取数据
            this.search()
        },
        // 页码改变事件
        handleCurrentChange(page){
            // 保存新页码
            this.pagenum=page

            // 重新获取数据
            this.search()
        },
        
    },
}
</script>

<style lang="scss" scoped>
.user {
  .margin-20 {
    margin: 20px 0;
  }
  .search-input {
    width: 300px;
  }
  .page {
    padding: 5px 0;
    background-color: #D3DCE6;
  }
}

</style>