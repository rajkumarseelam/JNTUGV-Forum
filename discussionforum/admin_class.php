<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
					return 1;
			}else{
				return 3;
			}
	}
	// function signup(){
		
	// 		extract($_POST);		
	// 		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' ");
	// 		if($qry->num_rows > 0){
	// 			header("location:login.php");
	// 		}else{
	// 			return 1;
	// 		}
	// }



	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function signup(){

		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", description = '$description' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO categories set $data");
			}else{
				$save = $this->db->query("UPDATE categories set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete){
			return 1;
		}
	}

	function save_topic(){
		extract($_POST);
		// echo $title;
		// echo $content;
		// echo "\n\n\n";
		if(!isset($_POST["category_ids"])){
			$category_ids=array();
		}
		$command=escapeshellcmd("python pythonfile.py \"$title\" \"$content\"");
		// echo $command;
		$output=shell_exec($command);
		$tags=explode(" ",$output);
		// echo $output;
		if(isset($tags[0])){
		$temp="(\"".$tags[0]."\"";
		foreach ($tags as $key => $tag) {
			$temp=$temp.","."\"".trim($tag)."\"";
		}
		$temp=$temp.")";
		// echo $temp;
	}
		// print_r($tags);
		// echo"<br/>";
		// $category_ids=array();
		$ids=$this->db->query("SELECT id FROM CATEGORIES WHERE name in ".$temp);
		// echo "SELECT id FROM CATEGORIES WHERE name in ".$temp;
		// echo $ids->num_rows;
		while($row= $ids->fetch_assoc()):
			array_push($category_ids,$row['id']);
		endwhile;

		// echo "ids=".print_r($category_ids);
		// echo"<br/>";
		$data = " title = '$title' ";
		// $category_ids=array_merge($category_ids,$ids);

		$data .= ", category_ids = '".(implode(",",$category_ids))."' ";
		// $data .= ", category_ids = $categ ";
		$data .= ", content = '".htmlentities(str_replace("'","&#x2019;",$content))."' ";

		if(empty($id)){
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO topics set ".$data);
		}else{
			$save = $this->db->query("UPDATE topics set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_topic(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM topics where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";

		if(empty($id)){
			$data .= ", topic_id = '$topic_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO comments set ".$data);
		}else{
			$save = $this->db->query("UPDATE comments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_comment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM comments where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_reply(){
		extract($_POST);
		$data = " reply = '".htmlentities(str_replace("'","&#x2019;",$reply))."' ";

		if(empty($id)){
			$data .= ", comment_id = '$comment_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO replies set ".$data);
		}else{
			$save = $this->db->query("UPDATE replies set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_reply(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM replies where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function search(){
		extract($_POST);
		$data = array();
		$tag = $this->db->query("SELECT * FROM categories order by name asc");
		while($row= $tag->fetch_assoc()):
			$tags[$row['id']] = $row['name'];
		endwhile;
		$ts = $this->db->query("SELECT * FROM categories where name like '%{$keyword}%' ");
		$tsearch = '';
		while($row= $ts->fetch_assoc()):
			$tsearch .=" or concat('[',REPLACE(t.category_ids,',','],['),']') like '%[{$row['id']}]%' ";
		endwhile;
		// echo "SELECT t.*,u.name FROM topics t inner join users u on u.id = t.user_id where t.title LIKE '%{$keyword}%' or content LIKE '%{$keyword}%' $tsearch order by unix_timestamp(t.date_created) desc";
		$topic = $this->db->query("SELECT t.*,u.name FROM topics t inner join users u on u.id = t.user_id where t.title LIKE '%{$keyword}%' or content LIKE '%{$keyword}%' $tsearch order by unix_timestamp(t.date_created) desc");
		while($row= $topic->fetch_assoc()):
			$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
	        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
	        $desc = strtr(html_entity_decode($row['content']),$trans);
	        $row['desc']=strip_tags(str_replace(array("<li>","</li>"), array("",","), $desc));
	        $row['view'] = $this->db->query("SELECT * FROM forum_views where topic_id=".$row['id'])->num_rows;
	        $row['comments'] = $this->db->query("SELECT * FROM comments where topic_id=".$row['id'])->num_rows;
	        $row['replies'] = $this->db->query("SELECT * FROM replies where comment_id in (SELECT id FROM comments where topic_id=".$row['id'].")")->num_rows;
	        $row['tags'] = array();
			$i=0;
	        foreach(explode(",",$row['category_ids']) as $cat):
	        	array_push($row['tags'],(isset($tags[$cat]))? $tags[$cat]: "others");
			endforeach;
			$row['created'] = date('M d, Y h:i A',strtotime($row['date_created']));
			$row['posted'] = ucwords($row['name']);
	        $data[]= $row;
		endwhile;
		return json_encode($data);
	}
	function vote(){
		extract($_POST);
		$user_id=$_SESSION['login_id'];
		$vote_exits=$this->db->query("select * from votes where user_id=$user_id and topic_id=$id")->num_rows;
		echo $vote_exits;
		if($vote_exits==0){
		// echo "insert into votes(topic_id,user_id,vote_type) values($id,$user_id,$vote_type)";
		$vote=$this->db->query("insert into votes(topic_id,user_id,vote_type) values($id,$user_id,$vote_type)");
		// echo $vote;
		}
		else{
			$vote=$this->db->query("update votes set votes_type=$vote_type where user_id=$user_id and topic_id=$id");
		}
		if($vote_type==1){
            $upvote=$this->db->query("update topics set likes=likes+1 where id=$id");
              
		}
		else{
			$downvote=$this->db->query("update topics set dislikes=dislikes+1 where id=$id"); 
		}
		if($vote){
			return 1;
		}
	}
}

// function vote(){
//     extract($_POST);
//     $user_id=$_SESSION['login_id'];
//     $vote_exits=$this->db->query("select * from votes where user_id=$user_id and topic_id=$id")->num_rows;
//     echo $vote_exits;
//     if($vote_exits==0){
//         $vote=$this->db->query("insert into votes(topic_id,user_id,vote_type) values($id,$user_id,$vote_type)");
//         if($vote_type==0){
//             $upvote=$this->db->query("update topics set upvotes=upvotes+1 where id=$id");
//         } else {
//             $downvote=$this->db->query("update topics set downvotes=downvotes+1 where id=$id");
//         }
//     } else {
//         $vote=$this->db->query("update votes set vote_type=$vote_type where user_id=$user_id and topic_id=$id");
//         if($vote_type==0){
//             $upvote=$this->db->query("update topics set upvotes=upvotes+1, downvotes=downvotes-1 where id=$id");
//         } else {
//             $downvote=$this->db->query("update topics set downvotes=downvotes+1, upvotes=upvotes-1 where id=$id");
//         }
//     }
//     if($vote){
//         return 1;
//     }
// }

