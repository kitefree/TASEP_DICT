<?php
namespace Services;

use stdClass;
use DateTime;
class StudentExamService{

    private $_pdo;
    private $_logService;
    
    public function __construct($pdo,$logService) {
        $this->_pdo = $pdo;
        $this->_logService = $logService;
    }

    //頁面載入相關數據準備
    public function pageLoad($sunnetData)
    {        
        $created_at = (new DateTime('now'))->format('Y-m-d H:i:s');

        $results = new StdClass();

        $querySQL01 = "SELECT e.id as exam_id,e.exam_code,eq.id as exam_question_id, eq.question_code,eq.query_limit 
        FROM exams as e 
        JOIN exam_questions as eq ON e.id = eq.exam_id 
        WHERE e.exam_code = ? 
        AND eq.question_code = ?";
        
        
        $results_01 = $this->_pdo->query($querySQL01,[$sunnetData->exam_code,$sunnetData->exam_question_code])->first();
        
        $querySQL02 = "SELECT * 
        FROM student_query_logs        
        WHERE exam_id = ? 
        AND exam_question_id = ? 
        AND student_code = ? 
        AND type = 2";        

        $results_02 = $this->_pdo->query($querySQL02,[$results_01->exam_id,
                                                      $results_01->exam_question_id,
                                                      $sunnetData->student_code])->all();

        $results->exam_id = $results_01->exam_id;
        $results->exam_code = $sunnetData->exam_code;
        $results->exam_question_id = $results_01->exam_question_id;
        $results->exam_question_code = $sunnetData->exam_question_code;
        $results->student_code = $sunnetData->student_code;
        $results->query_limit = $results_01->query_limit;
        $results->query_count = count($results_02);

        $log = [
            'created_at' => $created_at,
            'exam_code' => $sunnetData->exam_code,
            'exam_question_code' => $sunnetData->exam_question_code,
            'student_code' => $sunnetData->student_code,
            'exam_id' => $sunnetData->exam_id,
            'exam_question_id' => $sunnetData->exam_question_id,
            'exam_question_word_id' => NULL,
            'word' => NULL,
            'meta_keyword' => NULL,
            'type' => "1",
            'query_word' => NULL,
            'query_time' => $created_at,
        ];
        
        //$rowCount = $this->InsertQueryLog($log);
        $rowCount = $this->_logService->InsertQueryLog($log);
        
        return $results;
    }

}

?>