<template>
  <div
    class="file-item"
    align="center">
    <i style="padding: 20px 50px; display: block; font-size: 5em;" class="el-icon-document"></i>
    <span :title="file.name" class="item-name">{{ file.name }}</span>
    <div class="operation">
      <a :href="file.link" :download="file.name"><el-button type="text">下载</el-button></a>
      <el-button type="text" @click="handleDelete">删除</el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileItem',
  props: ['file', 'onDeleteFile'],
  methods: {
    handleDelete() {
      if (this.onDeleteFile) {
        this.onDeleteFile(this.file);
      }
    },
  },
};
</script>

<style lang="scss">
.operation {
  opacity: 0;
}
.file-item {
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
