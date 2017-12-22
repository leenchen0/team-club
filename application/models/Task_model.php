<?php
class Task_model extends CI_Model {
  public function __construct(){
    parent::__construct();

    $this->load->database();
    $this->load->library('session');
    $this->load->model('Project_model', 'project');
  }

  public function createTaskList($pid, $name) {
    if (!$this->project->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL create_task_list(?, ?, ?)";
    $query = $this->db->query($sql, array($uid, $pid, $name));
    return array(
      'error' => null,
      'data' => $query->row()->tid
    );
  }

  public function getTaskListInfo($taskListId) {
    $sql = "SELECT name, archived, deleted FROM TaskList WHERE task_list_id = ?";
    $query = $this->db->query($sql, array($taskListId));
    $taskList = $query->row();
    if (!isset($taskList)) {
      return array('error' => '获取信息失败');
    }
    // 获取该任务清单下的所有任务
    $sql = "SELECT task_id as taskId, name, doing, uid, finished FROM Task WHERE task_list_id = ? AND deleted = 0";
    $query = $this->db->query($sql, array($taskListId));
    $tasks = $query->result_array();
    // 获取事件列表
    $sql = "SELECT name, avatar, type, `date`, info FROM TaskListEvent te, User u WHERE u.uid = te.uid AND task_list_id = ?";
    $query = $this->db->query($sql, array($taskListId));
    $events = $query->result_array();
    return array('error' => null, 'data' => array(
      'taskList' => $taskList,
      'tasks' => $tasks,
      'events' => $events
    ));
  }

  public function deleteTaskList($taskListId) {
    if (!$this->checkTaskListAuth($taskListId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL delete_task_list(?, ?)";
    $this->db->query($sql, array($uid, $taskListId));
    return null;
  }

  public function recoverTaskList($taskListId) {
    if (!$this->checkTaskListAuth($taskListId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL recover_task_list(?, ?)";
    $this->db->query($sql, array($uid, $taskListId));
    return null;
  }

  public function editTaskList($taskListId, $taskListName) {
    if (!$this->checkTaskListAuth($taskListId)) {
      return '权限不足';
    }

    $sql = "UPDATE TaskList SET name = ? WHERE task_list_id = ?";
    $query = $this->db->query($sql, array($taskListName, $taskListId));
    return $query > 0 ? null : '修改信息失败';
  }

  public function archivedTaskList($taskListId) {
    if (!$this->checkTaskListAuth($taskListId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL archive_task_list(?, ?)";
    $this->db->query($sql, array($uid, $taskListId));
    return null;
  }

  public function unArchivedTaskList($taskListId) {
    if (!$this->checkTaskListAuth($taskListId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL active_task_list(?, ?)";
    $this->db->query($sql, array($uid, $taskListId));
    return null;
  }

  public function create($pid, $taskListId, $name) {
    if (!$this->project->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL create_task(?, ?, ?, ?)";
    $this->db->query($sql, array($pid, $taskListId, $name, $uid));
    return array('error' => null);
  }

  public function delete($taskId) {
    if (!$this->checkAuth($taskId)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL delete_task(?, ?)";
    $this->db->query($sql, array($uid, $taskId));
    return null;
  }

  public function recover($taskId) {
    if (!$this->checkAuth($taskId)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL recover_task(?, ?)";
    $this->db->query($sql, array($uid, $taskId));
    return null;
  }

  public function edit($taskId, $name, $description) {
    if (!$this->checkAuth($taskId)) {
      return array('error' => '权限不足');
    }

    if (!isset($description)) {
      $sql = "UPDATE Task SET name = ? WHERE task_id = ?";
      $query = $this->db->query($sql, array($name, $taskId));
    } else {
      $sql = "UPDATE Task SET name = ?, description = ? WHERE task_id = ?";
      $query = $this->db->query($sql, array($name, $description, $taskId));
    }

    return $query > 0 ? null : '更新任务信息失败';
  }

  public function getInfo($taskId) {
    $sql = "SELECT name, description, uid, finished, doing, deleted, task_list_id FROM Task t WHERE task_id = ?";
    $query = $this->db->query($sql, array($taskId));
    $task = $query->row();
    if (!isset($task)) {
      return array('error' => '获取信息失败');
    }
    // 判断任务是否在已归档任务清单中
    if ($task->task_list_id === null) {
      $task->archived = null;
    } else {
      $sql = "SELECT archived FROM TaskList WHERE task_list_id = ?";
      $query = $this->db->query($sql, array($taskId));
      $taskList = $query->row();
      if (!isset($taskList)) {
        return array('error' => '获取信息失败');
      }
      $task->archived = $taskList->archived;
    }

    // 获取事件列表
    $sql = "SELECT name, avatar, type, `date`, info FROM TaskEvent te, User u WHERE u.uid = te.uid AND task_id = ?";
    $query = $this->db->query($sql, array($taskId));
    $events = $query->result_array();
    return array('error' => null, 'data' => array(
      'task' => $task,
      'events' => $events
    ));
  }

  public function allocating($taskId, $uid) {
    if (!$this->checkAuth($taskId)) {
      return array('error' => '权限不足');
    }

    $sql = "UPDATE Task SET uid = ?, doing = 0 WHERE task_id = ?";
    $query = $this->db->query($sql, array($uid, $taskId));
    return $query > 0 ? null : '分配任务失败';
  }

  public function begin($taskId) {
    if (!$this->checkAuth($taskId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "UPDATE Task SET uid = ?, doing = 1 WHERE task_id = ?";
    $query = $this->db->query($sql, array($uid, $taskId));
    return $query > 0 ? null : '操作失败';
  }

  public function pause($taskId) {
    if (!$this->checkAuth($taskId)) {
      return '权限不足';
    }

    $sql = "UPDATE Task SET doing = 0 WHERE task_id = ?";
    $query = $this->db->query($sql, $taskId);
    return $query > 0 ? null : '操作失败';
  }

  public function markFinished($taskId) {
    if (!$this->checkAuth($taskId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "UPDATE Task SET uid = ?, doing = 0, finished = CURRENT_TIMESTAMP WHERE task_id = ?";
    $query = $this->db->query($sql, array($uid, $taskId));
    return $query > 0 ? null : '操作失败';
  }

  public function markUnfinished($taskId) {
    if (!$this->checkAuth($taskId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "UPDATE Task SET uid = ?, finished = null WHERE task_id = ?";
    $query = $this->db->query($sql, array($uid, $taskId));
    return $query > 0 ? null : '操作失败';
  }

  public function comment($taskId, $comment) {
    if (!$this->checkAuth($taskId)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "INSERT INTO TaskEvent (uid, type, info, task_id) VALUES (?, 'comment', ?, ?)";
    $query = $this->db->query($sql, array($uid, $comment, $taskId));
    return $query > 0 ? array('error' => null, 'data' => $this->db->insert_id()) : array('error' => '评论失败');
  }

  public function commentTaskList($taskListId, $comment) {
    if (!$this->checkTaskListAuth($taskListId)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "INSERT INTO TaskListEvent (uid, type, info, task_list_id) VALUES (?, 'comment', ?, ?)";
    $query = $this->db->query($sql, array($uid, $comment, $taskListId));
    return $query > 0 ? array('error' => null, 'data' => $this->db->insert_id()) : array('error' => '评论失败');
  }

  public function checkAuth($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL check_task_auth(?, ?)";
    $query = $this->db->query($sql, array($uid, $taskId));
    $row = $query->row();
    // 调用存储过程后需要重新连接数据库才可以执行其他语句
    $this->db->close();
    $this->db->initialize();
    if (!isset($row) || $row->auth === '0') {
      return false;
    }
    return true;
  }

  public function checkTaskListAuth($taskListId) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM TaskList t, Project p, TeamMember tm WHERE t.task_list_id = ? AND t.pid = p.pid AND p.tid = tm.tid AND tm.uid = ? AND tm.accept = 1";
    $query = $this->db->query($sql, array($taskListId, $uid));
    return $query->num_rows() > 0;
  }
}
?>