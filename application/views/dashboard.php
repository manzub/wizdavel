<div class="content-wrapper">
	<section class="content-header">
		<h1>Dashboard <small>Control panel</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>

	<section class="content">
		<?php if ($is_admin == true) { ?>
			<div class="row">
				<div class="col-lg-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><?php echo $total_products ?></h3>

							<p>Total Products</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="<?php echo base_url('products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-6 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3><?php echo $total_sales ?></h3>

							<p>Total Products</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="<?php echo base_url('sales/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>

			<div class="row">
				<h3 style="padding: 10px 20px">Reports</h3>


				<div class="col-md-12 col-xs-12">
					<form class="form-inline" action="<?php echo base_url('reports/') ?>" method="POST">
						<div class="form-group">
							<label for="date">Year</label>
							<select class="form-control" name="select_year" id="select_year">
								<?php foreach ($report_years as $key => $value): ?>
									<option value="<?php echo $value ?>" <?php if($value == $selected_year) { echo "selected"; } ?>><?php echo $value; ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
				</div>
				<br/><br/>

				<div class="col-md-12 col-xs-12">

					<?php if($this->session->flashdata('success')): ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php elseif($this->session->flashdata('error')): ?>
						<div class="alert alert-error alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $this->session->flashdata('error'); ?>
						</div>
					<?php endif; ?>

					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Total Parking - Report</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="chart">
								<canvas id="barChart" style="height:250px"></canvas>
							</div>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Total Sales - Report Data</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<table id="datatables" class="table table-bordered table-striped">
								<thead>
								<tr>
									<th>Month - Year</th>
									<th>Amount</th>
								</tr>
								</thead>
								<tbody>

								<?php foreach ($results as $k => $v): ?>
									<tr>
										<td><?php echo $k; ?></td>
										<td><?php

											echo $company_currency .' ' . number_format($v);
											//echo $v;

											?></td>
									</tr>
								<?php endforeach ?>

								</tbody>
								<tbody>
								<tr>
									<th>Total Amount</th>
									<th>
										<?php echo $company_currency . ' ' . number_format(array_sum($results)); ?>
									</th>
								</tr>
								<tr>
									<th>Profit</th>
									<th>
										<?php echo $company_currency . ' ' . number_format(array_sum($total_earned)); ?>
									</th>
								</tr>
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>


					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Total Sales - Report Data</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<table id="datatables" class="table table-bordered table-striped">
								<thead>
								<tr>
									<th>Day - Month</th>
									<th>Amount Sold</th>
									<th>Profit</th>
								</tr>
								</thead>
								<tbody>

								<?php foreach ($daily_sold as $k => $v): ?>
									<tr>
										<td><?php echo $k; ?></td>
										<td><?php

											echo $company_currency .' ' . number_format($v);
											//echo $v;

											?></td>
										<td><?php

											echo $company_currency .' ' . number_format($daily_profit[$k]);
											//echo $v;

											?></td>
									</tr>
								<?php endforeach ?>

								</tbody>
								<tbody>
								<tr>
									<th>Total Amount</th>
									<th>
										<?php echo $company_currency . ' ' . number_format(array_sum($results)); ?>
									</th>
								</tr>
								<tr>
									<th>Profit</th>
									<th>
										<?php echo $company_currency . ' ' . number_format(array_sum($total_earned)); ?>
									</th>
								</tr>
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		<?php } ?>
	</section>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		$("#dashboardMainMenu").addClass('active');
	});

	var report_data = <?php echo '[' . implode(',', $results) . ']'; ?>;


	$(function () {
		/* ChartJS
		 * -------
		 * Here we will create a few charts using ChartJS
		 */
		var areaChartData = {
			labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			datasets: [
				{
					label               : 'Electronics',
					fillColor           : 'rgba(210, 214, 222, 1)',
					strokeColor         : 'rgba(210, 214, 222, 1)',
					pointColor          : 'rgba(210, 214, 222, 1)',
					pointStrokeColor    : '#c1c7d1',
					pointHighlightFill  : '#fff',
					pointHighlightStroke: 'rgba(220,220,220,1)',
					data                : report_data
				}
			]
		}

		//-------------
		//- BAR CHART -
		//-------------
		var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
		var barChart                         = new Chart(barChartCanvas)
		var barChartData                     = areaChartData
		barChartData.datasets[0].fillColor   = '#00a65a';
		barChartData.datasets[0].strokeColor = '#00a65a';
		barChartData.datasets[0].pointColor  = '#00a65a';
		var barChartOptions                  = {
			//Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
			scaleBeginAtZero        : true,
			//Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines      : true,
			//String - Colour of the grid lines
			scaleGridLineColor      : 'rgba(0,0,0,.05)',
			//Number - Width of the grid lines
			scaleGridLineWidth      : 1,
			//Boolean - Whether to show horizontal lines (except X axis)
			scaleShowHorizontalLines: true,
			//Boolean - Whether to show vertical lines (except Y axis)
			scaleShowVerticalLines  : true,
			//Boolean - If there is a stroke on each bar
			barShowStroke           : true,
			//Number - Pixel width of the bar stroke
			barStrokeWidth          : 2,
			//Number - Spacing between each of the X value sets
			barValueSpacing         : 5,
			//Number - Spacing between data sets within X values
			barDatasetSpacing       : 1,
			//String - A legend template
			legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
			//Boolean - whether to make the chart responsive
			responsive              : true,
			maintainAspectRatio     : true
		}

		barChartOptions.datasetFill = false
		barChart.Bar(barChartData, barChartOptions)
	})
</script>
