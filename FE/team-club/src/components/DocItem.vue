<template>
  <router-link
    :to="{ name: 'DocumentEditor', params: { doc_id: doc.doc_id } }">
    <div
      class="doc-item"
      align="center">
      <i style="padding: 20px 50px; display: block; font-size: 5em;" class="el-icon-document"></i>
      <span :title="doc.name" class="item-name">{{ doc.name }}</span>
      <div class="operation">
        <el-button type="text" @click.stop.prevent="handleDeleteDoc">删除</el-button>
      </div>
    </div>
  </router-link>
</template>

<script>
export default {
  name: 'DocItem',
  props: ['doc', 'onDeleteDoc'],
  methods: {
    handleDeleteDoc() {
      if (this.onDeleteDoc) {
        this.onDeleteDoc(this.doc);
      }
    },
  },
};
</script>

<style lang="scss">
.operation {
  opacity: 0;
}
.doc-item {
  position: relative;
  padding: 10px;
  border: 1px solid transparent;

  &:hover {
    background-color: rgba(80, 80, 0, .1);
    border: 1px solid rgba(80, 80, 0, .3);
    border-radius: 10px;

    i {
      color: teal;
    }
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
