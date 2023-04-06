<?php
namespace Services;

use stdClass;

class StudentExamService{

    private $_pdo;

    
    public function __construct($pdo) {
        $this->_pdo = $pdo;
    }

    public function pageLoad($sunnetData)
    {        
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
        return $results;
    }

    public function InsertQueryLog($log)
    {
        //待寫
        // $queryStatus = DB::table('student_query_logs')->insert(
        //     [
        //         'created_at' => $log["created_at"],
        //         'exam_code' => $log["exam_code"],
        //         'exam_question_code' => $log["exam_question_code"],
        //         'student_code' => $log["student_code"],
        //         'exam_id' => $log["exam_id"],
        //         'exam_question_id' => $log["exam_question_id"],
        //         'exam_question_word_id' => $log["exam_question_word_id"],
        //         'word' => $log["word"],
        //         'meta_keyword' => $log["meta_keyword"],                
        //         'type' => $log["type"],
        //         'query_word' => $log["query_word"],
        //         'query_time' => $log["query_time"],
        //     ]




        // );
        // return $queryStatus;
    }

}

?>