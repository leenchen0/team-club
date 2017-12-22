import fetch from './fetch';

// 用户相关接口
export const login = (email, password) => fetch('/index.php/user/login', {
  method: 'POST',
  body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
});

export const logout = () => fetch('/index.php/user/logout');

export const register = (name, email, password) => fetch('/index.php/user/register', {
  method: 'POST',
  body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
});

export const getUserInfo = () => fetch('/index.php/user/get_info');

export const updateAvatar = formData => fetch('/index.php/user/avatar', {
  method: 'POST',
  headers: {},
  body: formData,
});

export const updateUserInfo = (name, email) => fetch('/index.php/user/updateInfo', {
  method: 'POST',
  body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`,
});

// 团队相关接口
export const getTeamList = () => fetch('/index.php/team/teamList');

export const getTeamDynamic = tid => fetch(`/index.php/team/getDynamic?tid=${encodeURIComponent(tid)}`);

export const createTeam = teamName => fetch('/index.php/team/createTeam', {
  method: 'POST',
  body: `name=${encodeURIComponent(teamName)}`,
});

export const deleteTeam = tid => fetch('/index.php/team/deleteTeam', {
  method: 'POST',
  body: `tid=${encodeURIComponent(tid)}`,
});

export const getProjects = tid => fetch(`/index.php/team/projects?tid=${encodeURIComponent(tid)}`);

export const createProject = (tid, name) => fetch(`/index.php/team/createProject?tid=${encodeURIComponent(tid)}`, {
  method: 'POST',
  body: `name=${encodeURIComponent(name)}`,
});

export const setName = (tid, name) => fetch(`/index.php/team/setName?tid=${encodeURIComponent(tid)}`, {
  method: 'POST',
  body: `name=${encodeURIComponent(name)}`,
});

export const applyTeam = tid => fetch(`/index.php/team/applyTeam?tid=${encodeURIComponent(tid)}`);

export const rejectApply = (tid, uid) => fetch(`/index.php/team/rejectApply?tid=${encodeURIComponent(tid)}`, {
  method: 'POST',
  body: `uid=${encodeURIComponent(uid)}`,
});

export const acceptApply = (tid, uid) => fetch(`/index.php/team/acceptApply?tid=${encodeURIComponent(tid)}`, {
  method: 'POST',
  body: `uid=${encodeURIComponent(uid)}`,
});

export const getUnfinishedTasks = (tid, uid) => fetch(`/index.php/team/taskUnfinished?tid=${encodeURIComponent(tid)}&uid=${encodeURIComponent(uid)}`);

export const getFinishedTasks = (tid, uid) => fetch(`/index.php/team/taskFinished?tid=${encodeURIComponent(tid)}&uid=${encodeURIComponent(uid)}`);

export const getMembers = tid => fetch(`/index.php/team/getMembers?tid=${encodeURIComponent(tid)}`);

export const getAcceptedMembers = tid => fetch(`/index.php/team/getAcceptedMembers?tid=${encodeURIComponent(tid)}`);

// 项目相关接口
export const setProjectIcon = (pid, icon) => fetch(`/index.php/project/setIcon?pid=${encodeURIComponent(pid)}`, {
  method: 'POST',
  body: `icon=${encodeURIComponent(icon)}`,
});

export const setProjectColor = (pid, color) => fetch(`/index.php/project/setColor?pid=${encodeURIComponent(pid)}`, {
  method: 'POST',
  body: `color=${encodeURIComponent(color)}`,
});

export const getProjectSettingInfo = pid => fetch(`/index.php/project/getSettingInfo?pid=${encodeURIComponent(pid)}`);

export const saveProjectInfo = (pid, isPrivate, name, description) => fetch('/index.php/project/saveSettingInfo', {
  method: 'POST',
  body: `pid=${encodeURIComponent(pid)}&isPrivate=${encodeURIComponent(isPrivate)}&name=${encodeURIComponent(name)}&description=${encodeURIComponent(description)}`,
});

export const getProjectInfo = pid => fetch(`/index.php/project/getInfo?pid=${encodeURIComponent(pid)}`);

export const getProjectFinishedTasks = pid => fetch(`/index.php/project/finishedTasks?pid=${encodeURIComponent(pid)}`);

export const getArchivedTaskList = pid => fetch(`/index.php/project/archivedTaskList?pid=${encodeURIComponent(pid)}`);

// 任务相关接口

export const createTaskList = (pid, name) => fetch('/index.php/task/createTaskList', {
  method: 'POST',
  body: `pid=${encodeURIComponent(pid)}&name=${encodeURIComponent(name)}`,
});

export const getTaskListInfo = taskListId => fetch(`/index.php/task/getTaskListInfo?taskListId=${encodeURIComponent(taskListId)}`);

export const deleteTaskList = taskListId => fetch('/index.php/task/deleteTaskList', {
  method: 'POST',
  body: `taskListId=${encodeURIComponent(taskListId)}`,
});

export const recoverTaskList = taskListId => fetch('/index.php/task/recoverTaskList', {
  method: 'POST',
  body: `taskListId=${encodeURIComponent(taskListId)}`,
});

export const editTaskListName = (taskListId, taskListName) => fetch('/index.php/task/editTaskList', {
  method: 'POST',
  body: `taskListId=${encodeURIComponent(taskListId)}&taskListName=${encodeURIComponent(taskListName)}`,
});

export const archivedTaskList = taskListId => fetch('/index.php/task/archivedTaskList', {
  method: 'POST',
  body: `taskListId=${encodeURIComponent(taskListId)}`,
});

export const unArchivedTaskList = taskListId => fetch('/index.php/task/unArchivedTaskList', {
  method: 'POST',
  body: `taskListId=${encodeURIComponent(taskListId)}`,
});

export const commentTaskList = (taskListId, comment) => fetch('/index.php/task/commentTaskList', {
  method: 'POST',
  body: `taskListId=${encodeURIComponent(taskListId)}&comment=${encodeURIComponent(comment)}`,
});

export const createTask = (pid, name, taskListId) => fetch('/index.php/task/create', {
  method: 'POST',
  body: `pid=${encodeURIComponent(pid)}&taskListId=${encodeURIComponent(taskListId === undefined ? null : taskListId)}&name=${encodeURIComponent(name)}`,
});

export const deleteTask = taskId => fetch('/index.php/task/delete', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}`,
});

