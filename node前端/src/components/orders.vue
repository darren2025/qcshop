<template>
    <div>
        <!-- 顶部面包屑导航 -->
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>订单管理</el-breadcrumb-item>
            <el-breadcrumb-item>列表列表</el-breadcrumb-item>
        </el-breadcrumb>
        <!-- 搜索模块 -->
       <div class="params">
            <!-- 单号查询面板 -->
            <el-input placeholder="订单号" v-model="ordersobject.query" class="input-with-select">
                <el-select slot="append" placeholder="支付状态" v-model="ordersobject.pay_status">
                    <el-option label="已支付" value="1"></el-option>
                    <el-option label="未支付" value="0"></el-option>
                </el-select>
            </el-input>

            <!-- 发货查询面板 -->
            <el-input placeholder="收件地址" class="input-with-select" v-model="ordersobject.consignee_addr">
                <el-select slot="append" placeholder="发货状态" v-model="ordersobject.is_send">
                    <el-option label="已发货" value="1"></el-option>
                    <el-option label="未发货" value="0"></el-option>
                </el-select>
            </el-input>

            <!-- 买家编号 -->
            <el-input placeholder="买家编号" v-model="ordersobject.user_id" style="width:200px;"></el-input>
        </div>

        <div class="params">
            <!-- 发票查询面板 -->
            <el-input placeholder="发票抬头" v-model="ordersobject.order_fapiao_company"></el-input>
            
            <el-input placeholder="发票内容" class="input-with-select" v-model="ordersobject.order_fapiao_content">
                <el-select slot="append" placeholder="发票类型" v-model="ordersobject.order_fapiao_title">
                    <el-option label="个人" value="1"></el-option>
                    <el-option label="公司" value="0"></el-option>
                </el-select>
            </el-input>

            <!-- 查询按钮 -->
            <el-button type="primary" @click="sreach">查询按钮</el-button>
            <!-- 重置按钮 -->
            <el-button type="primary" @click="clear">重置按钮</el-button>
        </div>
        <!-- 订单模块 -->
        <el-table
    :data="ordersData"
    style="width: 100%"
    :border="true"
    max-height="550">
    <el-table-column
      fixed
      prop="order_id"
      label="#"
      width="40">
    </el-table-column>
    <el-table-column
      fixed
      prop="order_number"
      label="订单编号"
      width="210">
    </el-table-column>
    <el-table-column
      prop="user_id"
      label="买家编号"
      width="">
    </el-table-column>
    <el-table-column
      prop="order_price"
      label="订单总价"
      width="">
    </el-table-column>
    <el-table-column
      label="支付状态"
      width="">
        <template slot-scope="orders">
            <!-- 自己的思路写的 -->
          <!-- <div :class="`color${orders.row.pay_status}`">
              {{ orders.row.pay_status == 0 ? "未支付":"已支付"}}
          </div> -->
          <!-- 大大思路写的 -->
          <div :style="{'color':orders.row.pay_status== 0 ?'red':'blue'}">{{ orders.row.pay_status == 0 ? "未支付":"已支付"}}</div>
        </template>
    </el-table-column>
    <el-table-column
      prop="is_send"
      label="发货状态"
      width="">
    </el-table-column>
    <el-table-column
      prop="order_fapiao_title"
      label="发票标题"
      width="">
    </el-table-column>
    <el-table-column
      prop="order_fapiao_content"
      label="发票抬头"
      width="">
    </el-table-column>
    <el-table-column
      prop="order_fapiao_company"
      label="发票内容"
      width="">
    </el-table-column>
    <el-table-column
      label="创建时间"
      width="165">
        <template slot-scope="orders">
          <div>
              {{ orders.row.create_time | formtime}}
          </div>
        </template>
    </el-table-column>
    <el-table-column
      label="修改时间"
      width="165">
      <template slot-scope="orders">
          <div>
              {{ orders.row.update_time | formtime}}
          </div>
      </template>
    </el-table-column>
    <el-table-column
      label="操作"
      width="">
      <template slot-scope="orders">
          <div>
              <el-button type="primary" icon="el-icon-edit" circle @click="EditOrdersbtton(orders.row)"></el-button>
          </div>
      </template>
    </el-table-column>
  </el-table>
   <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="this.ordersobject.pagenum"
      :page-sizes="[20, 50, 100, 200]"
      :page-size="this.ordersobject.pagesize"
      layout="total, sizes, prev, pager, next, jumper"
      :total="this.ordersobject.total"
      class="page"
      >
    </el-pagination>
    <!-- 编辑订单弹出层 -->
      <el-dialog title="编辑用户" :visible.sync="Editorders.ordersForm" width="40%">
        <el-form :model="Editorders" label-width="80px">
            <el-form-item label="订单ID">
            <el-input :value="Editorders.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="订单发货">
                <el-select v-model="Editorders.is_send" placeholder="请选择">
                <el-option label="已发货" value="1"></el-option>
                <el-option label="未发货" value="0"></el-option>
            </el-select>
            </el-form-item>
            <el-form-item label="订单价格">
            <el-input v-model="Editorders.order_price" ></el-input>
            </el-form-item>
            <el-form-item label="支付状态">
            <el-select v-model="Editorders.pay_status" placeholder="请选择">
                <el-option label="未付款" value="0"></el-option>
                <el-option label="已付款" value="1"></el-option>
            </el-select>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="Editorders.ordersForm = false">取 消</el-button>
            <el-button type="primary" @click="Editordersfn">修 改</el-button>
        </div>
        </el-dialog>
    </div>
