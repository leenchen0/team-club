<?php
class File extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('User_model', 'user');
    $this->load->model('File_model', 'file');
  }

  public function upload() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $file = $_FILES['file'];
    if (!is_uploaded_file($file['tmp_name'])) {
      return;
    }

    $dirId = $this->input->post('dirId');
    if (!isset($dirId)) {
      echo json_encode(array('error' => '参数错误'), JSON_UNESCAPED_UNICODE);
      return;
    }

    $uploadDir = '/static/file/';
    $uploadFileName = md5_file($file['tmp_name']);
    if (!$uploadFileName) {
      $res = array('error' => $file['error']);
    } else if (move_uploaded_file($file['tmp_name'], dirname(dirname(dirname(__file__))).$uploadDir.$uploadFileName)) {
      $path = '/static/file/'.$uploadFileName;
      $res = $this->file->saveFile($dirId, $path, $file['name'], $file['size']);
      // 保存文件失败则同时删除磁盘上的文件
      if ($res['error']) {
        unlink(dirname(dirname(dirname(__file__))).$uploadDir.$uploadFileName);
      }
    } else {
      $res = array('error' => '上传头像失败');
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function createDir() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $dirName = $this->input->post('dirName');
    $dirId = $this->input->post('dirId');
    if (!isset($dirId) || !isset($dirName)) {
      echo json_encode(array('error' => '参数错误'), JSON_UNESCAPED_UNICODE);
      return;
    }

    $res = $this->file->createDir($dirId, $dirName);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function deleteFile() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $fid = $this->input->post('fid');
    if (!isset($fid)) {
      $error = '参数错误';
    } else {
      $error = $this->file->deleteFile($fid);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function deleteDir() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $dirId = $this->input->post('dirId');
    if (!isset($dirId)) {
      $error = '参数错误';
    } else {
      $error = $this->file->deleteDir($dirId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function getInfo() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $dirId = $this->input->get('dirId');
    if (!isset($dirId)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->file->getInfo($dirId);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }
}