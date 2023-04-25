<?php
namespace Services;

/* 專案使用 Start */
use Enums\LogType;
/* 專案使用 End */

/* PHP 內鍵 Start */
use stdClass;
use DateTime;
/* PHP 內鍵 END Start */

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
        // print_r($sunnetData);
        // exit;

        //1. 組完成的資訊render回前端
        $results = new StdClass();

        $querySQL01 = "SELECT e.id as exam_id,e.exam_code,eq.id as exam_question_id, eq.question_code,eq.query_limit 
        FROM exams as e 
        JOIN exam_questions as eq ON e.id = eq.exam_id 
        WHERE e.exam_code = ? 
        AND eq.question_code = ?";
                
        $results01 = $this->_pdo->query($querySQL01,[$sunnetData->exam_code,$sunnetData->exam_question_code])->first();
                
        if (!isset($results01) || empty($results01)) {
            echo "參數錯誤";
            exit;
        }

        

        //from sunnetData
        $results->exam_code = $sunnetData->exam_code;
        $results->exam_question_code = $sunnetData->exam_question_code;
        $results->exam_sub_question_code = $sunnetData->exam_sub_question_code;
        $results->student_code = $sunnetData->student_code;

        //from query data
        $results->exam_id = $results01->exam_id;
        $results->exam_question_id = $results01->exam_question_id;
        
        if($sunnetData->exam_code == 'tasep_test' && $sunnetData->exam_question_code == 'R23023')    
        {
            $results->query_limit = $results01->query_limit;            
        }
        else if($sunnetData->exam_code == 'tasep_test')    
        {
            $results->query_limit = $results01->query_limit;
            $results->query_limit = 999;
        }        
        else {
            $results->query_limit = $results01->query_limit;
        }
        

        $results->query_count = $this->getStudentQueryWordCount($sunnetData);
        

        //2.寫log
        $created_at = (new DateTime('now'))->format('Y-m-d H:i:s');
        $log = [
            'created_at' => $created_at,
            'exam_code' => $sunnetData->exam_code,
            'exam_question_code' => $sunnetData->exam_question_code,
            'exam_sub_question_code' => $sunnetData->exam_sub_question_code,
            'student_code' => $sunnetData->student_code,
            'exam_id' => $results01->exam_id,
            'exam_question_id' => $results01->exam_question_id,
            'exam_question_word_id' => NULL,
            'word' => NULL,
            'meta_keyword' => NULL,
            'type' => LogType::PAGE_LOAD_EVENT,
            'query_word' => NULL,
            'query_time' => $created_at,
        ];
        
        //$rowCount = $this->InsertQueryLog($log);
        $rowCount = $this->_logService->InsertQueryLog($log);

        return $results;
    }


    //取得查詢單字次數總計
    public function getStudentQueryWordCount($sunnetData){

        $querySQL = "SELECT id 
        FROM student_query_logs        
        WHERE exam_code = ? 
        AND exam_question_code = ? 
        AND student_code = ? 
        AND type = ?";        

        $results = $this->_pdo->query($querySQL,[$sunnetData->exam_code,
                                                 $sunnetData->exam_question_code,
                                                 $sunnetData->student_code,
                                                 LogType::WORD_QUERY_EVENT
                                                ])->all();
        
        return count($results);
    }

}

?>