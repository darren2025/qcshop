<template>
    <div>
        <!-- 面包屑导航 -->
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item :to="{ path: '/goods' }">商品列表</el-breadcrumb-item>
      <el-breadcrumb-item>添加商品</el-breadcrumb-item>
    </el-breadcrumb>
    <!-- 步骤条 -->
    <el-steps :active="active">
        <el-step title="基本信息" icon="el-icon-edit"></el-step>
        <el-step title="商品分类" icon="el-icon-menu"></el-step>
        <el-step title="商品图片" icon="el-icon-picture"></el-step>
        <el-step title="商品介绍" icon="el-icon-check"></el-step>
    </el-steps>
    <!-- 标签页 -->
      <el-tabs :tab-position="tabPosition" style="height: 500px;" v-model="goodsFoem.activeName">
        <el-tab-pane label="基本信息" name="0">
        <el-form  label-width="80px">
             <el-form-item label="商品名称" >
                <el-input v-model="goodsFoem.goods_name"></el-input>
            </el-form-item>
             <el-form-item label="价格">
                <el-input v-model.number="goodsFoem.goods_price"></el-input>
            </el-form-item>
             <el-form-item label="数量">
                <el-input v-model.number="goodsFoem.goods_number"></el-input>
            </el-form-item>
             <el-form-item label="重量(KG)">
                <el-input v-model.number="goodsFoem.goods_weight"></el-input>
            </el-form-item>
        </el-form>
              <el-button type="primary" class="right" @click="goodsFoem.activeName='1'">下一步</el-button>
        </el-tab-pane>
        <el-tab-pane label="商品分类" name="1">
            <el-cascader
                expand-trigger="hover"
                :options="options"
                :props="props"
                placeholder="试试搜索：商品名称"
                :filterable=true
                v-model="selectedOptions"
                class="search">
            </el-cascader>
             <el-button type="primary"  class="left" @click="goodsFoem.activeName='0'">上一步</el-button>
             <el-button type="primary" class="right" @click="goodsFoem.activeName='2'">下一步</el-button>
        </el-tab-pane>
        <el-tab-pane label="商品图片" name="2">
            <!-- 商品图片 -->
            <el-upload
                class="upload-demo margin"
                action='http://localhost:8888/api/private/v1/upload'
                :on-success="success"
                :on-remove="remove"
                :headers=headerstop
                :multiple=true
                list-type="picture"
                >
                <el-button size="small" type="primary">点击上传</el-button>
                <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                </el-upload>
             <el-button type="primary"   @click="goodsFoem.activeName='1'">上一步</el-button>
             <el-button type="primary"  @click="goodsFoem.activeName='3'">下一步</el-button>
        </el-tab-pane>
        <el-tab-pane label="商品介绍" name="3">
            <!-- 商品介绍 -->
            <div id="editor"></div>
            <el-button type="primary" class="right" @click="addGoodServer">提交</el-button>
            </el-tab-pane>
    </el-tabs>
    </div>
</template>

<script>
import {Categorieslist,addGoods} from '@/api'
export default {
    data() { 
        return {
            // 进度条上边显示方向
            tabPosition: 'right',
            // 上传数据对象
            goodsFoem:{
                // 商品名称
                goods_name:"",
                // 以为‘，’分割的分类列表
                goods_cat:"",
                // 价格
                goods_price:"",
                // 数量
                goods_number:"",
                // 重量
                goods_weight:"",
                // 介绍
                goods_introduce:"",
                // 上传图片的临时路径（对象）
                pics:[],
                // 关联页的数据
                activeName:"0",
            },
            // 从服务器拿到数据存储
            options:[],
            // 定义自定义数据
            props:{
                value:"cat_id",
                label:"cat_name",
                children:"children",
            },
            // 用户选择的数据
            selectedOptions:[],
            // 上传图片参数
            headerstop:{
                "Authorization":localStorage.getItem("token")
            },
            // 富文本的数据
            ue:"",
        }

    },
    methods: {
        // 提交添加商品事件
        addGoodServer(){
            // 将富文本框的内容保存在对象中
            this.goodsFoem.goods_introduce=this.ue.getContent()
            // 将用户选择的值,加入到对象中,并且用,进行分割
            this.goodsFoem.goods_cat=this.selectedOptions.join(",")
            addGoods(this.goodsFoem).then(res=>{
                if (res.meta.status==201) {
                  this.$message({
                    showClose: true,
                    message: res.meta.msg,
                    type: 'success',
                    duration:1000,
                   });
                    // 成功后跳转回去商品列表
                    this.$router.push("/goods")
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                    data.mg_state=!data.mg_state
                }
            })
        },
        // 图片上传成功的钩子
        success(res,file){
            // console.log(res)
            // console.log(file)
            // 上传成功将图片的临时地址存储到相应的对象,然后保存相应uid
            this.goodsFoem.pics.push({"pic":res.data.tmp_path,uid:file.uid})
        },
        // 图片被移除之后的钩子
        remove(file){
            // console.log(file)
            // 遍历数组,找到那个添加到的数组一样的那条数据
           var index= this.goodsFoem.pics.findIndex(item=>{
               return item.uid==file.uid
            })
            // 找到,返回那个数组的下标,然后返回,最后删除,那条数据
            this.goodsFoem.pics.splice(index,1)
        }

    },
    computed: {
        active(){
            return this.goodsFoem.activeName*1
        }
    },
    created() {
        // 页面一加载完成加载分类数据
        Categorieslist(3).then(res=>{
            this.options=res.data
        })
    },
    mounted() {
        
        this.ue = UE.getEditor('editor')
    },
    destroyed() {
        this.ue.destroyed();
    },
}
</script>

<style lang="css" scoped>

.right{
    position: relative;
    bottom: 0px;
    left:1050px;
}
.left{
    position: relative;
    top: 60px;
    left:-1100px;
    margin-top: 10px;
}
  .search {
    width: 1100px;
    margin-bottom: 20px;
  }
  .margin{
    margin-bottom: 30px;
  }
  #editor{
      margin-bottom: 20px;
  }
</style>