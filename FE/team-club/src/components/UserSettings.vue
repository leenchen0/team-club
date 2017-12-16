<template>
  <div>
    <div style="margin-bottom: 30px;">
      <h2 style="display: inline;">个人设置</h2>
      <el-button style="float: right; margin-top: 3px;" size="small" type="danger" plain @click="exit_team">退出团队</el-button>
    </div>
    <div class="avatar-edit">
      <img class="avatar" width="64px" src="../assets/avatar.jpg" alt="avatar">
      <el-button type="text" @click.stop.prevent="$refs.avatarPicker.click();">选择新头像</el-button>
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
        <el-button type="primary" @click="save_info">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
const supportedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

export default {
  name: 'UserSettings',
  data() {
    return {
      form: {
        name: 'Pencil',
        email: '9807175@qq.com',
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
  methods: {
    upload_avatar(e) {
      const file = e.target.files[0];
      if (file && supportedTypes.indexOf(file.type) >= 0) {
        const formData = new FormData();
        formData.append('avatar', file);
      } else {
        this.$message.error('只支持jpg/png格式');
        e.target.value = '';
      }
    },
    save_info() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.$message({
            message: '保存成功',
            type: 'success',
          });
          return true;
        }
        return false;
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
.avatar {
  border-radius: 50%;
  padding: 8px;
  float: left;
}
</style>
