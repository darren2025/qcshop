<template>
    <div class="login">
        <div class="container">
            <div class="avatar">
                <img src="../assets/images/register.jpg">
            </div>
            <el-form ref="loginform" :model="loginForm" label-width="80px" :rules="rules" >
                <el-form-item label="账　号" prop="username">
                    <el-input v-model="loginForm.username" placeholder="请输入内容" prefix-icon=" myicon myicon-user" ></el-input>
                </el-form-item>
                <el-form-item label="密　码" prop="password">
                    <el-input placeholder="请输入密码" v-model="loginForm.password" show-pas sword prefix-icon="myicon myicon-key" @keydown.native.enter="ruleForm" show-password></el-input>
                </el-form-item>
                <el-form-item >
                    <el-button type="primary" @click="ruleForm">登录</el-button>
                    <el-button type="primary" @click="$router.push('/Retions')">注册</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>
<script>
import {login} from '@/api'
export default {
    data() { 
        return {
            loginForm:{
                username:localStorage.getItem("username"),
                password:"",
            },
            rules: {
                username: [
                    { required: true, message: '请填写账号', trigger: 'blur' },
                    { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '请填写密码', trigger: 'blur' },
                    { min: 6, max: 12, message: '长度在 6 到 12 个字符', trigger: 'blur' }
                ],

            },
        }
    },

    watch:{
        "loginForm.username":function(){
            // 把用户名保存在浏览器本地
            localStorage.setItem("username",this.loginForm.username)
        }
    },
    methods:{
        ruleForm(){
            this.$refs["loginform"].validate((valid) => {
                if (valid) {
                    login(this.loginForm).then(res=>{
                        if (res.meta.status==200) {

                            console.log(res.data);
                            
                            localStorage.setItem("token",res.data.token)
                            localStorage.setItem("username",this.loginForm.username)
                            this.$message({
                                showClose: true,
                                message: res.meta.msg,
                                type: 'success',
                                duration:1000,
                            });
                            this.$router.push('/')
                        }else{
                            this.$message.error(res.meta.msg);
                        }
                    })
                // axios.post("http://localhost:8888/api/private/v1/login",this.loginForm).then(res=>{
                //     if (res.data.meta.status==200) {
                //          this.$message({
                //             showClose: true,
                //             message: res.data.meta.msg,
                //             type: 'success',
                //             duration:1000,
                //         });
                //         this.$router.push('/Home')
                //     }else{
                //         this.$message.error(res.data.meta.msg);
                //     }
                // })
                } else {
                     this.$message.error("登陆失败,请检查账号与密码");
                    return false;
                }
            });
        }
    }
}
</script>

<style lang="scss" scoped="">
.login {
  position: fixed;
  width: 100%;
  height: 100%;
//   background: url(../assets/images/timg.jpg);
background-color:#545c64;
  background-size:cover;
.container {
    position: absolute;
    left: 0;
    right: 0;
    width: 400px;
    padding: 0px 40px 15px 40px;
    margin: 200px auto;
    background-color: rgba(175,238,238,0.5);
    border-radius: 20px 20px 20px 20px;
.avatar {
    position: relative;
    left: 50%;
    width: 120px;
    height: 120px;
    margin-left: -60px;
    margin-top: -60px;
    box-sizing: border-box;
    border-radius: 50%;
    border: 10px solid #fff;
    box-shadow: 0 1px 5px #ccc;
    overflow: hidden;
    // box-shadow: darkgrey 5px 5px 50px 10px ;//边框阴影
    }
.login-btn {
    width: 100%;
    }
  }
}
</style>