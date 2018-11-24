<form name="setup" action="" class="form-license">
	<h2 class="card-inside-title">
		<?php echo $lang['setup']['liceanse']; ?>
    </h2>
	<div class="form-group">
		<div class="form-line">
			<textarea name="copy" rows="15" class="form-control no-resize" readonly="readonly"><?php include 'license.txt'; ?></textarea>
		</div>
	</div>
	<input name="agree_check" type="checkbox" id="agree_check" class="filled-in" value="ON" checked>
	<label for="agree_check"><?php echo $lang['setup']['agree']; ?></label>
    <div class="card--bottom">
        <button name="agree" type="submit" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    </div>
</form>
<script type="text/javascript">
	var form = document.querySelector('.form-license');

	form.addEventListener('submit', function(e) {
		e.preventDefault();
		if (form.agree_check.checked == true) {
			Go(2);
		} else {
			alert('<?php echo $lang['setup']['agree_please'];?>');
		}
	});
</script>