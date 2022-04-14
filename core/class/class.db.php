<?php

// require_once ROOT . 'core/libs/vendor/paragonie/easydb/src/Factory.php';
// require_once ROOT . 'core/libs/vendor/paragonie/easydb/src/EasyStatement.php';
// require_once ROOT . 'core/libs/vendor/paragonie/easydb/src/EasyDB.php';
require_once ROOT . 'core/libs/vendor/autoload.php';

// class_alias(ParagonIE\EasyDB\EasyDB::class, 'DB');
class_alias(ParagonIE\EasyDB\Factory::class, 'DBFactory');
// var_dump(DBFactory::class);

