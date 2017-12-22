<?php
class Task extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('Task_model', 'task');
    $this->load->model('User_model', 'user');
  }

  public function createTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->post('pid');
    $name = $this->input->post('name');

    if (!isset($name) || !isset($pid)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->task->createTaskList($pid, $name);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function getTaskListInfo() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->get('taskListId');

    if (!isset($taskListId)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->task->getTaskListInfo($taskListId);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function deleteTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->post('taskListId');

    if (!isset($taskListId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->deleteTaskList($taskListId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function recoverTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->post('taskListId');

    if (!isset($taskListId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->recoverTaskList($taskListId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function editTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->post('taskListId');
    $taskListName = $this->input->post('taskListName');

    if (!isset($taskListId) || !isset($taskListName)) {
      $error = '参数错误';
    } else {
      $error = $this->task->editTaskList($taskListId, $taskListName);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function archivedTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->post('taskListId');

    if (!isset($taskListId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->archivedTaskList($taskListId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function unArchivedTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->post('taskListId');

    if (!isset($taskListId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->unArchivedTaskList($taskListId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function commentTaskList() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskListId = $this->input->post('taskListId');
    $comment = $this->input->post('comment');

    if (!isset($taskListId) || !isset($comment)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->task->commentTaskList($taskListId, $comment);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function create() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $pid = $this->input->post('pid');
    $taskListId = $this->input->post('taskListId');
    $name = $this->input->post('name');

    if (!isset($pid) || !isset($name)) {
      $res = array('error' => '参数错误');
    } else {
      if (!isset($taskListId) || $taskListId === 'null') {
        $taskListId = null;
      }

      $res = $this->task->create($pid, $taskListId, $name);
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

    $taskId = $this->input->post('taskId');

    if (!isset($taskId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->delete($taskId);
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

    $taskId = $this->input->post('taskId');

    if (!isset($taskId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->recover($taskId);
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

    $taskId = $this->input->post('taskId');
    $name = $this->input->post('name');
    $description = $this->input->post('description');
    $private = $this->input->post('private');

    if (!isset($taskId) || !isset($name)) {
      $error = '参数错误';
    } else {
      $error = $this->task->edit($taskId, $name, $description, $private);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function allocating() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskId = $this->input->post('taskId');
    $uid = $this->input->post('uid');

    if (!isset($uid) || !isset($taskId)) {
      $error = '参数错误';
    } else {
      if ($uid === '') {
        $uid = null;
      }
      $error = $this->task->allocating($taskId, $uid);
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

    $taskId = $this->input->get('taskId');
    if (!isset($taskId)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->task->getInfo($taskId);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function begin() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskId = $this->input->post('taskId');

    if (!isset($taskId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->begin($taskId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function pause() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskId = $this->input->post('taskId');

    if (!isset($taskId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->pause($taskId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function markFinished() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskId = $this->input->post('taskId');

    if (!isset($taskId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->markFinished($taskId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function markUnfinished() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskId = $this->input->post('taskId');

    if (!isset($taskId)) {
      $error = '参数错误';
    } else {
      $error = $this->task->markUnfinished($taskId);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function comment() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $taskId = $this->input->post('taskId');
    $comment = $this->input->post('comment');

    if (!isset($taskId) || !isset($comment)) {
      $res = array('error' => '参数错误');
    } else {
      $res = $this->task->comment($taskId, $comment);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }
}
?>