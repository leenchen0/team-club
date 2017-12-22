<?php
class Discussion_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->load->library('session');
    $this->load->model('Project_model', 'project');
  }

  public function create($pid, $topic) {
    if (!$this->project->checkAuth($pid)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "INSERT INTO Discussion (uid, pid, topic) VALUES (?, ?, ?)";
    $query = $this->db->query($sql, array($uid, $pid, $topic));
    if ($query > 0) {
      return array(
        'error' => null,
        'data' => $this->db->insert_id()
      );
    }
    return array('error' => '创建讨论失败');
  }

  public function delete($did) {
    if (!$this->checkAuth($did)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL delete_discussion(?, ?)";
    $this->db->query($sql, array($uid, $did));
    return null;
  }

  public function recover($did) {
    if (!$this->checkAuth($did)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL recover_discussion(?, ?)";
    $this->db->query($sql, array($uid, $did));
    return null;
  }

  public function getInfo($did) {
    if (!$this->checkAuth($did)) {
      return array('error' => '权限不足');
    }

    $sql = "SELECT topic, description, `date`, deleted, name, avatar FROM Discussion d, User u WHERE d.uid = u.uid AND did = ?";
    $query = $this->db->query($sql, array($did));
    $discussion = $query->row();
    if (!isset($discussion)) {
      return array('error' => '获取信息失败');
    }

    $sql = "SELECT name, avatar, type, `date`, info FROM DiscussionEvent d, User u WHERE d.uid = u.uid AND d.did = ?";
    $query = $this->db->query($sql, array($did));
    $events = $query->result_array();

    return array(
      'error' => null,
      'data' => array(
        'discussion' => $discussion,
        'events' => $events
      )
    );
  }

  public function comment($did, $comment) {
    if (!$this->checkAuth($did)) {
      return array('error' => '权限不足');
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL comment_discussion(?, ?, ?)";
    $this->db->query($sql, array($uid, $did, $comment));
    return array('error' => null);
  }

  public function edit($did, $topic, $description) {
    if (!$this->checkAuth($did)) {
      return array('error' => '权限不足');
    }

    $sql = "UPDATE Discussion SET topic = ?, description = ? WHERE did = ?";
    $query = $this->db->query($sql, array($topic, $description, $did));

    return $query > 0 ? null : '更新讨论信息失败';
  }

  public function checkAuth($did) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM Discussion d, Project p, TeamMember tm WHERE d.did = ? AND d.pid = p.pid AND p.tid = tm.tid AND tm.uid = ?";
    $query = $this->db->query($sql, array($did, $uid));
    return $query->num_rows();
  }
}