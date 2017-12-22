<?php
class Project extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('Project_model', 'project');
    $this->load->model('User_model', 'user');
  }

  public function setIcon() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->get('pid');
    $icon = $this->input->post('icon');

    if (!isset($pid) || !isset($icon)) {
      $error = '参数错误';
    } else {
      $error = $this->project->setIcon($pid, $icon);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function setColor() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->get('pid');
    $color = $this->input->post('color');

    if (!isset($pid) || !isset($color)) {
      $error = '参数错误';
    } else {
      $error = $this->project->setColor($pid, $color);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function getSettingInfo() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->get('pid');
    if (!isset($pid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->project->getSettingInfo($pid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function saveSettingInfo() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->post('pid');
    $isPrivate = $this->input->post('isPrivate');
    $name = $this->input->post('name');
    $description = $this->input->post('description');
    if (!isset($pid) || !isset($isPrivate) || !isset($name) || !isset($description)) {
      $error = '参数错误';
    } else {
      $error = $this->project->saveSettingInfo($pid, $isPrivate, $name, $description);
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

    $pid = $this->input->get('pid');
    if (!isset($pid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->project->getInfo($pid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function finishedTasks() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->get('pid');
    if (!isset($pid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->project->finishedTasks($pid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function archivedTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->get('pid');
    if (!isset($pid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->project->archivedTaskList($pid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }
}