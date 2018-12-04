<?php
if(! empty($error)) {
    notifyMsg($error);
}
?>
<div class="setup-info">
    <?php echo $lang['setup']['admin_info']; ?>
</div>
<form name="admin_form" method="post" action="">
    <div class="form-group form-float">
		<div class="form-line">
			<input name="user" type="text" class="form-control" value="<?php echo (isset($user) ? $user : ''); ?>">
			<label class="form-label">
				<?php echo $lang['reg']['username']; ?>
			</label>
		</div>
    </div>
    <div class="form-group form-float">
		<div class="form-line">
			<input name="pass" type="password" class="form-control">
			<label class="form-label">
				<?php echo $lang['reg']['password']; ?>
			</label>
		</div>
    </div>
    <div class="form-group form-float">
		<div class="form-line">
			<input name="pass2" type="password" class="form-control">
			<label class="form-label">
				<?php echo $lang['reg']['confirmpassword']; ?>
			</label>
		</div>
    </div>
    <div class="form-group form-float">
		<div class="form-line">
			<input name="email" type="text" class="form-control" value="<?php echo (isset($email) ? $email : ''); ?>">
			<label class="form-label">
				<?php echo $lang['reg']['email']; ?>
			</label>
		</div>
    </div>
    <div class="card--bottom">
        <button name="acc_create" type="submit" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    </div>
</form>