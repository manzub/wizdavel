<?php


class Sales extends Admin_Controller {

	public function __construct() {
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_sales');
		$this->load->model('model_products');
	}

	public function index() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Manage Sales';
		$this->render_template('sales/index', $this->data);
	}

	public function fetchOrdersData() {
		$result = array('data' => array());

		$data = $this->model_sales->getSalesData();

		foreach ($data as $key => $value) {
			$date = date('d-m-Y', strtotime($value['date_sold']));
			$date_sold = $date;

			$buttons = '';
			if(in_array($this->permission, array(1, 2))) {
				$buttons .= ' <a href="'.base_url('sales/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			$product_data = $this->model_products->getProductData($value['product_id']);

			$itrm_profit = (int) $value['sold_at'] - (int) $value['price'];

			$result['data'][$key] = array(
				$product_data['name'],
				'₦'.$value['price'],
				'₦'.$value['sold_at'],
				ucfirst($value['sold_by'] != null ? $value['sold_by'] : 'truck'),
				'₦'.number_format($itrm_profit * (int) $value['qty']),
				$value['qty'],
				$date_sold,
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function getProductValueById() {
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	public function getProductRecords(){
		$product_id = $this->input->post('product_id');
		if ($product_id) {
			$product = $this->model_products->getProductData($product_id);
			$records = $this->model_products->getProductRecords($product_id);
			$records[] = $product;
			echo json_encode($records);
		}
	}

	public function getTableProductRow(){
		$products = $this->model_products->getActiveProductData();
		echo json_encode($products);
	}

	public function create() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Record Sale';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$sale_id = $this->model_sales->create();
			if ($sale_id) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('sales/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('sales/create/', 'refresh');
			}
		}else {
			$this->data['products'] = $this->model_products->getActiveProductData();

			$this->render_template('sales/create', $this->data);
		}
	}

	public function update($id) {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Sales Record #'.$id;

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$update = $this->model_sales->update($id);

			if($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('sales/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('sales/update/'.$id, 'refresh');
			}
		} else {
			// load records and sale data
			$result = array();
			$sale_data = $this->model_sales->getSalesData($id);
			$result['sale'] = $sale_data;

			$this->data['sale_data'] = $result;
			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->data['product_records'] = $this->model_products->getProductRecords($result['sale']['product_id']);

			$this->render_template('sales/edit', $this->data);
		}
	}

	public function remove() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$sale_id = $this->input->post('sale_id');

		$response = array();
		if($sale_id) {
			$delete = $this->model_sales->remove($sale_id);
			if($delete == true) {
				$response['success'] = true;
				$response['messages'] = 'Successfully removed';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error occurred while deleting product, contact admin';
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Could not delete product';
		}

		echo json_encode($response);
	}
}
