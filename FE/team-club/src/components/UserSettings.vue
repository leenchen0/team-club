<template>
  <div>
    <div style="margin-bottom: 30px;">
      <h2 style="display: inline;">个人设置</h2>
      <el-button style="float: right; margin-top: 3px;" size="small" type="danger" plain @click="exit_team">退出团队</el-button>
    </div>
    <div class="avatar-edit">
      <img
        style="padding: 8px; float: left;"
        class="avatar"
        width="64"
        height="64"
        :src="user.avatar"
        alt="avatar"
      >
      <el-button type="text" @click.stop.prevent="$refs.avatarPicker.click();">更换头像</el-button>
      <small><p class="tips">你可以选择 png/jpg 图片作为头像</p></small>
      <input ref="avatarPicker" type="file" @change="upload_avatar" v-show="false">
    </div>
    <el-form ref="form" :model="form" :rules="rules" label-width="80px">
      <el-form-item label="名字" prop="name">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="form.email"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="save_info" :disabled="!hasChanged">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import * as service from '../service';
import * as types from '../store/mutation-types';

const supportedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

export default {
  name: 'UserSettings',
  data() {
    return {
      form: {
        name: '',
        email: '',
      },
      rules: {
        name: [
          { required: true, message: '请输入新的名字', trigger: 'blur' },
        ],
        email: [
          { type: 'email', required: true, message: '请输入正确的邮箱地址', trigger: 'blur' },
        ],
      },
    };
  },
  created() {
    this.form = {
      name: this.user.name,
      email: this.user.email,
    };
  },
  computed: {
    ...mapState([
      'user',
    ]),
    hasChanged() {
      return this.form.name !== this.user.name || this.form.email !== this.user.email;
    },
  },
  methods: {
    ...mapMutations({
      update_avatar: types.UPDATE_AVATAR,
      update_info: types.UPDATE_INFO,
    }),
    upload_avatar(e) {
      const file = e.target.files[0];
      if (file && supportedTypes.indexOf(file.type) >= 0) {
        const formData = new FormData();
        formData.append('avatar', file);
        service.updateAvatar(formData).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.update_avatar(data.data);
        }).catch((err) => {
          e.target.value = '';
          this.$message.error(err.message);
        });
      } else {
        this.$message.error('只支持jpg/png格式');
        e.target.value = '';
      }
    },
    save_info() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          const { name, email } = this.form;
          service.updateUserInfo(name, email).then((data) => {
            if (data.error) {
              throw Error(data.error);
            }
            this.$message({
              message: '保存成功',
              type: 'success',
            });
            this.update_info({ name: name, email: email });
          }).catch((err) => {
            this.$message.error(err.message);
          });
        }
      });
    },
    exit_team() {
      this.$confirm('确定要退出团队？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        this.$message({
          type: 'success',
          message: '退出成功!',
        });
      }).catch(() => { });
    },
  },
};
</script>

<style lang="scss">
.avatar-edit {
  overflow: hidden;
  margin-bottom: 20px;
}
</style>
