<?php echo $lang['setup']['file_check_info1']; ?><br />
<h2 class="card-inside-title">
    <?php echo $lang['setup']['file_check_legend']; ?>
</h2>
<i class="material-icons error-icon">cancel</i>
<?php echo $lang['setup']['file_check_info2']; ?>
<br />
<i class="material-icons tick-icon">check_circle</i>
<?php echo sprintf($lang['setup']['file_check_info3'], '<i class="material-icons error-icon">cancel</i>'); ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th width="10%"><?php echo $lang['setup']['file'];?></th>
            <th width="5%"><?php echo $lang['setup']['point'];?></th>
            <th width="35%"><?php echo $lang['setup']['about_error'];?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($chmod_files as $key => $file) { ?>
            <?php
                $permissions = substr(sprintf('%o', fileperms($file)), -4);
                if ($permissions != 777 && $permissions != 666 && ! is_writable($file)) {
                    $file_error = 'Y';
                }
            ?>
            <tr>
                <td>
                    <?php echo $file; ?>
                </td>
                <td>
                    <?php if(($permissions == 777) || ($permissions == 666) || is_writable($file)) { ?>
                        <i class="material-icons tick-icon">check_circle</i>
                    <?php } else { ?>
                        <i class="material-icons error-icon">cancel</i>
                    <?php } ?>
                </td>
                <td>
                    <?php if(($permissions == 777) || ($permissions == 666) || is_writable($file)) { ?>
                        --
                    <?php } else { ?>
                        <?php echo $lang['setup']['chmod_777']; ?>
                        <strong><?php echo $file; ?></strong> 
                        <?php echo $lang['setup']['chmod_777_2']; ?>
                        <strong><?php echo $permissions; ?></strong>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div class="card--bottom">
    <?php if (isset($file_error ) && $file_error == 'Y') { ?>
        <button name="agree"  type="reset" class="btn bg-deep-orange waves-effect">
            <span>
                <?php echo $lang['setup']['reload']; ?>
            </span>    
            <i class="material-icons">refresh</i> 
        </button>
    <?php } else { ?>
        <button name="agree" type="button" class="btn bg-deep-orange waves-effect" onclick="Go(3);">
            <span>
                <?php echo $lang['setup']['next']; ?>
            </span>    
            <i class="material-icons">keyboard_arrow_right</i> 
        </button>
    <?php } ?>
</div>