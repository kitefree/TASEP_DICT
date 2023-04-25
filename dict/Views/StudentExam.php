<?php
/*
旭聯系統環境
Mysql : 8.0.15    sql_mode 未特別定義 enginee=innodb
Php 使用 php-fpm 模式 7.3.12

測試URL
http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=FT2247&exam_question_code=R22018&exam_sub_question_code=R22018-1&student_code=s111123450017

*/


spl_autoload_register(function ($class) {
    // 將命名空間的反斜線轉換為目錄路徑的斜線
    $path = str_replace('\\', '/', $class);

    $parent_dir = dirname(__DIR__);
    // 假設類別檔案都放在 classes 目錄下
    $file = $parent_dir . '/' . $path . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

use Common\DBHelper;
use Configs\GlobalConfig;
use Services\LogService;
use Services\StudentExamService;


$results = new stdClass();

$sunnetData = new stdClass();


/* 旭聯協助傳入資訊 START */

//施測編碼   
$sunnetData->exam_code              = $_GET["exam_code"];
//題組代號編碼   
$sunnetData->exam_question_code     = $_GET["exam_question_code"];
//子題代號編碼
$sunnetData->exam_sub_question_code = $_GET["exam_sub_question_code"];
//考生編號
$sunnetData->student_code           = $_GET["student_code"];


/* 旭聯協助傳入資訊 END */

$randomVersionNumber = time();

$pdo = new DBHelper(GlobalConfig::$DBConfig["DB_HOST"],
                    GlobalConfig::$DBConfig["DB_DATABASE"],
                    GlobalConfig::$DBConfig["DB_USERNAME"],
                    GlobalConfig::$DBConfig["DB_PASSWORD"]);


$logService = new LogService($pdo);
$studentExamService = new StudentExamService($pdo,$logService);

$results = $studentExamService->pageLoad($sunnetData);

$pdo->close();

?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TASEP - DICT</title>
    <link href="./lib/css/bootstrap-icons.css" rel="stylesheet">
    <link href="./lib/css/bootstrap.min.css" rel="stylesheet">    
    <link href="./lib/css/jquery-ui.min.css" rel="stylesheet">
    <link href="./lib/css/jquery-ui.theme.min.css" rel="stylesheet">
    
    <script src="./lib/js/bootstrap.bundle.min.js"></script>
    <script src="./lib/js/autocomplete.js"></script>
    <script src="./lib/js/axios.min.js"></script>
    <script src="./lib/js/jquery.js"></script>
    <script src="./lib/js/jquery-ui.min.js"></script>  
    <style>
        thead tr th {
  position: sticky;
  top: 0;
}
/* Set a fixed scrollable wrapper */
.tableWrap {
  height: 350px;  
  overflow: auto;
}
    </style>
</head>

<body>


    <div class="container mt-4 tasep_dict_container">
        <div class="tasep_dict_circle"> 
            <i class="tasep_dict_icon"></i>
        </div>
        
        <div class="row mt-4 justify-content-center">
            <div class="col-md-12 mx-4">
                <div class="row">
                    <div class="col-md-12 text-end mb-2 tasep_dict_query_limit_text">
                    剩餘查詢次數：<span class="tasep_dict_query_limit_number"><?= $results->query_limit ?></span>&nbsp;次
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 position-relative">                        
                        <input type="text" class="form-control tasep_dict_txtSearch" id="txtSearch" />
                        <!-- <i class="bi bi-search position-absolute tasep_dict_search_icon"></i> -->
                    </div>
                </div>
                
            </div>
          
        </div>
        
            <div class="row mt-4 justify-content-center">
                <div class="col-md-12">
                    <div class="tableWrap mb-3">
                    <table class="table tasep_dict_table" id="wordList">
                        <thead>
                        <tr>
                            <th>單字</th>
                            <th>中文</th>
                            <th>衍生</th>
                        </tr>
                        </thead>
                        
                        <tbody></tbody>
                    </table>
                    </div>
                </div>
            </div>
        
        <input type="hidden" name="exam_id" id="exam_id" value="<?= $results->exam_id ?>" />    
        <input type="hidden" name="exam_code" id="exam_code" value="<?= $results->exam_code ?>" />
        <input type="hidden" name="exam_question_id" id="exam_question_id" value="<?= $results->exam_question_id ?>" />
        <input type="hidden" name="exam_question_code" id="exam_question_code" value="<?= $results->exam_question_code ?>" />
        <input type="hidden" name="exam_sub_question_code" id="exam_sub_question_code" value="<?= $results->exam_sub_question_code ?>" />
        <input type="hidden" name="student_code" id="student_code" value="<?= $results->student_code ?>" />
        <input type="hidden" name="query_limit" id="query_limit" value="<?= $results->query_limit ?>" />
        <input type="hidden" name="query_count" id="query_count" value="<?= $results->query_count ?>" />        

    </div>

    <!-- <link href="./lib/css/studentexam.css?v=<?php echo $randomVersionNumber;?>" rel="stylesheet">
    <script src="./lib/js/StudentExam.js?v=<?php echo $randomVersionNumber;?>"></script> -->
    <link href="./lib/css/studentexam.css" rel="stylesheet">
    <script src="./lib/js/StudentExam.js"></script>


</body>

</html>
