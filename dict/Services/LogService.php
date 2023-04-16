<?php
namespace Services;

/* PHP 內鍵 Start */
use DateTime;
/* PHP 內鍵 END Start */

class LogService{

    private $_pdo;
    
    public function __construct($pdo) {
        $this->_pdo = $pdo;
    }


    public function InsertQueryLog($log)
    {
        
        $stmt = $this->_pdo->prepare('INSERT INTO student_query_logs (created_at, exam_code, exam_question_code,exam_sub_question_code, student_code, exam_id, exam_question_id, exam_question_word_id, word, meta_keyword, type, query_word, query_time) 
        VALUES (:created_at, :exam_code, :exam_question_code,:exam_sub_question_code, :student_code, :exam_id, :exam_question_id, :exam_question_word_id, :word, :meta_keyword, :type, :query_word, :query_time)');
        $stmt->execute([
            'created_at' => $log["created_at"],
            'exam_code' => $log["exam_code"],
            'exam_question_code' => $log["exam_question_code"],
            'exam_sub_question_code' => $log["exam_sub_question_code"],
            'student_code' => $log["student_code"],
            'exam_id' => $log["exam_id"],
            'exam_question_id' => $log["exam_question_id"],
            'exam_question_word_id' => $log["exam_question_word_id"],
            'word' => $log["word"],
            'meta_keyword' => $log["meta_keyword"],                
            'type' => $log["type"],
            'query_word' => $log["query_word"],
            'query_time' => $log["query_time"]
        ]);

        return $stmt->rowCount();  
    }

}

?>