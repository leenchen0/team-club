<template>
  <div>
    <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
      <h2 style="margin: 0; display: inline; margin-right: 10px;">编辑历史</h2>
    </div>
    <el-table
      :data="tableData"
      style="width: 100%">
      <el-table-column
        prop="date"
        label="编辑时间">
      </el-table-column>
      <el-table-column
        prop="name"
        label="用户">
      </el-table-column>
      <el-table-column
        label="操作"
        width="100">
        <template slot-scope="scope">
          <router-link :to="{ name: 'ViewDocument', params: { hid: scope.row.hid, path: path } }">
            <el-button type="text" size="small">查看</el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'EditHistory',
  created() {
    this.getHistory();
  },
  data() {
    return {
      tableData: [],
    };
  },
  computed: {
    path() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      oldPath.push({
        name: '编辑历史',
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      });
      return oldPath;
    },
  },
  methods: {
    getHistory() {
      service.getDocHistory(this.$route.params.doc_id).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.tableData = data.data;
        if (this.tableData.length > 0) {
          this.tableData[0].date += ' (当前版本)';
        }
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">

</style>
