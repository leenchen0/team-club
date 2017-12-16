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

export default {
  name: 'Project',
  data() {
    return {
      projectList: [
        {
          pid: 1,
          name: 'Team',
          icon: 2,
          color: 5,
        },
        {
          pid: 2,
          name: 'Club',
          icon: 1,
          color: 3,
        },
        {
          pid: 3,
          name: 'cf-dispatching',
          icon: 3,
          color: 1,
        },
        {
          pid: 3,
          name: 'cf-dispatching',
          icon: 3,
          color: 1,
        },
        {
          pid: 3,
          name: 'cf-dispatching',
          icon: 3,
          color: 1,
        },
      ],
      handleChangeIcon: (project, iconIndex) => {
        const i = this.projectList.indexOf(project);
        if (i > -1) {
          this.projectList[i].icon = iconIndex;
        }
      },
      handleChangeColor: (project, colorIndex) => {
        const i = this.projectList.indexOf(project);
        if (i > -1) {
          this.projectList[i].color = colorIndex;
        }
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
        this.projectList.push({ pid: 1, name: value, icon: 0, color: 0 });
      }).catch(() => { });
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
