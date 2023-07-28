<?php


class Products extends Admin_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Products';

		$this->load->model('model_products');
	}

	public function index()
	{
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$this->render_template('products/index', $this->data);
	}

	public function fetchProductData() {
		$result = array('data' => array());

		$data = $this->model_products->getProductData();
		foreach ($data as $key => $value) {
			$buttons = '';
//			in_array($user_permission, array(1, 2))
			if(in_array($this->permission, array(1, 2))) {
				$buttons .= '<a href="'.base_url('products/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
				$buttons .= '<button type="button" class="btn btn-default" onclick="restockFunc('.$value['id'].')" data-toggle="modal" data-target="#restockModal" title="Restock Product"><i class="fa fa-plus"></i></button>';
			}

			$availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$qty_status = '';
			if($value['qty'] <= 50) {
				$qty_status = '<span class="label label-warning">Low !</span>';
			} else if($value['qty'] <= 0) {
				$qty_status = '<span class="label label-danger">Out of stock !</span>';
			}

			$result['data'][$key] = array(
				$value['name'],
				$value['price'],
				$value['qty'] . ' ' . $qty_status,
				$availability,
				$buttons
			);
		} // fpreach

		echo json_encode($result);
	}

	public function create() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
		$this->form_validation->set_rules('availability', 'Availability', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'name' => $this->input->post('product_name'),
				'price' => $this->input->post('price'),
				'qty' => $this->input->post('qty'),
				'availability' => $this->input->post('availability'),
			);

			$create = $this->model_products->create($data);
			if($create == true) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('products/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('products/create', 'refresh');
			}
		} else {
			$this->render_template('products/create', $this->data);
		}
	}

	public function update($product_id) {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		if(!$product_id) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
		$this->form_validation->set_rules('availability', 'Availability', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'name' => $this->input->post('product_name'),
				'price' => $this->input->post('price'),
				'qty' => $this->input->post('qty'),
				'availability' => $this->input->post('availability'),
			);

			$update = $this->model_products->update($data, $product_id);
			if($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('products/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('products/update/'.$product_id, 'refresh');
			}
		} else {
			$product_data = $this->model_products->getProductData($product_id);
			$this->data['product_data'] = $product_data;
			$this->render_template('products/edit',  $this->data);
		}
	}

	public function remove() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$product_id = $this->input->post('product_id');

		$response = array();
		if($product_id) {
			$delete = $this->model_products->remove($product_id);
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

	public function restock() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$product_id = $this->input->post('product_id');

		$response = array();
		if($product_id) {
			$restock = $this->model_products->restock();

			if($restock == true) {
				$response['success'] = true;
				$response['messages'] = 'Successfully updated';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error occurred while updating product, contact admin';
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Could not add record';
		}

		echo json_encode($response);
	}

}
