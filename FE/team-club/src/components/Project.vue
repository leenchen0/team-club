<template>
  <div>
    <div class="project-header">
      <h2 class="project-name">{{ project.name }}</h2>
      <p class="tips">{{ project.description || '无项目描述' }}</p>

      <div class="project-menu">
        <project-menu name="成员" :icon="members.length" :link="{ name: 'Member' }" />
        <div v-if="isMember" class="divider"></div>
        <project-menu
          v-if="isMember"
          name="设置"
          icon="el-icon-setting"
          :link="{ name: 'ProjectSettings', params: { path: path } }" />
      </div>
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
        v-if="task.taskListId === null"
        :name="project.name"
        :key="task.taskId"
        :task="task"
        :members="members"
        :onBeginTask="onBeginTask"
        :onPauseTask="onPauseTask"
        :onEditTask="onEditTask"
        :onDeleteTask="onDeleteTask"
        :onFinishTask="onFinishTask"
        :onAllocatingTask="onAllocatingTask"
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
            <el-button title="删除" class="icon-button" type="text" icon="el-icon-delete" @click="handleOnDeleteTaskList(taskList)"></el-button>
            <el-button title="编辑" class="icon-button" type="text" icon="el-icon-edit" @click="handleOnEditTaskList(taskList)"></el-button>
            <el-button title="归档" class="icon-button" type="text" icon="el-icon-sold-out" @click="handleOnArchiveTaskList(taskList)"></el-button>
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
          :name="project.name"
          :task="task"
          :members="members"
          :onBeginTask="onBeginTask"
          :onPauseTask="onPauseTask"
          :onEditTask="onEditTask"
          :onDeleteTask="onDeleteTask"
          :onFinishTask="onFinishTask"
          :onAllocatingTask="onAllocatingTask"
        />
        <el-button type="text" @click="addTask(taskList.taskListId)">添加任务</el-button>
      </div>
      <router-link
        :to="{ name: 'ProjectFinishedTasks', params: { pid: $route.params.pid, path: path } }"
      >
        <el-button type="text">已完成任务</el-button>
      </router-link>
      <router-link
        :to="{ name: 'ArchivedTaskList', params: { pid: $route.params.pid, path: path } }"
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
        <div style="display: flex; align-items: center;">
          <img class="avatar" width="32" :src="discussion.avatar" alt="avatar">
          <small class="username">{{ discussion.name }}</small>
          <router-link
            :to="{ name: 'DiscussionDetail', params: { pid: $route.params.pid, did: discussion.did, path: path } } ">
            <el-button class="discussion-topic" type="text">{{ discussion.topic }}</el-button>
          </router-link>
        </div>
        <span class="discussion-date">{{ discussion.date }}</span>
      </div>
    </div>
    <file-browser :name="project.name" :dirId="project.dirId" />
    <document-browser :name="project.name" :docDirId="project.docDirId" />
  </div>
</template>

<script>
import { mapState } from 'vuex';
import FileBrowser from './FileBrowser';
import DocumentBrowser from './DocumentBrowser';
import Task from './Task';
import ProjectMenu from './ProjectMenu';
import * as service from '../service';

export default {
  name: 'Project',
  props: ['members'],
  components: {
    FileBrowser,
    DocumentBrowser,
    Task,
    ProjectMenu,
  },
  mounted() {
    this.getProjectInfo();
  },
  data() {
    return {
      tasks: [],
      taskLists: [],
      discussions: [],
      project: {
        name: '',
        dirId: null,
        docDirId: null,
        description: 'loading',
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
        this.tasks[i].doing = '1';
      },
      onPauseTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks[i].doing = '0';
      },
      onFinishTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks.splice(i, 1);
      },
      onAllocatingTask: (task, uid) => {
        const i = this.tasks.indexOf(task);
        this.tasks[i].uid = uid;
      },
    };
  },
  computed: {
    ...mapState([
      'user',
    ]),
    isMember() {
      return this.members.filter(m => m.uid === this.user.uid).length > 0;
    },
    path() {
      return [{
        name: this.project.name,
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      }];
    },
  },
  methods: {
    addTask(taskListId) {
      this.$prompt('请输入任务名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '任务名不可为空',
      }).then(({ value }) => {
        service.createTask(this.$route.params.pid, value, taskListId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.tasks.push({ taskId: data.data, uid: this.user.uid, name: value, taskListId: taskListId || null, doing: '0', finished: null });
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    addTaskList() {
      this.$prompt('请输入任务清单名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '清单名不可为空',
      }).then(({ value }) => {
        service.createTaskList(this.$route.params.pid, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.taskLists.push({ taskListId: data.data, name: value });
        }).catch((err) => {
          this.$message.error(err.message);
        });
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
        service.deleteTaskList(taskList.taskListId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          const i = this.taskLists.indexOf(taskList);
          this.taskLists.splice(i, 1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
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
        service.editTaskListName(taskList.taskListId, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          taskList.name = value;
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    handleOnArchiveTaskList(taskList) {
      service.archivedTaskList(taskList.taskListId).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        const i = this.taskLists.indexOf(taskList);
        this.taskLists.splice(i, 1);
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    addDiscusstion() {
      this.$prompt('请输入讨论话题', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '话题不可为空',
      }).then(({ value }) => {
        service.createDiscussion(this.$route.params.pid, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.discussions.push({
            did: data.data,
            topic: value,
            description: 'R.T.',
            ...this.user,
          });
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    getProjectInfo() {
      service.getProjectInfo(this.$route.params.pid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.taskLists = data.data.taskLists;
        this.tasks = data.data.tasks;
        this.discussions = data.data.discussions;
        this.project = data.data.project;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">
.divider {
    background-color: #a4d0b7;
    width: 1px;
    margin: 15px 10px;
    height: 40px;
}
.project-header {
  position: relative;
  padding-bottom: 20px;
  margin-bottom: 5px;
  border-bottom: 4px solid #b4d0c7;
  overflow: hidden;
}
.project-menu {
  position: absolute;
  right: 0px;
  top: 0px;

  display: flex;
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
}uoq
.icon-button {
  padding: 0;
  font-size: 1.5em;
}
.username {
  margin-left: 20px;
  line-height: 35px;
}
.project-discussion {
  margin-bottom: 30px;
}
.discussion-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 12px;
  margin-bottom: 12px;
  border-bottom: 1px solid #eee;

  .discussion-topic {
    display: inline;
    margin-left: 10px;
  }

  .discussion-date {
    margin-right: 20px;
    font-size: 0.8em;
  }
}
</style>
