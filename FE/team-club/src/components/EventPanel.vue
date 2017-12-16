<template>
  <div>
    <div
      v-for="event in events"
      :key="event.eid"
      class="event">
      <span class="event-left" align="center" :style="{ color: event.type === 'delete' ? '#fd7b7e' : '#55c6dc' }">
        <i
          v-if="event.type != 'comment'"
          style="font-size: 2em;"
          :class="getIcon(event.type)"
        />
        <img
          v-else
          width="48"
          class="avatar"
          src="../assets/avatar.jpg"
          alt="avatar">
      </span>
      <small v-if="event.type != 'comment'" class="event-right" :class="{ danger: event.type === 'delete' }">
        <span class="event-date">{{ event.date }}</span>
        <span class="event-user"><b>{{ event.user.name }}</b></span>
        <span class="event-type">{{ event.info }}</span>
      </small>
      <small v-else class="event-right">
        <span class="event-user"><b>{{ event.user.name }}</b></span>
        <span class="event-date">{{ event.date }}</span>
        <p style="margin: 0 0 0 70px;">{{ event.info }}</p>
      </small>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EventPanel',
  props: ['events', 'type'],
  methods: {
    getIcon(type) {
      switch (type) {
        case 'add':
          return 'el-icon-circle-plus';
        case 'finish':
          return 'el-icon-success';
        case 'begin':
          return 'el-icon-caret-right';
        case 'pause':
          return 'el-icon-warning';
        case 'reopen':
          return 'el-icon-d-arrow-right';
        case 'delete':
          return 'el-icon-circle-close';
        default:
          return '';
      }
    },
  },
};
</script>

<style lang="scss">
.event-left {
  float: left;
  width: 60px;
  height: 100%;
}
.event-right {
  line-height: 2.5em;

  span {
    margin-left: 10px;
  }
}
.danger {
  color: #cd3b3e;
}
.event {
  overflow: hidden;
  margin-bottom: 20px;
}
</style>
