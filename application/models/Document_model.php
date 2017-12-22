<?php
class Document_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->load->library('session');
  }

  public function create($dirId, $docName) {
    if (!$this->checkAuth($dirId)) {
      return array('error' => '权限不足');
    }

    $sql = "INSERT INTO Document (doc_dir_id, name, content) VALUES (?, ?, '')";
    $query = $this->db->query($sql, array($dirId, $docName));
    if ($query > 0) {
      return array('error' => null, 'data' => $this->db->insert_id());
    }
    return array('error' => '创建文件失败');
  }

  public function editDoc($docId, $docName) {
    if (!$this->checkDocAuth($docId)) {
      return '权限不足';
    }

    $sql = "UPDATE Document SET name = ? WHERE doc_id = ?";
    $query = $this->db->query($sql, array($docName, $docId));
    if ($query > 0) {
      return null;
    }
    return '保存文档失败';
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
    if (!$this->checkDocAuth($docId)) {
      return array('error' => '权限不足');
    }

    $sql = "SELECT hid, name, `date` FROM DocumentEditHistory d, User u WHERE u.uid = d.uid AND doc_id = ? ORDER BY `date` DESC";
    $query = $this->db->query($sql, array($docId));
    return array('error' => null, 'data' => $query->result_array());
  }

  public function getHistoryContent($hid) {
    $sql = "SELECT `date`, content, doc_id FROM DocumentEditHistory d WHERE hid = ?";
    $query = $this->db->query($sql, array($hid));
    $row = $query->row();
    if (!isset($row) || !$this->checkDocAuth($row->doc_id)) {
      return array('error' => '权限不足');
    }
    return array('error' => null, 'data' => $row);
  }

  public function saveDoc($docId, $content) {
    if (!$this->checkDocAuth($docId)) {
      return '权限不足';
    }

    $uid = $this->session->user['uid'];
    $sql = "CALL update_document(?, ?, ?)";
    $this->db->query($sql, array($uid, $docId, $content));
    return null;
  }

  public function deleteDoc($docId) {
    if (!$this->checkDocAuth($docId)) {
      return '权限不足';
    }

    $sql = "DELETE FROM Document WHERE doc_id = ?";
    $query = $this->db->query($sql, array($docId));
    if ($query > 0) {
      return null;
    }
    return '删除失败';
  }

  public function createDir($parent, $dirName) {
    if (!$this->checkAuth($parent)) {
      return array('error' => '权限不足');
    }

    $sql = "INSERT INTO DocumentDir (parent, name) VALUES (?, ?)";
    $query = $this->db->query($sql, array($parent, $dirName));
    if ($query > 0) {
      return array('error' => null, 'data' => $this->db->insert_id());
    }
    return array('error' => '创建文档夹失败');
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