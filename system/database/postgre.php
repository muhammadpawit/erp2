<?php
final class Postgre {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = pg_connect('host=' . $hostname . ' port=5432 user=' . $username . ' password='	. $password . ' dbname=' . $database)) {
      		trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
    	}

			if (!pg_ping($this->link)) {
				throw new \Exception('Error: Could not connect to database ' . $database);
			}

		pg_query($this->link, "SET CLIENT_ENCODING TO 'UTF8'");
  	}

  public function query($sql) {
		$resource = pg_query($this->link, $sql);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = array();

				while ($result = pg_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}

				pg_free_result($resource);

				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);

				return $query;
    		} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . pg_result_error($this->link) . '<br />' . $sql);
			exit();
    	}
  	}

	public function escape($value) {
		return pg_escape_string($this->link, $value);
	}

  	public function countAffected() {
    	return pg_affected_rows($this->link);
  	}

  	public function getLastId() {
		$query = $this->query("SELECT LASTVAL() AS id");

    	return $query->row['id'];
  	}

	public function __destruct() {
		pg_close($this->link);
	}

	public function insert($table,$data){
		$column='';
		$vals='';
		$i=1;
		foreach($data as $key => $value){

				if($i != 1){
			         $column .=",";
							 $vals .=",";
				}
				$column .= $key;
				$vals .= "'".$value."'";
				$i++;
			}


		$sql="INSERT INTO ".DB_PREFIX.$table."(".$column.") values(".$vals.")";

		$this->query($sql);
	}

	public function update($table,$data,$where=array()){
		$i=1;
		$sql="UPDATE ".DB_PREFIX.$table." SET ";
		foreach($data as $key => $value){

				if($i != 1){
			      $sql .=",";
				}
				$sql .=$key.' = ';
				$sql .= "'".$value."'";

				$i++;
			}


		$sql .=" WHERE ";
		$y=1;

		foreach($where as $key => $value){
			if($y != 1){
					$sql .=" AND ";
			}
			$sql .=$key.' = ';
			$sql .= "'".$value."'";

			$y++;
		}

		$this->query($sql);
	}

	public function delete($table,$where=array()){
		$i=1;
		$sql="DELETE FROM ".DB_PREFIX.$table;
		if(!empty($where)){
		$sql .=" WHERE ";
		$y=1;

		foreach($where as $key => $value){
			if($y != 1){
					$sql .=" AND ";
			}
			if(!is_array($value)){
				$sql .=$key.' = ';
				$sql .= "'".$value."'";
			}
			$y++;
		}
	}

		$this->query($sql);
	}
	public function first($tablename,$where){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename;
		$sql .=" WHERE hapus=0";
		$y=1;

		foreach($where as $key => $value){
		//	if($y != 1){
					$sql .=" AND ";
			//}
			$sql .=$key.' = ';
			$sql .= "'".$value."'";

			$y++;
		}
		$res=$this->query($sql);
		return $res->row;
	}

	public function all($tablename,$where,$orderby=array(),$limit=0,$offset=null){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename;
		//if(!empty($where)){
		$sql .=" WHERE hapus=0 ";
		$y=1;

		foreach($where as $key => $value){
			$sql .=" AND ";
			/*if($y != 1){
					$sql .=" AND ";
			}*/
			if(is_array($value)){
				/*
					name -> key
					value-> array(operator, value)
				*/
				if($value[0] == 'LIKE'){
					$sql .='lower('.$key.') LIKE ';
					$sql .= "'%".utf8_strtolower($value[1])."%'";
				}elseif($value[0] == 'IN' | $value[0] == 'NOT IN'){
					if(!empty($value[1])){
						$sql .=$key." ";
						$sql .=$value[0].'('.$value[1].')';
					}else{
						$sql.= $key ." < '-999999' ";
					}
				}
				else{
					$sql .=$key.' '.$value[0];
					$sql .= "'".$value[1]."'";
					if(isset($value[2])){
						$sql .=" AND ";
						$sql .=$key.' '.$value[2];
						$sql .= "'".$value[3]."'";
					}
				}
			}else{
				$sql .=$key.' = ';
				$sql .= "'".$value."'";
			}
			$y++;
		}

		if(!empty($orderby)){
		$sql .=" ORDER BY ";
		$y=1;

		foreach($orderby as $key => $value){
			if($y != 1){
					$sql .=", ";
			}
			$sql .='"'.$key.'" '.$value;

			$y++;
		}
	}
		if(!empty($limit)){
			$sql .=" LIMIT ".$limit;
		}
		if($offset != null){
			$sql .=" OFFSET ".$offset;
		}

		$res=$this->query($sql);
		return $res->rows;
	}

	public function count($tablename,$where){
		$sql="SELECT count(*) as total FROM ".DB_PREFIX.$tablename;
		//if(!empty($where)){
		$sql .=" WHERE hapus=0";
		$y=1;

		foreach($where as $key => $value){
			$sql .=" AND ";
			/*if($y != 1){
					$sql .=" AND ";
			}*/
			if(is_array($value)){
				/*
					name -> key
					value-> array(operator, value)
				*/
				if($value[0] == 'LIKE'){
					$sql .='lower('.$key.') LIKE ';
					$sql .= "'%".utf8_strtolower($value[1])."%'";
				}elseif($value[0] == 'IN' | $value[0] == 'NOT IN'){
					if(!empty($value[1])){
						$sql .=$key." ";
						$sql .=$value[0].'('.$value[1].')';
					}else{
						$sql.= $key ." < '-999999' ";
					}
				}else{
					$sql .=$key.' '.$value[0];
					$sql .= "'".$value[1]."'";
					if(isset($value[2])){
						$sql .=" AND ";
						$sql .=$key.' '.$value[2];
						$sql .= "'".$value[3]."'";
					}
				}
			}else{
				$sql .=$key.' = ';
				$sql .= "'".$value."'";
			}
			$y++;
		}

		$res=$this->query($sql);
		return $res->row['total'];
	}

	public function countAll($tablename,$where=array(),$join=array(),$leftjoin=array()){
		$sql="SELECT count(*) as total FROM ".DB_PREFIX.$tablename;
		//if(!empty($where)){
		if(!empty($join)){
			$x=1;
			foreach($join as $c){
				/*if($x != 1){
						$sql .=", ";
				}*/
				$sql .=" JOIN ".$c['tablename']." ON(".$c['firsttable']." = ".$c['secondtable'].") ";

				$x++;
			}
		}
		if(!empty($leftjoin)){
			$x=1;
			foreach($leftjoin as $c){
				/*if($x != 1){
						$sql .=", ";
				}*/
				$sql .=" LEFT JOIN ".$c['tablename']." ON(".$c['firsttable']." = ".$c['secondtable'].") ";

				$x++;
			}
		}
		if(!empty($where)){
		//$sql .=" WHERE hapus=0 ";
		//$sql .= " WHERE ";
		$y=1;

		foreach($where as $key => $value){
			//$sql .=" AND ";

			if(is_array($value)){
				/*
					name -> key
					value-> array(operator, value)
				*/
				if($value[0] == 'LIKE'){
					if(!empty($value[1])){
						if($y == 1){
							$sql .= " WHERE ";
						}
						if($y != 1){
								$sql .=" AND ";
						}
						$sql .='lower('.$key.') LIKE ';
						$sql .= "'%".utf8_strtolower($value[1])."%'";
						$y++;
					}
				}elseif($value[0] == 'IN' | $value[0] == 'NOT IN'){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					if(!empty($value[1])){
						$sql .=$key." ";
						$sql .=$value[0].'('.$value[1].')';
					}else{
						$sql.= $key ." < '-999999' ";
					}
				}else{
					if(!empty($value)){
						if($y == 1){
							$sql .= " WHERE ";
						}
						if($y != 1){
								$sql .=" AND ";
						}
						$sql .=''.$key.' '.$value[0];
						$sql .= "'".$value[1]."'";
						if(isset($value[2])){
							$sql .=" AND ";
							$sql .=$key.' '.$value[2];
							$sql .= "'".$value[3]."'";
						}
						$y++;
					}
				}
			}else{
				if(!empty($value)){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					$sql .=''.$key.' = ';
					$sql .= "'".$value."'";
					$y++;
				}
			}

		}
	}

		$res=$this->query($sql);
		return $res->row['total'];
	}

	public function alljoin($tablename,$column=array(),$join=array(),$where,$orderby=array(),$limit=0,$offset=null){
		if(empty($column)){
			$sql="SELECT * FROM ".DB_PREFIX.$tablename;
		}else{
			$sql="SELECT ";
			$x=1;
			foreach($column as $c){
				if($x != 1){
						$sql .=", ";
				}
				$sql .=$c;

				$x++;
			}
			$sql .=" FROM ".DB_PREFIX.$tablename;
		}
		if(!empty($join)){
			$x=1;
			foreach($join as $c){
				/*if($x != 1){
						$sql .=", ";
				}*/
				$sql .=" JOIN ".$c['tablename']." ON(".$c['firsttable']." = ".$c['secondtable'].") ";

				$x++;
			}
		}
		if(!empty($where)){
		//$sql .=" WHERE hapus=0 ";
		//$sql .= " WHERE ";
		$y=1;

		foreach($where as $key => $value){
			//$sql .=" AND ";

			if(is_array($value)){
				/*
					name -> key
					value-> array(operator, value)
				*/
				if($value[0] == 'LIKE'){
					if(!empty($value[1])){
						if($y == 1){
							$sql .= " WHERE ";
						}
						if($y != 1){
								$sql .=" AND ";
						}
						$sql .='lower('.$key.') LIKE ';
						$sql .= "'%".utf8_strtolower($value[1])."%'";
						$y++;
					}
				}elseif($value[0] == 'IN' | $value[0] == 'NOT IN'){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					if(!empty($value[1])){
						$sql .=$key." ";
						$sql .=$value[0].'('.$value[1].')';
					}else{
						$sql.= $key ." < '-999999' ";
					}
					$y++;
				}else{
					if(!empty($value)){
						if($y == 1){
							$sql .= " WHERE ";
						}
						if($y != 1){
								$sql .=" AND ";
						}
						$sql .=$key.' '.$value[0];
						$sql .= "'".$value[1]."'";
						if(isset($value[2])){
							$sql .=" AND ";
							$sql .=$key.' '.$value[2];
							$sql .= "'".$value[3]."'";
						}
						$y++;
					}
				}
			}else{
				if(!empty($value)){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					$sql .=$key.' = ';
					$sql .= "'".$value."'";
					$y++;
				}
			}
			//$y++;
		}
	}
	if(!empty($orderby)){
	$sql .=" ORDER BY ";
	$y=1;

	foreach($orderby as $key => $value){
		if($y != 1){
				$sql .=", ";
		}
		$sql .=''.$key.' '.$value;

		$y++;
	}
}
	if(!empty($limit)){
		$sql .=" LIMIT ".$limit;
	}
	if($offset != null){
		$sql .=" OFFSET ".$offset;
	}

	$res=$this->query($sql);
	return $res->rows;
}

