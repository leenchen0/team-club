<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
  public function __construct() {
    parent::__construct();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");
    $this->load->model('user_model', 'user');
  }

  public function login() {
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    $error = $this->user->verify_user($email, $password);
    if ($error === null) {
      $res = array(
        'error' => null,
        'data' => $this->user->get_info()
      );
    } else {
      $res = array(
        'error' => $error
      );
    }
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function logout() {
    $this->user->logout();
    echo json_encode(array('error' => null), JSON_UNESCAPED_UNICODE);
  }

  public function get_info() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }
    $res = array(
      'error' => null,
      'data' => $this->user->get_info()
    );
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function avatar() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $file = $_FILES['avatar'];
    if (!is_uploaded_file($file['tmp_name'])) {
      return;
    }

    $uploadDir = '/static/avatar/';
    $uploadFileName = md5_file($file['tmp_name']);
    if (!$uploadFileName) {
      $error = $file['error'];
    } else if (move_uploaded_file($file['tmp_name'], dirname(dirname(dirname(__file__))).$uploadDir.$uploadFileName)) {
      $path = '/static/avatar/'.$uploadFileName;
      $error = $this->user->setAvatar($path);
    } else {
      $error = '上传头像失败';
    }

    if ($error === null) {
      $res = array('error' => null, 'data' => $path);
    } else {
      $res = array('error' => $error);
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

  public function updateInfo() {
    if (!$this->user->check_login())  {
      echo json_encode(array(
        'code' => 10011
      ), JSON_UNESCAPED_UNICODE);
      return;
    }

    $name = $this->input->post('name');
    $email = $this->input->post('email');

    if (empty($name) || empty($email)) {
      $error = '参数错误';
    } else {
      $error = $this->user->updateInfo($name, $email);
    }

    echo json_encode(array('error' => $error), JSON_UNESCAPED_UNICODE);
  }

  public function register() {
    $name = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    if (empty($name) || empty($email) || empty($password) || strlen($password) < 6) {
      $error = '参数错误';
    } else {
      $error = $this->user->register($name, $email, $password);
    }

    $res = array(
      'error' => $error,
      'data' => $this->user->get_info()
    );
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }
}
?>
