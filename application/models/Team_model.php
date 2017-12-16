<?php
class Team_model extends CI_Model{
	function _construct(){
		parent::_construct();
		$this->load->database();
	}
	public function  getUnfinishedTask($team_id,$user_id){
		$sql="select name,email,avatar from User where uid=".$user_id.";";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$row = $query->row(1);
        	$user = array(
            	'name' =>$row->name,
            	'email'=>$row->email,
            	'avatar' => $row->avatar
        	);
        	$sql=" select t.task_id,t.name as name,p.name as projectname from Task t inner join Project p where t.uid=".$user_id." and t.pid=p.pid and p.tid=".$team_id." and t.finished is null order by projectname desc;";
        	$query=$this->db->query($sql);
        	$result=array();
        	foreach ($query->result_array() as $row){
        		$temp=array(
        			'task_id'=>$row['task_id'],
        			'name'=>$row['name'],
        			'projectname'=>$row['projectname']
        			);
        		array_push($result, $temp);
			}
			$data=array(
        			'user'=>$user,
        			'tasks'=>$result
        		);
			$res=array(
					'error'=>null,
					'data'=>$data
				);
        }else{
        	$res = array(
            	'error' =>'用户不存在'
        	);
        }
        return $res;
	}
	public function  getFinishedTask($team_id,$user_id){
		$sql="select name,email,avatar from User where uid=".$user_id.";";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$row = $query->row(1);
        	$user = array(
            	'name' =>$row->name,
            	'email'=>$row->email,
            	'avatar' => $row->avatar
        	);
        	$sql=" select t.task_id,t.name as name,p.name as projectname from Task t inner join Project p where t.uid=".$user_id." and t.pid=p.pid and p.tid=".$team_id." and isnull(t.finished) is false order by projectname desc;";
        	$query=$this->db->query($sql);
        	$result=array();
        	foreach ($query->result_array() as $row){
        		$temp=array(
        			'task_id'=>$row['task_id'],
        			'name'=>$row['name'],
        			'projectname'=>$row['projectname']
        			);
        		array_push($result, $temp);
			}
			$data=array(
        			'user'=>$user,
        			'tasks'=>$result
        		);
			$res=array(
					'error'=>null,
					'data'=>$data
				);
        }else{
        	$res = array(
            	'error' =>'用户不存在'
        	);
        }
        return $res;
	}
	public function getTeams(){
		$uid=$this->session->userdata('uid');
		$sql="select tm.tid ,t.name from TeamMember tm inner join Team t where tm.uid=".$uid." and tm.tid=t.tid;";
		$query=$this->db->query($sql);
        $result=array();
        foreach ($query->result_array() as $row){
        	$temp=array(
        			'tid'=>$row['tid'],
        			'name'=>$row['name']
        		);
        	array_push($result, $temp);
        }
        $res=array(
        		'error'=>null,
        		'data'=>$result
        	);
        return $res;
	}
	public function newTeam($name){
		$uid=$this->session->userdata('uid');
		$sql="insert into Team (uid,name) values(".$uid.",'".$name."');";
		$query=$this->db->query($sql);
		$id=$this->db->insert_id();
		if($query>0){
			$sql="insert into TeamMember values(".$uid.",".$id.");";
			$query=$this->db->query($sql);
			$res=array(
					'error'=>null,
					'data'=>$id
				);
			return $res;
		}else{
			$res=array(
					'error'=>'创建团队失败',
					'data'=>null
				);
			return $res;
		}
	}
	public function applyteam($team_id){
		$uid=$this->session->userdata('uid');
		$sql="insert into Apply (uid,tid) values(".$uid.",".$team_id.");";
		$query=$this->db->query($sql);
		if($query>0){
			$res=array(
					'error'=>null
				);
			return $res;
		}else{
			$res=array(
					'error'=>'加入团队失败'
				);
			return $res;
		}
	}
	public function agreejoin($aid){
		$sql="select uid,tid from Apply where aid=".$aid.";";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$row = $query->row(1);
			$uid=$row->uid;
			$tid=$row->tid;
			$sql="delete from Apply where aid=".$aid;
			$this->db->query($sql);
			$sql="insert into TeamMember (uid,tid) values(".$uid.",".$tid.");";
			$this->db->query($sql);
			$res=array(
					'error'=>null
				);
			return $res;
		}else{
			$res=array(
					'error'=>'操作失败'
				);
			return $res;
		}
	}
	public function changeteamname($tid,$name){
		$uid=$this->session->userdata('uid');
		$sql="select * from Team where tid=".$tid." and uid=".$uid.";";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$sql="update Team set name='".$name."' where tid=".$tid." and uid=".$uid.";";
			$query=$this->db->query($sql);
			if($query>0){
				$res=array(
					'error'=>null
				);
			return $res;
			}else{
				$res=array(
					'error'=>'修改团队名字失败'
				);
			return $res;
			}
		}else{
			$res=array(
					'error'=>'你没有修改权限'
				);
			return $res;
		}
	}
	/*
	*删除团队
	*/
	public function deleteTeam($t_id){
		$uid=$this->session->userdata('uid');
		$sql="select * from Team where tid=".$tid." and uid=".$uid.";";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$sql="";
		}else{
			$sql="select * from Team where tid=".$tid." and uid=".$uid.";";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
			
			}else{
			
			}
		}
	}
	/*
	*删除成员
	*/
	public function deleteMember($tid,$uid){

	}
	public function getprojects($tid){
		$sql="select pid,name,icon,color from Project where tid=".$tid.";";
		$query=$this->db->query($sql);
		$result=array();
        foreach ($query->result_array() as $row){
        	$temp=array(
        			'pid'=>$row['pid'],
        			'name'=>$row['name'],
        			'icon'=>$row['icon'],
        			'color'=>$row['color']
        		);
        	array_push($result, $temp);
        }
        $res=array(
        		'error'=>null,
        		'data'=>$result
        	);
        return $res;
	}
	public function getteamname($tid){
		$sql="select name from Team where tid=".$tid.";";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$row = $query->row(1);
			$result=array(
					'error'=>null,
					'data'=>$row->name
				);
			return $result;
		}else{
			$result=array(
					'error'=>'没有该团队名字'
				);
			return $result;
		}
	}
	public function getmember($tid){
		$sql="select u.* from TeamMember tm inner join User u where tm.tid=".$tid." and tm.uid=u.uid;";
		$query=$this->db->query($sql);
		$result=array();
        foreach ($query->result_array() as $row){
        	$temp=array(
        			'uid'=>$row['uid'],
        			'name'=>$row['name'],
        			'avatar'=>$row['avatar'],
        			'email'=>$row['email']
        		);
        	array_push($result, $temp);
        }
        $res=array(
        		'error'=>null,
        		'data'=>$result
        	);
        return $res;
	}
	public function newproject($tid,$name,$description,$private){
		$uid=$this->session->userdata('uid');
		$sql="select * from TeamMember where uid=".$uid." and tid=".$tid;
		$query=$this->db->query($sql);
		if($query->num_rows()<1){
			$res=array(
					'error'=>'创建项目失败'
				);
			return $res;
		}
		$sql="insert into Project (tid,name,description,private) values (".$tid.",'".$name."','".$description."',".$private.");";
		$query=$this->db->query($sql);
		if($query>0){
			$id=$this->db->insert_id();
			$res=array(
					'error'=>null,
					'data'=>$id
				);
			return $res;
		}else{
			$res=array(
					'error'=>'创建项目失败'
				);
			return $res;
		}
	}
}
?>