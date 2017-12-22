<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('User_model', 'user');
    $this->load->model('Team_model', 'team');
  }

  public function teamList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $res = $this->team->getTeams();
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function createTeam() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $name = $this->input->post('name');
    $res = empty($name) ? array('error' => '参数错误') : $this->team->newTeam($name);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getDynamic() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    if (empty($tid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->team->getDynamic($tid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function projects(){
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    if (empty($tid)) {
      $res = array('error' => '参数错误');
    } else {
      $data = $this->team->getProjects($tid);
      $res = array(
        'error' => null,
        'data' => $data
      );
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function createProject() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    $name = $this->input->post('name');
    if (empty($tid) || empty($name)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->team->newProject($tid, $name);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getMembers() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    if (!isset($tid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->team->getMembers($tid);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getAcceptedMembers() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    if (!isset($tid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = array('error' => null, 'data' => $this->team->getAcceptedMembers($tid));
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function deleteTeam() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->post('tid');
    if (!isset($tid)) {
      $error = '参数错误';
    } else {
      $error = $this->team->deleteTeam($tid);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function setName() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    $name = $this->input->post('name');

    if (!isset($tid) || !isset($name)) {
      $error = '参数错误';
    } else {
      $error = $this->team->changeTeamName($tid, $name);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function applyTeam() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    if (!isset($tid)) {
      $error = '参数错误';
    } else {
      $error = $this->team->applyTeam($tid);
    }
    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function rejectApply() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    $uid = $this->input->post('uid');
    if (!isset($tid) || !isset($uid)) {
      $error = '参数错误';
    } else {
      $error = $this->team->rejectApply($tid, $uid);
    }
    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function acceptApply() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    $uid = $this->input->post('uid');
    if (!isset($tid) || !isset($uid)) {
      $error = '参数错误';
    } else {
      $error = $this->team->acceptApply($tid, $uid);
    }
    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function taskUnfinished() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    $uid = $this->input->get('uid');

    if (!isset($tid) || !isset($uid)) {
      $res = array('error' => '参数错误');
    } else {
      $user = $this->user->getOtherUserInfo($uid);
      if ($user === null) {
        $res = array('error' => '用户不存在');
      } else {
        $tasks = $this->team->getUnfinishedTask($uid, $tid);
        $res = array('error' => null, 'data' => array(
          'user' => $user,
          'tasks' => $tasks
        ));
      }
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function taskFinished() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $tid = $this->input->get('tid');
    $uid = $this->input->get('uid');

    if (!isset($tid) || !isset($uid)) {
      $res = array('error' => '参数错误');
    } else {
      $user = $this->user->getOtherUserInfo($uid);
      if ($user === null) {
        $res = array('error' => '用户不存在');
      } else {
        $tasks = $this->team->getFinishedTask($uid, $tid);
        $res = array('error' => null, 'data' => array(
          'user' => $user,
          'tasks' => $tasks
        ));
      }
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }
}
?>