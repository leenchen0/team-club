<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function checklogin(){
		if(isset($_SESSION['uid'])){
        	return true;   
        }else{
        	return false;
        }
	}
	public function index(){
		//测试上传表单
		$this->load->view('upload_form');
	}
	/*
	*登录
	*/
	public function login(){
		$email=$_GET['email'];
		$password=$_GET['password'];
		$this->load->model('user_model');
		$res=$this->user_model->verify_users($email,$password);
		header('content-type:application/json;charset=utf8');    
		echo json_encode($res);
	}
	/*
	*登出
	*/
	public function logout(){  
     	$this->session->sess_destroy();
        $this->load->view('welcome_message');  
    }
    /*
    *修改名字
    */
    public function name(){
    	if($this->checklogin()){
			$name=$_GET['name'];
			$this->load->model('user_model');
			$res=$this->user_model->setname($name);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
    }
    /*
    *修改头像
    */
    public function avatar(){
    	if($this->checklogin()){
			$config['upload_path']      = './static/';
        	$config['allowed_types']    = 'gif|jpg|png';
        	$config['max_size']     = 20000;
        	$config['max_width']        = 2000;
        	$config['max_height']       = 2000;
        	 $config['file_name'] = md5(uniqid(microtime()));
        	$this->load->library('upload', $config);
        	if (!$this->upload->do_upload('avatar'))
        	{
            	$error = array('error' => '修改头像失败');
           		header('content-type:application/json;charset=utf8');    
				echo json_encode($error);
        	}
        	else
        	{
        		$path='/static/'.$this->upload->data('file_name');
        		$this->load->model('user_model');
				$res=$this->user_model->setavatar($path);
           		header('content-type:application/json;charset=utf8');    
				echo json_encode($res);
        	}
        }else{
        	$this->load->view('welcome_message'); 
        }
    }

    /*
    *修改密码
    */
    public function password(){
    	if($this->checklogin()){
			$oldPassword=$_GET['oldPassword'];
			$password=$_GET['password'];
			$this->load->model('user_model');
			$res=$this->user_model->setpassword($oldPassword,$password);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
    }
    /*
    *注册
    */
    public function register(){
    	$name=$this->input->post('name');
    	$email=$this->input->post('email');
    	$password=$this->input->post('password');
    	$this->load->model('user_model');
    	$res=$this->user_model->register($name,$email,$password);
    	header('content-type:application/json;charset=utf8');    
		echo json_encode($res);
    }
}
?>
