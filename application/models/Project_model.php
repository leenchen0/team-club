<?php
class Project_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->load->database();
    $this->load->library('session');
    $this->load->model('Team_model', 'team');
  }

  public function setIcon($pid, $icon) {
    $uid = $this->session->user['uid'];
    if (!$this->checkAuth($uid)) {
      return '权限不足';
    }

    $sql = "UPDATE Project SET icon = ? WHERE pid = ?";
    $query = $this->db->query($sql, array($icon, $pid));
    if ($query > 0) {
      return null;
    }
    return '更新失败';
  }

  public function setColor($pid, $color) {
    if (!$this->checkAuth($pid)) {
      return '权限不足';
    }

    $sql = "UPDATE Project SET color = ? WHERE pid = ?";
    $query = $this->db->query($sql, array($color, $pid));
    if ($query > 0) {
      return null;
    }
    return '更新失败';
  }

  public function getSettingInfo($pid) {
    if (!$this->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $sql = "SELECT name, description, `private` as isPrivate FROM Project WHERE pid = ?";
    $query = $this->db->query($sql, array($pid));
    $project = $query->row();
    if (isset($project)) {
      return array('error' => null, 'data' => $project);
    }
    return array('error' => '获取信息失败');
  }

  public function saveSettingInfo($pid, $isPrivate, $name, $description) {
    if (!$this->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $sql = "UPDATE Project SET private = ?, name = ?, description = ? WHERE pid = ?";
    $query = $this->db->query($sql, array($isPrivate, $name, $description, $pid));
    return $query > 0 ? null : '保存失败';
  }

  public function checkAuth($pid) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM Project p, TeamMember tm WHERE p.pid = ? AND p.tid = tm.tid AND tm.uid = ? AND tm.accept = 1";
    $query = $this->db->query($sql, array($pid, $uid));
    return $query->num_rows() > 0;
  }

  public function getInfo($pid) {
    // 获取项目信息
    $sql = "SELECT dir_id as dirId, doc_dir_id as docDirId, name, description, private, tid FROM Project WHERE pid = ?";
    $query = $this->db->query($sql, array($pid));
    $project = $query->row();
    if (!isset($project)) {
      return array('error' => '获取信息失败');
    }
    // 检查是否有权限查看项目
    if ($project->private === '1') {
      $uid = $this->session->user['uid'];
      $sql = "SELECT uid FROM TeamMember WHERE tid = ? AND uid = ? AND accept = 1";
      $query = $this->db->query($sql, array($project->tid, $uid));
      if ($query->num_rows() <= 0) {
        return array('error' => '权限不足');
      }
    }
    // 获取未完成任务列表
    $sql = "SELECT task_id as taskId, task_list_id as taskListId, name, doing, uid, finished FROM Task WHERE pid = ? AND deleted = 0 AND finished is null";
    $query = $this->db->query($sql, array($pid));
    $tasks = $query->result_array();
    // 获取任务清单列表
    $sql = "SELECT task_list_id as taskListId, name FROM TaskList WHERE pid = ? AND deleted = 0 AND archived is null";
    $query = $this->db->query($sql, array($pid));
    $taskLists = $query->result_array();
    // 获取讨论列表
    $sql = "SELECT did, u.uid, u.avatar, u.name, topic, `date` FROM Discussion d, User u WHERE d.uid = u.uid AND d.pid = ? AND deleted = 0";
    $query = $this->db->query($sql, array($pid));
    $discussions = $query->result_array();

    return array(
      'error' => null,
      'data' => array(
        'tasks' => $tasks,
        'taskLists' => $taskLists,
        'discussions' => $discussions,
        'project' => $project
      )
    );
  }

  public function archivedTaskList($pid) {
    if (!$this->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $sql = "SELECT task_list_id, name, archived FROM TaskList WHERE pid = ? AND deleted = 0 AND archived is not null";
    $query = $this->db->query($sql, array($pid));
    return array('error' => null, 'data' => $query->result_array());
  }

  public function finishedTasks($pid) {
    if (!$this->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $sql = "SELECT task_id, name, finished FROM Task WHERE pid = ? AND deleted = 0 AND finished is not null";
    $query = $this->db->query($sql, array($pid));
    return array('error' => null, 'data' => $query->result_array());
  }
}