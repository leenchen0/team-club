<template>
  <div style="position: relative;">
    <div class="forbid-mask" v-if="discussion.deleted === '1'">
      <el-button type="warning" @click="recoverDiscussion">该讨论已被删除，点此恢复</el-button>
    </div>
    <div class="discussion-header">
      <h3>{{ discussion.topic }}</h3>
      <div style="float: left; width: 60px;" align="center">
        <img style="float: none;" width="48" class="avatar" :src="discussion.avatar" alt="avatar">
      </div>
      <div style="margin-left: 70px;">
        <b>{{ discussion.name }}</b>
        <small class="tips">{{ discussion.date }}</small>
        <p style="tips">{{ discussion.description }}</p>
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
      table="讨论"
      :events="events"
    />
    <comment
      :onSubmit="onSubmit"
    />
  </div>
</template>

<script>
import { mapState } from 'vuex';
import router from '../router';
import EventPanel from './EventPanel';
import Comment from './Comment';
import * as service from '../service';

export default {
  name: 'Discussion',
  created() {
    this.getDiscussionInfo();
  },
  data() {
    return {
      discussion: {
        topic: '',
        description: '',
        date: '',
        name: '',
        avatar: '',
      },
      events: [],
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
        service.commentDiscussion(this.did, content).then((data) => {
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
    };
  },
  computed: {
    ...mapState([
      'user',
    ]),
    did() {
      return this.$route.params.did;
    },
  },
  methods: {
    handleOnDelete() {
      this.$confirm('确认删除该讨论？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteDiscussion(this.did).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          router.go(-1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    recoverDiscussion() {
      service.recoverDiscussion(this.did).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.discussion.deleted = '0';
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    editDiscussion() {
      this.form = {
        topic: this.discussion.topic,
        description: this.discussion.description,
      };
      this.showEditDiscussion = true;
    },
    saveDiscussion() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          const { topic, description } = this.form;
          service.editDiscussion(this.did, topic, description).then((data) => {
            if (data.error) {
              throw Error(data.error);
            }
            this.showEditDiscussion = false;
            this.discussion.topic = topic;
            this.discussion.description = description;
          }).catch((err) => {
            this.$message.error(err.message);
          });
        }
      });
    },
    getDiscussionInfo() {
      service.getDiscussionInfo(this.did).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.discussion = data.data.discussion;
        this.events = data.data.events;
      }).catch((err) => {
        this.$message.error(err.message);
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
