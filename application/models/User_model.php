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
      $this->session->user['avatar'] = $path;
      return null;
    }
    return '更换头像失败';
  }

  public function updateInfo($name, $email) {
    $user = $this->session->user;
    if ($user['email'] !== $email && !$this->checkEmail($email)) {
      return '邮箱已被注册';
    }

    $sql = "UPDATE User SET name = ?, email = ? WHERE uid = ?";
    $query = $this->db->query($sql, array($name, $email, $user['uid']));
    if ($query > 0) {
      // 更新 session 数据
      $this->session->user['name'] = $name;
      $this->session->user['email'] = $email;
      return null;
    }
    return '更新名字失败';
  }

  public function checkEmail($email) {
    $sql = "SELECT email FROM User WHERE email = ?";
    $query = $this->db->query($sql, array($email));
    return $query->num_rows() <= 0;
  }

  public function register($name, $email, $password) {
    if (!$this->checkEmail($email)) {
      return '邮箱已被注册';
    }

    $sql = "INSERT INTO User (name, email, password) VALUES (?, ?, ?)";
    $query = $this->db->query($sql, array($name, $email, substr(hash('sha256', $password), -50)));
    if($query > 0) {
      $this->session->user = array(
        'uid' => $this->db->insert_id(),
        'name' => $name,
        'email' => $email,
        'avatar' => '/static/avatar/avatar.jpg'
      );
      return null;
    }
    return '注册失败';
  }
}
?>