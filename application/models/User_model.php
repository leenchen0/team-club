<?php
class User_model extends CI_Model{
	function _construct(){
		parent::_construct();
		$this->load->database();
	}
	public function verify_users($email,$password){
		$this->load->database();
		$sql="select uid,name,avatar from User where email='".$email."' and password='".$password."';";
		$this->db->limit(1);
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$row = $query->row(1);
			$error=null;
			$data=array(
				'name'=>$row ->name,
				'avatar'=>$row->avatar
			);
			$result=array(
				'error'=>null,
				'data'=>$data
			);
			$sessiondata=array(
					'uid'=>$row->uid,
					'uname'=>$row->name
				);
			$this->session->set_userdata($sessiondata);
		}
		else{
			$error='用户名或者密码错误';
			$result=array(
					'error'=>$error,
					'data'=>null
				);
		}
		return $result;  
	} 
	public function setname($name){
		$id=$this->session->userdata('uid');
		$sql="update User set name='".$name."' where uid=".$id.";";
		$query=$this->db->query($sql);
		if($query>0){
			$result=array(
				'error'=>null
				);
		}
		else{
			$result=array(
				'error'=>'更新名字失败'
				);
		}
		return $result;
	}
	public function setpassword($oldPassword,$password){
		if($oldPassword==$password){
			$result=array(
				'error'=>'新密码和旧密码相同'
				);
		}else{
			$id=$this->session->userdata('uid');
			$sql="select password from User where uid=".$id;
			$query=$this->db->query($sql);
			$row = $query->row(1);
			if($row->password==$oldPassword){
				$sql="update User set password='".$password."' where uid=".$id;
				$query=$this->db->query($sql);
				if($query>0){
					$result=array(
						'error'=>null
					);
				}else{
					$result=array(
						'error'=>'更新密码失败'
					);
				}
			}else{
				$result=array(
					'error'=>'旧密码不对'
				);
			}
		}
		return $result;
	}
	public function setavatar($path){
		$uid=$this->session->userdata('uid');
		$sql="update User set avatar='".$path."' where uid=".$uid.";";
		$query=$this->db->query($sql);
		if($query>0){
        	$data = array(
            	'error' =>null,
            	'data' => $path
        	);
        }else{
        	$data = array(
            	'error' =>'更新头像失败'
        	);
        }
        return $data;
	}
	public function register($name,$email,$password){
		$sql="select email from User where email='".$email."';";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$res=array(
				'error'=>'邮箱已经被注册'
			);
			return $res;
		}
		$sql="insert into User (name,email,password) values('".$name."','".$email."','".$password."')";
		$query=$this->db->query($sql);
		if($query>0){
			$res=array(
				'error'=>null
			);
			$sessiondata=array(
				'uid'=>$row->uid,
				'uname'=>$row->name
			);
			$this->session->set_userdata($sessiondata);
			return $res;
		}
		else{
			$res=array(
				'error'=>'注册失败'
			);
			return $res;
		}
	}
}
?>