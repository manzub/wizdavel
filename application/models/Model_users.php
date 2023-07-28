<?php


class Model_users extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function getUserData($userId = null)
	{
		if($userId) {
			$sql = "SELECT * FROM users WHERE id = ?";
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM users WHERE id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}
}
