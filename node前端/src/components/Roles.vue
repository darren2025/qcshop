<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>权限管理</el-breadcrumb-item>
      <el-breadcrumb-item>角色列表</el-breadcrumb-item>
    </el-breadcrumb>
    <el-button type="primary" v-on:click="AddRolesBtton">新增角色</el-button>
      <el-table
    :data="rolesdata"
    style="width: 100%"
    border>
    <el-tag closable> </el-tag>
    <el-table-column type="expand">
      <template slot-scope="props">
          <!-- {{props.row.children}} -->
          <!-- 遍历顶级权限 显示行 -->
          <el-row v-for="leven0 in props.row.children" :key="leven0.id">
            <!-- 顶级权限名称 -->
            <el-col :span="3"><el-tag closable type='danger' @close="deleteRoles(leven0.id,props.row)">{{leven0.authName}} </el-tag></el-col>
            <!-- 顶级的后代权限 -->
            <el-col :span="21">
              <!-- 遍历一级权限 显示行 -->
                <el-row v-for="leven1 in leven0.children" :key="leven1.id">
                  <!-- 一级权限名称 -->
                  <el-col :span="3"><el-tag closable type= 'warning' @close="deleteRoles(leven1.id,props.row)">{{leven1.authName}} </el-tag></el-col>
                  <!-- 所有二级权限 -->
                  <el-col :span="21">
                    <!-- 遍历二级权限 span元素 -->
                      <span v-for="leven2 in leven1.children" :key="leven2.id"><el-tag closable @close="deleteRoles(leven2.id,props.row)">{{leven2.authName}} </el-tag></span>
                  </el-col>
                </el-row>
            </el-col>
          </el-row>
      </template>
    </el-table-column>
    <el-table-column
      label="角色 ID"
      prop="id">
    </el-table-column>
    <el-table-column
      label="角色名称"
      prop="roleName">
    </el-table-column>
    <el-table-column
      label="角色描述"
      prop="roleDesc">
    </el-table-column>
    <el-table-column
      label="操作">
       <template slot-scope="roles">
          
          <el-button type="primary" icon="el-icon-edit" circle @click="Editrolesbtton(roles.row)"></el-button>
          <el-button type="success" icon="el-icon-setting" circle @click="rolesbtton(roles.row)"></el-button>
          <el-button type="danger" icon="el-icon-delete" circle @click="delroles(roles.row)"></el-button>
        </template>
    </el-table-column>
  </el-table>
  <!-- 修改角色弹出层 -->
   <el-dialog title="修改角色" :visible.sync="EditrolesForm" width="30%">
        <el-form :model="Editroles" label-width="80px">
            <el-form-item label="I D">
            <el-input :value="Editroles.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="角色名称">
            <el-input v-model="Editroles.roleName"></el-input>
            </el-form-item>
            <el-form-item label="角色描述">
            <el-input v-model="Editroles.roleDesc"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="EditrolesForm = false">取 消</el-button>
            <el-button type="primary" @click="EditsolesForm">修 改</el-button>
        </div>
    </el-dialog>
    <!-- 新增角色的弹出层 -->
    <el-dialog title="新增角色" :visible.sync="AddRoles.AddRolesForm" width="30%">
        <el-form :model="AddRoles" label-width="80px">
            <el-form-item label="角色名称">
            <el-input v-model="AddRoles.roleName"></el-input>
            </el-form-item>
            <el-form-item label="角色描述">
            <el-input v-model="AddRoles.roleDesc"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="AddRoles.AddRolesForm = false">取 消</el-button>
            <el-button type="primary" @click="AddRolesSever">修 改</el-button>
        </div>
    </el-dialog>
    <!-- 分配角色对话框 -->
    <el-dialog title="分配角色" :visible.sync="AssignRole.AssignRoleForm" width="30%">
        <el-form :model="AssignRole" label-width="80px">
            <el-form-item label="ID">
            <el-input v-model="AssignRole.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="角色名称">
            <el-input v-model="AssignRole.roleName" disabled></el-input>
            </el-form-item>
            <!-- 这里是一个树状的列表 -->
            <el-tree
              :data="AssignRole.RolesList"
              show-checkbox
              node-key="id"
              :default-expand-all="true"
              ref="tree"
              :default-checked-keys="AssignRole.hasRights"
              :props="AssignRole.props">
            </el-tree>
              
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="AssignRole.AssignRoleForm = false">取 消</el-button>
            <el-button type="primary" @click="rolesServer">修 改</el-button>
        </div>
    </el-dialog>
  </div>
</template>


