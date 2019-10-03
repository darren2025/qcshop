<template>
    <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item >商品管理</el-breadcrumb-item>
      <el-breadcrumb-item>商品列表</el-breadcrumb-item>
    </el-breadcrumb>
      <el-input placeholder="请输入内容"        v-model="input3" class=" input-with-select search-input" @keydown.native.enter="init">
          <el-button slot="append" icon="el-icon-search" @click="init"></el-button>
      </el-input>
      <el-button type="primary" @click="$router.push('/goodadd')">新增商品</el-button>
         <el-table
    :data="tableData"
    style="width: 100%"
    max-height="500">
    <el-table-column
      fixed
      prop="goods_id"
      label="ID"
      width="80">
    </el-table-column>
    <el-table-column
      fixed
      prop="goods_name"
      label="商品名称"
      width="">
    </el-table-column>
    <el-table-column
      fixed
      prop="goods_number"
      label="库存"
      width="">
    </el-table-column>
    <el-table-column
      fixed
      prop="goods_price"
      label="价格"
      width="">
    </el-table-column>
    <el-table-column
      fixed
      label="操作"
      width="">
        <template slot-scope="Goods">
          <el-button type="primary" icon="el-icon-edit" circle @click="EditUserbtton(Goods.row)"></el-button>
          <el-button type="danger" icon="el-icon-delete" circle @click="del(Goods.row)"></el-button>
        </template>
    </el-table-column>
     </el-table>
     <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="goodslistvalue.pagenum"
      :page-sizes="[10, 50, 100, 200]"
      :page-size="goodslistvalue.pagesize"
      layout="total, sizes, prev, pager, next, jumper"
      :total="goodslistvalue.total"
      class="page">
    </el-pagination>
    </div>
</template>

<script>
import {Goodslist,delGoods} from '@/api'
export default {
    data() { 
        return {
            goodslistvalue:{
                query:"",
                pagenum:1,
                pagesize:10,
                total:0
            },
            // 分页的数据保存
            tableData:[],
            input3:"",


        }
    },
    created() {
        this.init()
    },
    watch: {
        input3:function() {
            if (this.input3=="") {
                this.init()
            }
        }
    },
    methods: {
      // 编辑商品的
      EditUserbtton(data){
        // console.log(data)
        // 收到请求后,马上将这id写入到localStorage本地
        localStorage.setItem("id",data.goods_id)
        // 然后跳转到相应页面
        this.$router.push("/Editgoods")
      },
      // 删除商品
      del(data){
         this.$confirm(`你确定要删除${data.goods_name}吗`, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
            }).then(() => {
            delGoods(data.goods_id).then(res=>{
                if (res.meta.msg=="删除成功") {
                    this.$message({
                        showClose: true,
                        message: res.meta.msg,
                        type: 'success',
                        duration:1000,
                    });
                    // 完成以后刷新列表
                    this.init()
                    
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
        // 封装一个函数用来请求
        init(){
            Goodslist(this.goodslistvalue).then(res=>{
                console.log(res)
                this.tableData=res.data.goods
                this.goodslistvalue.pagenum=res.data.pagenum*1
                this.goodslistvalue.total=res.data.total*1
                this.goodslistvalue.query=this.input3
            })
        },
        handleSizeChange(size){
          this.goodslistvalue.pagesize=size
          this.init()
        },
        handleCurrentChange(size){
          this.goodslistvalue.pagenum=size
          this.init()
        }
    },
}
</script>

<style lang="" scoped>
  .search-input {
    width: 300px;
  }
  .page {
    padding: 5px 0;
    background-color: #D3DCE6;
  }

</style>