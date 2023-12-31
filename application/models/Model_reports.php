<?php


class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$sql = "SELECT * FROM sales ";
		$query = $this->db->query($sql);
		$result = $query->result_array();

		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', strtotime($v['date_sold']));
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getOrderData($year)
	{
		if($year) {
			$months = $this->months();

			$sql = "SELECT * FROM sales";
			$query = $this->db->query($sql);
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;

				$final_data['months'][$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', strtotime($v['date_sold']));

					if($get_mon_year == $month_year) {
						$final_data['months'][$get_mon_year][] = $v;
					}
				}
			}

			foreach ($result as $k => $v) {
				$final_data['daily'][date('Y-m-d', strtotime($v['date_sold']))][] = $v;
			}


			return $final_data;

		}
	}
}
