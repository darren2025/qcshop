<template>
    <div class="login">
        <div class="container">
            <div class="avatar">
                <img src="../assets/images/register.jpg">
            </div>
            <el-form ref="RetionForm" :model="RetionForm" label-width="80px" :rules="rules" >
                <el-form-item label="账　号" prop="username">
                    <el-input v-model="RetionForm.username" placeholder="请输入内容" prefix-icon=" myicon myicon-user" ></el-input>
                </el-form-item>
                <el-form-item label="密　码" prop="password">
                    <el-input placeholder="请输入密码" v-model="RetionForm.password" show-password prefix-icon="myicon myicon-key" ></el-input>
                </el-form-item>
                <el-form-item label="确认密码" prop="checkpassword">
                    <el-input placeholder="请输入密码" v-model="RetionForm.checkpassword" show-password prefix-icon="myicon myicon-key" ></el-input>
                </el-form-item>
                <el-form-item >
                    <el-button type="primary" @click="$router.push('/login')">登录</el-button>
                    <el-button type="primary" @click="submitForm()">注册</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
export default {
    data() { 
        var validatePass = (rule, value, callback) => {
            if (value === '') {
            callback(new Error('请输入密码'));
            } else {
            if (this.RetionForm.checkpassword !== '') {
                this.$refs.RetionForm.validateField('checkpassword');
            }
            callback();
            }
        };
        var validatePass2 = (rule, value, callback) => {
            if (value === '') {
            callback(new Error('请再次输入密码'));
            } else if (value !== this.RetionForm.password) {
            callback(new Error('两次输入密码不一致!'));
            } else {
            callback();
            }
        };
        return {
            RetionForm:{
                username:localStorage.getItem("username"),
                password:"",
                checkpassword:"",
            },
            rules: {
                username: [
                    { required: true, message: '请填写账号', trigger: 'blur' },
                    { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }
                ],
                password: [
                    { validator: validatePass, trigger: 'blur' }
                ],
                checkpassword: [
                    { validator: validatePass2, trigger: 'blur' }
                ],
            },
        }
    },
    watch:{
        "RetionForm.username":function(){
            // 把用户名保存在浏览器本地
            localStorage.setItem("username",this.RetionForm.username)
        }
    },
    methods: {
      submitForm(){
        this.$refs["RetionForm"].validate((valid) => {
          if (valid) {
                this.$message({
                showClose: true,
                message: '注册成功',
                type: 'success',
                duration:1000,
                });
          } else {
            this.$message.error("注册失败,请检查账号与密码");
            return false;
          }
        });
      },
    },
}
</script>

<style lang="scss" scoped="">
.login {
  position: fixed;
  width: 100%;
  height: 100%;
  background: url(../assets/images/timg.jpg);
  background-size:cover;
.container {
    position: absolute;
    left: 0;
    right: 0;
    width: 400px;
    padding: 0px 40px 15px 40px;
    margin: 200px auto;
    background: white;
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
    }
.login-btn {
    width: 100%;
    }
  }
}
</style>