</template>

<script>
import {getorders,getEditorders} from '@/api'
export default {
    data() { 
        return {
            // 获取数据的保存的地方
            ordersData:[],
            // 对象所需要的参数
            ordersobject:{
                // 查询参数
                query:"",
                // 当前页码
                pagenum:1,
                // 每页显示条数
                pagesize:20,
                // 用户ID
                user_id:"",
                // 支付状态
                pay_status:"",
                // 是否发货
                is_send:"",
                // ['个人','公司']
                order_fapiao_title:"",
                //    公司名称
                order_fapiao_company:"",
                // 发票内容
                order_fapiao_content:"",
                // 发货地址
                consignee_addr:"",
                // 数据的总量
                total:0
            },
            Editorders:{
                ordersForm:false,
                // 订单ID	不能为空携带在url中
                id:"",
                // 订单是否发货	1:已经发货，0:未发货	
                is_send:"",
                // 订单支付	支付方式 0未支付 1支付宝 2微信 3银行卡
                order_pay:"",	
                // 订单价格
                order_price:"",	
                // 订单数量		
                order_number:"",
                // 支付状态	订单状态： 0未付款、1已付款	
                pay_status:"",	



            }
        }
    },
    created() {
        // 页面加载完成以后去调用这个事件
        this.initlist()
    },
    methods: {
        // 订单修改
        EditOrdersbtton(data){
            this.Editorders.ordersForm=true;
            this.Editorders.id=data.order_id
            this.Editorders.is_send=data.is_send
            this.Editorders.order_price=data.order_price
            this.Editorders.pay_status=data.pay_status
        },
        // 封装一个函数
        initlist(){
            getorders(this.ordersobject).then(res=>{
                // 自己是思路做的
                // if (res.data.orders.length==0) {
                //     this.ordersobject.pagenum=1
                //     if (this.ordersobject.pagenum==1) {
                //         this.initlist()
                //     }
                // }
                // 老龚思路做的
                console.log(res);
                if (res.data.orders.length==0&&this.ordersobject.pagenum!=1) {
                    this.ordersobject.pagenum=1
                    this.initlist()
                }
                this.ordersData=res.data.orders
                this.ordersobject.total=res.data.total
            })
        },
        // 订单提交
        Editordersfn(){
            getEditorders(this.Editorders).then(res=>{
                if (res.meta.status==201) {
                    this.$message({
                        showClose: true,
                        message: res.meta.msg,
                        type: 'success',
                        duration:1000,
                    });
                    // 关闭对话框
                    this.Editorders.ordersForm = false
                    // 成功后刷新数据
                    this.ordersobject.user_id=res.data.user_id
                    this.initlist()
                }else{
                    // 不满足不关闭对话框,然后返回服务器的的结果
                    this.$message.error(res.meta.msg);
                }
            })
        },

        // 分页大小改变的触发的事件
        handleSizeChange(size){
            this.ordersobject.pagesize=size
            this.initlist()
        },
        // 分页改变的时候的事件
        handleCurrentChange(size){
            this.ordersobject.pagenum=size
            this.initlist()
        },
        // 查询按钮事件
        sreach(){
            
            this.initlist()
        },
        // 清除所有的按钮
        clear(){
            // 清除所有的双向数据绑定的值
            this.ordersobject.query=""
            this.ordersobject.pay_status=""
            this.ordersobject.consignee_addr=""
            this.ordersobject.is_send=""
            this.ordersobject.user_id=""
            this.ordersobject.order_fapiao_company=""
            this.ordersobject.order_fapiao_content=""
            this.ordersobject.order_fapiao_title=""
            // 重新请求
            this.initlist()
        },
    },
    filters:{
        formtime(time){
            // 创建时间对象
            var date = new Date(time*1000)
            // 获取年
            var n = date.getFullYear()
            // 获取月
            var y =(date.getMonth()+1).toString().padStart(2,0)
            // 获取日
            var r = date.getDate().toString().padStart(2,0)
            // 获取时
            var s = date.getHours().toString().padStart(2,0)
            // 获取分
            var f = date.getMinutes().toString().padStart(2,0)
            // 获取秒
            var ss = date.getSeconds().toString().padStart(2,0)
            // 获取时间轴
            var times = date.getTime()
            // 返回传参的数据
            return `${n}-${y}-${r}/${s}:${f}:${ss}`
        }
    }
}
</script>

<style lang="css" scoped>
.color0{
    color: red;
    font-size: 14px;
}
.color1{
    color: blue;
    font-size: 14px;
}
.page {
    padding: 5px 0;
    background-color: #D3DCE6;
  }
.el-select{
    margin:0px;
    width: 110px;
}
.el-button{
    margin: 0px;
}
.el-input{
    width : 475px;
}
.params{
    width:1180px;
}
#kuan{
    width: 300px;
}
</style>