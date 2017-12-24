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
    $uid = $this->session->user['uid'];
    $sql = "CALL get_dynamic(?, ?)";
    $query = $this->db->query($sql, array($uid, $tid));
    return array('error' => null, 'data' => $query->result_array());
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
    $sql = "CALL create_project(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $tid, $name))->row();
    if (isset($row) && $row->pid > 0) {
      return array('error' => null, 'data' => $row->pid);
    }
    return array('error' => '权限不足');
  }

  public function getMembers($tid) {
    $uid = $this->session->user['uid'];
    $sql = "CALL get_members(?, ?)";
    $query = $this->db->query($sql, array($uid, $tid));
    return array(
      'error' => null,
      'data' => $query->result_array()
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
    $sql = "CALL change_team_name(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $tid, $name))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function applyTeam($tid) {
    $uid = $this->session->user['uid'];
    $sql = "CALL apply_team(?, ?)";
    $row = $this->db->query($sql, array($uid, $tid))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '您已在团队或申请列表中';
  }

  public function rejectApply($tid, $uid) {
    $id = $this->session->user['uid'];
    $sql = "CALL reject_apply(?, ?, ?)";
    $row = $this->db->query($sql, array($id, $tid, $uid))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function acceptApply($tid, $uid) {
    $id = $this->session->user['uid'];
    $sql = "CALL accept_apply(?, ?, ?)";
    $row = $this->db->query($sql, array($id, $tid, $uid))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
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
}
?>