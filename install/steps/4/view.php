<?php echo $lang['setup']['mysql_info']; ?>
<h2 class="card-inside-title">
	<?php echo $lang['setup']['mysql_connect']; ?>
</h2>

<form name="mysql" method="post" action="">
	<div class="form-group form-float">
		<div class="form-line">
			<input name="host" type="text" class="form-control" value="<?php echo (isset($_SESSION['mysql']['host']) ? $_SESSION['mysql']['host'] : 'localhost'); ?>">
			<label class="form-label">
				<?php echo $lang['setup']['mysql_host']; ?>
			</label>
		</div>
	</div>
	<div class="form-group form-float">
		<div class="form-line">
			<input name="user" type="text" class="form-control" value="<?php echo (isset($_SESSION['mysql']['user']) ? $_SESSION['mysql']['user'] : 'root'); ?>">
			<label class="form-label">
				<?php echo $lang['setup']['mysql_user']; ?>
			</label>
		</div>
	</div>
	<div class="form-group form-float">
		<div class="form-line">
			<input name="pass" type="password" class="form-control" value="<?php echo (isset($_SESSION['mysql']['pass']) ? $_SESSION['mysql']['pass'] : ''); ?>">
			<label class="form-label">
				<?php echo $lang['setup']['mysql_pass']; ?>
			</label>
		</div>
	</div>
	<div class="form-group form-float">
		<div class="form-line">
			<input name="db" type="text" class="form-control" value="<?php echo (isset($_SESSION['mysql']['db']) ? $_SESSION['mysql']['db'] : 'mightmedia'); ?>">
			<label class="form-label">
				<?php echo $lang['setup']['mysql_db']; ?>
			</label>
		</div>
	</div>
	<div class="form-group form-float">
		<div class="form-line">
			<input name="prefix" type="text" class="form-control" value="<?php echo (isset($_SESSION['mysql']['prefix']) ? $_SESSION['mysql']['prefix'] : random()); ?>">
			<label class="form-label">
				<?php echo $lang['setup']['mysql_prfx']; ?>
			</label>
		</div>
	</div>
	<div class="card--bottom">
		<?php if(! empty($next_mysql)) { ?>
			<button <?php echo isset($next_mysql['name']) ? 'name="' . $next_mysql['name'] . '"' : ''; ?> 
			type="<?php echo isset($next_mysql['type']) ? $next_mysql['type'] : 'button'; ?>" 
			class="btn bg-deep-orange waves-effect" 
			<?php echo isset($next_mysql['go']) ? 'onclick="Go(' . $next_mysql['go'] . ');"' : ''; ?>>
				<span>
					<?php echo isset($next_mysql['value']) ? $next_mysql['value'] : 'Submit'; ?>
				</span>    
				<i class="material-icons">keyboard_arrow_right</i> 
			</button>
		<?php } ?>
	</div>
	<?php if (isset($mysql_info)){ ?>
		<h2 class="card-inside-title">
			<?php echo $lang['user']['user_info']; ?>
		</h2>
		<?php echo $mysql_info; ?>
	<?php } ?>
</form>