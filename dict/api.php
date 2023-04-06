<?php
include_once "./Config/database.php";
include_once "./Common/DBHelper.php";
include_once "./Services/ApiService.php";

use Services\ApiService;


$db = new DBHelper(TASEP_DB_HOST, TASEP_DB_DATABASE, TASEP_DB_USERNAME, TASEP_DB_PASSWORD);

$apiService = new ApiService($db);



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
        //待寫
        $results = $apiService->queryWord();
        break;        
}

?>