<script>
import {selroles,delroles,deleteRoles,EditRolesForm,addRoles,rights,RoleAuthorization} from "@/api";
export default {
  data() {
    return {
      // 建立一个数据源接受返回的数据
      rolesdata: [],
      // 注册修改角色的相关对象
      Editroles:{
        id:"",
        roleName:"",
        roleDesc:"",
      },
      // 修改对象表单的弹出层按钮
      EditrolesForm:false,
      // 注册新增角色的相关对象参数
      AddRoles:{
        roleName:"",
        roleDesc:"",
        AddRolesForm:false
      },
      // 分配角色对话框
      AssignRole:{
        AssignRoleForm:false,
        id:"",
        roleName:"",
        // 当前角色的ID
        rid:"",
        // 当前角色的已有权限保存
        hasRights:[],
        // 获取服务端所有角色列表保存在下面数组
        RolesList:[],
        // 树状控件字段信息
        props:{
          label:"authName",
          children:"children"
        }
      },
    };
  },
  methods: {
    // 分配角色对话框完成发送到服务端
    rolesServer(){
      // 这边需要几个数据 角色的id与权限id列表post请求提交
      // 第一个形参为角色的id,第二个形参为用户已经选择的权限id 
         RoleAuthorization(this.AssignRole.id,this.$refs.tree.getCheckedKeys().join(',')).then(res=>{
          //  收到响应处理
            if (res.meta.status==200) {
                this.$message({
                  showClose: true,
                  message: res.meta.msg,
                  type: 'success',
                  duration:1000,
            });
              // 添加成功关闭窗口
                this.AssignRole.AssignRoleForm=false
                  //刷新新的数据
                this.init()
            }else{
                // 不满足不关闭对话框,然后返回服务器的的结果
                this.$message.error(res.meta.msg);
            }
         })
    },
    // 分配角色的对话框按钮
    rolesbtton(data) {
      // 打开对话,并且
      this.AssignRole.AssignRoleForm=true
      // 取得相关值,进行一系列赋值
      this.AssignRole.id=data.id
      this.AssignRole.roleName=data.roleName
      // 从服务端获取相关角色列表,并且保存到对象中数组中
      rights("tree").then(res=>{
        // 请求所有权限,并且将请求回来的数据保存在AssignRole下的RolesList数组中
         this.AssignRole.RolesList=res.data
      })
      // 根据当前角色所有顶级权限,把所有权限的Id遍历出来然后追加到hasRights
      // 每次拿到请求删除上一轮的数据,把原来的数组变成空数组
       this.AssignRole.hasRights=[]
      // 把当前点击的id添加到最前面
     
      // this.AssignRole.hasRights.unshift(data.id)
      // 开始遍历下面的第一集级下面的
      data.children.forEach(item0=>{
        // 遍历第一级别数组id
        // this.AssignRole.hasRights.push(item0.id)
        item0.children.forEach(item1=>{
          // 遍历第二集数组id
          // this.AssignRole.hasRights.push(item1.id)
          item1.children.forEach(item2=>{
          // 遍历第三集数组id
            this.AssignRole.hasRights.push(item2.id)
          })
        })

      })
    },
    // 新增角色的填写完数据发送数据到后端事件
    AddRolesSever(){
      addRoles(this.AddRoles).then(res=>{
        if (res.meta.status==201) {
            this.$message({
                showClose: true,
                message: res.meta.msg,
                type: 'success',
                duration:1000,
            });
            // 添加成功关闭窗口
              this.AddRoles.AddRolesForm=false
              // 重置对象已经存在的数据
              this.AddRoles.roleName=""
              this.AddRoles.roleDesc=""
                //刷新新的数据
               this.init()
        }else{
            // 不满足不关闭对话框,然后返回服务器的的结果
            this.$message.error(res.meta.msg);
        }
      })
    },
    // 新增角色的弹窗事件
    AddRolesBtton(){
        this.AddRoles.AddRolesForm=true;
    },
    // 注册tag标签点击关闭事件,就注销相关权限
    deleteRoles(rid,id){
      // rid是要删除的权限的id
      // id是角色的Id
      deleteRoles(rid,id.id).then(res=>{
        if (res.meta.status==200) {
            this.$message({
                showClose: true,
                message: res.meta.msg,
                type: 'success',
                duration:1000,
            });
            id.children=res.data
        }else{
            // 不满足不关闭对话框,然后返回服务器的的结果
            this.$message.error(res.meta.msg);
        }
      })
    },
    // 修改角色的数据按钮
    Editrolesbtton(data) {
      // 打开对话框
      this.EditrolesForm=true;
      // 将传回来的值,赋值给现在的弹出层中
      this.Editroles.id=data.id
      this.Editroles.roleName=data.roleName
      this.Editroles.roleDesc=data.roleDesc
    },
    // 修改角色的提交事件
    EditsolesForm(){
      EditRolesForm(this.Editroles).then(res=>{
          if (res.meta.status==200) {
            this.$message({
              showClose: true,
              message: res.meta.msg,
              type: 'success',
              duration:1000,
              });
              // 成功后刷新数据
              this.init()
              // 关闭修改弹出层
              this.EditrolesForm=false;
          }else{
              // 不满足不关闭对话框,然后返回服务器的的结果
              this.$message.error(res.meta.msg);
          }
      })
    },
    // 删除角色的模块
    delroles(data) {
      this.$confirm(`你确定要删除${data.roleName}这个角色吗?`, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          delroles(data).then(res => {
            if (res.meta.msg == "删除成功") {
              this.$message({
                showClose: true,
                message: res.meta.msg,
                type: "success",
                duration: 1000
              });
              //   刷新列表
              this.init();
            } else {
              this.$message.error(res.meta.msg);
            }
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
    // 删除角色结束

    // 封装一个刷新函数
    init() {
      selroles().then(res => {
        console.log(res.data)
        this.rolesdata = res.data;
      });
    }
  },
  //   钩子函数,页面创建完成
  created() {
    this.init();
  }
};
</script>

<style lang="css" scoped>
.el-tag{
  margin: 5px;
}
</style>