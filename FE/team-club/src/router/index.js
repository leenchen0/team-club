import Vue from 'vue';
import Router from 'vue-router';
import Login from '@/components/Login';
import Launchpad from '@/components/Launchpad';
import Team from '@/components/Team';
import Projects from '@/components/Projects';
import Settings from '@/components/Settings';
import Member from '@/components/Member';
import UserSettings from '@/components/UserSettings';
import UserInfo from '@/components/UserInfo';
import Project from '@/components/Project';
import FinishedTasks from '@/components/FinishedTasks';
import FileBrowser from '@/components/FileBrowser';
import DocumentBrowser from '@/components/DocumentBrowser';
import DocumentEditor from '@/components/DocumentEditor';
import ProjectFinishedTasks from '@/components/ProjectFinishedTasks';
import ArchivedTaskList from '@/components/ArchivedTaskList';
import TaskDetail from '@/components/TaskDetail';
import DiscussionDetail from '@/components/DiscussionDetail';
import TaskListDetail from '@/components/TaskListDetail';
import ApplyTeam from '@/components/ApplyTeam';
import ProjectSettings from '@/components/ProjectSettings';
import EditHistory from '@/components/EditHistory';
import ViewDocument from '@/components/ViewDocument';
import Dynamic from '@/components/Dynamic';

import store from '../store';

Vue.use(Router);

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login,
    },
    {
      path: '/launchpad',
      name: 'Launchpad',
      component: Launchpad,
    },
    {
      path: '/team',
      redirect: '/launchpad',
    },
    {
      path: '/team/:tid',
      name: 'Team',
      component: Team,
      redirect: '/team/:tid/projects',
      children: [
        {
          path: 'dynamic',
          name: 'Dynamic',
          component: Dynamic,
        },
        {
          path: 'projects',
          name: 'Projects',
          component: Projects,
        },
        {
          path: 'member',
          name: 'Member',
          component: Member,
        },
        {
          path: 'userInfo/:uid/task/finished',
          name: 'FinishedTasks',
          component: FinishedTasks,
        },
        {
          path: 'userInfo/:uid',
          name: 'UserInfo',
          component: UserInfo,
        },
        {
          path: 'task/:task_id',
          name: 'TaskDetail',
          component: TaskDetail,
        },
        {
          path: 'settings',
          name: 'Settings',
          component: Settings,
        },
        {
          path: 'userSettings',
          name: 'UserSettings',
          component: UserSettings,
        },
        {
          path: 'project/:pid/task/finished',
          name: 'ProjectFinishedTasks',
          component: ProjectFinishedTasks,
        },
        {
          path: 'project/:pid/taskList/archived',
          name: 'ArchivedTaskList',
          component: ArchivedTaskList,
        },
        {
          path: 'project/:pid/taskList/:task_list_id',
          name: 'TaskListDetail',
          component: TaskListDetail,
        },
        {
          path: 'project/:pid/discussion/:did',
          name: 'DiscussionDetail',
          component: DiscussionDetail,
        },
        {
          path: 'project/:pid/settings',
          name: 'ProjectSettings',
          component: ProjectSettings,
        },
        {
          path: 'project/:pid',
          name: 'Project',
          component: Project,
        },
        {
          path: 'fileBrowser/:dir_id',
          name: 'FileBrowser',
          component: FileBrowser,
        },
        {
          path: 'documentBrowser/:doc_dir_id',
          name: 'DocumentBrowser',
          component: DocumentBrowser,
        },
        {
          path: 'document/:doc_id',
          name: 'DocumentEditor',
          component: DocumentEditor,
        },
        {
          path: 'editHistory/:doc_id',
          name: 'EditHistory',
          component: EditHistory,
        },
        {
          path: 'viewDocument/:hid',
          name: 'ViewDocument',
          component: ViewDocument,
        },
        {
          path: 'applyTeam',
          name: 'ApplyTeam',
          component: ApplyTeam,
        },
      ],
    },
  ],
});

router.beforeEach((to, from, next) => {
  // 为登录则先获取用户信息
  if (store.state.user === null && to.name !== 'Login') {
    store.dispatch('getUserInfo', next);
  } else {
    next();
  }
});

export default router;
