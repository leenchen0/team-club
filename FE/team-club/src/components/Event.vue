<template>
  <div class="event">
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
        :src="event.avatar"
        alt="avatar">
    </span>
    <small v-if="event.type != 'comment'" class="event-right" :class="{ danger: event.type === 'delete' }">
      <span class="event-date">{{ formatDate(event.date) }}</span>
      <span class="event-user"><b>{{ event.name }}</b></span>
      <span class="event-type">{{ `${getInfo(event.type, table, event.info)}` }}</span>
    </small>
    <small v-else class="event-right">
      <span class="event-user"><b>{{ event.name }}</b></span>
      <span class="event-date">{{ formatDate(event.date) }}</span>
      <p style="margin: 0 0 0 70px;">{{ event.info }}</p>
    </small>
  </div>
</template>

<script>
export default {
  name: 'Event',
  props: ['event', 'table'],
  methods: {
    getIcon(type) {
      switch (type) {
        case 'create':
          return 'el-icon-circle-plus';
        case 'finish':
          return 'el-icon-success';
        case 'continue':
          return 'el-icon-loading';
        case 'begin':
          return 'el-icon-caret-right';
        case 'pause':
          return 'el-icon-warning';
        case 'reopen':
          return 'el-icon-d-arrow-right';
        case 'delete':
          return 'el-icon-circle-close';
        case 'archive':
          return 'el-icon-check';
        case 'active':
          return 'el-icon-time';
        default:
          return '';
      }
    },
    getInfo(type, table, info) {
      if (type === 'create') {
        return `创建了${table}`;
      } else if (type === 'finish') {
        return `完成了${table}`;
      } else if (type === 'continue') {
        return `继续执行${table}`;
      } else if (type === 'begin') {
        return `开始执行${table}`;
      } else if (type === 'pause') {
        return `暂停处理${table}`;
      } else if (type === 'reopen') {
        return `恢复了${table}`;
      } else if (type === 'delete') {
        return `删除了${table}`;
      } else if (type === 'archive') {
        return `归档${table}`;
      } else if (type === 'active') {
        return `激活${table}`;
      } else if (type === 'createTask') {
        return `创建了任务${info}`;
      }
      return '';
    },
    formatDate(date) {
      const d = new Date(date);
      return `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()} ${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}`;
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
