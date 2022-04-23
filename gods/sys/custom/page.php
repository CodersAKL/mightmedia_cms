<?php
// d($postType);
// d($viewData);
// d($type);
// d($page);

?>
<div class="block-header">
	<h2>
		<?php echo $postType['label']; ?>
	</h2>
</div>

<!-- Headings -->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>
					<?php echo $page; ?>
				</h2>
			</div>
			<div class="body">
				<?php if(! empty($page) && $page == 'create') { ?>
					<form>
						<?php if($postType['fields']['title']) { ?>
							<label for="title">Title</label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="title" name="title" class="form-control" placeholder="Enter your post title">
								</div>
							</div>
						<?php } ?>
						<?php if($postType['fields']['excerpt']) { ?>
							<label for="excerpt">Excerpt</label>
							<div class="form-group">
								<div class="form-line">
									<textarea id="excerpt" name="excerpt" rows="1" class="form-control no-resize auto-growth" placeholder="Enter your excerpt"></textarea>
								</div>
							</div>
						<?php } ?>
						<?php if($postType['fields']['editor']) { ?>
							<label for="description">Description</label>
							<div class="form-group">
								<?php echo editor('description'); ?>
							</div>
						<?php } ?>

						<label for="media">Media</label>
						<div class="form-group">
							<button type="button" class="btn btn-default waves-effect" data-toggle="modal" data-target="#largeModal">
								Media
							</button>
						</div>

						




						<label for="active">Post activation</label>
						<div class="switch">
							<label>
								NO
								<input id="active" name="active" type="checkbox" checked>
								<span class="lever switch-col-deep-orange"></span>
								YES
							</label>
						</div>
						<br>
						<button type="button" class="btn btn-primary m-t-15 waves-effect">Submit</button>
					</form>


						<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" >
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="largeModalLabel">Modal title</h4>
									</div>
									<div class="modal-body">
										<form action="<?php echo adminUrl() . getRoute('ajax', 'mediaUpload'); ?>" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
											<div class="dz-message">
												<div class="drag-icon-cph">
													<i class="material-icons">touch_app</i>
												</div>
												<h3>Drop files here or click to upload.</h3>
												<em>(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</em>
											</div>
											<div class="fallback">
												<input name="file" type="file" multiple />
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-link waves-effect">SAVE CHANGES</button>
										<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
									</div>
								</div>
							</div>
						</div>


				<?php } else { ?>
				<?php } ?>

			</div>
		</div>
	</div>
</div>
<!-- #END# Headings -->