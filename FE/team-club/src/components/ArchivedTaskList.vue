<template>
  <div>
    <h2>已归档的任务清单</h2>
    <div class="date-container" v-for="date in dateSet" :key="date">
      <span class="date-label">{{ date }}</span>
      <div class="tasks-container">
        <div style="margin-bottom: 10px;" v-for="taskList in taskLists[date]" :key="taskList.task_list_id">
          <i class="el-icon-check finished-icon"></i>
          <router-link
            class="task-link"
            :to="{ name: 'TaskListDetail', params: { pid: $route.params.pid, task_list_id: taskList.task_list_id, path: path } }">
            {{ taskList.name }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'FinishedTaskLists',
  created() {
    this.getArchivedTaskList();
  },
  data() {
    return {
      unsortedTaskLists: {},
    };
  },
  computed: {
    dateSet() {
      return Object.keys(this.taskLists);
    },
    taskLists() {
      const res = {};
      const { unsortedTaskLists } = this;
      for (let i = 0; i < unsortedTaskLists.length; ++i) {
        const date = new Date(unsortedTaskLists[i].archived);
        const dateStr = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
        if (!res[dateStr]) {
          res[dateStr] = [
            unsortedTaskLists[i],
          ];
        } else {
          res[dateStr].push(unsortedTaskLists[i]);
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
        name: '已归档的任务清单',
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      });
      return oldPath;
    },
  },
  methods: {
    getArchivedTaskList() {
      service.getArchivedTaskList(this.pid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.unsortedTaskLists = data.data;
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
