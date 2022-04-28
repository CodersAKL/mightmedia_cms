<?php
	global $headerData;
?>

<div class="block-header">
	<h2>
		<?php echo $headerData['pageName']; ?>
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
					<form action="" method="post">
						<label for="title">Title</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" id="title" name="title" class="form-control" placeholder="Enter your post title">
							</div>
						</div>
						<label for="description">Description</label>
						<div class="form-group">
							<?php echo editor('description'); ?>
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


				<?php } else { ?>
				<?php } ?>

			</div>
		</div>
	</div>
</div>