<template>
  <div>
    <div>
      <el-breadcrumb separator=">" v-if="id === $route.params.doc_dir_id">
        <el-breadcrumb-item
          v-for="dir in parent"
          :key="dir.doc_dir_id"
          :to="{ name: 'DocumentBrowser', params: { doc_dir_id: dir.doc_dir_id, path: path } }">
          {{ dir.name }}
        </el-breadcrumb-item>
      </el-breadcrumb>
      <h3 class="module-name">
        {{
          id === $route.params.doc_dir_id ? 
          (parent.length > 0 ? parent[parent.length - 1].name : 'Loading') : 
          '文档'
        }}
      </h3>
      <el-dropdown
        split-button
        type="primary"
        trigger="click"
        size="mini"
        @click="createDoc"
        @command="handleCommand"
      >
        创建文档
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="doc">创建文档</el-dropdown-item>
          <el-dropdown-item command="dir">创建文档夹</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <div style="display: flex; flex-wrap: wrap;">
        <doc-dir-item
          v-for="dir in dirs"
          :key="dir.doc_dir_id"
          :dir="dir"
          :onDeleteDir="deleteDir"
          :path="path"
        />
        <doc-item
          v-for="doc in docs"
          :key="doc.doc_id"
          :doc="doc"
          :onDeleteDoc="deleteDoc"
          :path="path"
        />
      </div>
    </div>
  </div>
</template>

<script>
import DocDirItem from './DocDirItem';
import DocItem from './DocItem';
import * as service from '../service';

export default {
  name: 'DocumentBrowser',
  props: ['docDirId', 'name'],
  created() {
    this.getDocList();
  },
  data() {
    return {
      docs: [],
      dirs: [],
      parent: [],
    };
  },
  computed: {
    id() {
      return this.docDirId || this.$route.params.doc_dir_id;
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
    createDoc() {
      this.$prompt('请输入文档名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '文档名不可为空',
      }).then(({ value }) => {
        service.createDoc(this.id, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.docs.push({
            doc_id: data.data,
            name: value,
          });
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    createDir() {
      this.$prompt('请输入文档夹名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '文档夹名不可为空',
      }).then(({ value }) => {
        service.createDocDir(this.id, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.dirs.push({
            doc_dir_id: data.data,
            name: value,
          });
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    handleCommand(command) {
      if (command === 'doc') {
        this.createDoc();
      } else {
        this.createDir();
      }
    },
    deleteDir(dir) {
      this.$confirm('删除文档夹会同时将文档夹下的所有文档删除，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteDocDir(dir.doc_dir_id).then((data) => {
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
    deleteDoc(doc) {
      this.$confirm('删除后将无法还原，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        service.deleteDoc(doc.doc_id).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          const i = this.docs.indexOf(doc);
          this.docs.splice(i, 1);
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    getDocList() {
      if (this.id) {
        service.getDocDirInfo(this.id).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.dirs = data.data.dirs;
          this.docs = data.data.docs;
          this.parent = data.data.parent;
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }
    },
  },
  components: {
    DocItem,
    DocDirItem,
  },
  watch: {
    id() {
      this.getDocList();
    },
  },
};
</script>

<style lang="scss">
.module-name {
  display: inline-block;
  margin-right: 12px;
}
</style>
