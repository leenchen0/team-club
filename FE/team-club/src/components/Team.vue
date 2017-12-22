<template>
  <div>
    <header>
      <el-popover
        placement="bottom"
        width="100"
        trigger="click"
        v-model="showTeamMenu">
        <h3 slot="reference" class="team-name">{{ currentTeam.name }} <small><i class="el-icon-caret-bottom"></i></small></h3>      
        <ul class="team-menu">
          <li class="clickable-item" v-if="user.uid === currentTeam.uid">
            <router-link :to="{ name: 'Settings' }">团队账户</router-link>
          </li>
          <li style="padding-left: 8px; height: 20px;">
            <small style="color: #aaa;">切换团队</small>
            <div class="part-line" style="margin-top: -8px;"></div>
          </li>
          <li
            class="clickable-item"
            v-for="(team, index) in otherTeamList"
            :key="index">
            <router-link :to="{ name: 'Team', params: { tid: team.tid } }">{{ team.name }}</router-link>
          </li>
          <li class="part-line"></li>
          <li class="clickable-item">
            <router-link :to="{ name: 'Launchpad' }">
              <small>创建/管理团队</small>
            </router-link>
          </li>
        </ul>
      </el-popover>
      <ul class="nav">
        <li><router-link :to="{ name: 'Projects', params: { tid: $route.params.tid }}">项目</router-link></li>
        <li><router-link v-if="isTeamMember" :to="{ name: 'Dynamic', params: { tid: $route.params.tid }}">动态</router-link></li>
        <li class="dividing"></li>
        <li><router-link :to="{ name: 'Member', params: { tid: $route.params.tid } }">团队</router-link></li>
        <li><router-link :to="{ name: 'UserInfo', params: { tid: $route.params.tid, uid: user.uid } }">我自己</router-link></li>
      </ul>
      <el-popover
        placement="bottom"
        width="48"
        trigger="click"
        v-model="showUserMenu">
        <div slot="reference" class="account-info">
          <img id="avatar" width="24" height="24" :src="user.avatar" alt="avatar">
          <small><i class="el-icon-caret-bottom" style="color: #888"></i></small>
        </div>
        <ul class="team-menu">
          <li class="clickable-item">
            <router-link :to="{ name: 'UserSettings', params: { tid: $route.params.tid } }">个人设置</router-link>
          </li>
          <li class="part-line"></li>
          <li class="clickable-item">
            <a href="#" @click="logout">退出</a>
          </li>
        </ul>
      </el-popover>
    </header>
    <div
      v-for="(path, index) in ($route.params.path || [])"
      :key="index"
      @click="go(path.params)">
      <el-card class="path">
        {{ path.name }}
      </el-card>
    </div>
    <el-card class="module-container">
      <!-- <keep-alive> -->
      <router-view :teamName="currentTeam.name" :owner="currentTeam.uid" :changeTeamName="updateTeamName" :members="members"></router-view>
      <!-- </keep-alive> -->
    </el-card>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import router from '../router';
import * as service from '../service';
import * as types from '../store/mutation-types';

export default {
  name: 'Team',
  created() {
    service.getTeamList().then((data) => {
      if (data.error) {
        throw Error(data.error);
      }
      this.teamList = data.data;
    }).catch((err) => {
      this.$message.error(err.message);
    });
    this.getTeamMembers();
  },
  data() {
    return {
      teamList: [],
      members: [],
      showTeamMenu: false,
      showUserMenu: false,
    };
  },
  computed: {
    ...mapState([
      'user',
    ]),
    otherTeamList() {
      return this.teamList.filter(t => t.tid !== this.$route.params.tid, 10);
    },
    currentTeam() {
      const { teamList } = this;
      for (let i = 0; i < teamList.length; i++) {
        const team = teamList[i];
        if (team.tid === this.$route.params.tid) {
          return team;
        }
      }
      return {
        tid: -1,
        name: 'Loading',
      };
    },
    isTeamMember() {
      return this.members.filter(m => m.uid === this.user.uid).length > 0;
    },
  },
  methods: {
    ...mapMutations({
      remove_info: types.LOGOUT,
    }),
    updateTeamName(teamName) {
      const { teamList } = this;
      for (let i = 0; i < teamList.length; i++) {
        const team = teamList[i];
        if (team.tid === this.$route.params.tid) {
          team.name = teamName;
          this.$set(this.teamList, i, team);
          break;
        }
      }
    },
    logout() {
      service.logout().then(() => {
        this.remove_info();
        router.push({ name: 'Login' });
      });
    },
    getTeamMembers() {
      service.getAcceptedMembers(this.$route.params.tid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.members = data.data;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    go(params) {
      router.push(params);
    },
  },
  watch: {
    $route() {
      this.showTeamMenu = false;
      this.showUserMenu = false;
    },
  },
};
</script>

<style lang="scss">
.team-name {
  margin: 0px;
  cursor: pointer;
  float: left;
  color: #84a097;
}
header {
  overflow: hidden;
  padding: 1.5em 0 1em 0;
}
.nav {
  position: relative;
  float: left;
  list-style: none;
  margin: 2px 0 0 20px;
  padding: 0;

  .dividing {
    width: 1px;
    height: 20px;
    margin-top: 2px;
    background-color: #ccc;
  }

  li {
    a {
      color: #84a097;
      &:hover {
        color: #446057;
      }
    }
    float: left;
    margin: 0 10px;
  }
}
.account-info {
  float: right;
  cursor: pointer;

  #avatar {
    float: left;
    border-radius: 50%;
  }
}
.module-container {
  margin: 1em;
}
.team-menu {
  list-style: none;
  margin: 0;
  padding: 0;

  .clickable-item {
    a {
      display: block;
      padding: 8px;
    }

    &:hover {
      background-color: #efefef;

      a {
        color: #94b0a7;
      }
    }
  }

  .part-line {
    height: 1px;
    background-color: #efefef;
    margin: 3px 0;
  }
}
.path {
  height: 50px;
  background-color: #f9f9f9;
  color: #888;
  border-bottom: 0;
  margin: 1em 2em -1em 2em;
  cursor: pointer;

  &:hover {
    background-color: #fdfdfd;
  }

  .el-card__body {
    display: flex;
    font-size: 1.2em;
    align-items: center;
    padding: 0px 20px;
    height: 100%;
  }
}
</style>