public function alljoins($tablename,$column=array(),$join=array(),$leftjoin=array(),$where,$orderby=array(),$limit=0,$offset=null){
	if(empty($column)){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename;
	}else{
		$sql="SELECT ";
		$x=1;
		foreach($column as $c){
			if($x != 1){
					$sql .=", ";
			}
			$sql .=$c;

			$x++;
		}
		$sql .=" FROM ".DB_PREFIX.$tablename;
	}
	if(!empty($join)){
		$x=1;
		foreach($join as $c){
			/*if($x != 1){
					$sql .=", ";
			}*/
			$sql .=" JOIN ".$c['tablename']." ON(".$c['firsttable']." = ".$c['secondtable'].") ";

			$x++;
		}
	}
	if(!empty($leftjoin)){
		$x=1;
		foreach($leftjoin as $c){
			/*if($x != 1){
					$sql .=", ";
			}*/
			$sql .=" LEFT JOIN ".$c['tablename']." ON(".$c['firsttable']." = ".$c['secondtable'].") ";

			$x++;
		}
	}
	if(!empty($where)){
	//$sql .=" WHERE hapus=0 ";
	//$sql .= " WHERE ";
	$y=1;

	foreach($where as $key => $value){
		//$sql .=" AND ";

		if(is_array($value)){
			/*
				name -> key
				value-> array(operator, value)
			*/
			if($value[0] == 'LIKE'){
				if(!empty($value[1])){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					$sql .='lower('.$key.') LIKE ';
					$sql .= "'%".utf8_strtolower($value[1])."%'";
					$y++;
				}
			}elseif($value[0] == 'IN' | $value[0] == 'NOT IN'){
				if($y == 1){
					$sql .= " WHERE ";
				}
				if($y != 1){
						$sql .=" AND ";
				}
				if(!empty($value[1])){
					$sql .=$key." ";
					$sql .=$value[0].'('.$value[1].')';
				}else{
					$sql.= $key ." < '-999999' ";
				}
				$y++;
			}else{
				if(!empty($value)){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					$sql .=$key.' '.$value[0];
					$sql .= "'".$value[1]."'";
					if(isset($value[2])){
						$sql .=" AND ";
						$sql .=$key.' '.$value[2];
						$sql .= "'".$value[3]."'";
					}
					$y++;
				}
			}
		}else{
			if(!empty($value)){
				if($y == 1){
					$sql .= " WHERE ";
				}
				if($y != 1){
						$sql .=" AND ";
				}
				$sql .=$key.' = ';
				$sql .= "'".$value."'";
				$y++;
			}
		}
		//$y++;
	}
}
if(!empty($orderby)){
$sql .=" ORDER BY ";
$y=1;

foreach($orderby as $key => $value){
	if($y != 1){
			$sql .=", ";
	}
	$sql .=''.$key.' '.$value;

	$y++;
}
}
if(!empty($limit)){
	$sql .=" LIMIT ".$limit;
}
if($offset != null){
	$sql .=" OFFSET ".$offset;
}

$res=$this->query($sql);
return $res->rows;
}

