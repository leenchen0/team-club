<template>
  <div class="container" align="center">
    <h1 id="title">
      Team Club
    </h1>
    <transition name="el-zoom-in-center">
      <el-card class="form-container" v-show="login">
        <el-form :model="loginForm">
          <el-form-item>
            <el-input :model="loginForm.email" placeholder="登录邮箱"></el-input>
          </el-form-item>
          <el-form-item>
            <el-input :model="loginForm.password" placeholder="密码"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" id="login-button">登录</el-button>
          </el-form-item>
          <el-form-item class="register-container">
            <span style="color: #2c3e50;">没有账号？</span><a href="#" @click="login = false">立即注册></a>
          </el-form-item>
        </el-form>
      </el-card>
    </transition>
    <transition name="el-zoom-in-center">
      <el-card class="form-container" v-show="!login">
        <el-form :model="registerForm">
          <el-form-item>
            <el-input :model="registerForm.name" placeholder="你的名字"></el-input>
          </el-form-item>
          <el-form-item>
            <el-input :model="registerForm.email" placeholder="邮箱"></el-input>
          </el-form-item>
          <el-form-item>
            <el-input :model="registerForm.password" placeholder="密码（不少于6位）"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" id="login-button">注册</el-button>
          </el-form-item>
          <el-form-item class="register-container">
            <span style="color: #2c3e50;">已有账号？</span><a href="#" @click="login = true">去登录</a>
          </el-form-item>
        </el-form>
      </el-card>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      loginForm: {
        email: '',
        password: '',
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
      },
      login: true,
    };
  },
};
</script>

<style lang="scss">
.container {
  width: 100%;
  height: 100%;
  overflow: hidden;
}
#title {
  color: #84a097;
  margin: 0px;
  padding: 2em 0;
}
.form-container {
  width: 500px;
  border-radius: 10px;
  position: absolute;
  left: 0px;
  right: 0px;
  margin: auto;
}
#login-button {
  width: 100%;
}
.register-container a:hover {
  text-decoration: underline;
}
</style>
