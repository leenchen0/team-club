<template>
  <div style="position: relative">
    <div class="forbid-mask" v-if="taskList.deleted === '1'">
      <el-button type="warning" @click="recoverTaskList">该任务清单已被删除，点此恢复</el-button>
    </div>
    <div class="task-list-header">
      <h3>{{ taskList.name }}</h3>
      <div style="margin-left: 15px;" v-if="taskList.archived === null">
        <el-button type="primary" size="mini" plain @click="editTaskListName">编辑</el-button>
        <el-button type="info" size="mini" plain @click="archive">归档</el-button>
        <el-button type="danger" size="mini" plain @click="handleOnDelete">删除</el-button>
      </div>
    </div>
    <el-progress :percentage="progress"></el-progress>
    <div style="margin-bottom: 30px" v-if="taskList.archived === null">
      <task
        v-for="task in tasks"
        :key="task.taskId"
        :task="task"
        :members="members"
        :onBeginTask="onBeginTask"
        :onPauseTask="onPauseTask"
        :onEditTask="onEditTask"
        :onDeleteTask="onDeleteTask"
        :onFinishTask="onFinishTask"
      />
      <el-button type="text" @click="addTask">添加新任务</el-button>
    </div>
    <div v-else>
      <div v-for="task in tasks" :key="task.taskId">
        <input checked="true" type="checkbox" disabled>
        <router-link
          :to="{ name: 'TaskDetail', params: { task_id: task.taskId } }"
        >
          <el-button class="task-name" type="text">{{ task.name }}</el-button>
        </router-link>
        <el-tag
          slot="reference"
          size="small"
          class="tag"
          type="info">
          {{ allocatingUserName(task.uid) }}
        </el-tag>
      </div>
      任务清单已归档，需要编辑请
      <el-button type="text" @click="unArchive">激活</el-button>
    </div>
    <event-panel
      table="任务清单"
      :events="events"
    />
    <comment
      v-if="taskList.archived === null"
      :onSubmit="onSubmit"
    />
  </div>
</template>

<script>
import { mapState } from 'vuex';
import router from '../router';
import EventPanel from './EventPanel';
import Comment from './Comment';
import Task from './Task';
import * as service from '../service';

export default {
  name: 'TaskList',
  props: ['members'],
  data() {
    return {
      taskList: {
        name: 'loading',
        archived: null,
        deleted: '0',
      },
      tasks: [],
      events: [],
      onSubmit: (content) => {
        service.commentTaskList(this.taskListId, content).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.events.push({
            name: this.user.name,
            avatar: this.user.avatar,
            type: 'comment',
            date: Date.now(),
            info: content,
          });
        }).catch((err) => {
          this.$message.error(err.message);
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
        this.tasks[i].doing = '1';
      },
      onPauseTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.tasks[i].doing = '0';
      },
      onFinishTask: (task) => {
        const i = this.tasks.indexOf(task);
        this.$set(this.tasks, i, task);
      },
    };
  },
  created() {
    this.getTaskListInfo();
  },
  computed: {
    taskListId() {
      return this.$route.params.task_list_id;
    },
    ...mapState([
      'user',
    ]),
    progress() {
      if (this.tasks.length === 0) {
        return 0;
      }
      return (this.tasks.filter(t => t.finished !== null).length / this.tasks.length) * 100;
    },
  },
  methods: {
    addTask() {
      this.$prompt('请输入任务名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '任务名不可为空',
      }).then(({ value }) => {
        service.createTask(this.$route.params.pid, value, this.taskListId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.tasks.push({ taskId: data.data, uid: this.user.uid, name: value, taskListId: this.taskListId || null, doing: '0', finished: null });
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    handleOnDelete() {
      this.$confirm('确认删除该任务清单？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteTaskList(this.taskListId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          router.go(-1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    recoverTaskList() {
      service.recoverTaskList(this.taskListId).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.taskList.deleted = '0';
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    archive() {
      service.archivedTaskList(this.taskListId).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.taskList.archived = Date.now();
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    unArchive() {
      service.unArchivedTaskList(this.taskListId).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.taskList.archived = null;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    editTaskListName() {
      this.$prompt('请输入新的任务清单名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: this.taskList.name,
        inputPattern: /.+/,
        inputErrorMessage: '清单名称不能为空',
      }).then(({ value }) => {
        service.editTaskListName(this.taskListId, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.taskList.name = value;
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    getTaskListInfo() {
      service.getTaskListInfo(this.taskListId).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.taskList = data.data.taskList;
        this.events = data.data.events;
        this.tasks = data.data.tasks;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    allocatingUserName(uid) {
      if (uid === null) {
        return '未分配';
      }
      const { members } = this;
      for (let i = 0; i < members.length; ++i) {
        if (members[i].uid === uid) {
          return members[i].name;
        }
      }
      return '';
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
  margin-bottom: 10px;

  * {
    display: inline;
  }
}
.task-name {
  color: black;
  font-size: 1.2em;
  font-weight: 500;
  padding: 0;

  &:hover {
    color: #84a097;
  }
}

.tag {
  margin-left: 8px;
  border-radius: 8px;

  &:hover {
    color: black;
  }
}
</style>
