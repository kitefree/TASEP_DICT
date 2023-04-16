<?php
namespace Configs;

date_default_timezone_set('Asia/Taipei');

class GlobalConfig{
    public static $DBConfig = array(
        "TASEP_DICT_DB_HOST" => "localhost",
        "TASEP_DICT_DB_DATABASE" => "TASEP_DICT",
        "TASEP_DICT_DB_USERNAME" => "root",
        "TASEP_DICT_DB_PASSWORD" => "0000",
    );    
}




?>