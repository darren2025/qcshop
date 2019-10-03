<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>权限管理</el-breadcrumb-item>
      <el-breadcrumb-item>权限列表</el-breadcrumb-item>
    </el-breadcrumb>
    <el-table :data="rightsdata" border style="width: 100%">
      <el-table-column prop="id" label="序号" width="80"></el-table-column>
      <el-table-column label="权限名称">
        <template slot-scope="rights">
          <div :class="`red${rights.row.level}`">{{rights.row.authName | rightss(rights.row.level)}}</div>
        </template>
      </el-table-column>
      <el-table-column prop="level" label="权限层级"></el-table-column>
      <el-table-column prop="path" label="权限路径"></el-table-column>
      <el-table-column label="操作"></el-table-column>
    </el-table>
  </div>
</template>

<script>
import { rights } from "@/api";
export default {
  filters: {
    rightss(authName, level) {
      return "　".repeat(level) + authName;
    }
  },
  data() {
    return {
      rightsdata: []
    };
  },
  // 页面一加载完成去服务端获取数据
  created() {
    rights("list").then(res => {
      console.log(res);
      // 建立一个数组用来接收值
      var rusolt = [];
      // 递归函数
      function levelstor(level, id) {
        // 遍历每一个传进来的元素
        level.forEach(item => {
          // 判断传进来的元素pid与与我传进来的id是否一样
          if (item.pid == id) {
            // 满足就将这个元素添加到新创建的数组中
            rusolt.push(item);
            // 寻找完成在寻找他的后代
            levelstor(level, item.id);
          }
        });
      }
      levelstor(res.data, 0);
      this.rightsdata = rusolt;
    });
  }
};
</script>

<style  scoped>
.red0 {
  color: red;
  font-size: 18px;
  font-weight: bold;
}
.red1 {
  color: crimson;
  font-size: 16px;
  font-weight: bold;
}
.red2 {
  color: brown;
  font-size: 14px;
  font-weight: bold;
}
</style>