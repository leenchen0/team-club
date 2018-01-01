<?php
class Task_model extends CI_Model {
  public function __construct(){
    parent::__construct();

    $this->load->database();
    $this->load->library('session');
    $this->load->model('Project_model', 'project');
  }

  public function createTaskList($pid, $name) {
    $uid = $this->session->user['uid'];
    $sql = "CALL create_task_list(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $pid, $name))->row();
    if (isset($row) && $row->tid > 0) {
      return array(
        'error' => null,
        'data' => $row->tid
      );
    }
    return array('error' => '权限不足'); 
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
    $uid = $this->session->user['uid'];
    $sql = "CALL delete_task_list(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskListId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function recoverTaskList($taskListId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL recover_task_list(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskListId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
  }

  public function editTaskList($taskListId, $taskListName) {
    $uid = $this->session->user['uid'];
    $sql = "CALL edit_taskList(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $taskListId, $taskListName))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function archivedTaskList($taskListId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL archived_taskList(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskListId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function unArchivedTaskList($taskListId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL active_task_list(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskListId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function create($pid, $taskListId, $name) {
    $uid = $this->session->user['uid'];
    $sql = "CALL create_task(?, ?, ?, ?)";
    $row = $this->db->query($sql, array($pid, $taskListId, $name, $uid))->row();
    if (isset($row) && $row->taskId > 0) {
      return array('error' => null, 'data' => $row->taskId);
    }
    return array('error' => '创建失败');
  }

  public function delete($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL delete_task(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function recover($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL recover_task(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function edit($taskId, $name, $description) {
    $uid = $this->session->user['uid'];
    $sql = "CALL edit_task(?, ?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId, $name, $description))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
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
      $query = $this->db->query($sql, array($task->task_list_id));
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
    $cu = $this->session->user['uid'];
    $sql = "CALL allocate_task(?, ?, ?)";
    $row = $this->db->query($sql, array($cu, $taskId, $uid))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function begin($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL begin_task(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function pause($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL pause_task(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function markFinished($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL mark_task_finished(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function markUnfinished($taskId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL mark_task_unfinished(?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function comment($taskId, $comment) {
    $uid = $this->session->user['uid'];
    $sql = "CALL comment_task(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $taskId, $comment))->row();
    if (isset($row) && $row->res === '1') {
      return array('error' => null);
    }
    return array('error' => '评论失败');
  }

  public function commentTaskList($taskListId, $comment) {
    $uid = $this->session->user['uid'];
    $sql = "CALL comment_taskList(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $taskListId, $comment))->row();
    if (isset($row) && $row->res === '1') {
      return array('error' => null);
    }
    return array('error' => '评论失败');
  }
}
?>