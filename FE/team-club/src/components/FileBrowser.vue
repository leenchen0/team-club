<template>
  <div>
    <div>
      <el-breadcrumb v-if="id === $route.params.dir_id" separator=">">
        <el-breadcrumb-item
          v-for="dir in parent"
          :key="dir.dir_id"
          :to="{ name: 'FileBrowser', params: { dir_id: dir.dir_id, path: path } }">
          {{ dir.name }}
        </el-breadcrumb-item>
      </el-breadcrumb>
      <h3 class="module-name">
        {{
          id === $route.params.dir_id ? 
          (parent.length > 0 ? parent[parent.length - 1].name : 'Loading' ) : 
          '文件'
        }}
      </h3>
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
          :path="path"
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
import * as service from '../service';

export default {
  name: 'FileBrowser',
  props: ['dirId', 'name'],
  created() {
    this.getFileList();
  },
  data() {
    return {
      dirs: [],
      files: [],
      parent: [],
    };
  },
  computed: {
    id() {
      return this.dirId || this.$route.params.dir_id;
    },
    path() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      if (this.name) {
        oldPath.push({
          name: this.name,
          params: {
            name: this.$route.name,
            params: JSON.parse(JSON.stringify(this.$route.params)),
          },
        });
      }
      return oldPath;
    },
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
        service.createDir(this.id, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.dirs.push({
            dir_id: data.data,
            name: value,
          });
        }).catch((err) => {
          this.$message.error(err.message);
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
      const formData = new FormData();
      formData.append('file', file);
      formData.append('dirId', this.id);
      service.uploadFile(formData).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.files.push({
          fid: data.data.fid,
          name: file.name,
          path: data.data.path,
        });
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    deleteDir(dir) {
      this.$confirm('删除文件夹会同时将文件夹下的所有文件删除，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteDir(dir.dir_id).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          const i = this.dirs.indexOf(dir);
          this.dirs.splice(i, 1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    deleteFile(file) {
      this.$confirm('删除后将无法还原，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteFile(file.fid).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          const i = this.files.indexOf(file);
          this.files.splice(i, 1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    getFileList() {
      if (this.id) {
        service.getDirInfo(this.id).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.dirs = data.data.dirs;
          this.files = data.data.files;
          this.parent = data.data.parent;
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }
    },
  },
  components: {
    FileItem,
    DirItem,
  },
  watch: {
    id() {
      this.getFileList();
    },
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
