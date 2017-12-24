<?php
class User_model extends CI_Model {
  function _construct() {
    parent::_construct();

    $this->load->database();
    $this->load->library('session');
    $this->load->helper('url');
  }

  public function check_login() {
    return !!$this->session->user;
  }

  public function logout() {
    if (isset($_SESSION['user'])) {
      unset($_SESSION['user']);
    }
  }

  public function get_info() {
    return $this->session->user;
  }

  public function getOtherUserInfo($uid) {
    $sql = "SELECT uid, name, avatar, email FROM User WHERE uid = ?";
    $query = $this->db->query($sql, array($uid));
    $user = $query->row();
    if(isset($user)) {
      return $user;
    }
    return null;
  }

  public function verify_user($email, $password) {
    $sql = "SELECT uid, name, avatar, email FROM User WHERE email = ? AND password = ?";
    $query = $this->db->query($sql, array($email, substr(hash('sha256', $password), -50)));

    $user = $query->row();
    if(isset($user)) {
      $this->session->user = array(
        'uid' => $user->uid,
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $user->avatar
      );;
      return null;
    }
    return '用户名或者密码错误';
  }

  public function setAvatar($path) {
    $uid = $this->session->user['uid'];
    $sql = "UPDATE User SET avatar = ? WHERE uid = ?";
    $query = $this->db->query($sql, array($path, $uid));
    if($query > 0){
      // 更新 session 数据
      $user = $this->session->user;
      $user['avatar'] = $path;
      $this->session->user = $user;
      return null;
    }
    return '更换头像失败';
  }

  public function updateInfo($name, $email) {
    $user = $this->session->user;
    $sql = "CALL update_user_info(?, ?, ?)";
    $row = $this->db->query($sql, array($user['uid'], $name, $email))->row();
    if (isset($row) && $row->res === '1') {
      // 更新 session 数据
      $user = $this->session->user;
      $user['name'] = $name;
      $user['email'] = $email;
      $this->session->user = $user;
      return null;
    }
    return '邮箱已被注册';
  }

  public function register($name, $email, $password) {
    $sql = "CALL register(?, ?, ?)";
    $row = $this->db->query($sql, array($name, $email, substr(hash('sha256', $password), -50)))->row();
    if(isset($row) && $row->uid > 0) {
      $this->session->user = array(
        'uid' => strval($row->uid),
        'name' => $name,
        'email' => $email,
        'avatar' => '/static/avatar/avatar.jpg'
      );
      return null;
    }
    return '邮箱已被注册';
  }
}
?>