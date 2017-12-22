<template>
  <div class="container" align="center">
    <h1 id="title">
      Team Club
    </h1>
    <transition name="el-zoom-in-center">
      <el-card class="form-container" v-show="isLogin">
        <el-form ref="loginForm" :rules="loginFormRules" :model="loginForm">
          <el-form-item prop="email">
            <el-input v-model="loginForm.email" placeholder="登录邮箱"></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-input type="password" v-model="loginForm.password" placeholder="密码"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" class="form-button" @click="login">登录</el-button>
          </el-form-item>
          <el-form-item class="register-container">
            <span style="color: #2c3e50;">没有账号？</span><a href="#" @click="isLogin = false">立即注册></a>
          </el-form-item>
        </el-form>
      </el-card>
    </transition>
    <transition name="el-zoom-in-center">
      <el-card class="form-container" v-show="!isLogin">
        <el-form ref="registerForm" :rules="registerFormRules" :model="registerForm">
          <el-form-item prop="name">
            <el-input v-model="registerForm.name" placeholder="你的名字"></el-input>
          </el-form-item>
          <el-form-item prop="email">
            <el-input v-model="registerForm.email" placeholder="邮箱"></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-input type="password" v-model="registerForm.password" placeholder="密码（不少于6位）"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" class="form-button" @click="register">注册</el-button>
          </el-form-item>
          <el-form-item class="register-container">
            <span style="color: #2c3e50;">已有账号？</span><a href="#" @click="isLogin = true">去登录</a>
          </el-form-item>
        </el-form>
      </el-card>
    </transition>
  </div>
</template>

<script>
import { mapMutations } from 'vuex';
import * as types from '../store/mutation-types';
import router from '../router';
import * as service from '../service';

export default {
  name: 'Login',
  data() {
    return {
      loginForm: {
        email: '',
        password: '',
      },
      loginFormRules: {
        email: [
          { type: 'email', required: true, message: '请输入正确的邮箱地址', trigger: 'blur' },
        ],
        password: [
          { required: true, min: 6, message: '请输入正确的密码', trigger: 'blur' },
        ],
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
      },
      registerFormRules: {
        email: [
          { type: 'email', required: true, message: '请输入正确的邮箱地址', trigger: 'blur' },
        ],
        password: [
          { required: true, min: 6, message: '请输入正确的密码', trigger: 'blur' },
        ],
        name: [
          { required: true, message: '名字不能为空', trigger: 'blur' },
        ],
      },
      isLogin: true,
    };
  },
  methods: {
    ...mapMutations({
      update_login: types.LOGIN,
    }),
    login() {
      this.$refs.loginForm.validate((valid) => {
        if (valid) {
          const { email, password } = this.loginForm;
          service.login(email, password).then((data) => {
            if (data.error) {
              this.$message.error(data.error);
            } else {
              this.update_login(data.data);
              this.$message({
                message: '登录成功',
                type: 'success',
              });
              setTimeout(() => router.push({ name: 'Launchpad' }), 1000);
            }
          });
        }
      });
    },
    register() {
      this.$refs.registerForm.validate((valid) => {
        if (valid) {
          const { name, email, password } = this.registerForm;
          service.register(name, email, password).then((data) => {
            if (data.error) {
              this.$message.error(data.error);
            } else {
              this.update_login(data.data);
              this.$message({
                message: '注册成功',
                type: 'success',
              });
              setTimeout(() => router.push({ name: 'Launchpad' }), 1000);
            }
          });
        }
      });
    },
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
.form-button {
  width: 100%;
}
.register-container a:hover {
  text-decoration: underline;
}
</style>
