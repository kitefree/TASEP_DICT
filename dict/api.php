<?php
spl_autoload_register(function ($class) {
    // 將命名空間的反斜線轉換為目錄路徑的斜線
    $path = str_replace('\\', '/', $class);

    // 假設類別檔案都放在 classes 目錄下
    $file = $path . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

use Common\DBHelper;
use Configs\GlobalConfig;
use Services\ApiService;
use Services\LogService;

$pdo = new DBHelper(GlobalConfig::$DBConfig["TASEP_DICT_DB_HOST"],
                    GlobalConfig::$DBConfig["TASEP_DICT_DB_DATABASE"],
                    GlobalConfig::$DBConfig["TASEP_DICT_DB_USERNAME"],
                    GlobalConfig::$DBConfig["TASEP_DICT_DB_PASSWORD"]);

$logService = new LogService($pdo);
$apiService = new ApiService($pdo,$logService);



$api_name = $_GET["api_name"];

switch($api_name)
{
     //取得使用者查詢單字歷史清單,依照施測代號、題組名稱、考生代號取得查詢歷程
    case "getStudentQueryWordHistoryList":
        $exam_code = $_GET["exam_code"];
        $exam_question_code = $_GET["exam_question_code"];   
        $student_code = $_GET["student_code"];        
        
        $results = $apiService->getStudentQueryWordHistoryList($exam_code,$exam_question_code,$student_code);
        echo $results;
        break;

    //取得字典單字清單,依照施測代號、題組名稱取得字典資料
    case "getDictWordsList":
        $exam_code = $_GET["exam_code"];
        $exam_question_code = $_GET["exam_question_code"];        
        $results = $apiService->getDictWordsList($exam_code,$exam_question_code);
        echo $results;
        break;

    //使用者查詢單字
    case "queryWord":        
        $exam_id = $_GET["exam_id"];
        $exam_code = $_GET["exam_code"];

        $exam_question_id = $_GET["exam_question_id"];        
        $exam_question_code = $_GET["exam_question_code"];

        $exam_sub_question_code = $_GET["exam_sub_question_code"];
        
        $exam_question_word_id = $_GET["exam_question_word_id"];    
        $query_word = $_GET["query_word"];                        

        $student_code = $_GET["student_code"];

        $results = $apiService->queryWord($exam_id,$exam_code,$exam_question_id,$exam_question_code,$exam_sub_question_code,$exam_question_word_id,$query_word,$student_code);
        echo $results;
        break;

    //取得查詢單字次數總計，依照施測代號、題組名稱、考生名稱取得查詢次數 
    case "getStudentQueryWordCount":        
        $exam_code = $_GET["exam_code"];
        $exam_question_code = $_GET["exam_question_code"];
        $student_code = $_GET["student_code"];        
        $results = $apiService->getStudentQueryWordCount($exam_code,$exam_question_code,$student_code);
        echo $results;
        break;
    default:
        echo "參數不正確";
        break;
}

$pdo->close();

?>