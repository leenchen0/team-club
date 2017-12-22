<template>
  <div>
    <p class="tips" style="margin-bottom: 20px">编辑于 {{ history.date }}</p>
    <mavon-editor default_open="preview" :value="history.content" :subfield="false" :toolbarsFlag="false" />  
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'ViewDocument',
  created() {
    this.getContent();
  },
  data() {
    return {
      history: {
        date: '',
        content: '',
        doc_id: 0,
      },
    };
  },
  methods: {
    getContent() {
      service.getHistoryContent(this.$route.params.hid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        this.history = data.data;
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
};
</script>

<style lang="scss">

</style>
