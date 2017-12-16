<template>
  <div>
    <div align="center">
      <header>
        <h2 id="header-title">TeamClub</h2>
        <div id="userinfo">
          <small>hi, {{ name }}</small>
          <small><a href="#">退出</a></small>
        </div>
      </header>
      <el-card id="team-list-container" :body-style="{ padding: '0px' }">
        <router-link v-for="(team, index) in teamList" :key="index" :to="{ name: 'Team', params: { tid: team.tid } }" class="team-item">
          {{ team.name }}
        </router-link>
        <div class="team-item create-team-item" @click="showCreateDialog = true">
          <i class="el-icon-plus"></i> 新的团队
        </div>
      </el-card>
    </div>
    <el-dialog
      title="新建团队"
      :visible.sync="showCreateDialog"
      width="30%">
      <el-form :model="createForm">
        <el-form-item label="">
          <el-input v-model="createForm.name" placeholder="新团队名称"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="showCreateDialog = false">取 消</el-button>
        <el-button type="primary" @click="showCreateDialog = false">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'Launchpad',
  data() {
    return {
      name: 'Pencil',
      teamList: [
        {
          tid: 1,
          name: 'Team Club',
        },
        {
          tid: 2,
          name: 'Pencil',
        },
        {
          tid: 3,
          name: 'IoT',
        },
      ],
      showCreateDialog: false,
      createForm: {
        name: '',
      },
    };
  },
};
</script>

<style lang="scss">
header {
  overflow: hidden;
  padding: 1.5em 0 1em 0;
}
#team-list-container {
  width: 500px;
  margin-top: 2em;
}
#header-title {
  color: #84a097;
  margin: 0px;
  float: left;
}
#userinfo {
  float: right;
  margin-top: 8px;
  height: 100%;
  line-height: 100%;
  a {
    color: teal;
    &:hover {
      color: #20a0a0;
      text-decoration: underline;
    }
  }
}
.team-item {
  display: block;
  font-size: 1.3em;
  padding: 1em 0;
  &:hover {
    background-color: #efefef;
  }
}
.create-team-item {
  cursor: pointer;
  background-color: #f5f5f5;
  &:hover {
    background-color: #efefef;
  }
}
</style>
