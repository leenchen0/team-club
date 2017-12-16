<template>
  <div>
    <h2>已完成的任务</h2>
    <div class="date-container" v-for="date in dateSet" :key="date">
      <span class="date-label">{{ date }}</span>
      <div class="tasks-container">
        <div style="margin-bottom: 10px;" v-for="task in tasks[date]" :key="task.task_id">
          <i class="el-icon-check finished-icon"></i>
          <router-link class="task-link" :to="'/team/1'">{{ task.name }}</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FinishedTasks',
  data() {
    return {
      tasks: {
        '11/11': [
          {
            task_id: 1,
            name: '支持站点输入',
          },
          {
            task_id: 1,
            name: '支持站点输入',
          },
        ],
        '11/9': [
          {
            task_id: 1,
            name: '支持站点输入',
          },
          {
            task_id: 1,
            name: '支持站点输入',
          },
        ],
      },
    };
  },
  computed: {
    dateSet() {
      return Object.keys(this.tasks);
    },
  },
};
</script>

<style lang="scss">
.date-label {
  float: left;
  width: 100px;
  font-size: 1.5em;
}
.date-container {
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}
.task-name {
  color: black;
}
.tasks-container {
  display: inline-block;
}
.finished-icon {
  color: #84a097;
}
.task-link:hover {
  color: #648077;
  font-weight: 600;
}
</style>
