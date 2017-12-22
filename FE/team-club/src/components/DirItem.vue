<template>
  <router-link
    :to="{ name: 'FileBrowser', params: { dir_id: dir.dir_id, path: path } }">
    <div
      class="dir-item"
      align="center">
      <i style="padding: 20px 50px; display: block; font-size: 5em; color: lightblue;" class="el-icon-message"></i>
      <span :title="dir.name" class="item-name">{{ dir.name }}</span>
      <div class="operation">
        <el-button type="text" @click.stop.prevent="handleDeleteDir">删除</el-button>
      </div>
    </div>
  </router-link>
</template>

<script>
export default {
  name: 'DirItem',
  props: ['dir', 'onDeleteDir', 'path'],
  methods: {
    handleDeleteDir() {
      if (this.onDeleteDir) {
        this.onDeleteDir(this.dir);
      }
    },
  },
};
</script>

<style lang="scss">
.operation {
  opacity: 0;
}
.dir-item {
  position: relative;
  padding: 10px;
  border: 1px solid transparent;

  &:hover {
    background-color: rgba(80, 80, 0, .1);
    border: 1px solid rgba(80, 80, 0, .3);
    border-radius: 10px;

    .operation {
      opacity: 1;
    }
  }
}
.item-name {
  display: inline-block;
  max-width: 100px;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
</style>
