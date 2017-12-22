<template>
  <div>
    <router-link :to="{ name: 'EditHistory', params: { doc_id: doc_id, path: path } }">
      <el-button type="text">查看编辑历史</el-button>
    </router-link>
    <div class="document-info" align="center">
      <h3 class="document-name">
        {{ name }}
        <el-button class="edit-document" type="text" @click="editName">编辑</el-button>
      </h3>
    </div>
    <mavon-editor default_open="edit" :value="content" v-model="content" :toolbars="toolbars" />
    <el-button style="margin-top: 15px;" type="primary" @click="submit">发布</el-button>
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'DocumentEditor',
  created() {
    this.getDocInfo();
  },
  data() {
    return {
      name: '',
      content: '',
      toolbars: {
        bold: true, // 粗体
        italic: true, // 斜体
        header: true, // 标题
        underline: true, // 下划线
        strikethrough: true, // 中划线
        mark: true, // 标记
        superscript: true, // 上角标
        subscript: true, // 下角标
        quote: true, // 引用
        ol: true, // 有序列表
        ul: true, // 无序列表
        link: true, // 链接
        // imagelink: true, // 图片链接
        code: true, // code
        table: true, // 表格
        fullscreen: true, // 全屏编辑
        readmodel: true, // 沉浸式阅读
        /* 1.3.5 */
        undo: true, // 上一步
        redo: true, // 下一步
        /* 2.1.8 */
        alignleft: true, // 左对齐
        aligncenter: true, // 居中
        alignright: true, // 右对齐
        /* 2.2.1 */
        subfield: true, // 单双栏模式
        preview: true, // 预览
      },
    };
  },
  computed: {
    doc_id() {
      return this.$route.params.doc_id;
    },
    path() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      oldPath.push({
        name: this.name,
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      });
      return oldPath;
    },
  },
  methods: {
    submit() {
      service.saveDoc(this.doc_id, this.content).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.$message({
          message: '保存成功',
          type: 'success',
        });
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    editName() {
      this.$prompt('请输入新的文档名', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: this.name,
        inputPattern: /.+/,
        inputErrorMessage: '文档名不能为空',
      }).then(({ value }) => {
        service.editDoc(this.doc_id, value).then((data) => {
          if (data.error) {
            throw Error(data.error);
          }
          this.name = value;
        }).catch((err) => {
          this.$message.error(err.message);
        });
      }).catch(() => { });
    },
    getDocInfo() {
      service.getDocInfo(this.doc_id).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.name = data.data.name;
        this.content = data.data.content;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">
.document-info {
  .edit-document {
    position: absolute;
    top: -5px;
    right: -40px;
    opacity: 0;
  }

  &:hover {
    .edit-document {
      opacity: 1;
    }
  }
}
.document-name {
  display: inline-block;
  position: relative;
}
.v-note-wrapper .v-note-op .right {
  max-width: 20% !important;
}
</style>
