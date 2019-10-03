<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商品管理</el-breadcrumb-item>
      <el-breadcrumb-item>商品分类</el-breadcrumb-item>
    </el-breadcrumb>
    <el-button type="primary" @click="AddGoodsBtton">新增分类</el-button>
    <tree-grid
      :treeStructure="false"
      :columns="columns"
      :data-source="Categoriesdata"
      @deleteCate="deleteCategory"
      @editCate="editCategorys"
    ></tree-grid>
    <!-- 添加商品分类 -->
    <el-dialog title="添加商品分类" :visible.sync="AddGoods.AddGoodsForm" width="30%">
      <el-form :model="AddGoods" label-width="80px">
        <el-form-item label="分类名称">
          <el-input v-model="AddGoods.cat_name"></el-input>
        </el-form-item>
        <el-form-item label="父级分类">
          <el-cascader
            expand-trigger="hover"
            :props="AddGoods.props"
            v-model="AddGoods.selectedOptions"
            :options="AddGoods.options"
            change-on-select
          ></el-cascader>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="AddGoods.AddGoodsForm= false">取 消</el-button>
        <el-button type="primary" @click="AddGoodsServer">添 加</el-button>
      </div>
    </el-dialog>
    <!-- 修改商品分类 -->
    <el-dialog title="修改名称" :visible.sync="editCategory.editCategoryForm" width="35%">
      <el-form :model="editCategory" label-width="150px">
        <el-form-item label="新分类名称">
          <el-input v-model="editCategory.cat_name"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editCategory.editCategoryForm= false">取 消</el-button>
        <el-button type="primary" @click="editCategoryServer">修 改</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {Categorieslist,addcategories,deletecategories,EditcategoriesForm} from "@/api";
import TreeGrid from "@/components/TreeGrid"
export default {
  data() {
    return {
      Categoriesdata: [],
      columns: [
        {
          text: "分类名称",
          dataIndex: "cat_name",
          width: ""
        },
        {
          text: "是否有效",
          dataIndex: "cat_deleted",
          width: ""
        },
        {
          text: "排序",
          dataIndex: "cat_level",
          width: ""
        }
      ],
      AddGoods: {
        // 这个控制对话框
        AddGoodsForm: false,
        // 这个对话框的用户填写的商品信息
        cat_name: "",
        // 商品的上一级父ID
        cat_pid: "",
        // 商品的层级
        cat_level: "",

        // 自定义渲染下拉框的数据格式
        props: {
          value: "cat_id",
          label: "cat_name",
          children: "children"
        },
        // 保存的数据
        selectedOptions: "",

        // 下拉列表的数据源
        options: ""
      },
      editCategory: {
        editCategoryForm: false,
        cat_name: "",
        // 保存id
        id: 0
      }
    };
  },
  created() {
    this.init();

    // 这个是获取所有商品下拉选框的
    Categorieslist(2).then(res => {
      console.log(res);
      // 将传输回来的数据保存在数组中
      this.AddGoods.options = res.data;
      this.AddGoods.options.push({ cat_name: "作为顶级分类", cat_id: 0 });
    });
  },

  methods: {
    // 修改按钮以后的触发
    editCategoryServer() {
      EditcategoriesForm(this.editCategory).then(res => {
        if (res.meta.status == 200) {
          this.$message({
            showClose: true,
            message: res.meta.msg,
            type: "success",
            duration: 1000
          });
          // 刷新列表
          this.init();
          // 关闭对话框
          this.editCategory.editCategoryForm = false;
        } else {
          // 不满足不关闭对话框,然后返回服务器的的结果
          this.$message.error(res.meta.msg);
        }
      });
    },
    // 修改分类的事件
    editCategorys(cid) {
      // 保存cid
      this.editCategory.id = cid;
      // console.log(typeof cid)
      // // 打开对话框
      this.editCategory.editCategoryForm = true;
    },
    // 删除分类的事件
    deleteCategory(cid) {
      this.$confirm("此操作将永久删除该分类, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          deletecategories(cid).then(res => {
            if (res.meta.status == 200) {
              this.$message({
                showClose: true,
                message: res.meta.msg,
                type: "success",
                duration: 1000
              });
              // 刷新列表
              this.init();
            } else {
              // 不满足不关闭对话框,然后返回服务器的的结果
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
    // 完成填写相关信息后发送到服务器事件
    AddGoodsServer() {
      // 判断用户的层级
      if (this.AddGoods.selectedOptions.length == 1) {
        // 如果为1,那么判断用户的是新增顶级还是选择顶级某一个元素,那么进行进一步判断
        if (this.AddGoods.selectedOptions[0] == 0) {
          // 这里是要新增顶级的
          this.AddGoods.cat_pid = 0; //这个是当前元素的父元素pid是0
          this.AddGoods.cat_level = 0; //这个是当前元素的层级为0也就是最高级的
        } else {
          // 这里是选择顶级某一个层级的
          this.AddGoods.cat_pid = this.AddGoods.selectedOptions[0]; //那么就将用户选择的值赋值给他
          this.AddGoods.cat_level = 1; //那么层级就是一级分类
        }
      } else {
        // 这里是选择下拉的选框二级的
        this.AddGoods.cat_pid = this.AddGoods.selectedOptions[1];
        this.AddGoods.cat_level = 2;
      }
      // 将所有的数据发送到服务器
      addcategories(this.AddGoods).then(res => {
        // 接受响应处理
        if (res.meta.status == 201) {
          this.$message({
            showClose: true,
            message: res.meta.msg,
            type: "success",
            duration: 1000
          });
          // 关闭对话框
          this.AddGoods.AddGoodsForm = false;
          // 清除上一轮的数据
          this.AddGoods.cat_name = "";
          this.AddGoods.selectedOptions = [];
        } else {
          // 不满足不关闭对话框,然后返回服务器的的结果
          this.$message.error(res.meta.msg);
        }
      });
    },
    init() {
      // 这个是获取所有商品列表渲染到页面的
      Categorieslist(3).then(res => {
        // 将传输回来的数据保存在数组中
        this.Categoriesdata = res.data;
      });
    },
    // 新增商品按钮
    AddGoodsBtton() {
      // 打开对话框
      this.AddGoods.AddGoodsForm = true;
      // 从服务器获取所有的分类的信息
    }
  },
  // 定义自定义私有组件
  components: {
    TreeGrid
  }
};
</script>

<style lang="" scoped>
</style>