<template>
  <div>
    <div>
      <h3 class="module-name">文件</h3>
      <el-dropdown
        split-button
        type="primary"
        trigger="click"
        size="mini"
        @click="selectFile"
        @command="handleCommand"
      >
        上传文件
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="file">上传文件</el-dropdown-item>
          <el-dropdown-item command="dir">创建文件夹</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <input ref="filePicker" type="file" @change="uploadFile" v-show="false">
      <div style="display: flex; flex-wrap: wrap;">
        <dir-item
          v-for="dir in dirs"
          :key="dir.dir_id"
          :dir="dir"
          :onDeleteDir="deleteDir"
        />
        <file-item
          v-for="file in files"
          :key="file.fid"
          :file="file"
          :onDeleteFile="deleteFile"
        />
      </div>
    </div>
  </div>
</template>

<script>
import FileItem from './FileItem';
import DirItem from './DirItem';

export default {
  name: 'FileBrowser',
  props: ['dir'],
  data() {
    return {
      dirs: [],
      files: [],
    };
  },
  methods: {
    selectFile() {
      this.$refs.filePicker.click();
    },
    createDir() {
      this.$prompt('请输入文件夹名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '文件夹名不可为空',
      }).then(({ value }) => {
        this.dirs.push({
          dir_id: 1,
          name: value,
        });
      }).catch(() => { });
    },
    handleCommand(command) {
      if (command === 'file') {
        this.selectFile();
      } else {
        this.createDir();
      }
    },
    uploadFile(e) {
      const file = e.target.files[0];
      e.target.value = '';
      this.files.push({
        fid: 1,
        name: file.name,
        link: 'http://www.baidu.com',
      });
    },
    deleteDir(dir) {
      this.$confirm('删除文件夹会同时将文件夹下的所有文件删除，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        const i = this.dirs.indexOf(dir);
        this.dirs.splice(i, 1);
      }).catch(() => { });
    },
    deleteFile(file) {
      this.$confirm('删除后将无法还原，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        const i = this.files.indexOf(file);
        this.files.splice(i, 1);
      }).catch(() => { });
    },
  },
  components: {
    FileItem,
    DirItem,
  },
};
</script>

<style lang="scss">
.module-name {
  display: inline-block;
  margin-right: 12px;
}
.operation {
  opacity: 0;
}
</style>
