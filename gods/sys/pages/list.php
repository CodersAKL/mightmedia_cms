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
							<?php if(! empty($pages)) { ?>
								<?php foreach ($pages as $pageItem) { ?>
									<tr<?php echo (!$pageItem['active'] ? ' class=" warning"' : ''); ?> >
										<th scope="row">
											<?php echo $pageItem['id']; ?>
										</th>
										<td>
											<?php echo getRouteUrl('pages.edit', ['id' => $pageItem['id'], 'slug' => $pageItem['slug']]); ?>
											<a href="<?php echo getRouteUrl('pages.edit', ['id' => $pageItem['id'], 'slug' => $pageItem['slug']]); ?>">
												<?php echo $pageItem['title']; ?>
											</a>
										</td>
										<td>
											<?php echo $pageItem['slug']; ?>
										</td>
										<td>
											<?php echo $pageItem['active']; ?>
										</td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
									<td colspan="4">
										No items
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<!-- pagination -->
					<nav>
						<ul class="pagination">
							<?php if($page != 1) { ?>
								<li>
									<a href="<?php echo getRouteUrl('pages.list.pagination', ['page' => ($page - 1)]); ?>" class="waves-effect">
										<i class="material-icons">chevron_left</i>
									</a>
								</li>
							<?php } ?>
							<?php for($pag = 1; $pag <= $totalPages; $pag++) { ?>
								<?php
									if($page == $pag) {
										$class = ' class="active"';
									} else {
										$class = '';
									}
								?>
								<li<?php echo $class; ?>>
									<a href="<?php echo getRouteUrl('pages.list.pagination', ['page' => $pag]); ?>" class="waves-effect">
										<?php echo $pag; ?>	
									</a>
								</li>
							<?php } ?>
							
							<?php if($page != $totalPages) { ?>
								<li>
									<a href="<?php echo getRouteUrl('pages.list.pagination', ['page' => ($page + 1)]); ?>" class="waves-effect">
										<i class="material-icons">chevron_right</i>
									</a>
								</li>
							<?php } ?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>