export const recoverTask = taskId => fetch('/index.php/task/recover', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}`,
});

export const editTaskName = (taskId, name) => fetch('/index.php/task/edit', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}&name=${encodeURIComponent(name)}`,
});

export const editTask = (taskId, name, description) => fetch('/index.php/task/edit', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}&name=${encodeURIComponent(name)}&description=${encodeURIComponent(description)}`,
});

export const getTaskInfo = taskId => fetch(`/index.php/task/getInfo?taskId=${encodeURIComponent(taskId)}`);

export const beginTask = taskId => fetch('/index.php/task/begin', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}`,
});

export const pauseTask = taskId => fetch('/index.php/task/pause', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}`,
});

export const markTaskFinished = taskId => fetch('/index.php/task/markFinished', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}`,
});

export const markTaskUnfinished = taskId => fetch('/index.php/task/markUnfinished', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}`,
});

export const allocatingTask = (taskId, uid) => fetch('/index.php/task/allocating', {
  method: 'POST',
  body: `uid=${encodeURIComponent(uid)}&taskId=${encodeURIComponent(taskId)}`,
});

export const commentTask = (taskId, comment) => fetch('/index.php/task/comment', {
  method: 'POST',
  body: `taskId=${encodeURIComponent(taskId)}&comment=${encodeURIComponent(comment)}`,
});

// 讨论相关接口

export const createDiscussion = (pid, topic) => fetch('/index.php/discussion/create', {
  method: 'POST',
  body: `pid=${encodeURIComponent(pid)}&topic=${encodeURIComponent(topic)}`,
});

export const deleteDiscussion = did => fetch('/index.php/discussion/delete', {
  method: 'POST',
  body: `did=${encodeURIComponent(did)}`,
});

export const recoverDiscussion = did => fetch('/index.php/discussion/recover', {
  method: 'POST',
  body: `did=${encodeURIComponent(did)}`,
});

export const editDiscussion = (did, topic, description) => fetch('/index.php/discussion/edit', {
  method: 'POST',
  body: `did=${encodeURIComponent(did)}&topic=${encodeURIComponent(topic)}&description=${encodeURIComponent(description)}`,
});

export const getDiscussionInfo = did => fetch(`/index.php/discussion/getInfo?did=${encodeURIComponent(did)}`);

export const commentDiscussion = (did, comment) => fetch('/index.php/discussion/comment', {
  method: 'POST',
  body: `did=${encodeURIComponent(did)}&comment=${encodeURIComponent(comment)}`,
});

// 文件相关接口

export const uploadFile = formData => fetch('/index.php/file/upload', {
  method: 'POST',
  headers: {},
  body: formData,
});

export const createDir = (dirId, dirName) => fetch('/index.php/file/createDir', {
  method: 'POST',
  body: `dirId=${encodeURIComponent(dirId)}&dirName=${encodeURIComponent(dirName)}`,
});

export const deleteFile = fid => fetch('/index.php/file/deleteFile', {
  method: 'POST',
  body: `fid=${encodeURIComponent(fid)}`,
});

export const deleteDir = dirId => fetch('/index.php/file/deleteDir', {
  method: 'POST',
  body: `dirId=${encodeURIComponent(dirId)}`,
});

export const getDirInfo = dirId => fetch(`/index.php/file/getInfo?dirId=${encodeURIComponent(dirId)}`);

// 文档相关接口

export const createDoc = (dirId, docName) => fetch('/index.php/document/create', {
  method: 'POST',
  body: `dirId=${encodeURIComponent(dirId)}&docName=${encodeURIComponent(docName)}`,
});

export const getDocInfo = docId => fetch(`/index.php/document/getDocInfo?docId=${encodeURIComponent(docId)}`);

export const getDocHistory = docId => fetch(`/index.php/document/getDocHistory?docId=${encodeURIComponent(docId)}`);

export const getHistoryContent = hid => fetch(`/index.php/document/getHistoryContent?hid=${encodeURIComponent(hid)}`);

export const editDoc = (docId, docName) => fetch('/index.php/document/editDoc', {
  method: 'POST',
  body: `docId=${encodeURIComponent(docId)}&docName=${encodeURIComponent(docName)}`,
});

export const saveDoc = (docId, content) => fetch('/index.php/document/saveDoc', {
  method: 'POST',
  body: `content=${encodeURIComponent(content)}&docId=${encodeURIComponent(docId)}`,
});

export const createDocDir = (dirId, dirName) => fetch('/index.php/document/createDir', {
  method: 'POST',
  body: `dirId=${encodeURIComponent(dirId)}&dirName=${encodeURIComponent(dirName)}`,
});

export const deleteDoc = docId => fetch('/index.php/document/deleteDoc', {
  method: 'POST',
  body: `docId=${encodeURIComponent(docId)}`,
});

export const deleteDocDir = dirId => fetch('/index.php/document/deleteDir', {
  method: 'POST',
  body: `dirId=${encodeURIComponent(dirId)}`,
});

export const getDocDirInfo = dirId => fetch(`/index.php/document/getInfo?dirId=${encodeURIComponent(dirId)}`);
