<template>
  <div>
    <div class="project-header">
      <h2 class="project-name">项目名</h2>
      <p class="tips">项目描述</p>
    </div>
    <div class="project-tasks">
      <div>
        <h3 class="module-name">任务</h3>
        <el-dropdown
          split-button
          type="primary"
          trigger="click"
          size="mini"
          @click="() => addTask()"
          @command="handleCommand"
        >
          添加任务
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="task">添加任务</el-dropdown-item>
            <el-dropdown-item command="taskList">添加清单</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
      <task
        v-for="task in tasks"
        v-if="task.taskListId === undefined"
        :key="task.taskId"
        :task="task"
        :onBeginTask="onBeginTask"
        :onPauseTask="onPauseTask"
        :onEditTask="onEditTask"
        :onDeleteTask="onDeleteTask"
        :onFinishTask="onFinishTask"
      />
      <div
        v-for="taskList in taskLists"
        :key="taskList.taskListId"
      >
        <el-popover
          placement="left"
          width="150"
          trigger="hover">
          <div style="float: right;">
            <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
            <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
            <el-button class="icon-button" type="text" icon="el-icon-delete" @click="handleOnDeleteTaskList(taskList)"></el-button>
            <el-button class="icon-button" type="text" icon="el-icon-edit" @click="handleOnEditTaskList(taskList)"></el-button>
            <el-button class="icon-button" type="text" icon="el-icon-sold-out" @click="handleOnArchiveTaskList(taskList)"></el-button>
          </div>
          <router-link
            slot="reference"
            :to="{ name: 'TaskListDetail', params: { pid: $route.params.pid, task_list_id: taskList.taskListId } } ">
            <h4 class="task-list-name">{{ taskList.name }}</h4>
          </router-link>
        </el-popover>
        <task
          v-for="task in tasks"
          v-if="task.taskListId === taskList.taskListId"
          :key="task.taskId"
          :task="task"
          :onBeginTask="onBeginTask"
          :onPauseTask="onPauseTask"
          :onEditTask="onEditTask"
          :onDeleteTask="onDeleteTask"
          :onFinishTask="onFinishTask"
        />
        <el-button type="text" @click="addTask(taskList.taskListId)">添加任务</el-button>
      </div>
      <router-link
        :to="{ name: 'ProjectFinishedTasks', params: { pid: $route.params.pid } }"
      >
        <el-button type="text">已完成任务</el-button>
      </router-link>
      <router-link
        :to="{ name: 'ArchivedTaskList', params: { pid: $route.params.pid } }"
      >
        <el-button type="text">已归档任务清单</el-button>
      </router-link>
    </div>
    <div class="project-discussion">
      <div>
        <h3 class="module-name">讨论</h3>
        <el-button type="primary" size="mini" @click="addDiscusstion">发起讨论</el-button>
      </div>
      <div
        v-for="discussion in discussions"
        :key="discussion.did"
        class="discussion-container">
        <img class="avatar" width="32" :src="discussion.user.avatar" alt="avatar">
        <small class="username">{{ discussion.user.name }}</small>
        <router-link
          :to="{ name: 'DiscussionDetail', params: { pid: $route.params.pid, did: discussion.did } } ">
          <el-button class="discussion-topic" type="text">{{ discussion.topic }}</el-button>
        </router-link>
      </div>
    </div>
    <file-browser />
    <document-browser />
  </div>
</template>

<script>
import FileBrowser from './FileBrowser';
import DocumentBrowser from './DocumentBrowser';
import Task from './Task';

export default {
  name: 'Project',
  components: {
    FileBrowser,
    DocumentBrowser,
    Task,
  },
  data() {
    return {
      tasks: [],
      taskLists: [],
      discussions: [],
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
    addTaskList() {
      this.$prompt('请输入任务清单名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '清单名不可为空',
      }).then(({ value }) => {
        this.taskLists.push({ taskListId: 1, name: value });
      }).catch(() => { });
    },
    handleCommand(command) {
      if (command === 'task') {
        this.addTask();
      } else {
        this.addTaskList();
      }
    },
    handleOnDeleteTaskList(taskList) {
      this.$confirm('删除任务清单会将任务清单下的任务一并删除，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        const i = this.taskLists.indexOf(taskList);
        this.taskLists.splice(i, 1);
      }).catch(() => { });
    },
    handleOnEditTaskList(taskList) {
      this.$prompt('请输入新的任务清单名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: taskList.name,
        inputPattern: /.+/,
        inputErrorMessage: '清单名不可为空',
      }).then(({ value }) => {
        taskList.name = value;
      }).catch(() => { });
    },
    handleOnArchiveTaskList(taskList) {
      const i = this.taskLists.indexOf(taskList);
      this.taskLists.splice(i, 1);
    },
    addDiscusstion() {
      this.$prompt('请输入讨论话题', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '话题不可为空',
      }).then(({ value }) => {
        this.discussions.push({
          did: 1,
          topic: value,
          description: 'R.T.',
          user: {
            uid: 1,
            name: 'Pencil',
            avatar: '/static/img/avatar.f7270b8.jpg',
          },
        });
      }).catch(() => { });
    },
  },
};
</script>

<style lang="scss">
.project-header {
  padding-bottom: 10px;
  margin-bottom: 5px;
  border-bottom: 4px solid #b4d0c7;
}
.project-name {
  margin: 0;
  padding: 0;
}
.project-tasks {
  border-top: 1px solid #b4d0c7;
}
.module-name {
  display: inline-block;
  margin-right: 12px;
}
.task-list-name {
  color: teal;
  cursor: pointer;

  &:hover {
    color: turquoise;
  }
}
.icon-button {
  padding: 0;
  font-size: 1.5em;
}
.avatar {
  display: inline-block;
  margin-top: 3px;
  border-radius: 50%;
}
.username {
  margin-left: 20px;
  line-height: 35px;
}
.project-discussion {
  margin-bottom: 30px;
}
.discussion-container {
  overflow: hidden;
  padding-bottom: 12px;
  margin-bottom: 12px;
  border-bottom: 1px solid #eee;

  .discussion-topic {
    display: inline;
    margin-left: 10px;
  }

  .discussion-date {
    margin-left: 10px;
    float: right;
  }
}
</style>
