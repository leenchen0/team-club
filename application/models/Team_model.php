<?php
class Team_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();
    $this->load->library('session');
    $this->load->model('Document_model', 'document');
    $this->load->model('File_model', 'file');
	}

  public function getTeams() {
    $uid = $this->session->user['uid'];
    $sql = "SELECT t.tid as tid, name, t.uid as uid from TeamMember tm, Team t WHERE tm.tid = t.tid AND tm.uid = ?";
    $query = $this->db->query($sql, array($uid));
    return array('error' => null, 'data' => $query->result_array());
  }

  public function newTeam($name) {
    $uid = $this->session->user['uid'];
    $sql = "CALL create_team(?, ?)";
    $query = $this->db->query($sql, array($uid, $name));
    return array(
      'error' => null,
      'data' => $query->row()->t
    );
  }

  public function getDynamic($tid) {
    if (!$this->isTeamMember($tid)) {
      return array('error' => '权限不足');
    }
    // DiscussionEvent
    $sql = "SELECT u.uid, u.name, avatar, type, de.date, de.did as id, info, p.name as pname, p.pid, d.topic as ename
            FROM DiscussionEvent de, Discussion d, Project p, User u 
            WHERE de.did = d.did AND d.pid = p.pid AND p.tid = ? AND de.uid = u.uid";
    $query = $this->db->query($sql, array($tid));
    $discussionEvents = $query->result_array();
    // TaskEvent
    $sql = "SELECT u.uid, u.name, avatar, type, te.date, te.task_id as id, info, p.name as pname, p.pid, t.name as ename
            FROM TaskEvent te, Task t, Project p, User u 
            WHERE te.task_id = t.task_id AND t.pid = p.pid AND p.tid = ? AND te.uid = u.uid";
    $query = $this->db->query($sql, array($tid));
    $taskEvents = $query->result_array();
    // TaskListEvent
    $sql = "SELECT u.uid, u.name, avatar, type, te.date, te.task_list_id as id, info, p.name as pname, p.pid, t.name as ename
            FROM TaskListEvent te, TaskList t, Project p, User u 
            WHERE te.type != 'createTask' AND te.task_list_id = t.task_list_id AND t.pid = p.pid AND p.tid = ? AND te.uid = u.uid";
    $query = $this->db->query($sql, array($tid));
    $taskListEvents = $query->result_array();
    return array('error' => null, 'data' => array(
      'discussion' => $discussionEvents,
      'task' => $taskEvents,
      'taskList' => $taskListEvents
    ));
  }

  public function getProjects($tid) {
    $uid = $this->session->user['uid'];
    // 获取团队下的非私有项目或若为私有项目，且当前用户为团队成员
    $sql = "SELECT pid, name, icon, color FROM Project p, TeamMember tm WHERE p.tid = ? AND tm.uid = ? AND (p.private = 0 || (p.tid = tm.tid AND tm.accept = 1))";
    $query = $this->db->query($sql, array($tid, $uid));
    return $query->result_array();
  }

  public function newProject($tid, $name) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM TeamMember WHERE uid = ? AND tid = ?";
    $query = $this->db->query($sql, array($uid, $tid));

    if($query->num_rows() < 1) {
      return array('error' => '权限不足');
    }

    // 创建项目文件夹
    $sql = "INSERT INTO Directory (name) VALUES ('root')";
    $query = $this->db->query($sql);
    $dir_id = $this->db->insert_id();

    // 创建项目文档夹
    $sql = "INSERT INTO DocumentDir (name) VALUES ('root')";
    $query = $this->db->query($sql);
    $doc_dir_id = $this->db->insert_id();

    // 创建项目
    $sql = "INSERT INTO Project (tid, dir_id, doc_dir_id, name) VALUES (?, ?, ?, ?)";
    $query = $this->db->query($sql, array($tid, $dir_id, $doc_dir_id, $name));
    if($query > 0) {
      $id=$this->db->insert_id();
      return array('error'=>null, 'data'=>$id);
    }
    return array('error' => '创建项目失败');
  }

  public function getMembers($tid) {
    $uid = $this->session->user['uid'];
    if ($this->isOwner($uid, $tid)) {
      $sql = "SELECT u.uid as uid, name, avatar, email, accept FROM TeamMember tm, User u WHERE tm.uid = u.uid AND tm.tid = ?";
      $query = $this->db->query($sql, array($tid));
    } else {
      $sql = "SELECT u.uid as uid, name, avatar, email, accept FROM TeamMember tm, User u WHERE tm.uid = u.uid AND tm.tid = ? AND (accept = 1 OR tm.uid = ?)";
      $query = $this->db->query($sql, array($tid, $uid));
    }
    $data = $query->result_array();
    return array(
      'error' => null,
      'data' => $data
    );
  }

  public function getAcceptedMembers($tid) {
    $sql = "SELECT u.uid as uid, name FROM TeamMember tm, User u WHERE tm.uid = u.uid AND tm.tid = ? AND accept = 1";
    $query = $this->db->query($sql, array($tid));
    $data = $query->result_array();
    return $data;
  }

  public function deleteTeam($tid){
    $uid = $this->session->user['uid'];
    
    if(!$this->isOwner($uid, $tid)) {
      return '权限不足';
    }

    $sql = "SELECT dir_id, doc_dir_id FROM Project WHERE tid = ?";
    $query = $this->db->query($sql, array($tid));
    $projects = $query->result();

    $sql = "DELETE FROM Team WHERE tid = ?";
    $query = $this->db->query($sql, array($tid));

    // 删除团队下所有项目的文件和文档
    foreach ($projects as $project) {
      if (isset($project)) {
        $this->document->deleteAllFile($project->doc_dir_id);
        $this->file->deleteAllFile($project->dir_id);
      }
    }

    return null;
  }

  public function isOwner($uid, $tid) {
    $sql = "SELECT * FROM Team WHERE tid = ? AND uid = ?";
    $query = $this->db->query($sql, array($tid, $uid));
    return $query->num_rows() > 0;
  }

  public function changeTeamName($tid, $name){
    $uid = $this->session->user['uid'];

    if (!$this->isOwner($uid, $tid)) {
      return '权限不足';
    }

    $sql = "UPDATE Team SET name = ? WHERE tid = ?";
    $query = $this->db->query($sql, array($name, $tid));
    if ($query > 0) {
      return null;
    }
    return '修改团队名失败';
  }

  public function applyTeam($tid) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM TeamMember WHERE tid = ? AND uid = ?";
    $query = $this->db->query($sql, array($tid, $uid));
    if ($query->num_rows() > 0) {
      return '您已在申请列表中或已是团队成员';
    }
    $sql = "INSERT INTO TeamMember (uid, tid) VALUES (?, ?)";
    $query = $this->db->query($sql, array($uid, $tid));
    if($query > 0) {
      return null;
    }
    return '申请失败';
  }

  public function rejectApply($tid, $uid) {
    $id = $this->session->user['uid'];
    if (!$this->isOwner($id, $tid)) {
      return '权限不足';
    }
    $sql = "DELETE FROM TeamMember WHERE tid = ? AND uid = ?";
    $query = $this->db->query($sql, array($tid, $uid));
    if ($query > 0) {
      return null;
    }
    return '操作失败';
  }

  public function acceptApply($tid, $uid) {
    $id = $this->session->user['uid'];
    if (!$this->isOwner($id, $tid)) {
      return '权限不足';
    }
    $sql = "UPDATE TeamMember SET accept = 1 WHERE tid = ? AND uid = ?";
    $query = $this->db->query($sql, array($tid, $uid));
    if ($query > 0) {
      return null;
    }
    return '操作失败';
  }

	public function getUnfinishedTask($uid, $tid) {
    $sql = "SELECT t.task_id as taskId, t.name as name, doing, p.name as projectName, t.uid as uid, finished FROM Task t, Project p WHERE t.pid = p.pid AND p.tid = ? AND t.uid = ? AND t.finished is NULL AND t.deleted = 0";
    $query = $this->db->query($sql, array($tid, $uid));
    return $query->result_array();
	}

  public function getFinishedTask($uid, $tid) {
    $sql = "SELECT t.task_id as taskId, t.name as name, t.finished as finished FROM Task t, Project p WHERE t.pid = p.pid AND p.tid = ? AND t.uid = ? AND t.finished IS NOT NULL AND t.deleted = 0";
    $query = $this->db->query($sql, array($tid, $uid));
    return $query->result_array();
  }

  public function isTeamMember($tid) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM TeamMember WHERE accept = 1 AND tid = ? AND uid = ?";
    $query = $this->db->query($sql, array($tid, $uid));
    return $query->num_rows() > 0;
  }
}
?>