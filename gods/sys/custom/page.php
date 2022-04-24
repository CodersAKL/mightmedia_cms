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
							<div class="media-thumb"></div>
							<input type="hidden" class="media-thumbInput">
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
						<?php echo CSRFinput(); ?>
					</form>


						<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-mm-media>
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="largeModalLabel">
											Media
										</h4>
									</div>
									<div class="modal-body">
										<ul class="nav nav-tabs tab-nav-right" role="tablist">
											<li role="presentation" class="active">
												<a href="#mediaSelect" data-toggle="tab" aria-expanded="true">
													Select
												</a>
											</li>
											<li role="presentation">
												<a href="#mediaUpload" data-toggle="tab" aria-expanded="false">
													Upload
												</a>
											</li>
										</ul>
										<div class="tab-content">
											<div role="tabpanel" class="tab-pane fade active in" id="mediaSelect">
												<?php if($mediaList = mediaList()) { ?>
													<div class="row">
														<?php foreach($mediaList as $mediaItem) { ?>
															<div class="col-xs-6 col-md-3">
																<a href="<?php echo siteUrl() . $mediaItem['path'] . $mediaItem['name']; ?>" class="thumbnail" style="word-break: break-all;" data-mm-media-link="<?php echo htmlspecialchars(json_encode($mediaItem, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>">
																	<h3>
																		<?php echo $mediaItem['name']; ?>
																	</h3>
																	<?php if($mediaItem['type'] == 'image') { ?>
																		<img src="<?php echo siteUrl() . $mediaItem['path'] . $mediaItem['name']; ?>" class="img-responsive">
																	<?php } else { ?>
																		<i class="material-icons">description</i>
																	<?php } ?>	
																</a>
															</div>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="mediaUpload">
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
													<?php echo CSRFinput(); ?>
												</form>
											</div>
										</div>
										
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