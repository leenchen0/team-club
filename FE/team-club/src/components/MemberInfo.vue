<template>
  <div class="member-item-container">
    <div style="display: flex; align-items: center;">
      <img
        class="avatar"
        width="64"
        height="64"
        :src="user.avatar"
        alt="avatar"
      >
      <router-link
        :to="{ name: 'UserInfo', params: { tid: $route.params.tid, uid: user.uid, path: path } }"
        style="line-height: 64px; margin-left: 10px;"
        class="username-link"
      >
        <b>{{ user.name }}</b>
      </router-link>
    </div>
    <span style="margin-right: 10px;">{{ user.email }}</span>    
  </div>
</template>

<script>
export default {
  name: 'MemberInfo',
  props: ['user'],
  computed: {
    path() {
      return [{
        name: '团队',
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      }];
    },
  },
};
</script>

<style lang="scss">
.username-link:hover {
  color: #84a097;
}
.member-item-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid #eee; 
  padding: 10px; 
  overflow: hidden;
}
</style>
