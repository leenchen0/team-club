<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {
	public function checklogin(){
		if(isset($_SESSION['uid'])){
        	return true;   
        }else{
        	return false;
        }
	}
	public function index(){
		$this->load->view('welcome_message');
	}
	public function taskunfinish(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$user_id=$_GET['user_id'];
			$this->load->model('team_model');
			$res=$this->team_model->getUnfinishedTask($team_id,$user_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	public function taskfinished(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$user_id=$_GET['user_id'];
			$this->load->model('team_model');
			$res=$this->team_model->getFinishedTask($team_id,$user_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	public function teams(){
		if($this->checklogin()){
			$this->load->model('team_model');
			$res=$this->team_model->getTeams();
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	public function newteam(){
		if($this->checklogin()){
			$name=$this->input->post('name');
			$this->load->model('team_model');
			$res=$this->team_model->newTeam($name);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	public function applyteam(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$this->load->model('team_model');
			$res=$this->team_model->applyteam($team_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	public function member(){
		if($this->checklogin()){
			$aid=$_GET['aid'];
			$this->load->model('team_model');
			$res=$this->team_model->agreejoin($aid);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	public function setteamname(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$name=$this->input->post('name');
			$this->load->model('team_model');
			$res=$this->team_model->changeteamname($team_id,$name);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message'); 
		}
	}
	/*
	*删除团队
	*/
	public function deleteteam($team_id){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$this->load->model('team_model');
			$res=$this->team_model->deleteTeam($team_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message');
		}
	}
	/*
	*删除成员
	*/
	public function deletemember(){

	}
	public function getprojects(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$this->load->model('team_model');
			$res=$this->team_model->getprojects($team_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message');
		}
	}
	public function getteamname(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$this->load->model('team_model');
			$res=$this->team_model->getteamname($team_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message');
		}
	}
	public function getmember(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$this->load->model('team_model');
			$res=$this->team_model->getmember($team_id);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message');
		}
	}
	public function newproject(){
		if($this->checklogin()){
			$team_id=$_GET['team_id'];
			$name=$this->input->post('name');
			$description=$this->input->post('description');
			$private=$this->input->post('private');
			$this->load->model('team_model');
			$res=$this->team_model->newproject($team_id,$name,$description,$private);
			header('content-type:application/json;charset=utf8');    
			echo json_encode($res);
		}else{
			$this->load->view('welcome_message');
		}
	}
}
?>