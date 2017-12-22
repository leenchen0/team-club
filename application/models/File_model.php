<?php
class File_model extends CI_Model {
  public function __construct() {
    parent::__construct();

    $this->load->library('session');
  }

  public function saveFile($dirId, $path, $name, $size) {
    if (!$this->checkAuth($dirId)) {
      return array('error' => '权限不足');
    }

    $sql = "INSERT INTO File (dir_id, name, `path`, size) VALUES (?, ?, ?, ?)";
    $query = $this->db->query($sql, array($dirId, $name, $path, $size));
    if ($query > 0) {
      return array('error' => null, 'data' => array(
        'fid' => $this->db->insert_id(),
        'path' => $path
      ));
    }
    return array('error' => '保存文件失败');
  }

  public function createDir($parent, $dirName) {
    if (!$this->checkAuth($parent)) {
      return array('error' => '权限不足');
    }

    $sql = "INSERT INTO Directory (parent, name) VALUES (?, ?)";
    $query = $this->db->query($sql, array($parent, $dirName));
    if ($query > 0) {
      return array('error' => null, 'data' => $this->db->insert_id());
    }
    return array('error' => '创建文件夹失败');
  }

  public function deleteFile($fid) {
    $sql = "SELECT `path`, dir_id FROM File WHERE fid = ?";
    $query = $this->db->query($sql, array($fid));
    $row = $query->row();
    if (!isset($row) || !$this->checkAuth($row->dir_id)) {
      return '权限不足';
    }
    $this->deleteDiskFile($row->path);
    $sql = "DELETE FROM File WHERE fid = ?";
    $query = $this->db->query($sql, array($fid));
    return $query > 0 ? null : '删除失败';
  }

  public function deleteDir($dirId) {
    if (!$this->checkAuth($dirId)) {
      return '权限不足';
    }
    return $this->deleteAllFile($dirId);
  }

  public function deleteAllFile($dirId) {
    // 删除目录下的所有文件夹
    $sql = "SELECT dir_id FROM Directory WHERE parent = ?";
    $query = $this->db->query($sql, array($dirId));
    foreach ($query->result() as $row) {
      $this->deleteAllFile($row->dir_id);
    }
    // 删除目录下的所有文件
    $sql = "SELECT fid, `path` FROM File WHERE dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    foreach ($query->result() as $row) {
      $this->deleteDiskFile($row->path);
      $sql = "DELETE FROM File WHERE fid = ?";
      $this->db->query($sql, array($row->fid));
    }
    // 删除该目录
    $sql = "DELETE FROM Directory WHERE dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    return $query > 0 ? null : '删除失败';
  }

  public function getInfo($dirId) {
    // 获取文件夹下的文件信息
    $sql = "SELECT * FROM File WHERE dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    $files = $query->result_array();
    // 获取文件夹下的文件夹信息
    $sql = "SELECT * FROM Directory WHERE parent = ?";
    $query = $this->db->query($sql, array($dirId));
    $dirs = $query->result_array();
    // 获取当前文件夹的父文件夹信息
    $parent = $this->getParentInfo($dirId);
    if ($parent === null) {
      return array('error' => '获取文件夹信息失败');
    }
    return array(
      'error' => null,
      'data' => array(
        'files' => $files,
        'dirs' => $dirs,
        'parent' => $parent
      )
    );
  }

  public function getParentInfo($dirId) {
    if ($dirId === null) {
      return array();
    }
    $sql = "SELECT name, dir_id, parent FROM Directory WHERE dir_id = ?";
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
      'dir_id' => $row->dir_id
    ));
    return $res;
  }

  public function checkAuth($dirId) {
    $uid = $this->session->user['uid'];
    $rootDirId = $this->getRootDirId($dirId);
    if ($rootDirId === null) {
      return false;
    }
    $sql = "SELECT * FROM Project p, TeamMember tm WHERE dir_id = ? AND p.tid = tm.tid AND tm.uid = ? AND tm.accept = 1";
    $query = $this->db->query($sql, array($rootDirId, $uid));
    return $query->num_rows() > 0;
  }

  public function getRootDirId($dirId) {
    $sql = "SELECT parent FROM Directory WHERE dir_id = ?";
    $query = $this->db->query($sql, array($dirId));
    $row = $query->row();
    if (!isset($row)) {
      return null;
    }
    return $row->parent === null ? $dirId : $this->getRootDirId($row->parent);
  }

  public function deleteDiskFile($path) {
    // 检查是否存在相同文件，不存在则删除磁盘上的文件
    $sql = "SELECT * FROM File WHERE `path` = ?";
    $query = $this->db->query($sql, array($path));
    if ($query->num_rows() === 1) {
      unlink(dirname(dirname(dirname(__file__))).$path);
    }
  }
}