<template>
  <div id="app" align="center">
    <div id="background"></div>
    <div id="conntainer" align="left">
      <router-view/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'app',
};
</script>

<style lang="scss">
html, body {
  width: 100%;
  height: 100%;
  padding: 0px;
  margin: 0px;
}
header {
  overflow: hidden;
  margin-top: 1.5em 0;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  width: 100%;
  height: 100%;
}
#conntainer {
  margin: 0 150px;
  width: 1000px;
}
a {
  text-decoration: none;
  color: #2c3e50;
}
.router-link-active {
  color: #446057 !important;
}
.tips {
  color: #999;
  margin: 0;
  padding: 0;
}
#background {
  position: fixed;
  z-index: -1;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  background-color: #f4f7ee;
}
.avatar {
  border-radius: 50%;
}
.forbid-mask {
  z-index: 100;
  position: absolute;
  width: calc(100% + 60px);
  height: calc(100% + 100px);
  top: -30px;
  left: -30px;
  background-color: rgba(88, 88, 88, 0.5);

  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
