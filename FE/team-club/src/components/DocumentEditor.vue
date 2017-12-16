<template>
  <div>
    <div class="document-info" align="center">
      <h3 class="document-name">{{ name }}</h3>
      <el-button class="edit-document" type="text" @click="editName">编辑</el-button>
    </div>
    <mavon-editor default_open="edit" :value="content" v-model="content" :toolbars="toolbars" />
    <el-button style="margin-top: 15px;" type="primary" @click="submit">发布</el-button>
  </div>
</template>

<script>
export default {
  name: 'DocumentEditor',
  data() {
    return {
      name: '文档名',
      content: '**asdasd**',
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
        imagelink: true, // 图片链接
        code: true, // code
        table: true, // 表格
        fullscreen: true, // 全屏编辑
        readmodel: true, // 沉浸式阅读
        htmlcode: true, // 展示html源码
        help: true, // 帮助
        /* 1.3.5 */
        undo: true, // 上一步
        redo: true, // 下一步
        trash: true, // 清空
        save: true, // 保存（触发events中的save事件）
        /* 1.4.2 */
        navigation: true, // 导航目录
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
  methods: {
    submit() {
      this.$message({
        message: this.content,
        type: 'info',
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
        this.name = value;
      }).catch(() => { });
    },
  },
};
</script>

<style lang="scss">
.document-info {
  .edit-document {
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
}
</style>
