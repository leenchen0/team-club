<template>
  <div>
    <div style="padding-bottom: 20px; margin-bottom: 20px;">
      <h2 style="margin: 0; display: inline; margin-right: 10px;">{{ teamName }}</h2>
      <span class="tips">(共 {{ acceptedMembers.length }} 人)</span>
      <el-button v-if="owner === user.uid" type="text" style="float: right;" @click="showLinkDialog = true">邀请新成员</el-button>
    </div>
    <div>
      <member-info v-for="member in acceptedMembers" :key="member.uid" :user="member" />
    </div>
    <div v-if="applyingMembers.length > 0">
      <h4>申请列表</h4>
      <div
        v-for="member in applyingMembers"
        :key="member.uid"
        style="display: flex; align-items: center; justify-content: space-between;"
      >
        <div style="display: flex; align-items: center;">
          <img
            class="avatar"
            style="margin-right: 10px;"
            width="64"
            height="64"
            :src="member.avatar"
            alt="avatar"
          >
          <div>
            <b>{{ member.name }}</b>
            <p class="tips">{{ member.email }}</p>
          </div>
        </div>
        <div>
          <template v-if="owner === user.uid">
            <el-button type="primary" size="mini" @click="acceptApply(member)" plain>同意</el-button>
            <el-button type="danger" size="mini" @click="rejectApply(member)" plain>拒绝</el-button>
          </template>
          <span v-else class="tips">审核中</span>
        </div>
      </div>
    </div>
    <el-dialog
      title="提示"
      :visible.sync="showLinkDialog"
      width="30%">
      <span>将下面的公共邀请链接通过QQ或微信发送给需要邀请的人</span>
      <el-input :value="invideLink" readonly></el-input>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="showLinkDialog = false">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import MemberInfo from './MemberInfo';
import * as service from '../service';

export default {
  name: 'Member',
  props: ['teamName', 'owner'],
  created() {
    this.getMembers(this.$route.params.tid);
  },
  data() {
    return {
      members: [],
      showLinkDialog: false,
    };
  },
  computed: {
    ...mapState([
      'user',
    ]),
    acceptedMembers() {
      return this.members.filter(m => m.accept === '1');
    },
    applyingMembers() {
      return this.members.filter(m => m.accept === '0');
    },
    invideLink() {
      return `${window.location.protocol}//${window.location.host}/#/team/${this.$route.params.tid}/applyTeam`;
    },
  },
  methods: {
    getMembers(tid) {
      service.getMembers(tid).then((data) => {
        if (data.error) {
          throw Error('获取成员列表失败');
        }
        this.members = data.data;
      }).catch(() => {
        this.$message.error('获取成员列表失败');
      });
    },
    rejectApply(member) {
      service.rejectApply(this.$route.params.tid, member.uid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        const i = this.members.indexOf(member);
        this.members.splice(i, 1);
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
    acceptApply(member) {
      service.acceptApply(this.$route.params.tid, member.uid).then((data) => {
        if (data.error) {
          throw Error(data.error);
        }
        const i = this.members.indexOf(member);
        member.accept = '1';
        this.$set(this.members, i, member);
      }).catch((err) => {
        this.$message.error(err.message);
      });
    },
  },
  components: {
    MemberInfo,
  },
};
</script>

<style lang="scss">

</style>
