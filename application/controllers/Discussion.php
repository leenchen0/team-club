<?php
class Discussion extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('User_model', 'user');
    $this->load->model('Discussion_model', 'discussion');
  }

  public function create() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->post('pid');
    $topic = $this->input->post('topic');

    if (!isset($pid) || !isset($topic)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->discussion->create($pid, $topic);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function delete() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $did = $this->input->post('did');

    if (!isset($did)) {
      $error = '参数错误';
    } else {
      $error = $this->discussion->delete($did);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function recover() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $did = $this->input->post('did');

    if (!isset($did)) {
      $error = '参数错误';
    } else {
      $error = $this->discussion->recover($did);
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

    $did = $this->input->get('did');

    if (!isset($did)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->discussion->getInfo($did);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function comment() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $did = $this->input->post('did');
    $comment = $this->input->post('comment');

    if (!isset($did) || !isset($comment)) {
      $error ='参数错误';
    } else {
      $error = $this->discussion->comment($did, $comment);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function edit() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $did = $this->input->post('did');
    $topic = $this->input->post('topic');
    $description = $this->input->post('description');

    if (!isset($did) || !isset($topic) || !isset($description)) {
      $error = '参数错误';
    } else {
      $error = $this->discussion->edit($did, $topic, $description);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }
}
?>