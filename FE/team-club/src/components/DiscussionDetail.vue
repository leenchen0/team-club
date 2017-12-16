<template>
  <div>
    <div class="discussion-header">
      <h3>{{ topic }}</h3>
      <div style="float: left; width: 60px;" align="center">
        <img style="float: none;" width="48" class="avatar" src="../assets/avatar.jpg" alt="avatar">
      </div>
      <div style="margin-left: 70px;">
        <b>{{ user.name }}</b>
        <small class="tips">{{ date }}</small>
        <p style="tips">{{ description }}</p>
      </div>
      <div style="margin-left: 70px; margin-top: 15px;">
        <el-button type="primary" size="mini" plain @click="editDiscussion">编辑</el-button>
        <el-button type="danger" size="mini" plain @click="handleOnDelete">删除</el-button>
      </div>
      <el-dialog title="编辑讨论" :visible.sync="showEditDiscussion">
        <el-form ref="form" :model="form" :rules="rules">
          <el-form-item prop="topic" label="话题" label-width="100px">
            <el-input v-model="form.topic" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="描述" label-width="100px">
            <el-input v-model="form.description" auto-complete="off"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer">
          <el-button @click="showEditDiscussion = false">取 消</el-button>
          <el-button type="primary" @click="saveDiscussion">确 定</el-button>
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
  name: 'Discussion',
  data() {
    return {
      topic: 'Topic',
      user: {
        uid: 1,
        name: '陈林',
        avatar: '/static/img/avatar.f7270b8.jpg',
      },
      date: '12月7日',
      description: 'R.T.',
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
      showEditDiscussion: false,
      form: {
        topic: '',
        description: '',
      },
      rules: {
        topic: [
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
      this.$confirm('确认删除该讨论？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        router.push({ name: 'Project', params: { pid: this.$route.params.pid } });
      }).catch(() => { });
    },
    editDiscussion() {
      this.form = {
        topic: this.topic,
        description: this.description,
      };
      this.showEditDiscussion = true;
    },
    saveDiscussion() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.showEditDiscussion = false;
          this.topic = this.form.topic;
          this.description = this.form.description;
        }
      });
    },
  },
  components: {
    EventPanel,
    Comment,
  },
};
</script>

<style lang="scss">
.discussion-header {
  overflow: hidden;
  padding-bottom: 20px;
  margin-bottom: 20px;
  border-bottom: 1px solid #eee;
}
</style>
