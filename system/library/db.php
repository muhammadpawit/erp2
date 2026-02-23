<?php
class DB {
	private $driver;

	public function __construct($driver, $hostname, $username, $password, $database) {
		if (file_exists(DIR_DATABASE . $driver . '.php')) {
			require_once(DIR_DATABASE . $driver . '.php');
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}

		$this->driver = new $driver($hostname, $username, $password, $database);
	}

  	public function query($sql) {
		return $this->driver->query($sql);
  	}

	public function escape($value) {
		return $this->driver->escape($value);
	}

	public function countAffected() {
	return $this->driver->countAffected();
	}

	public function getLastId() {
	return $this->driver->getLastId();
	}

	public function insert($table,$data) {
		return $this->driver->insert($table,$data);
	}
	public function update($table,$data,$where=array()) {
		return $this->driver->update($table,$data,$where);
	}
	public function delete($table,$where=array()) {
		return $this->driver->delete($table,$where);
	}
	public function first($table,$where=array()) {
		return $this->driver->first($table,$where);
	}
	public function all($table,$where=array(),$order=array(),$limit,$offset) {
		return $this->driver->all($table,$where,$order,$limit,$offset);
	}
	public function count($table,$where=array()) {
		return $this->driver->count($table,$where);
	}
	public function countAll($table,$where=array(),$join=array(),$leftjoin=array()) {
		return $this->driver->countAll($table,$where,$join,$leftjoin);
	}
	public function alljoin($table,$column=array(),$join=array(),$where=array(),$order=array(),$limit,$offset) {
		return $this->driver->alljoin($table,$column,$join,$where,$order,$limit,$offset);
	}
	public function alljoins($table,$column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit,$offset) {
		return $this->driver->alljoins($table,$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function firstdetail($table,$column=array(),$join=array(),$where=array(),$order=array()) {
		return $this->driver->firstdetail($table,$column,$join,$where,$order);
	}
}
?>
