<template>
  <div style="margin-top: 20px">
    <div style="float: left; width: 60px;" align="center">
      <img width="48" class="avatar" src="../assets/avatar.jpg" alt="avatar">
    </div>
    <div style="margin-left: 70px;">
      <el-form ref="form" :model="form" :rules="rules">
        <el-form-item prop="content">
          <el-input
            type="textarea"
            :rows="2"
            placeholder="请输入内容"
            v-model="form.content">
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="handleSubmit">发表评论</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Comment',
  props: ['onSubmit'],
  data() {
    return {
      form: {
        content: '',
      },
      rules: {
        content: [
          { required: true, message: '评论内容不能为空', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$refs.form.validate((valid) => {
        if (valid && this.onSubmit) {
          this.onSubmit(this.form.content);
        }
      });
    },
  },
};
</script>

<style lang="scss">
.avatar {
  display: inline;
  border-radius: 50%;
}
</style>
