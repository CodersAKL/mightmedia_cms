<form name="lang" method="post" action="">
    <h2 class="card-inside-title">
        Select language / Pasirinkite kalbą
    </h2>

    <select class="form-control show-tick" name="language">
        <option value="lt.php">Lietuvių</option>
        <option value="en.php">English</option>
    </select>

    <div class="card--bottom">
        <button name="go" type="submit" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    </div>
</form>