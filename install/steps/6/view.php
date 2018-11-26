<?php echo $lang['setup']['time_zone_info']; ?>
<br /><br />
<form name="tz" method="post" action="">
    <select name="time_zone" class="form-control show-tick" name="language">
        <?php foreach ( $timezone as $tz )
            echo '<option ' . ( $tz == 'Europe/Vilnius' ? 'selected' : '' ) . ' value="' . $tz . '">' . $tz;
        ?>
    </select>
    <div class="card--bottom">
        <button name="tzone" type="submit" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    </div>
</form>