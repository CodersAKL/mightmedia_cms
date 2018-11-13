<?php
function upgradeData($data)
{
    $data = $data['data'][0];

    if(isset($data['name']) && isset($data['value'])) {
        $data['versionData'] = checkVersion();

        return $data;
    }

    return false;
}

function moveFiles($srcDir, $destDir)
{
    $files = getFiles($srcDir);

    foreach ($files as $key => $item) {
        if($item['type'] === 'file') {
            rename($srcDir . '/' . $item['name'], $destDir . '/' . $item['name']);  
        } else if($item['type'] === 'dir') {
            if(! is_dir($destDir . '/' . $item['name'])) {
                mkdir($destDir . '/' . $item['name']);
            }

            moveFiles($srcDir . '/' . $item['name'], $destDir . '/' . $item['name']);
        }
    }
}

//AJAX funcs

function upgradeInit($data)
{
    if($data = upgradeData($data)) {
        $versionData    = $data['versionData'];
        $upgradeDir     = ROOT . 'upgrade';

        if(! is_dir($upgradeDir)) {
            return  [
                'type'      => 'error',
                'message'   => 'Nėra.' . $upgradeDir . ' direktorijos'
            ];
        }

        return [
            'type'      => 'success',
            'step'      => '1. Siunčiamas failas...',
            'nextStep'  => 2,
            'data'      => [
                'upgradeDir'    => $upgradeDir,
                'versionData'   => $versionData
            ]
        ];
       
    }

    return null;
}

function upgrade2Step($data)
{
    if(! isset($data['data'])) {
        return null;
    }

    $data = $data['data'];

    $versionData    = $data['versionData'];
    $file           = $versionData['download_link'];
    $fileParts      = explode('/', $file);
    $zipFolder      = $fileParts[4] . '-' . substr($fileParts[6], 0, -4); //mightmedia_cms-dev
    $newfile        = sys_get_temp_dir() . '/update_' . time() . '.zip';

    if (! copy($file, $newfile)) {
        return [
            'type'      => 'error',
            'message'   => 'Nepavyko atsisiųsti failo.'
        ];
    } else {
        return [
            'type'      => 'success',
            'step'      => '2. Išarchyvuojama...',
            'nextStep'  => 3,
            'data'      => [
                'zipFolder'     => $zipFolder,
                'newfile'       => $newfile,
                'upgradeDir'    => $data['upgradeDir']
            ]
        ];
    }
}

function upgrade3Step($data)
{
    if(! isset($data['data'])) {
        return null;
    }

    $data = $data['data'];
    //unzip
    $zip = new ZipArchive;
    $res = $zip->open($data['newfile']);

    if ($res === TRUE) {
        $zip->extractTo($data['upgradeDir']);
        $zip->close();

        return [
            'type'      => 'success',
            'step'      => '3. Perrašomi failai...',
            'nextStep'  => 4,
            'data'      => [
                'srcDir'        => $data['upgradeDir'] . '/' . $data['zipFolder'],
                'upgradeDir'    => $data['upgradeDir']
            ]
        ];
       
    } else {
        return [
            'type'      => 'error',
            'message'   => 'Nepavyko iš archyvuoti katalogo.'
        ];
    }
}

function upgrade4Step($data)
{
    if(! isset($data['data'])) {
        return null;
    }

    $data = $data['data'];

    try {
        moveFiles($data['srcDir'], ROOT);
        
        return [
            'type'      => 'success',
            'step'      => '4. Atnaujinama...',
            'nextStep'  => 5,
            'data'      => [
                'upgradeDir'    => $data['upgradeDir']
            ]
        ];

    } catch (\Exception $e) {

        return [
            'type'      => 'error',
            'message'   => $e->getMessage()
        ];
    }
}

if(is_file(ROOT . 'upgrade' . '/upgrade.php')) {
    include ROOT . 'upgrade' . '/upgrade.php';
}