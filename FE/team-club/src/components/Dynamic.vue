<template>
  <div>
    <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
      <h2 style="margin: 0; display: inline; margin-right: 10px;">团队动态</h2>
    </div>
    <div
      v-for="date in dateSet"
      :key="date"
    >
      <h3>{{ date }}</h3>
      <div
        v-for="(event, index) in events[date]"
        :key="index"
        class="dynamic-item"
      >
        <span class="dynamic-item-date">{{ getTime(event.date) }}</span>
        <img width="48" height="48" :src="event.avatar" alt="avatar" class="avatar">
        <span>
          <router-link class="link" :to="{ name: 'UserInfo', params: { uid: event.uid, path: path } }">
            {{ event.name }}
          </router-link>
          {{ getInfo(event.type, event.table) }}：
          <router-link :to="getRouterLink(event)" class="link">{{ event.ename }}</router-link>
          <router-link :to="{ name: 'Project', params: { pid: event.pid, path: path } }">
            <el-tag type="success">{{ event.pname }}</el-tag>
          </router-link>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import * as service from '../service';

export default {
  name: 'Dynamic',
  created() {
    this.getTeamDynamic();
  },
  data() {
    return {
      events: {},
      dateSet: [],
    };
  },
  computed: {
    path() {
      const oldPath = JSON.parse(JSON.stringify(this.$route.params.path || []));
      oldPath.push({
        name: '动态',
        params: {
          name: this.$route.name,
          params: JSON.parse(JSON.stringify(this.$route.params)),
        },
      });
      return oldPath;
    },
  },
  methods: {
    getTeamDynamic() {
      this.events = {};
      service.getTeamDynamic(this.$route.params.tid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        data.data.forEach((event) => {
          event.date = new Date(event.date);
          const date = this.getDate(event.date);
          this.events[date] === undefined ?
          this.events[date] = [event] :
          this.events[date].push(event);
        });
        this.dateSet = Object.keys(this.events).sort((d1, d2) => d1 < d2);
        window.events = this.events[this.dateSet[0]];
        this.dateSet.forEach((date) => {
          this.events[date].sort((e1, e2) => (e1.date > e2.date ? 1 : -1));
        });
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    getDate(date) {
      return `${date.getFullYear()}-${this.addZero(date.getMonth() + 1)}-${this.addZero(date.getDate())}`;
    },
    addZero(num) {
      return `00${num}`.substr(-2);
    },
    getTime(date) {
      return `${this.addZero(date.getHours())}:${this.addZero(date.getMinutes())}`;
    },
    getTableName(table) {
      if (table === 'discussion') {
        return '讨论';
      } else if (table === 'task') {
        return '任务';
      }
      return '任务清单';
    },
    getInfo(type, table) {
      table = this.getTableName(table);
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
      } else if (type === 'comment') {
        return `回复了${table}`;
      }
      return '';
    },
    getRouterLink(event) {
      if (event.table === '任务') {
        return { name: 'TaskDetail', params: { pid: event.pid, task_id: event.id, path: this.path } };
      } else if (event.table === '任务清单') {
        return { name: 'TaskListDetail', params: { pid: event.pid, task_list_id: event.id, path: this.path } };
      }
      return { name: 'DiscussionDetail', params: { pid: event.pid, did: event.id, path: this.path } };
    },
  },
  components: {
    Event,
  },
};
</script>

<style lang="scss">
.dynamic-item {
  margin-left: 55px;
  display: flex;
  align-items: center;

  * {
    margin: 10px;
  }
}
.dynamic-item-date {
  display: inline-block;
  font-size: 0.8em;
  color: #888;
  font-weight: 600;
}
.link {
  font-weight: 600;

  &:hover {
    color: #84a097;
  }
}
</style>
