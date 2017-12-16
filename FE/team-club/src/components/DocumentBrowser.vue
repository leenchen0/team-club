<template>
  <div>
    <div>
      <h3 class="module-name">文档</h3>
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
        />
        <doc-item
          v-for="doc in docs"
          :key="doc.doc_id"
          :doc="doc"
          :onDeleteDoc="deleteDoc"
        />
      </div>
    </div>
  </div>
</template>

<script>
import DocDirItem from './DocDirItem';
import DocItem from './DocItem';

export default {
  name: 'DocumentBrowser',
  data() {
    return {
      docs: [],
      dirs: [],
    };
  },
  methods: {
    createDoc() {
      this.$prompt('请输入文档名称', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern: /.+/,
        inputErrorMessage: '文档名不可为空',
      }).then(({ value }) => {
        this.docs.push({
          doc_id: 1,
          name: value,
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
        this.dirs.push({
          doc_dir_id: 1,
          name: value,
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
        const i = this.dirs.indexOf(dir);
        this.dirs.splice(i, 1);
      }).catch(() => { });
    },
    deleteDoc(doc) {
      this.$confirm('删除后将无法还原，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        const i = this.docs.indexOf(doc);
        this.docs.splice(i, 1);
      }).catch(() => { });
    },
  },
  components: {
    DocItem,
    DocDirItem,
  },
};
</script>

<style lang="scss">
.module-name {
  display: inline-block;
  margin-right: 12px;
}
</style>
