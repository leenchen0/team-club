<template>
  <div>
    <div style="padding-bottom: 20px; margin-bottom: 20px;">
      <h2 style="margin: 0; display: inline; margin-right: 10px;">{{ teamName }}</h2>
      <span class="tips">(共 {{ members.length }} 人)</span>
      <el-button type="text" style="float: right;" @click="showLinkDialog = true">邀请新成员</el-button>
    </div>
    <div>
      <member-info v-for="(member, index) in members" :key="index" :user="member" />
    </div>
    <el-dialog
      title="提示"
      :visible.sync="showLinkDialog"
      width="30%">
      <span>将下面的公共邀请链接通过QQ或微信发送给需要邀请的人</span>
      <el-input :value="'http://localhost:8002/' + $route.params.tid" readonly></el-input>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="showLinkDialog = false">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import MemberInfo from './MemberInfo';

export default {
  name: 'Member',
  props: ['teamName'],
  data() {
    return {
      members: [
        {
          uid: 1,
          name: 'Pencil',
          avatar: '../assets/avatar.jpg',
          email: '9807175@qq.com',
        },
        {
          uid: 2,
          name: 'Pencil2',
          avatar: '../assets/avatar.jpg',
          email: '98071725@qq.com',
        },
        {
          uid: 3,
          name: 'Pencil3',
          avatar: '../assets/avatar.jpg',
          email: '98073175@qq.com',
        },
      ],
      showLinkDialog: false,
    };
  },
  components: {
    MemberInfo,
  },
};
</script>

<style lang="scss">

</style>
