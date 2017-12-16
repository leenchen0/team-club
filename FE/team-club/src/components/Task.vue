<template>
  <div>
    <el-popover
      placement="left"
      width="150"
      trigger="hover">
      <div style="float: right;">
        <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
        <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
        <el-button class="icon-button" type="text" icon="el-icon-delete" @click="handleOnDelete"></el-button>
        <el-button class="icon-button" type="text" icon="el-icon-edit" @click="handleOnEdit"></el-button>
        <el-button class="icon-button" type="text" :icon="doingIcon" @click="handleOnDoing"></el-button>
      </div>
      <div slot="reference" class="task-container">
        <input @change="handleFinishTask" type="checkbox">
        <router-link
          :to="{ name: 'TaskDetail', params: { pid: $route.params.pid, task_id: task.taskId } }"
        >
          <el-button class="task-name" type="text">{{ task.name }}</el-button>
        </router-link>
        <el-popover
          placement="right"
          width="150"
          trigger="click">
          <div>
            <p style="margin: 0; padding: 0; font-weight: 800;">将任务指派给</p>
            <el-select @change="allocatingTask" v-model="uid" clearable placeholder="未指派任务" size="mini">
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
            陈林
          </el-tag>
        </el-popover>
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
export default {
  name: 'Task',
  props: ['task', 'onBeginTask', 'onPauseTask', 'onDeleteTask', 'onEditTask', 'onFinishTask'],
  data() {
    return {
      members: [
        {
          uid: 1,
          name: 'Pencil',
          avatar: '../assets/avatar.jpg',
          email: '9807175@qq.com',
        },
        {
          uid: 2,
          name: 'Pencil2',
          avatar: '../assets/avatar.jpg',
          email: '98071725@qq.com',
        },
        {
          uid: 3,
          name: 'Pencil3',
          avatar: '../assets/avatar.jpg',
          email: '98073175@qq.com',
        },
      ],
      uid: 1,
    };
  },
  computed: {
    doingIcon() {
      return this.task.doing ? 'el-icon-loading' : 'el-icon-caret-right';
    },
  },
  methods: {
    handleOnDelete() {
      this.onDeleteTask(this.task);
    },
    handleOnEdit() {
      this.$prompt('请输入新的任务名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: this.task.name,
        inputPattern: /.+/,
        inputErrorMessage: '任务名不能为空',
      }).then(({ value }) => {
        this.task.name = value;
        this.onEditTask(this.task);
      }).catch(() => { });
    },
    handleOnDoing() {
      if (this.task.doing) {
        this.onPauseTask(this.task);
      } else {
        this.onBeginTask(this.task);
      }
    },
    allocatingTask(currentUid) {
      this.uid = currentUid;
    },
    handleFinishTask() {
      this.onFinishTask(this.task);
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
