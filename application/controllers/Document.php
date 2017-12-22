<?php
class Document extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('User_model', 'user');
    $this->load->model('Document_model', 'doc');
  }

  public function create() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $dirId = $this->input->post('dirId');
    $docName = $this->input->post('docName');
    if (!isset($dirId) || !isset($docName)) {
      echo json_encode(array('error' => '参数错误'), JSON_UNESCAPED_UNICODE);
      return;
    }

    $res = $this->doc->create($dirId, $docName);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getDocInfo() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $docId = $this->input->get('docId');
    if (!isset($docId)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->doc->getDocInfo($docId);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getDocHistory() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $docId = $this->input->get('docId');
    if (!isset($docId)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->doc->getDocHistory($docId);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getHistoryContent() {
      if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $hid = $this->input->get('hid');
    if (!isset($hid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->doc->getHistoryContent($hid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function editDoc() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $docId = $this->input->post('docId');
    $docName = $this->input->post('docName');
    if (!isset($docId) || !isset($docName)) {
      $error = '参数错误';
    } else {
      $error = $this->doc->editDoc($docId, $docName);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function saveDoc() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $docId = $this->input->post('docId');
    $content = $this->input->post('content');
    if (!isset($docId) || !isset($content)) {
      $error = '参数错误';
    } else {
      $error = $this->doc->saveDoc($docId, $content);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function deleteDoc() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $docId = $this->input->post('docId');
    if (!isset($docId)) {
      $error = '参数错误';
    } else {
      $error = $this->doc->deleteDoc($docId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
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

    $res = $this->doc->createDir($dirId, $dirName);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
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
      $error = $this->doc->deleteDir($dirId);
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
      $res = $this->doc->getInfo($dirId);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

}