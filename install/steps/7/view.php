<form name="url_form" method="post" action="">
    <label for="time_zone">
        <?php echo $lang['setup']['url_info']; ?>
    </label>
    <div class="form-group">
        <div class="form-line">
           <input type="text" class="form-control" placeholder="http://" name="main_url">
        </div>
    </div>
    <div class="card--bottom">
        <button type="submit" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    </div>
</form>