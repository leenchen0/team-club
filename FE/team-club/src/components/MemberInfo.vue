<template>
  <div style="border-top: 1px solid #eee; padding: 10px; overflow: hidden;">
    <img class="avatar" width="64" src="../assets/avatar.jpg" alt="avatar">
    <router-link
      :to="{ name: 'UserInfo', params: { tid: $route.params.tid, uid: user.uid } }"
      style="line-height: 64px; margin-left: 10px;"
      class="username-link"
    >
      <b>{{ user.name }}</b>
    </router-link>
    <span style="line-height: 64px; margin-left: 10px; float: right; margin-right: 10px;">{{ user.email }}</span>    
  </div>
</template>

<script>
export default {
  name: 'MemberInfo',
  props: ['user'],
};
</script>

<style lang="scss">
.avatar {
  border-radius: 50%;
  float: left;
}
.username-link:hover {
  color: #84a097;
}
</style>
