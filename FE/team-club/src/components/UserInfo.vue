<template>
  <div>
    <div class="userinfo-header">
      <img
        style="float: left; padding-left: 10px; padding-right: 20px;"
        class="avatar"
        width="80"
        height="80"
        :src="user.avatar"
        alt="avatar">
      <h2 style="margin: 0; padding: 0;">{{ user.name }}</h2>
      <p class="tips">{{ user.email }}</p>
    </div>
    <ul class="userinfo-nav">
      <li class="activate">任务</li>
    </ul>
    <div style="margin-left: 30px;">
      <h5 class="sort-label"><i class="el-icon-tickets"></i> 未完成任务</h5>
      <task
        :onDeleteTask="onDeleteTask"
        :onEditTask="onEditTask"
        :onBeginTask="onBeginTask"
        :onPauseTask="onPauseTask"
        :onFinishTask="onFinishTask"
        :onAllocatingTask="onAllocatingTask"
        :members="members"
        :task="task"
        :name="user.name"
        v-for="task in tasks"
        :key="task.task_id" />
    </div>
    <div style="margin: 20px 0 0 30px;">
      <el-button type="text" @click="viewFinishedTasks">
        查看已完成任务
      </el-button>
    </div>
  </div>
</template>

<script>
import Task from './Task';
import router from '../router';
import * as service from '../service';

export default {
  name: 'UserInfo',
  props: ['members'],
  mounted() {
    this.getUnfinishedTasks();
  },
  data() {
    return {
      user: {
        uid: '',
        name: '',
        email: '',
        avatar: '',
      },
      tasks: [],
      onDeleteTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks.splice(i, 1);
      },
      onEditTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.$set(this.tasks, i, task);
      },
      onBeginTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.$set(this.tasks, i, task);
      },
      onPauseTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.$set(this.tasks, i, task);
      },
      onFinishTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks.splice(i, 1);
      },
      onAllocatingTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.$set(this.tasks, i, task);
      },
    };
  },
  components: {
    Task,
  },
  methods: {
    viewFinishedTasks() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      oldPath.push({
        name: this.user.name,
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      });
      router.push({ name: 'FinishedTasks', params: { uid: this.$route.params.uid, path: oldPath } });
    },
    getUnfinishedTasks() {
      const { tid, uid } = this.$route.params;
      service.getUnfinishedTasks(tid, uid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.tasks = data.data.tasks;
        this.user = data.data.user;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">
.userinfo-header {
  overflow: hidden;
}
.userinfo-nav {
  list-style: none;
  margin: 15px 0;
  padding: 8px 0;
  border-top: 1px solid #eee;
  border-bottom: 1px solid #eee;
  overflow: hidden;

  li {
    color: #999;
    float: left;
    margin-left: 30px;
  }

  .activate {
    color: #84a097;
    font-weight: 600;
  }
}
.sort-label {
  color: #999;
  font-weight: 800;
}
</style>
