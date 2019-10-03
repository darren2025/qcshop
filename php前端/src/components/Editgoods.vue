<template>
  <div>
    <!-- 编辑商品的页面 -->
        <el-breadcrumb separator-class="el-icon-arrow-right">
        <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>商品管理</el-breadcrumb-item>
        <el-breadcrumb-item>商品编辑</el-breadcrumb-item>
        </el-breadcrumb>
    <!-- 编辑页面的盗汗 -->
    <el-tabs  type="border-card"  v-model="activeName">
    <el-tab-pane label="商品修改" name="first" >
        <!-- 商品修改 -->
            <el-form label-width="80px">
            <el-form-item label="商品名称">
                <el-input v-model="Editgoodsdata.goods_name"></el-input>
            </el-form-item>
            <el-form-item label="价格">
                <el-input v-model="Editgoodsdata.goods_price"></el-input>
            </el-form-item>
            <el-form-item label="数量">
                <el-input v-model="Editgoodsdata.goods_number"></el-input>
            </el-form-item>
            <el-form-item label="重量">
                <el-input v-model="Editgoodsdata.goods_weight"></el-input>
            </el-form-item>
            </el-form>
            <el-button type="success" plain @click="EditBttonServer">提交修改</el-button>
    </el-tab-pane>
    <el-tab-pane label="商品图片" name="second">
        <!-- 商品图片 -->
        <el-upload
        class="upload-demo margin"
        action="http://localhost:8888/api/private/v1/upload"
        :multiple="true"
        list-type="picture"
        :file-list=Editgoodsdata.pics
        >
        <el-button size="small" type="primary">点击上传</el-button>
        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
        </el-upload>
        <el-button type="success" plain @click="EditBttonServer">提交修改</el-button>
    </el-tab-pane>
    <el-tab-pane label="商品介绍" name="third">
        <!-- 商品介绍 -->
    <div id="editor"></div>
    <el-button type="success" plain @click="EditBttonServer">提交修改</el-button>
    </el-tab-pane>
  </el-tabs>



  </div>
</template>

<script>
// 导入一个查询相关商品的id的方法
import { getgoods, getEditgoods } from "@/api";
// 导入echarts插件
// import echarts from 'echarts'
export default {
  data() {
    return {
      // 用来保存这个变量的值
      Editgoodsdata:[],
      activeName:"first",
      // 富文本的数据
      ue:"",
    };
  },
  // 创建一个钩子函数,马上去服务器请求这个值,然后去查询相关数据源,并且加载到页面中
  created() {
    getgoods(localStorage.getItem("id")).then(res => {
    //   console.log(res);

      this.Editgoodsdata = res.data;
    //   this.Editgoodsdata.pics[0].url =  this.Editgoodsdata.pics[0].pics_big_url
        this.Editgoodsdata.pics.forEach(item => {
            item.url=item.pics_big_url
        });
    });
    // 使用editor.setContent（“欢迎使用umeditor'，true）方法可以设置编辑器的内容
  },    
  methods: {
        EditBttonServer(){
           this.Editgoodsdata.goods_introduce=this.ue.getContent()
            getEditgoods(this.Editgoodsdata).then(res=>{
                if (res.meta.status==200) {
                  this.$message({
                    showClose: true,
                    message: '修改成功',
                    type: 'success',
                    duration:1000,
                   });
                    // 成功后跳转会相应的商品列表页面
                    this.$router.push("/goods")
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                    data.mg_state=!data.mg_state
                }
            })
        }
    },
    mounted() {
        this.ue = UE.getEditor('editor')       //挂载这个富文本插件 
        this.ue.addListener('ready',  () => {           //事件监听创建这个ready这东西
            getgoods(localStorage.getItem("id")).then(res => {        // 重新获取这个值
                this.ue.setContent(res.data.goods_introduce,true)     //将获取出来的值重新赋值给他
            });

        })
    },
    // vue钩子的函数
    destroyed() {
        this.ue.destroyed();
    },
    

};
</script>

<style lang="css" scoped>
.el-button{
    margin-top:50px;
    float: right
}
</style>