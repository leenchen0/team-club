<template>
  <div>
    <div class="task-list-header">
      <h3>{{ name }}</h3>
      <div style="margin-left: 15px; margin-top: 15px;">
        <el-button type="primary" size="mini" plain @click="editDiscussion">编辑</el-button>
        <el-button type="danger" size="mini" plain @click="handleOnDelete">删除</el-button>
      </div>
    </div>
    <div style="margin-bottom: 30px">
      <el-progress :percentage="0"></el-progress>
      <task
        v-for="task in tasks"
        :key="task.taskId"
        :task="task"
        :onBeginTask="onBeginTask"
        :onPauseTask="onPauseTask"
        :onEditTask="onEditTask"
        :onDeleteTask="onDeleteTask"
        :onFinishTask="onFinishTask"
      />
      <el-button type="text" @click="addTask">添加新任务</el-button>
    </div>
    <event-panel
      :events="events"
    />
    <comment
      :onSubmit="onSubmit"
    />
  </div>
</template>

<script>
import router from '../router';
import EventPanel from './EventPanel';
import Comment from './Comment';
import Task from './Task';

export default {
  name: 'TaskList',
  data() {
    return {
      taskListId: 1,
      name: '任务清单名称',
      tasks: [],
      events: [
        {
          eid: 1,
          type: 'comment',
          info: 'asdasdasd',
          user: {
            name: 'Pencil',
          },
          date: Date.now(),
        },
        {
          eid: 1,
          type: 'delete',
          info: '删除任务',
          user: {
            name: 'Pencil',
          },
          date: Date.now(),
        },
      ],
      onSubmit: (content) => {
        this.$message({
          message: content,
          type: 'info',
        });
      },
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
  methods: {
    addTask(taskListId) {
      this.$prompt('请输入任务名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '任务名不可为空',
      }).then(({ value }) => {
        this.tasks.push({ taskId: 1, name: value, taskListId: taskListId, doing: false });
      }).catch(() => { });
    },
    handleOnDelete() {
      this.$confirm('确认删除该任务订单？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        router.push({ name: 'Project', params: { pid: this.$route.params.pid } });
      }).catch(() => { });
    },
    editDiscussion() {
      this.$prompt('请输入新的任务清单名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: this.name,
        inputPattern: /.+/,
        inputErrorMessage: '清单名称不能为空',
      }).then(({ value }) => {
        this.name = value;
      }).catch(() => { });
    },
  },
  components: {
    EventPanel,
    Comment,
    Task,
  },
};
</script>

<style lang="scss">
.task-list-header {
  overflow: hidden;

  * {
    display: inline;
  }
}
</style>