public function firstdetail($tablename,$column=array(),$join=array(),$where,$orderby=array()){
		if(empty($column)){
			$sql="SELECT * FROM ".DB_PREFIX.$tablename;
		}else{
			$sql="SELECT ";
			$x=1;
			foreach($column as $c){
				if($x != 1){
						$sql .=", ";
				}
				$sql .=$c;

				$x++;
			}
			$sql .=" FROM ".DB_PREFIX.$tablename;
		}
		if(!empty($join)){
			$x=1;
			foreach($join as $c){
				/*if($x != 1){
						$sql .=", ";
				}*/
				$sql .=" JOIN ".$c['tablename']." ON(".$c['firsttable']." = ".$c['secondtable'].") ";

				$x++;
			}
		}
		if(!empty($where)){
		//$sql .=" WHERE hapus=0 ";
		//$sql .= " WHERE ";
		$y=1;

		foreach($where as $key => $value){
			//$sql .=" AND ";

			if(is_array($value)){
				/*
					name -> key
					value-> array(operator, value)
				*/
				if($value[0] == 'LIKE'){
					if(!empty($value[1])){
						if($y == 1){
							$sql .= " WHERE ";
						}
						if($y != 1){
								$sql .=" AND ";
						}
						$sql .='lower('.$key.') LIKE ';
						$sql .= "'%".utf8_strtolower($value[1])."%'";
						$y++;
					}
				}elseif($value[0] == 'IN' | $value[0] == 'NOT IN'){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					if(!empty($value[1])){
						$sql .=$key." ";
						$sql .=$value[0].'('.$value[1].')';
					}else{
						$sql.= $key ." < '-999999' ";
					}
					$y++;
				}else{
					if(!empty($value)){
						if($y == 1){
							$sql .= " WHERE ";
						}
						if($y != 1){
								$sql .=" AND ";
						}
						$sql .=''.$key.' '.$value[0];
						$sql .= "'".$value[1]."'";
						if(isset($value[2])){
							$sql .=" AND ";
							$sql .=$key.' '.$value[2];
							$sql .= "'".$value[3]."'";
						}
						$y++;
					}
				}
			}else{
				if(!empty($value)){
					if($y == 1){
						$sql .= " WHERE ";
					}
					if($y != 1){
							$sql .=" AND ";
					}
					$sql .=''.$key.' = ';
					$sql .= "'".$value."'";
					$y++;
				}
			}

		}
	}
	if(!empty($orderby)){
	$sql .=" ORDER BY ";
	$y=1;

	foreach($orderby as $key => $value){
		if($y != 1){
				$sql .=", ";
		}
		$sql .='"'.$key.'" '.$value;

		$y++;
	}
}

	$res=$this->query($sql);
	return $res->row;
}
}
?>
