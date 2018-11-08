<?php

if(! empty($_POST) && isset($_POST['upgrade']) && $_POST['upgrade'] == 1 && $versionData = checkVersion()) {

    $upgradeDir = ROOT . '/upgrade';
    if(! is_dir($upgradeDir)) {
        notifyMsg(
            [
                'type'      => 'error',
                'message'   => 'Nėra.' . $upgradeDir . ' direktorijos'
            ]
        );
    }
    //download file
    $file       = $versionData['download_link'];
    $fileParts  = explode('/', $file);
    $zipFolder  = $fileParts[4] . '-' . substr($fileParts[6], 0, -4); //mightmedia_cms-dev
    $newfile    = sys_get_temp_dir() . '/update_' . time() . '.zip';

    $updates[] = '1. Siunčiamas failas...';

    if (! copy($file, $newfile)) {
        notifyMsg(
            [
                'type'      => 'error',
                'message'   => 'Nepavyko atsisiųsti failo.'
            ]
        );
    }

    $updates[] = '2. Išarchyvuojama...';
    //unzip
    $zip = new ZipArchive;
    $res = $zip->open($newfile);

    if ($res === TRUE) {
        $zip->extractTo($upgradeDir);
        $zip->close();

        $updates[] = '3. Vyksta atnaujinimas...';

        if (! copy($zipFolder, ROOT)) {
            notifyMsg(
                [
                    'type'      => 'error',
                    'message'   => 'Nepavyko atnaujinti failų.'
                ]
            );
        }

        if(is_file($upgradeDir . '/upgrade.php')) {
            include $upgradeDir . '/upgrade.php';
        }

    } else {
        notifyMsg(
            [
                'type'      => 'error',
                'message'   => 'Nepavyko iš archyvuoti katalogo.'
            ]
        );
    }

    ?>
    <div class="card">
        <div class="header">
            <h2>
                Atnaujinimai
            </h2>
        </div>
        <div class="body">
            <ul class="list-group">
                <?php if(! empty($updates)) { ?>
                    <?php foreach($updates as $update) { ?>
                        <li class="list-group-item">
                            <?php echo $update; ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>

<?php } else { ?>
    <div class="alert alert-warning alert---with-cta">
        <div class="alert--info">
            <i class="material-icons">warning</i>
            <strong>Nepamirškite!</strong>
            Prieš atnaujindami sistemą rekomenduojame pasidaryti duomenų bazės ir failų kopijas.
        </div>
        <div class="alert--cta">
            <form action="" method="post">
                <input type="hidden" name="upgrade" value="1">
                <button type="submit" class="btn btn-default waves-effect" onclick="return confirm('Are you sure?');">
                    Pradėti atnaujinimą
                </button>
            </form>
        </div>
    </div>
<?php

}