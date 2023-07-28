<aside class="main-sidebar">
	<sectino class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			<li id="dashboardMainMenu">
				<a href="<?php echo base_url('dashboard') ?>">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				</a>
			</li>

			<?php if ($user_permission): ?>
				<?php if (in_array($user_permission, array(1, 2))) { ?>
					<li class="treeview" id="mainProductNav">
						<a href="#">
							<i class="fa fa-cube"></i>
							<span>Products</span>
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li id="addProductNav"><a href="<?php echo base_url('products/create') ?>"><i class="fa fa-circle-o"></i> Add Product</a></li>
							<li id="manageProductNav"><a href="<?php echo base_url('products') ?>"><i class="fa fa-circle-o"></i> Manage Products</a></li>
						</ul>
					</li>

					<li class="treeview" id="mainOrdersNav">
						<a href="#">
							<i class="fa fa-dollar"></i>
							<span>Sales</span>
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li id="addOrderNav"><a href="<?php echo base_url('sales/create') ?>"><i class="fa fa-circle-o"></i> Record Sales</a></li>
							<li id="manageOrdersNav"><a href="<?php echo base_url('sales') ?>"><i class="fa fa-circle-o"></i> Manage Sales</a></li>
						</ul>
					</li>
				<?php } ?>
			<?php endif; ?>
			<li><a href="<?php echo base_url('auth/logout') ?>"><i class="glyphicon glyphicon-log-out"></i> <span>Logout</span></a></li>
		</ul>
	</sectino>
</aside>
