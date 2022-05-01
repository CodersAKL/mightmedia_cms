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
					Edit
				</h2>
			</div>
			<div class="body">
				<form action="" method="post">
					<label for="title">Title</label>
					<div class="form-group">
						<div class="form-line">
							<input type="text" id="title" name="title" class="form-control" value="<?php echo ! empty($pages['title']) ? $pages['title'] : ''; ?>">
						</div>
					</div>
					<label for="slug">Slug</label>
					<div class="form-group">
						<div class="form-line">
							<input type="text" id="slug" name="slug" class="form-control" value="<?php echo ! empty($pages['slug']) ? $pages['slug'] : ''; ?>">
						</div>
					</div>
					<label for="description">Description</label>
					<div class="form-group">
						<?php echo editor('description', $pages['description']); ?>
					</div>

					<label for="active">Post activation</label>
					<div class="switch">
						<label>
							NO
							<input id="active" name="active" type="checkbox" value="1" checked>
							<span class="lever switch-col-deep-orange"></span>
							YES
						</label>
					</div>
					<br>
					<button type="submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
					<?php echo CSRFinput(); ?>
				</form>
			</div>
		</div>
	</div>
</div>