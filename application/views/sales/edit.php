

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Sales</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Sales</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12 col-xs-12">

				<div id="messages"></div>

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
						<h3 class="box-title">Edit Record</h3>
					</div>
					<!-- /.box-header -->
					<form role="form" action="<?php base_url('sales/create') ?>" method="post" class="form-horizontal">
						<div class="box-body">

							<?php echo validation_errors(); ?>

							<div class="col-md-6 col-xs-12 pull pull-left">

								<div class="form-group">
									<label for="date_sold" class="col-sm-5 control-label" style="text-align:left;">Date Sold</label>
									<div class="col-sm-7">
										<input value="<?php echo $sale_data['sale']['date_sold']; ?>" type="date" class="form-control" id="date_sold" name="date_sold" placeholder="Enter Date" autocomplete="off" />
									</div>
								</div>
							</div>

							<br /> <br/>
							<table class="table table-bordered" id="product_info_table">
								<thead>
								<tr>
									<th style="width:50%">Product</th>
									<th style="width:10%">Qty</th>
									<th style="width:10%">Market Price</th>
									<th style="width:20%">Sold At</th>
								</tr>
								</thead>

								<tbody>
								<tr id="row_1">
									<td>
										<select class="form-control select_group product" data-row-id="row_1" id="product_1" name="product" style="width:100%;" required onchange="getRecords(1)">
											<option value=""></option>
											<?php foreach ($products as $k => $v): ?>
												<option value="<?php echo $v['id'] ?>" <?php if ($sale_data['sale']['product_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['name'] ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td><input value="<?php echo $sale_data['sale']['qty'] ?>" type="text" name="qty" id="qty_1" class="form-control" required></td>
									<td>
										<select class="form-control select_group" data-row-id="row_1" id="rate_1" name="price" style="width:100%;" required>
											<option value="<?php echo $sale_data['sale']['price'] ?>" selected="selected"><?php echo $sale_data['sale']['price'] ?></option>
											<?php foreach ($product_records as $k => $v): ?>
												<option value="<?php echo $v['price'] ?>"><?php echo $v['price'].' => '.$v['date_time'] ?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td>
										<input value="<?php echo $sale_data['sale']['sold_at'] ?>" type="text" name="sold_at" id="amount_1" class="form-control" autocomplete="off">
									</td>
								</tr>
								</tbody>
							</table>

							<br /> <br/>

						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Record Sale</button>
							<a href="<?php echo base_url('sales/') ?>" class="btn btn-warning">Back</a>
						</div>
					</form>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- col-md-12 -->
		</div>
		<!-- /.row -->


	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";

	$(document).ready(function() {
		$(".select_group").select2();
		// $("#description").wysihtml5();

		$("#mainOrdersNav").addClass('active');
		$("#addOrderNav").addClass('active');

		var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' +
			'onclick="alert(\'Call your custom code here.\')">' +
			'<i class="glyphicon glyphicon-tag"></i>' +
			'</button>';

	}); // /document


	// get the product information from the server
	function getProductData(row_id) {
		var product_id = $("#product_"+row_id).val();
		if(product_id == "") {
			$("#rate_"+row_id).val("");
			$("#rate_value_"+row_id).val("");

			$("#qty_"+row_id).val("");

			$("#amount_"+row_id).val("");
			$("#amount_value_"+row_id).val("");

		} else {
			$.ajax({
				url: base_url + 'sales/getProductValueById',
				type: 'post',
				data: {product_id : product_id},
				dataType: 'json',
				success:function(response) {
					// setting the rate value into the rate input field

					$("#rate_"+row_id).val(response.price);
					$("#rate_value_"+row_id).val(response.price);

					$("#qty_"+row_id).val(1);
					$("#qty_value_"+row_id).val(1);

					var total = Number(response.price) * 1;
					total = total.toFixed(2);
					$("#amount_"+row_id).val(total);
					$("#amount_value_"+row_id).val(total);

				} // /success
			}); // /ajax function to fetch the product data
		}
	}

	function getRecords(row_id) {
		var product_id = $("#product_"+row_id).val();
		if(product_id != "") {
			$.ajax({
				url: base_url + 'sales/getProductRecords',
				type:'post',
				data: { product_id: product_id },
				dataType: 'json',
				success: function (response) {
					var html = '';
					$.each(response, function (index, value) {
						html += '<option value="'+value.price+'"> '+new Intl.NumberFormat().format(value.price)+'=>'+(value.date_time || value.date_posted)+'</option>';
					})
					$("#rate_"+row_id).html(html);
				},
			})
		}
	}

</script>
