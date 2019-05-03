<?php
	/*
	*
	* Author Sanjay S<vkre84u@gmail.com>
	* Minimum Functionality Of Database Connection Establishment
	* All CRUD Operations To Be Performed From Using This Functions
	***********************/
	class Database 
	{
		private $connection;
		private static $instance;
		private $host = "localhost";
		private $username = "root";
		private $password = "";
		private $database = "sample";
		public $error;
		public $error_msg;
		

		/*
		*
		* Get an instance of the Database
		**************************/
		public static function getInstance() 
		{
			if(!self::$instance) 
			{ 
				self::$instance = new self();
			}
			return self::$instance;
		}


		/*
		*
		* Default Function To Start The Database Connection
		**************************/
		public function __construct() 
		{
			$this->connection = new mysqli($this->host,$this->username,$this->password,$this->database);
			$this->init_database();
			if(!isset($_SESSION))
			{
				session_start();
				date_default_timezone_set('Asia/Kolkata');
			}
			if (!$this->connection->set_charset("utf8")) 
			{
				printf("Error loading character set utf8: %s\n", $this->connection->error);
				exit();
			}
			if(mysqli_connect_error()) 
			{
				$this->error = true;
				$this->error_msg = 'Unable to connect to DB';
				trigger_error("Failed to conencto to MySQL: ".mysqli_connect_error(),E_USER_ERROR);
				exit();
			}
		}


		/*
		*
		* 
		******************************/
		private function __clone() 
		{ 

		}

		private function init_database()
		{
			$this->query("CREATE DATABASE IF NOT EXISTS `sample`");
			$this->query("CREATE TABLE IF NOT EXISTS `Users`(`ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,`User_ID` varchar(25) NOT NULL,`Username` varchar(25) NOT NULL, `Password` varchar(500) NOT NULL, `Email` varchar(30) NOT NULL, `Status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',`Name` varchar(100) NOT NULL,`Created_Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`Updated_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, UNIQUE KEY `User_ID` (`User_ID`));");

			if(!$this->in_table("Users","Username='admin'"))
			{
				$field_values = "User_ID='VEN000000',Username='admin',Password='$2y$10$.RqTDxJCEzskpcVxQLcCqeOqwskla.oy0ttuo/SxUVf7nhoRATl66',Email='vkre84u@gmail.com',Status='ACTIVE',Name='Administrator'";
				$this->insert("Users",$field_values);
			}
		}

		/*
		*
		* Test Case For Error Function
		*************************/
		private function error_test($function,$query) 
		{
			if ($this->error_msg = mysqli_error($this->connection)) 
			{
				$this->error = true;
			}
			else 
			{
				$this->error = false;
			}
			return $this->error;
		}

	    
		/*
		*
		* Error Log Function
		*************************/
		private function log_error($function,$query) 
		{
			$fp = fopen('error_log.txt','a');
			fwrite($fp, date(DATE_RFC822) . ' | ' . $_SERVER["SCRIPT_NAME"] . ' -> ' . $function . ' | ' . $this->error_msg . ' | ' . $query . "\n\n\n");
			fclose($fp); 
		}


		/*
		*
		* Login the User
		***************************/
	    public function login($username,$password)
		{
			try 
			{
				if($stmt = $this->select('SELECT * FROM Users WHERE Username="'.$username.'" AND Status="ACTIVE" OR Email="'.$username.'" AND Status="ACTIVE"'))
				{
					$res=$stmt->fetch_object();
					if($res)
					{
						$hash=password_verify($password,$res->Password);
						if($hash == 0)
						{
							return array("Status"=>false,"Message"=>"Failed To Login, Please Re-Check Credentials");
						}
						else
						{
							$_SESSION['guid']         = $res->ID;
							$_SESSION['ssid']         = $res->User_ID;  
							$_SESSION['username']     = $res->Username;
							$_SESSION['timeout']      = time();
							$_SESSION['email']		  = $res->Email;
							$_SESSION['name']		  = $res->Name;
							return array("Status"=>true,"Message"=>"Successfully Logged In"); 
						}
					}
				}
			} 
			catch (Exception $e) 
			{
				return array("Status"=>false,"Message"=>"Number of allowed login attempts exceeded. Please try again later.");
				exit;
			}
		}
 

		/*
		*
		* Register The User
		*************************/
		public function register($userid,$username,$name,$email,$password)
		{ 
			$encryt_pass=password_hash($password,PASSWORD_DEFAULT);
			$insert_field = "User_ID='".$userid."',
				Username='".$username."',
				Password='".$encryt_pass."',
				Email='".$email."',
				Name='".$name."',
				Status='ACTIVE'";
			return $this->insert("Users",$insert_field);
	    }


		/*
		*
		* Logout
		*************************/
	    public function logout()
	   	{
	   		return session_destroy();
	   	}


		/*
		*
		* MySQL Date Function
		*************************/
		public function date($php_date) 
		{
			return date('Y-m-d H:i:s', strtotime($php_date));	
		}


	 	/*
		*
		* Avoid SQL Injection 
		******************************/
		public function escape($str) 
		{
			$description = preg_replace("/\r\n|\r|\n|�/",'',$str);
			$description = filter_var($description, FILTER_SANITIZE_STRING);
			return $this->connection->real_escape_string($description);
		}

		/*
		*
		* Check If Logged In
		******************************/
		public function is_logged_in()
		{
			if(isset($_SESSION['ssid']))
			{
				return $this->in_table('Users','User_ID="'.$_SESSION['ssid'].'" AND Status="ACTIVE"');
			}
			else
			{
				return false;
			}
		}
		
		/*
		*
		* Modify Enumerated Column List
		******************************/
		public function modifyEnum($tablename, $columnname, $field, $default)
		{
			$stmt = $this->connection->query("SHOW COLUMNS FROM {$tablename} WHERE Field = '{$columnname}'")->fetch_array(MYSQLI_ASSOC)['Type'];
			preg_match("/^enum\(\'(.*)\'\)$/", $stmt, $enum_list);
		    $enum_list[1] .= "','".$field;
		    $temp_query = "ALTER TABLE {$tablename} MODIFY COLUMN {$columnname} ENUM('".$enum_list[1]."') DEFAULT '$default';";
		    $stmt1 	= $this->connection->query($temp_query);
		}

		/*
		*
		* Get Enumerated Column List
		******************************/
		public function getEnumList($tablename,$columnname)
		{
			$stmt = $this->connection->query("SHOW COLUMNS FROM {$tablename} WHERE Field = '{$columnname}'")->fetch_array(MYSQLI_ASSOC)['Type'];
			preg_match("/^enum\(\'(.*)\'\)$/", $stmt, $enum_list);
			return $enum_list[1];
		}

	    /*
		*
		* Check Data Exists in Table Query Builder
		******************************/
		public function in_table($table,$where) 
		{
			$query = 'SELECT * FROM '.$table.' WHERE '.$where;
			$stmt= $this->connection->query($query);
			if($stmt)
			{
				return $stmt->num_rows > 0;
			}
			else
			{
				return false;
			}
		}


		/*
		*
		* Select Query Builder
		******************************/
		public function select($query) 
		{
			$stmt= $this->connection->query($query);
			return $stmt;
		}

		/*
		*
		* Get Auto Increment ID
		******************************/
		public function get_auto_id($table)
		{
			$stmt = $this->select("SHOW TABLE STATUS FROM ".$this->database);
			$auto_id = "";
		    while ($res=$stmt->fetch_assoc())
		    {
		        if (strtolower($res["Name"])== strtolower($table))
		        {
		        	$auto_id = $res["Auto_increment"];
		        }
		    }
		    return $auto_id;
		}

		/*
		*
		* Get Friendly Time (Last Seen)
		******************************/
		function time_ago( $timestamp ) 
		{
		    $strTime = array("second", "minute", "hour", "day", "month", "year");
		   	$length = array("60","60","24","30","12","10");
		   	$currentTime = time();
		   	if($currentTime >= $timestamp) 
		   	{
				$diff = time()- $timestamp;
				for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) 
				{
					$diff = $diff / $length[$i];
				}
				$diff = round($diff);
				return $diff . " " . $strTime[$i] . "(s) ago ";
		   	}
		}


	    /*
		*
		* Insert Query Builder
		******************************/
		public function insert($table,$field_values) 
		{
			$query = 'INSERT INTO '.$table.' SET '.$field_values;
			$stmt= $this->connection->prepare($query);
			if(false===$stmt)
			{
				$this->error_test('insert',$query);
				return false;
			}
			else
			{
				return $stmt->execute();
			}
		}


		/*
		*
		* Update Query Builder
		******************************/
		public function update($table,$field_values,$where) 
		{
			$query = 'UPDATE '.$table.' SET '.$field_values.' WHERE '.$where;
			$stmt= $this->connection->prepare($query);
			if(false===$stmt)
			{
				$this->error_test('update',$query);
				return false;
			}  
			else
			{
				return $stmt->execute();
			}
		} 


		/*
		*
		* Replace Query Builder
		******************************/
		public function query($query) 
		{
			$stmt= $this->connection->query($query);
			return $stmt;
		} 


		/*
		*
		* Delete Query Builder
		******************************/
		public function delete($table,$where)
		{
			$query='DELETE FROM '.$table.' WHERE '.$where;
	    	$stmt= $this->connection->prepare($query);
			return $stmt->execute();
		}
	}
?>




