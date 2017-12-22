<template>
  <div>
    <el-popover
      placement="left"
      width="150"
      trigger="hover">
      <div style="float: right;">
        <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
        <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
        <el-button title="删除" class="icon-button" type="text" icon="el-icon-delete" @click="handleOnDelete"></el-button>
        <el-button v-if="task.finished === null" title="编辑" class="icon-button" type="text" icon="el-icon-edit" @click="handleOnEdit"></el-button>
        <el-button v-if="task.finished === null" title="开始任务" class="icon-button" type="text" :icon="doingIcon" @click="handleOnDoing"></el-button>
      </div>
      <div slot="reference" class="task-container">
        <input :checked="task.finished !== null" @change="handleFinishTask" type="checkbox">
        <router-link
          v-if="!$route.params.task_id"
          :to="{ name: 'TaskDetail', params: { task_id: task.taskId, path: path } }"
        >
          <el-button class="task-name" type="text">{{ task.name }}</el-button>
        </router-link>
        <h3 v-else style="display: inline;" class="task-name" type="text">{{ task.name }}</h3>
        <el-popover
          v-if="task.finished === null"
          placement="right"
          width="150"
          trigger="click">
          <div>
            <p style="margin: 0; padding: 0; font-weight: 800;">将任务指派给</p>
            <el-select @change="allocatingTask" v-model="task.uid" clearable placeholder="未指派任务" size="mini">
              <el-option
                v-for="member in members"
                :key="member.uid"
                :label="member.name"
                :value="member.uid">
              </el-option>
            </el-select>
          </div>
          <el-tag
            slot="reference"
            size="small"
            class="tag tag-clickable"
            type="info">
            {{ allocatingUserName }}
          </el-tag>
        </el-popover>
        <el-tag
          v-else
          size="small"
          class="tag"
          type="info">
          {{ allocatingUserName }}
        </el-tag>
        <el-tag
          v-if="task.projectName"
          size="small"
          class="tag"
          type="info">
          {{ task.projectName }}
        </el-tag>
      </div>
    </el-popover>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import * as service from '../service';

export default {
  name: 'Task',
  props: ['task', 'onBeginTask', 'onPauseTask', 'onDeleteTask', 'onEditTask', 'onFinishTask', 'onAllocatingTask', 'members', 'name'],
  computed: {
    doingIcon() {
      return this.task.doing === '1' ? 'el-icon-loading' : 'el-icon-caret-right';
    },
    allocatingUserName() {
      if (this.task.uid === null) {
        return '未分配';
      }
      const { members } = this;
      for (let i = 0; i < members.length; ++i) {
        if (members[i].uid === this.task.uid) {
          return members[i].name;
        }
      }
      return '';
    },
    path() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      if (this.name) {
        oldPath.push({
          name: this.name,
          params: {
            name: this.$route.name,
            params: JSON.parse(JSON.stringify(this.$route.params)),
          },
        });
      }
      return oldPath;
    },
    ...mapState(['user']),
  },
  methods: {
    handleOnDelete() {
      this.$confirm('确定删除任务？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteTask(this.task.taskId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.onDeleteTask(this.task);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    handleOnEdit() {
      this.$prompt('请输入新的任务名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: this.task.name,
        inputPattern: /.+/,
        inputErrorMessage: '任务名不能为空',
      }).then(({ value }) => {
        service.editTaskName(this.task.taskId, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.task.name = value;
          this.onEditTask(this.task);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    handleOnDoing() {
      if (this.task.doing === '1') {
        service.pauseTask(this.task.taskId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.task.doing = '0';
          this.onPauseTask(this.task);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      } else {
        service.beginTask(this.task.taskId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.task.doing = '1';
          this.task.uid = this.user.uid;
          this.onBeginTask(this.task);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }
    },
    allocatingTask(currentUid) {
      service.allocatingTask(this.task.taskId, currentUid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.task.uid = currentUid || null;
        this.task.doing = '0';
        this.onAllocatingTask(this.task, currentUid || null);
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    handleFinishTask(e) {
      if (e.target.checked) {
        service.markTaskFinished(this.task.taskId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.task.finished = Date.now();
          this.onFinishTask(this.task);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      } else {
        service.markTaskUnfinished(this.task.taskId).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.task.finished = null;
          this.onFinishTask(this.task);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }
    },
  },
};
</script>

<style lang="scss">
.task-container {
  margin: 8px 0;
  input {
    font-size: 1.2em;
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

  .tag-clickable {
    outline: none;
    cursor: pointer;
  }
}
.icon-button {
  padding: 0;
  font-size: 1.5em;
}
</style>
