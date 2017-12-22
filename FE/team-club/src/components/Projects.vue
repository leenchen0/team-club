<template>
  <div>
    <div class="projects-header">
      <i class="el-icon-tickets" style="font-size: 1.2em;"></i>
      <el-button type="text" style="float: right;" @click="createProject">新建项目</el-button>
    </div>
    <div class="projects-container">
      <project-item
        style="margin: 20px 10px;"
        :handleChangeIcon="handleChangeIcon"
        :handleChangeColor="handleChangeColor"
        v-for="(project, index) in projectList"
        :key="index"
        :project="project"
      />
    </div>
  </div>
</template>

<script>
import ProjectItem from './ProjectItem';
import * as service from '../service';

export default {
  name: 'Project',
  created() {
    this.getProjects(this.$route.params.tid);
  },
  beforeRouteUpdate(to, from, next) {
    if (to.params.tid !== from.params.tid) {
      this.getProjects(to.params.tid);
    }
    next();
  },
  data() {
    return {
      projectList: [],
      handleChangeIcon: (project, iconIndex) => {
        service.setProjectIcon(project.pid, iconIndex).then((data) => {
          if (data.error) {
            throw Error('更新图标失败');
          }
          const i = this.projectList.indexOf(project);
          if (i > -1) {
            this.projectList[i].icon = iconIndex;
          }
        }).catch(() => {
          this.$message.error('更新图标失败');
        });
      },
      handleChangeColor: (project, colorIndex) => {
        service.setProjectColor(project.pid, colorIndex).then((data) => {
          if (data.error) {
            throw Error('更新颜色失败');
          }
          const i = this.projectList.indexOf(project);
          if (i > -1) {
            this.projectList[i].color = colorIndex;
          }
        }).catch(() => {
          this.$message.error('更新颜色失败');
        });
      },
    };
  },
  methods: {
    createProject() {
      this.$prompt('请输入项目名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '项目名不可为空',
      }).then(({ value }) => {
        service.createProject(this.$route.params.tid, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.projectList.push({ pid: data.data, name: value, icon: 0, color: 0 });
        }).catch(() => {
          this.$message.error('创建失败!');
        });
      }).catch(() => { });
    },
    getProjects(tid) {
      service.getProjects(tid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.projectList = data.data;
      }).catch(() => {
        this.$message.error('获取项目列表失败!');
      });
    },
  },
  components: {
    ProjectItem,
  },
};
</script>

<style lang="scss">
.projects-header {
  overflow: hidden;
  line-height: 100%;
  margin-bottom: 30px;
  line-height: 40px;
}
.projects-container {
  display: flex;
  flex-wrap: wrap;

  div {
    margin: 10px 0px;
  }
}
</style>
