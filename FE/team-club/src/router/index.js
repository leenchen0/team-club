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

Vue.use(Router);

export default new Router({
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
          path: 'project/:pid/task/:task_id',
          name: 'TaskDetail',
          component: TaskDetail,
        },
        {
          path: 'project/:pid/discussion/:did',
          name: 'DiscussionDetail',
          component: DiscussionDetail,
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
      ],
    },
  ],
});
