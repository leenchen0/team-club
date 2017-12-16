<template>
  <div>
    <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
      <h2 style="margin: 0; display: inline; margin-right: 10px;">{{ teamName }}</h2>
      <el-button type="text" @click="editTeamName">修改团队名称</el-button>
    </div>
    <div class="operation-container">
      <h3 style="margin: 0;">删除团队</h3>
      <p style="margin: 0;">如果你和你的团队成员，从今往后都不再需要访问该团队的信息，可以删除团队账户。</p>
      <el-button @click="deleteTeam" class="operation-button" size="small" type="danger" plain>了解风险，删除当前团队</el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Settings',
  props: ['teamName'],
  methods: {
    editTeamName() {
      this.$prompt('请输入新的团队名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '新团队名不可为空',
      }).then(({ value }) => {
        this.name = value;
      }).catch(() => { });
    },
    deleteTeam() {
      this.$confirm('此操作将永久删除该团队且无法恢复, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        this.$message({
          type: 'success',
          message: '删除成功!',
        });
      }).catch(() => { });
    },
  },
};
</script>

<style lang="scss">
.operation-container {
  position: relative;
}
.operation-button {
  position: absolute;
  top: 50%;
  margin-top: -15px;
  right: 0px;
}
</style>
