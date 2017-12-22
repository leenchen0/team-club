<template>
  <div>
    <event
      v-for="(event, index) in events"
      :key="index"
      :event="event"
      :table="table"
    />
  </div>
</template>

<script>
import Event from './Event';

export default {
  name: 'EventPanel',
  props: ['events', 'table'],
  components: {
    Event,
  },
};
</script>
