<template>
  <div>
    <h2>已完成的任务</h2>
    <div class="date-container" v-for="date in dateSet" :key="date">
      <span class="date-label">{{ date }}</span>
      <div class="tasks-container">
        <div style="margin-bottom: 10px;" v-for="task in tasks[date]" :key="task.task_id">
          <i class="el-icon-check finished-icon"></i>
          <router-link class="task-link" :to="{ name: 'TaskDetail', params: { task_id: task.task_id, path: path } }">{{ task.name }}</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'FinishedTasks',
  created() {
    this.getProjectFinishedTasks();
  },
  data() {
    return {
      unsortedTasks: {},
    };
  },
  computed: {
    dateSet() {
      return Object.keys(this.tasks);
    },
    tasks() {
      const res = {};
      const { unsortedTasks } = this;
      for (let i = 0; i < unsortedTasks.length; ++i) {
        const date = new Date(unsortedTasks[i].finished);
        const dateStr = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
        if (!res[dateStr]) {
          res[dateStr] = [
            unsortedTasks[i],
          ];
        } else {
          res[dateStr].push(unsortedTasks[i]);
        }
      }
      return res;
    },
    pid() {
      return this.$route.params.pid;
    },
    path() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      oldPath.push({
        name: '已完成的任务',
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      });
      return oldPath;
    },
  },
  methods: {
    getProjectFinishedTasks() {
      service.getProjectFinishedTasks(this.pid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.unsortedTasks = data.data;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">
.date-label {
  float: left;
  width: 120px;
  font-size: 1.2em;
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
