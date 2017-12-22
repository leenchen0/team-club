<template>
  <div @click="go" align="center" class="menu-item-container">
    <i v-if="isNaN(parseInt(icon))" class="menu-item-icon" :class="icon" style="padding-top: 3px; height: 22px;"/>
    <span v-else class="menu-item-icon">{{ icon }}</span>
    <span>{{ name }}</span>
  </div>
</template>

<script>
import router from '../router';

export default {
  name: 'ProjectMenu',
  props: ['name', 'icon', 'link'],
  methods: {
    go() {
      router.push(this.link);
    },
  },
};
</script>

<style lang="scss">
.menu-item-icon {
  display: block; 
  width: 25px;
  height: 25px;
  font-size: 1.5em;
  padding-bottom: 5px;
}
.menu-item-container {
  position: relative;
  padding: 5px 20px;
  cursor: pointer;

  * {
    color: #346047;
  }

  &:hover {
    background-color: rgba(180, 180, 180, .1);

    * {
      color: #84a097;
    }
  }
}
</style>
