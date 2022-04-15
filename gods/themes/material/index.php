<?php include 'header.php'; ?>
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <?php echo avatar(loggedUser('email'), 48); ?>
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                        $userName = loggedUser('name', true);
                        if(! empty($userName)) {
                            echo $userName;
                        } else {
                            echo loggedUser('email');
                        }
                    ?>
                </div>
                <div class="email"><?php echo loggedUser('email'); ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li> <a>Profilis</a></li>

                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="/<?php echo getLangText('user', 'logout'); ?>"><i class="material-icons">input</i><?php echo getLangText('user', 'logout'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">
                    <?php echo getLangText('admin', 'menu_main'); ?>
                </li>
				<?php
					$adminMenu = adminMenu();
				?>
                <?php foreach ($adminMenu as $id => $adminMenuItem) { ?>
                    <li>
                        <a href="<?php echo $adminMenuItem['url']; ?>">
							<i class="material-icons"><?php echo adminMenuIcon($id); ?></i>
							<span><?php echo $adminMenuItem['title']; ?></span>
						</a>
                    </li>
                <?php } ?>

            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <!-- <div class="copyright">
                &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
            </div> -->
            <div class="version">
                <b>Versija: </b> <?php echo versija(); ?>
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>

<section class="content">
    <div class="container-fluid">
        <?php adminPages(); ?>
    </div>
</section>

<?php include 'footer.php'; ?>