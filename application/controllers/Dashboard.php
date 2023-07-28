<?php


class Dashboard extends Admin_Controller {
	public function __construct(){
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Dashboard';
		// load models
		$this->load->model('model_products');
		$this->load->model('model_sales');
		$this->load->model('model_reports');
	}

	public function index() {
		if(!in_array($this->permission, array(1, 2))) {
			redirect('dashboard', 'refresh');
		}

		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$parking_data = $this->model_reports->getOrderData($today_year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();

		$final_parking_data = array();
		$final_profit = array();
		foreach ($parking_data['months'] as $k => $v) {

			if(count($v) > 1) {
				$total_amount_sold = array();
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_sold[] = $v2['sold_at'] * $v2['qty'];
						$total_amount_earned[] = ((int) $v2['sold_at'] - (int) $v2['price']) * (int) $v2['qty'];
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_sold);
				$final_profit[$k] = array_sum($total_amount_earned);
			}
			else {
				$final_parking_data[$k] = 0;
			}

		}

		$daily_profit = array();
		$daily_sold = array();
		foreach ($parking_data['daily'] as $k => $v) {
			if(count($v) > 1) {
				$total_amount_sold = array();
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_sold[] = $v2['sold_at'] * $v2['qty'];
						$total_amount_earned[] = ((int) $v2['sold_at'] - (int) $v2['price']) * (int) $v2['qty'];
					}
				}
				$daily_profit[$k] = array_sum($total_amount_earned);
				$daily_sold[$k] = array_sum($total_amount_sold);
			}else {
				$daily_sold[$k] = 0;
			}
		}


		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = '₦';
		$this->data['results'] = $final_parking_data;
		$this->data['daily_sold'] = $daily_sold;
		$this->data['daily_profit'] = $daily_profit;

		$this->data['total_earned'] = $final_profit;
		// load summary view
		$this->data['total_products'] = $this->model_products->countTotalProducts();
		$this->data['total_sales'] = $this->model_sales->countTotalSales();

		// check is admin
		$user_id = $this->session->userdata('id');
		$is_admin = $user_id == 1;

		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}
}
