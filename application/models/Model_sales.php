<?php


class Model_sales extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function getSalesData($id = null){
		if($id) {
			$sql = "SELECT * FROM sales where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM sales ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create() {
		$count_products = count($this->input->post('product'));
		for ($x = 0; $x < $count_products; $x++) {
			$data = array(
				'product_id' => $this->input->post('product')[$x],
				'qty' => $this->input->post('qty')[$x],
				'price' => $this->input->post('price')[$x],
				'sold_at' => $this->input->post('sold_at')[$x],
				'sold_by' => $this->input->post('sold_by')[$x],
				'date_sold' => date_format(date_create($this->input->post('date_sold')), 'Y-m-d'),
			);

			$insert = $this->db->insert('sales', $data);
			$insert_id = $this->db->insert_id();

			$this->load->model('model_products');

			$product = $this->model_products->getProductData($data['product_id']);
			$qty = (int) $product['qty'] - (int) $data['qty'];

			$update_product = array('qty' => $qty);
			$this->model_products->update($update_product, $data['product_id']);
		}

		return true;
	}

	public function update($id) {
		if($id) {
			$this->load->model('model_products');

			$product_id = $this->input->post('product');
			$product_data = $this->model_products->getProductData($product_id);
			$old_sales_data = $this->getSalesData($id);

			$qty = (int) $old_sales_data['qty'] - (int) $this->input->post('qty');
			$update_qty = (int) $product_data['qty'] + $qty;


			$data = array(
				'product_id' => $product_id,
				'qty' => $this->input->post('qty'),
				'price' => $this->input->post('price'),
				'sold_at' => $this->input->post('sold_at'),
				'date_sold' => date_format(date_create($this->input->post('date_sold')), 'Y-m-d'),
			);

			$this->db->where('id', $id);
			$update = $this->db->update('sales', $data);

			$update_product = array('qty' => $update_qty);
			$this->model_products->update($update_product, $data['product_id']);

			return true;
		}
	}

	public function remove($id): bool {
		if($id) {
			$sale = $this->getSalesData($id);

//			TODO: test
			$this->load->model('model_products');
			$item = $this->model_products->getProductData($sale['product_id']);
			$update_qty = (int) $item['qty'] + (int) $sale['qty'];
			$this->model_products->update(array('qty' => $update_qty), $sale['product_id']);

			$this->db->where('id', $id);
			$delete = $this->db->delete('sales');
			return $delete == true;
		}
		return false;
	}

	public function countTotalSales(){
		$sql = "SELECT * FROM sales";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
}
