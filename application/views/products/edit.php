<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Manage
			<small>Products</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Products</li>
		</ol>
	</section>

	<section class="content">
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
						<h3 class="box-title">Edit Product</h3>
					</div>

					<form role="form" action="<?php base_url('products/update') ?>" method="post">
						<div class="box-body">
							<?php echo validation_errors(); ?>

							<div class="form-group">
								<label for="product_name">Product name</label>
								<input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" value="<?php echo $product_data['name']; ?>"  autocomplete="off"/>
							</div>

							<div class="form-group">
								<label for="price">Price</label>
								<input type="text" class="form-control" id="price" name="price" placeholder="Enter price" value="<?php echo $product_data['price']; ?>" autocomplete="off" />
							</div>

							<div class="form-group">
								<label for="qty">Qty</label>
								<input type="text" class="form-control" id="qty" name="qty" placeholder="Enter Qty" value="<?php echo $product_data['qty']; ?>" autocomplete="off" />
							</div>

							<div class="form-group">
								<label for="store">Availability</label>
								<select class="form-control" id="availability" name="availability">
									<option value="1" <?php if($product_data['availability'] == 1) { echo "selected='selected'"; } ?>>Yes</option>
									<option value="2" <?php if($product_data['availability'] != 1) { echo "selected='selected'"; } ?>>No</option>
								</select>
							</div>
						</div>

						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Save Changes</button>
							<a href="<?php echo base_url('products/') ?>" class="btn btn-warning">Back</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		$(".select_group").select2();
		$("#description").wysihtml5();

		$("#mainProductNav").addClass('active');
		$("#manageProductNav").addClass('active');

</script>
