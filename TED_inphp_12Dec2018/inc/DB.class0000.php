<?php  
//DB.class.php
class DB {
	protected $db_host = 'localhost';
	protected $db_user = 'root';
	protected $db_pass = 'mdp123';
	protected $db_name = 'db_project';
	/*
	protected $db_host = '127.0.0.1';
	protected $db_user = 'developer3';
	protected $db_pass = '6Bg41irF';
	protected $db_name = 'projects';
*/
	
	private $con = '';
	
	function __construct(){
		$this->con = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		if ($this->con->connect_errno) {
    		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
		}
	}

	function __destruct() {
       $this->con->close();
   	}
	
	public function login($username, $password) {
		//$hashedPassword = md5($password);
		$sql = "SELECT * FROM contract_admin_login WHERE username = '$username' AND password = '$password'";
		$result = $this->con->query($sql);
		
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$_SESSION["cuserId"] = $row['user_id'];
			$_SESSION["cusername"] = $row['username'];
			$_SESSION["crole"] = $row['role'];
			$_SESSION["login_time"] = time();
			$_SESSION["logged_in"] = 1;
			$result->close();
			return true;
		} else {
			return false;
		}
	}


	//Log the user out. Destroy the session variables.
	public function logout() {
		unset($_SESSION['cuserId']);
		unset($_SESSION['cusername']);
		unset($_SESSION['crole']);
		unset($_SESSION['login_time']);
		unset($_SESSION['logged_in']);
		session_destroy();
	}

	//Updates a current row in the database.
	//takes an array of data, where the keys in the array are the column names
	//and the values are the data that will be inserted into those columns.
	//$table is the name of the table and $where is the sql where clause.
	public function update($data, $table, $where) {
		foreach ($data as $column => $value){
			$sql = "UPDATE $table SET $column = $value WHERE $where";
			$this->con->query($sql);
		}
		return true;
	}

	//Inserts a new row into the database.
	//takes an array of data, where the keys in the array are the column names
	//and the values are the data that will be inserted into those columns.
	//$table is the name of the table.
	public function insert($data, $table) {

		$columns = "";
		$values = "";
		foreach ($data as $column => $value){
			$columns .= ($columns == "") ? "" : ", ";
			$columns .= $column;
			$values .= ($values == "") ? "" : ", ";
			$values .= $value;
		}
		$sql = "insert into $table ($columns) values ($values)";
		$this->con->query($sql);
		//return the ID of the user in the database.
		$last_row = mysqli_insert_id($this->con);
		return $this->con->insert_id();
	}
	
	//Run own query
	public function getQuery($sql){
		$temp_arr = array();
		$result = $this->con->query($sql);
		while($row = $result->fetch_assoc()){
			$temp_arr[] =$row;
		}
		$result->close();
		return $temp_arr;
	}

	//to display all the row
	public function display($table){
		$temp_arr = array();
		$res = $this->con->query("SELECT * FROM $table");
		while($row = $res->fetch_assoc()){
			$temp_arr[] =$row;
		}
		$res->close();
		return $temp_arr;
	}

	public function delete($table,$where){

		$sql = "DELETE FROM $table WHERE $where";
		$this->con->query($sql);
	}

		
	public function show_one($table, $where) {
		$result = $this->con->query("SELECT * FROM $table WHERE $where ");
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$result->close();
			return $row;
		}
	}

	public function count($table, $where) {
		$result = $this->con->query("SELECT count(*) FROM $table WHERE $where ");
		$row = $result->fetch_row();
		return $row[0];
	}

	public function show_all($table, $where) {
		$temp_arr = array();
		$res = $this->con->query("SELECT * FROM $table WHERE $where") or die(mysql_error());
		
		while ($row = $res->fetch_array()) {
			$temp_arr[] = $row;
		}
		$res->close();
		return $temp_arr;
	}

	public function show_all_assoc($table, $where) {
		$temp_arr = array();
		$res = $this->con->query("SELECT * FROM $table WHERE $where") or die(mysql_error());
		
		while ($row = $res->fetch_assoc()) {
			$temp_arr[] = $row;
		}
		$res->close();
		return $temp_arr;
	}


	public function distinct($table,$col,$where) {
		$temp_arr = array();
		$sql="SELECT DISTINCT $col FROM $table WHERE $where";
		$res = $this->con->query($sql) or die(mysql_error());
		while ($row = $res->fetch_assoc()) {
			$temp_arr[] = $row;
		}
		$res->close();
		return $temp_arr;
	}
	
	public function get_country(){
		$temp_arr = array();
		$sql = "SELECT * FROM  country ORDER BY Country ASC";
		$res = $this->con->query($sql) or die(mysql_error());
		while ($row = $res->fetch_assoc()) {
			$temp_arr[] = $row;
		}
		$res->close();
		return $temp_arr;
	}
	
	public function get_financier(){
		$temp_arr = array();
		$sql = "SELECT * FROM  financier ORDER BY financier ASC";
		$res = $this->con->query($sql) or die(mysql_error());
		while ($row = $res->fetch_assoc()) {
			$temp_arr[] = $row;
		}
		$res->close();
		return $temp_arr;
	}
	public function get_sector(){
		$temp_arr = array();
		$sql = "SELECT * FROM  sector";
		$res = $this->con->query($sql) or die(mysql_error());
		while ($row = $res->fetch_assoc()) {
			$temp_arr[] = $row;
		}
		$res->close();
		return $temp_arr;
	}
}
?>	