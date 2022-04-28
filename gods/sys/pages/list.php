<div class="block-header">
	<h2>
		Pages
	</h2>
</div>

<!-- Headings -->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>
					List
				</h2>
			</div>
			<div class="body">
				<?php //d($pages); ?>
				<div class="body table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>title</th>
								<th>slug</th>
								<th>active</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach ($pages as $page) { ?>
								<tr>
									<th scope="row">
										<?php echo $page['id']; ?>
									</th>
									<td>
										<?php echo $page['title']; ?>
									</td>
									<td>
										<?php echo $page['slug']; ?>
									</td>
									<td>
										<?php echo $page['active']; ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>