<template>
  <div class="project-item-container" align="center" @click="enterProject">
    <i style="padding: 20px 70px; display: block; font-size: 3em;" :class="icons[project.icon]" :style="{ color: colorSet[project.color]}"></i>
    <span>{{ project.name }}</span>
    <el-popover
      placement="bottom"
      width="200"
      trigger="click">
      <i slot="reference" class="el-icon-info edit-icon" data-mark="1"></i>
      <div class="project-info-edit-container">
        <ul>
          <li
            class="color-item"
            v-for="(color, index) in colorSet"
            :key="index"
            :style="{ 'background-color': color }"
            @click="changeColor(index)"
          ></li>
        </ul>
        <ul style="margin-top: 10px;">
          <li
            class="icon-item"
            v-for="(icon, index) in icons"
            :key="index"
            @click="changeIcon(index)"
          >
            <i style="font-size: 2em;" :style="{ color: currentColor }" :class="icon"></i>
          </li>
        </ul>
      </div>
    </el-popover>
  </div>
</template>

<script>
import router from '../router';

export default {
  name: 'ProjectItem',
  props: ['project', 'handleChangeColor', 'handleChangeIcon'],
  data() {
    return {
      colorSet: [
        '#e9ddcf',
        '#e4edd1',
        '#dbeee6',
        '#8cb6c7',
        '#8fa6d1',
        '#dba8ba',
        '#b9c0c7',
      ],
      icons: [
        'el-icon-star-on',
        'el-icon-date',
        'el-icon-document',
        'el-icon-message',
        'el-icon-location',
        'el-icon-menu',
        'el-icon-picture',
        'el-icon-picture-outline',
        'el-icon-printer',
        'el-icon-service',
        'el-icon-star-off',
        'el-icon-tickets',
        'el-icon-setting',
        'el-icon-rank',
        'el-icon-view',
      ],
    };
  },
  computed: {
    currentColor() {
      return this.colorSet[this.project.color];
    },
  },
  methods: {
    changeIcon(index) {
      this.handleChangeIcon && this.handleChangeIcon(this.project, index);
    },
    changeColor(index) {
      this.handleChangeColor && this.handleChangeColor(this.project, index);
    },
    enterProject(e) {
      if (e.target.dataset.mark !== undefined) {
        return false;
      }
      router.push({ name: 'Project', params: { pid: this.project.pid } });
      return true;
    },
  },
};
</script>

<style lang="scss">
.edit-icon {
  display: none;
  position: absolute;
  right: 10px;
  top: 10px;
  color: #505000;

  &:hover {
    color: #707000;
  }
}
.project-item-container {
  position: relative;
  padding: 10px;
  border: 1px solid transparent;
  &:hover {
    background-color: rgba(80, 80, 0, .1);
    border: 1px solid rgba(80, 80, 0, .3);
    border-radius: 10px;

    .edit-icon {
      display: block;
    }
  }
  cursor: pointer;
}
.project-info-edit-container {
  ul {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;

    list-style: none;
    margin: 0;
    padding: 0;

    .color-item {
      width: 20px;
      height: 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .icon-item {
      margin: 5px;
      cursor: pointer;
    }
  }
}
</style>
