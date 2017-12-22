<template>
  <div style="position: relative">
    <div class="forbid-mask" v-if="task.deleted === '1'">
      <el-button type="warning" @click="recoverTask">该任务已被删除，点此恢复</el-button>
    </div>
    <div class="task-info" v-if="task.archived === null">
      <task
        :task="task"
        :members="members"
        :onBeginTask="onBeginTask"
        :onPauseTask="onPauseTask"
        :onEditTask="onEditTask"
        :onDeleteTask="onDeleteTask"
        :onFinishTask="onFinishTask"
        :onAllocatingTask="onAllocatingTask"
      />
      <p class="tips task-desc">{{ task.description }}</p>
      <div style="margin-left: 25px; margin-top: 15px;" v-if="task.finished === null && task.archived === null">
        <el-button type="primary" size="mini" plain @click="editTask">编辑</el-button>
        <el-button type="danger" size="mini" plain @click="handleOnDelete">删除</el-button>
      </div>
      <el-dialog title="编辑任务" :visible.sync="showEditTask">
        <el-form ref="form" :model="form" :rules="rules">
          <el-form-item prop="name" label="任务名" label-width="100px">
            <el-input v-model="form.name" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="任务描述" label-width="100px">
            <el-input v-model="form.description" auto-complete="off"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer">
          <el-button @click="showEditTask = false">取 消</el-button>
          <el-button type="primary" @click="saveTask">确 定</el-button>
        </div>
      </el-dialog>
    </div>
    <div class="task-info" v-else>
      <input checked="true" type="checkbox" disabled>
      <span class="task-name" type="text">{{ task.name }}</span>
      <el-tag
        slot="reference"
        size="small"
        class="tag"
        type="info">
        {{ currentUserName }}
      </el-tag>
    </div>
    <event-panel
      table="任务"
      :events="events"
    />
    <comment
      v-if="task.archived === null"
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
  name: 'TaskDetail',
  props: ['members'],
  created() {
    this.getTaskInfo();
  },
  data() {
    return {
      task: {
        name: '',
        description: '',
        uid: null,
        finished: null,
        doing: false,
        deleted: '1',
      },
      events: [],
      showEditTask: false,
      form: {
        name: '',
        description: '',
      },
      rules: {
        name: [
          { required: true, message: '任务名不能为空', trigger: 'blur' },
        ],
      },
      onDeleteTask: () => {
        router.go(-1);
      },
      onEditTask: (task) => {
        this.task = task;
      },
      onBeginTask: () => {
        this.task.doing = '1';
      },
      onPauseTask: () => {
        this.task.doing = '0';
      },
      onFinishTask: (task) => {
        this.task = task;
      },
      onAllocatingTask: (task, currentUid) => {
        this.task.uid = currentUid;
        this.task.doing = '0';
      },
      onSubmit: (content) => {
        service.commentTask(this.task_id, content).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.events.push({
            name: this.user.name,
            avatar: this.user.avatar,
            info: content,
            date: Date.now(),
            type: 'comment',
          });
        }).catch((err) => {
          this.$message.error(err.message);
        });
      },
    };
  },
  methods: {
    handleOnDelete() {
      this.$confirm('确认删除该任务？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteTask(this.task_id).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          router.go(-1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    recoverTask() {
      service.recoverTask(this.task_id).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.task.deleted = '0';
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    editTask() {
      this.showEditTask = true;
      this.form = {
        name: this.task.name,
        description: this.task.description,
      };
    },
    saveTask() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          const { name, description } = this.form;
          service.editTask(this.task_id, name, description).then((data) => {
            if (data.error) {
              throw Error(data.error);
            }
            this.task.name = name;
            this.task.description = description;
            this.showEditTask = false;
          }).catch((err) => {
            this.$message.error(err.message);
          });
        }
      });
    },
    getTaskInfo() {
      service.getTaskInfo(this.task_id).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.task = data.data.task;
        this.task.taskId = this.$route.params.task_id;
        this.events = data.data.events;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
  computed: {
    task_id() {
      return this.$route.params.task_id;
    },
    doingIcon() {
      return this.task.doing === '1' ? 'el-icon-loading' : 'el-icon-caret-right';
    },
    currentUserName() {
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
    ...mapState([
      'user',
    ]),
  },
  components: {
    EventPanel,
    Comment,
    Task,
  },
};
</script>

<style lang="scss">
.task-info {
  padding-bottom: 20px;
  margin-bottom: 20px;
  border-bottom: 1px solid #ccc;
}
.task-desc {
  margin-left: 25px;
}
.icon-button {
  padding: 0;
  font-size: 1.5em;
}
.task-name {
  color: black;
  font-size: 1.2em;
  font-weight: 500;
  padding: 0;
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
</style>
