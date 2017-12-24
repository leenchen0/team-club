<?php
class Discussion_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->load->library('session');
    $this->load->model('Project_model', 'project');
  }

  public function create($pid, $topic) {
    $uid = $this->session->user['uid'];
    $sql = "CALL create_discussion(?, ?, ?)";
    $query = $this->db->query($sql, array($uid, $pid, $topic));
    $row = $query->row();
    if (isset($row) && $row->did > 0) {
      return array(
        'error' => null,
        'data' => $row->did
      );
    }
    return array('error' => '权限不足');
  }

  public function delete($did) {
    $uid = $this->session->user['uid'];
    $sql = "CALL delete_discussion(?, ?)";
    $row = $this->db->query($sql, array($uid, $did))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function recover($did) {
    $uid = $this->session->user['uid'];
    $sql = "CALL recover_discussion(?, ?)";
    $row = $this->db->query($sql, array($uid, $did))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
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
    $uid = $this->session->user['uid'];
    $sql = "CALL comment_discussion(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $did, $comment))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function edit($did, $topic, $description) {
    $uid = $this->session->user['uid'];
    $sql = "CALL edit_discussion(?, ?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $did, $topic, $description))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function checkAuth($did) {
    $uid = $this->session->user['uid'];
    $sql = "SELECT * FROM Discussion d, Project p, TeamMember tm WHERE d.did = ? AND d.pid = p.pid AND p.tid = tm.tid AND tm.uid = ?";
    $query = $this->db->query($sql, array($did, $uid));
    return $query->num_rows();
  }
}