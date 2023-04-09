<?php
include_once "./Config/database.php";
include_once "./Common/DBHelper.php";
include_once "./Services/LogService.php";
include_once "./Services/ApiService.php";


use Services\ApiService;
use Services\LogService;

$pdo = new DBHelper(TASEP_DICT_DB_HOST, TASEP_DICT_DB_DATABASE, TASEP_DICT_DB_USERNAME, TASEP_DICT_DB_PASSWORD);

$logService = new LogService($pdo);
$apiService = new ApiService($pdo,$logService);



$api_name = $_GET["api_name"];

switch($api_name)
{
    case "getQueryWordHistoryList":
        $exam_id = $_GET["exam_id"];
        $exam_question_id = $_GET["exam_question_id"];        
        $student_code = $_GET["student_code"];        
        
        $results = $apiService->getQueryWordHistoryList($exam_id,$exam_question_id,$student_code);
        echo $results;
        break;
    case "getDictWordsList":
        $exam_id = $_GET["exam_id"];
        $exam_question_id = $_GET["exam_question_id"];        
        $results = $apiService->getDictWordsList($exam_id,$exam_question_id);
        echo $results;
        break;
    case "queryWord":
//印出所有 GET 的資料
// echo "GET data:<br>";
// foreach ($_GET as $key => $value) {
//     echo $key . " = " . $value . "<br>";
// }

// //印出所有 POST 的資料
// echo "POST data:<br>";
// foreach ($_POST as $key => $value) {
//     echo $key . " = " . $value . "<br>";
// }
// exit;
        //http://localhost/TASEP_DICT/dict/api.php?api_name=queryWord&exam_id=1&exam_question_id=1&exam_question_word_id=1
        $exam_id = $_GET["exam_id"];
        $exam_code = $_GET["exam_code"];

        $exam_question_id = $_GET["exam_question_id"];        
        $exam_question_code = $_GET["exam_question_code"];
        
        $exam_question_word_id = $_GET["exam_question_word_id"];    
        $query_word = $_GET["query_word"];                        

        $student_code = $_GET["student_code"];

        $results = $apiService->queryWord($exam_id,$exam_code,$exam_question_id,$exam_question_code,$exam_question_word_id,$query_word,$student_code);
        echo $results;
        break;        
}

?>