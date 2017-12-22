<template>
  <div>
    <div align="center">
      <header>
        <h2 id="header-title">TeamClub</h2>
        <div id="userinfo">
          <small>hi, {{ user.name }}</small>
          <small><a href="#" @click="logout">退出</a></small>
        </div>
      </header>
      <el-card id="team-list-container" :body-style="{ padding: '0px' }">
        <router-link v-for="(team, index) in teamList" :key="index" :to="{ name: 'Team', params: { tid: team.tid } }" class="team-item">
          {{ team.name }}
        </router-link>
        <div class="team-item create-team-item" @click="createTeam">
          <i class="el-icon-plus"></i> 新的团队
        </div>
      </el-card>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import * as service from '../service';
import router from '../router';
import * as types from '../store/mutation-types';

export default {
  name: 'Launchpad',
  mounted() {
    service.getTeamList().then((data) => {
      if (data.error) {
        throw Error(data.error);
      }
      this.teamList = data.data;
    }).catch(() => {
      this.$message.error('获取团队列表失败!');
    });
  },
  data() {
    return {
      name: 'Pencil',
      teamList: [],
      createForm: {
        name: '',
      },
    };
  },
  computed: mapState([
    'user',
  ]),
  methods: {
    ...mapMutations({
      remove_info: types.LOGOUT,
    }),
    logout() {
      service.logout().then(() => {
        this.remove_info();
        router.push({ name: 'Login' });
      });
    },
    createTeam() {
      this.$prompt('请输入新的团队名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '团队名称不能为空',
      }).then(({ value }) => {
        service.createTeam(value).then((data) => {
          if (data.error) {
            throw Error('创建团队失败');
          }
          this.teamList.push({
            tid: data.data,
            name: value,
            uid: this.user.uid,
          });
        });
      }).catch(() => {
        this.$message.error('创建团队失败!');
      });
    },
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
