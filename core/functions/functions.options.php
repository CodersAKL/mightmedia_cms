<?php

function getOption($key)
{
    $where = [
        'key'   => $key
    ];

    $data = dbRow('options', $where);

    return $data['val'];
}