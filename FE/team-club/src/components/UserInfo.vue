<template>
  <div>
    <div class="userinfo-header">
      <img class="avatar" src="../assets/avatar.jpg" alt="avatar" width="80">
      <h2 style="margin: 0; padding: 0;">Username</h2>
      <p class="tips">9807175@qq.com</p>
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
        :task="task"
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

export default {
  name: 'UserInfo',
  data() {
    return {
      tasks: [
        {
          task_id: 1,
          name: 'task name1',
          doing: false,
          projectName: '项目名',
        },
        {
          task_id: 2,
          name: 'task name2',
          doing: true,
          projectName: '项目名2',
        },
        {
          task_id: 3,
          name: 'task name3',
          doing: false,
          projectName: '项目名2',
        },
        {
          task_id: 4,
          name: 'task name4',
          doing: true,
          projectName: '项目名3',
        },
        {
          task_id: 5,
          name: 'task name5',
          doing: true,
          projectName: '项目名',
        },
      ],
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
        this.tasks[i].doing = true;
      },
      onPauseTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks[i].doing = false;
      },
      onFinishTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks.splice(i, 1);
      },
    };
  },
  components: {
    Task,
  },
  methods: {
    viewFinishedTasks() {
      router.push({ name: 'FinishedTasks', params: { uid: this.$route.params.uid } });
    },
  },
};
</script>

<style lang="scss">
.userinfo-header {
  overflow: hidden;
}
.avatar {
  border-radius: 50%;
  float: left;
  padding-left: 10px;
  padding-right: 20px;
}
.userinfo-nav {
  list-style: none;
  margin: 0;
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
