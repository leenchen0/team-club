<template>
  <div></div>
</template>

<script>
import * as service from '../service';
import router from '../router';

export default {
  name: 'ApplyTeam',
  mounted() {
    service.applyTeam(this.$route.params.tid).then((data) => {
      if (data.error) {
        throw Error(data.error);
      }
      this.$message({
        message: '申请成功',
        type: 'success',
      });
      router.push({ name: 'Member', params: { tid: this.$route.params.tid } });
    }).catch((err) => {
      router.push({ name: 'Member', params: { tid: this.$route.params.tid } });
      this.$message.error(err.message);
    });
  },
};
</script>

<style lang="scss">

</style>
