<?php
include 'header.php';
$user = getUserMail($_SESSION[SLAPTAS]['id']);
?>
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <?php echo avatar($user['email'], 48); ?>
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                        if(! empty($user['vardas']) && ! empty($user['pavarde'])) {
                            echo $user['vardas'] . ' ' . $user['pavarde'];
                        } else {
                            echo $user['nick'];
                        }
                    ?>
                </div>
                <div class="email"><?php echo $user['email']; ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="<?php echo url( '?id,' . $conf['puslapiai']['view_user.php']['id'] . ';' . $user['nick'] ); ?>"><i class="material-icons">person</i><?php echo $lang['user']['user_profile']; ?></a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="<?php echo $lang['user']['logout']; ?>"><i class="material-icons">input</i><?php echo $lang['user']['logout']; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <?php foreach (getAdminPages() as $id => $file) { ?>
                    <?php
                        $file       = basename( $file, '.php' );
                        $inArray    = array( 'config', 'meniu', 'logai', 'paneles', 'vartotojai', 'komentarai', 'banai', 'balsavimas', 'siustis', 'duk' );
                        $notArray   = array( 'index', 'pokalbiai', 'main', 'search', 'antivirus' );
                    ?>
                    <?php if ((isset( $conf['puslapiai'][$file . '.php']['id']) || in_array( $file, $inArray)) && !in_array($file, $notArray)) { ?>
                        <li <?php echo ( isset( $_GET['a'] ) && $_GET['a'] == $id ? 'class="active"' : '' ); ?>>
                            <a href="<?php echo url( '?id,999;a,' . $id ); ?>">
                                <i class="material-icons">home</i>
                                <span><?php echo ( isset( $lang['admin'][$file] ) ? $lang['admin'][$file] : nice_name( $file ) ); ?></span>
                            </a>
                            <?php if(isset( $_GET['a'] ) && $_GET['a'] == $id) { ?>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <i class="material-icons">widgets</i>
                                    <span>Widgets</span>
                                </a>
                                <ul class="ml-menu" id="sub-menu-admin">
                                    <li>
                                        <a href="pages/widgets/cards/basic.html">Basic</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/cards/colored.html">Colored</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/cards/no-header.html">No Header</a>
                                    </li>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php } ?>
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
    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
            <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                <ul class="demo-choose-skin">
                    <li data-theme="red" class="active">
                        <div class="red"></div>
                        <span>Red</span>
                    </li>
                    <li data-theme="pink">
                        <div class="pink"></div>
                        <span>Pink</span>
                    </li>
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>
                    <li data-theme="deep-purple">
                        <div class="deep-purple"></div>
                        <span>Deep Purple</span>
                    </li>
                    <li data-theme="indigo">
                        <div class="indigo"></div>
                        <span>Indigo</span>
                    </li>
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="light-blue">
                        <div class="light-blue"></div>
                        <span>Light Blue</span>
                    </li>
                    <li data-theme="cyan">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="teal">
                        <div class="teal"></div>
                        <span>Teal</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="light-green">
                        <div class="light-green"></div>
                        <span>Light Green</span>
                    </li>
                    <li data-theme="lime">
                        <div class="lime"></div>
                        <span>Lime</span>
                    </li>
                    <li data-theme="yellow">
                        <div class="yellow"></div>
                        <span>Yellow</span>
                    </li>
                    <li data-theme="amber">
                        <div class="amber"></div>
                        <span>Amber</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="deep-orange">
                        <div class="deep-orange"></div>
                        <span>Deep Orange</span>
                    </li>
                    <li data-theme="brown">
                        <div class="brown"></div>
                        <span>Brown</span>
                    </li>
                    <li data-theme="grey">
                        <div class="grey"></div>
                        <span>Grey</span>
                    </li>
                    <li data-theme="blue-grey">
                        <div class="blue-grey"></div>
                        <span>Blue Grey</span>
                    </li>
                    <li data-theme="black">
                        <div class="black"></div>
                        <span>Black</span>
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="settings">
                <div class="demo-settings">
                    <p>GENERAL SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Report Panel Usage</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Email Redirect</span>
                            <div class="switch">
                                <label><input type="checkbox"><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                    <p>SYSTEM SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Notifications</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Auto Updates</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                    <p>ACCOUNT SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Offline</span>
                            <div class="switch">
                                <label><input type="checkbox"><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Location Permission</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
    <!-- #END# Right Sidebar -->
</section>

<section class="content">
    <div class="container-fluid">
        <?php
            adminPages();
        ?>
        <!-- CPU Usage -->
        
        <!-- #END# CPU Usage -->

    </div>
</section>
<?php include 'footer.php'; ?>