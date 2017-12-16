<template>
  <div>
    <div class="task-info">
      <el-popover
        placement="left"
        width="150"
        trigger="hover">
        <div style="float: right;">
          <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
          <i style="color: #eee;" class="icon-button el-icon-star-on"></i>
          <el-button class="icon-button" type="text" icon="el-icon-delete" @click="handleOnDelete"></el-button>
          <el-button v-if="finished === null" class="icon-button" type="text" icon="el-icon-edit" @click="handleOnEdit"></el-button>
          <el-button v-if="finished === null" class="icon-button" type="text" :icon="doingIcon" @click="handleOnDoing"></el-button>
        </div>
        <div slot="reference" class="task-container">
          <input @change="handleFinishTask" type="checkbox">
          <span class="task-name" type="text">{{ name }}</span>
          <el-popover
            placement="right"
            width="150"
            trigger="click">
            <div>
              <p style="margin: 0; padding: 0; font-weight: 800;">将任务指派给</p>
              <el-select @change="allocatingTask" v-model="user.uid" clearable placeholder="未指派任务" size="mini">
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
              {{ user ? user.name : '未指派' }}
            </el-tag>
          </el-popover>
        </div>
      </el-popover>
      <p class="tips task-desc">{{ description }}</p>
      <div style="margin-left: 25px; margin-top: 15px;">
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

export default {
  name: 'TaskDetail',
  data() {
    return {
      name: '添加评论功能',
      description: '任务的详细描述',
      user: {
        uid: 1,
        name: '嗯嗯',
      },
      finished: null,
      doing: false,
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
      onSubmit: (content) => {
        this.$message({
          message: content,
          type: 'info',
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
        router.push({ name: 'Project', params: { pid: this.$route.params.pid } });
      }).catch(() => { });
    },
    handleOnEdit() {
      this.$prompt('请输入新的任务名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: this.name,
        inputPattern: /.+/,
        inputErrorMessage: '任务名不能为空',
      }).then(({ value }) => {
        this.name = value;
      }).catch(() => { });
    },
    handleOnDoing() {
      this.doing = !this.doing;
    },
    allocatingTask(currentUid) {
      this.uid = currentUid;
    },
    handleFinishTask(e) {
      this.finished = e.target.checked ? Date.now() : null;
    },
    editTask() {
      this.showEditTask = true;
      this.form = {
        name: this.name,
        description: this.description,
      };
    },
    saveTask() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.name = this.form.name;
          this.description = this.form.description;
          this.showEditTask = false;
        }
      });
    },
  },
  computed: {
    task_id: () => this.$route.params.task_id,
    doingIcon() {
      return this.doing ? 'el-icon-loading' : 'el-icon-caret-right';
    },
  },
  components: {
    EventPanel,
    Comment,
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
</style>
