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
          <li class="clickable-item">
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
        <li class="dividing"></li>
        <li><router-link :to="{ name: 'Member', params: { tid: $route.params.tid } }">团队</router-link></li>
        <li><router-link :to="{ name: 'UserInfo', params: { tid: $route.params.tid, uid: 1 } }">我自己</router-link></li>
      </ul>
      <el-popover
        placement="bottom"
        width="48"
        trigger="click"
        v-model="showUserMenu">
        <div slot="reference" class="account-info">
          <img id="avatar" width="24" src="../assets/avatar.jpg" alt="avatar">
          <small><i class="el-icon-caret-bottom" style="color: #888"></i></small>
        </div>
        <ul class="team-menu">
          <li class="clickable-item">
            <router-link :to="{ name: 'UserSettings', params: { tid: $route.params.tid } }">个人设置</router-link>
          </li>
          <li class="part-line"></li>
          <li class="clickable-item">
            <a href="/logout">退出</a>
          </li>
        </ul>
      </el-popover>
    </header>
    <el-card class="module-container">
      <keep-alive>
        <router-view :teamName="currentTeam.name"></router-view>
      </keep-alive>
    </el-card>
  </div>
</template>

<script>
export default {
  name: 'Team',
  data() {
    return {
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
      showTeamMenu: false,
      showUserMenu: false,
    };
  },
  computed: {
    otherTeamList() {
      return this.teamList.filter(t => t.tid !== parseInt(this.$route.params.tid, 10));
    },
    currentTeam() {
      const { teamList } = this;
      for (let i = 0; i < teamList.length; i++) {
        const team = teamList[i];
        if (team.tid === parseInt(this.$route.params.tid, 10)) {
          return team;
        }
      }
      return {
        tid: -1,
        name: 'Loading',
      };
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
</style>
