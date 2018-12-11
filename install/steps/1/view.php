<form name="lang" method="post" action="">
    <label for="language">
        <?php echo $lang['setup']['lang']; ?>
    </label>
    <div class="form-group">
        <div class="form-line">
            <select class="form-control show-tick" name="language" id="language">
                <option value="lt.php">Lietuvių</option>
                <option value="en.php">English</option>
            </select>
        </div>
    </div>

    <label for="time_zone">
        <?php echo $lang['setup']['time_zone']; ?>
    </label>
    <div class="form-group">
        <div class="form-line">
            <select class="form-control show-tick" name="time_zone" id="time_zone">
                <?php foreach ( $timezone as $tz )
                    echo '<option ' . ( $tz == 'Europe/Vilnius' ? 'selected' : '' ) . ' value="' . $tz . '">' . $tz;
                ?>
            </select>
        </div>
    </div>
    <?php echo $lang['setup']['time_zone_info']; ?>

    <div class="card--bottom">
        <button name="go" type="submit" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    </div>
</form>