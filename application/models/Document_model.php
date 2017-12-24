<?php
class Document_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->load->library('session');
  }

  public function create($dirId, $docName) {
    $uid = $this->session->user['uid'];
    $sql = "CALL create_doc(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $dirId, $docName))->row();
    if (isset($row) && $row->docId > 0) {
      return array('error' => null, 'data' => $row->docId);
    }
    return array('error' => '权限不足');
  }

  public function editDoc($docId, $docName) {
    $uid = $this->session->user['uid'];
    $sql = "CALL edit_doc(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $docId, $docName))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function getDocInfo($docId) {
    if (!$this->checkDocAuth($docId)) {
      return array('error' => '权限不足');
    }

    $sql = "SELECT name, content, edit_time FROM Document WHERE doc_id = ?";
    $query = $this->db->query($sql, array($docId));
    $row = $query->row();
    if (!isset($row)) {
      return array('error' => '读取文档失败');
    }
    return array('error' => null, 'data' => $row);
  }

  public function getDocHistory($docId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL get_doc_history(?, ?)";
    $query = $this->db->query($sql, array($uid, $docId));
    return array('error' => null, 'data' => $query->result_array());
  }

  public function getHistoryContent($hid) {
    $uid = $this->session->user['uid'];
    $sql = "CALL get_history_content(?, ?)";
    $row = $this->db->query($sql, array($uid, $hid))->row();
    if (isset($row)) {
      return array('error' => null, 'data' => $row);
    }
    return array('error' => '权限不足');
  }

  public function saveDoc($docId, $content) {
    $uid = $this->session->user['uid'];
    $sql = "CALL update_document(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $docId, $content))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function deleteDoc($docId) {
    $uid = $this->session->user['uid'];
    $sql = "CALL delete_document(?, ?)";
    $row = $this->db->query($sql, array($uid, $docId))->row();
    if (isset($row) && $row->res === '1') {
      return null;
    }
    return '权限不足';
  }

  public function createDir($parent, $dirName) {
    $uid = $this->session->user['uid'];
    $sql = "CALL create_doc_dir(?, ?, ?)";
    $row = $this->db->query($sql, array($uid, $parent, $dirName))->row();
    if (isset($row) && $row->dirId > 0) {
      return array('error' => null, 'data' => $row->dirId);
    }
    return array('error' => '权限不足');
  }

  public function deleteDir($dirId) {
    if (!$this->checkAuth($dirId)) {
      return '权限不足';
    }
    return $this->deleteAllFile($dirId);
  }

  public function deleteAllFile($docDirId) {
    // 删除目录下的所有文件夹
    $sql = "SELECT doc_dir_id FROM DocumentDir WHERE parent = ?";
    $query = $this->db->query($sql, array($docDirId));
    foreach ($query->result() as $row) {
      $this->deleteAllFile($row->doc_dir_id);
    }
    // 删除目录下的所有文件
    $sql = "DELETE FROM Document WHERE doc_dir_id = ?";
    $query = $this->db->query($sql, array($docDirId));
    // 删除该目录
    $sql = "DELETE FROM DocumentDir WHERE doc_dir_id = ?";
    $query = $this->db->query($sql, array($docDirId));
    return $query > 0 ? null : '删除失败';
  }

  public function getInfo($dirId) {
    // 获取文档夹下的文档信息
    $sql = "SELECT * FROM Document WHERE doc_dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    $documents = $query->result_array();
    // 获取文档夹下的文档夹信息
    $sql = "SELECT * FROM DocumentDir WHERE parent = ?";
    $query = $this->db->query($sql, array($dirId));
    $dirs = $query->result_array();
    // 获取当前文档夹的父文档夹信息
    $parent = $this->getParentInfo($dirId);
    if ($parent === null) {
      return array('error' => '获取文档夹信息失败');
    }
    return array(
      'error' => null,
      'data' => array(
        'docs' => $documents,
        'dirs' => $dirs,
        'parent' => $parent
      )
    );
  }

  public function getParentInfo($dirId) {
    if ($dirId === null) {
      return array();
    }
    $sql = "SELECT name, doc_dir_id, parent FROM DocumentDir WHERE doc_dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    $row = $query->row();
    if (!isset($row)) {
      return null;
    }
    $res = $this->getParentInfo($row->parent);
    if ($res === null) {
      return null;
    }
    array_push($res, array(
      'name' => $row->name,
      'doc_dir_id' => $row->doc_dir_id
    ));
    return $res;
  }

  public function checkDocAuth($docId) {
    $sql = "SELECT doc_dir_id FROM Document WHERE doc_id = ?";
    $query = $this->db->query($sql, array($docId));
    $row = $query->row();
    if (!isset($row)) {
      return false;
    }
    return $this->checkAuth($row->doc_dir_id);
  }

  public function checkAuth($dirId) {
    $uid = $this->session->user['uid'];
    $rootDirId = $this->getRootDirId($dirId);
    if ($rootDirId === null) {
      return false;
    }
    $sql = "SELECT * FROM Project p, TeamMember tm WHERE doc_dir_id = ? AND p.tid = tm.tid AND tm.uid = ? AND tm.accept = 1";
    $query = $this->db->query($sql, array($rootDirId, $uid));
    return $query->num_rows() > 0;
  }

  public function getRootDirId($dirId) {
    $sql = "SELECT parent FROM DocumentDir WHERE doc_dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    $row = $query->row();
    if (!isset($row)) {
      return null;
    }
    return $row->parent === null ? $dirId : $this->getRootDirId($row->parent);
  }

}
?>