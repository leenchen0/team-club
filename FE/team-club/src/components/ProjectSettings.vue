<template>
  <div>
    <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
      <h2 style="margin: 0; display: inline; margin-right: 10px;">项目设置</h2>
    </div>
    <el-form ref="form" :model="form" :rules="rules">
      <el-form-item prop="name">
        <el-input v-model="form.name" placeholder="请输入项目名称..."></el-input>
      </el-form-item>
      <el-form-item prop="description">
        <el-input type="textarea" v-model="form.description" placeholder="请输入项目描述..."></el-input>
      </el-form-item>
      <h3>项目公开性</h3>
      <el-radio-group v-model="form.isPrivate">
        <el-radio label="1" class="radio">
          <b style="font-size: 1.2em;">私有项目</b> <p class="tips" style="display: inline">仅项目成员可以查看和编辑该项目</p>
        </el-radio>
        <br>
        <el-radio label="0" class="radio">
          <b style="font-size: 1.2em;">公开项目</b> <p class="tips" style="display: inline">任何人都可以通过链接查看该项目，仅项目成员可以编辑该项目</p>
        </el-radio>
      </el-radio-group>
      <el-form-item>
        <el-button type="primary" @click="saveInfo" :disabled="!hasChanged">保存设置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'ProjectSettings',
  created() {
    this.getInfo();
  },
  data() {
    return {
      project: {
        name: '',
        description: '',
        isPrivate: '1',
      },
      form: {
        name: '',
        description: '',
        isPrivate: '1',
      },
      rules: {
        name: [
          { required: true, message: '项目名不能为空', trigger: 'blur' },
        ],
      },
    };
  },
  computed: {
    hasChanged() {
      const { name, description, isPrivate } = this.form;
      return this.project.name !== name ||
             this.project.description !== description ||
             this.project.isPrivate !== isPrivate;
    },
  },
  methods: {
    getInfo() {
      service.getProjectSettingInfo(this.$route.params.pid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.project = data.data;
        this.form = {
          name: data.data.name,
          description: data.data.description,
          isPrivate: data.data.isPrivate,
        };
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    saveInfo() {
      const { isPrivate, name, description } = this.form;
      service.saveProjectInfo(this.$route.params.pid, isPrivate, name, description).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.project.name = name;
        this.project.isPrivate = isPrivate;
        this.project.description = description;
        this.$message({
          message: '保存成功',
          type: 'success',
        });
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">
.radio {
  margin-bottom: 15px;
}
</style>
