<?php


class Model_products extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function getProductData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM products where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM products ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM products WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getProductRecords($product_id) {
		$sql = "SELECT * FROM product_records WHERE product_id = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array($product_id));
		return $query->result_array();
	}

	public function create($data): bool
	{
		if($data) {
			$insert = $this->db->insert('products', $data);
			return $insert == true;
		}
		return false;
	}

	public function update($data, $id): bool
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return $update == true;
		}
		return false;
	}

	public function remove($id): bool
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return $delete == true;
		}
		return false;
	}

	public function restock(): bool
	{
		if($this->input->post('product_id')) {
			$data = array(
				'product_id' => $this->input->post('product_id'),
				'price' => $this->input->post('price'),
				'qty' => $this->input->post('qty'),
				'date_time' => date_format(date_create($this->input->post('date_time')), 'Y-m-d')
			);
			$insert = $this->db->insert('product_records', $data);
			if($insert == true) {
				$product = $this->getProductData($data['product_id']);
				$qty = (int) $product['qty'] + (int) $data['qty'];
				$restock = $this->update(array('qty' => $qty), $data['product_id']);
				return $restock == true;
			}
		}
		return false;
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
}
