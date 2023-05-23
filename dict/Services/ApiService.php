<?php
namespace Services;

/* 專案使用 Start */
use Enums\LogType;
/* 專案使用 End */

/* PHP 內鍵 Start */
use stdClass;
use DateTime;
/* PHP 內鍵 END Start */

class ApiService{

    private $_pdo;
    private $_logService;
    
    public function __construct($pdo,$logService) {
        $this->_pdo = $pdo;
        $this->_logService = $logService;
    }

    //取得字典單字清單
    public function getDictWordsList($exam_code,$exam_question_code)
    {
        $querySQL = "SELECT
        e.exam_code,
        eq.question_code AS exam_question_code,
        eqw.id as word_id,
        eqw.word,
        eqw.meta_keyword,
        CASE
            WHEN eqw.meta_keyword IS NULL THEN LOWER(eqw.word)
            WHEN eqw.meta_keyword IS NOT NULL THEN CONCAT(LOWER(eqw.word), ' (',IFNULL(LOWER(eqw.meta_keyword),''),')')            
        END AS label,    
        eqw.id AS value,                 
        JSON_ARRAYAGG(JSON_OBJECT('part_of_speech',eqwd.part_of_speech,'chinese_description',eqwd.chinese_description)) as descriptions              
        FROM exams e
        JOIN exam_questions eq ON e.id = eq.exam_id
        JOIN exam_question_words eqw ON eq.exam_id = eqw.exam_id and eq.id = eqw.exam_question_id 
        JOIN exam_question_words_description eqwd on eqw.exam_id = eqwd.exam_id and eqw.exam_question_id = eqwd.exam_question_id and eqw.id = eqwd.word_id 
        WHERE e.exam_code = ?
        AND eq.question_code = ?    
        GROUP BY eqwd.word_id
        ORDER BY CHAR_LENGTH(eqw.word)
        ";



        $results = $this->_pdo->query($querySQL,[$exam_code,
                                                $exam_question_code,
                                                ])->all();


        return json_encode($results);
    }


    //使用者查詢單字
    public function queryWord($exam_id,$exam_code,$exam_question_id,$exam_question_code,$exam_sub_question_code,$exam_question_word_id,$query_word,$student_code)
    {

        $created_at = (new DateTime('now'))->format('Y-m-d H:i:s');
                
        $querySQL = "SELECT word,meta_keyword
        FROM exam_question_words        
        WHERE exam_id = ? 
        AND exam_question_id = ?  
        AND id = ?
        ";

        $queryData = $this->_pdo->query($querySQL,[$exam_id,
                                                 $exam_question_id,                                                 
                                                 $exam_question_word_id])->first();
        // print_r($queryData->word);
        // exit;

        $log = [
            'created_at' => $created_at,
            'exam_code' => $exam_code,
            'exam_question_code' => $exam_question_code,
            'exam_sub_question_code' => $exam_sub_question_code,
            'student_code' => $student_code,
            'exam_id' => $exam_id,
            'exam_question_id' => $exam_question_id,
            'exam_question_word_id' => $exam_question_word_id,
            'word' => $queryData->word,
            'meta_keyword' => $queryData->meta_keyword,
            'type' => LogType::WORD_QUERY_EVENT,
            'query_word' => $query_word,
            'query_time' => $created_at,
        ];

        
        $rowCount = $this->_logService->InsertQueryLog($log);
        
        if ($rowCount) {
    
            $querySQL = "SELECT id 
            FROM student_query_logs        
            WHERE exam_code = ? 
            AND exam_question_code = ? 
            AND student_code = ? 
            AND type = ?";        
            $results = $this->_pdo->query($querySQL,[$exam_code,
                                                          $exam_question_code,
                                                          $student_code,
                                                          LogType::WORD_QUERY_EVENT
                                                          ])->all();
            
            return count($results);         
            
        } else {
            //DB::rollBack();
            //return response()->json("N");
        }     
    }

    //取得使用者查詢單字歷史清單
    public function getStudentQueryWordHistoryList($exam_code,$exam_question_code,$student_code)
    {

        $querySQL = "SELECT word
        FROM student_query_logs        
        WHERE exam_code = ? 
        AND exam_question_code = ?
        AND student_code = ?
        AND type = ?                
        ";

        $words = $this->_pdo->query($querySQL,[$exam_code,
                                    $exam_question_code,
                                    $student_code,
                                    LogType::WORD_QUERY_EVENT
                                    ])->all();
         
        // print_r($words);
        // exit;
        if(count($words) == 0)
        {            
            return "[]";
        }
        else {

            $querySQL = "SELECT
            logs.exam_code,
            logs.exam_question_code,
            logs.exam_question_word_id as word_id,
            logs.word,
            logs.meta_keyword,
            CASE
                WHEN logs.meta_keyword IS NULL THEN LOWER(logs.word)
                WHEN logs.meta_keyword IS NOT NULL THEN CONCAT(LOWER(logs.word), ' (',IFNULL(LOWER(logs.meta_keyword),''),')')
            END AS label,    
            logs.exam_question_word_id AS value,                 
            JSON_ARRAYAGG(JSON_OBJECT('part_of_speech',eqwd.part_of_speech,'chinese_description',eqwd.chinese_description)) as descriptions              
            FROM student_query_logs logs
            JOIN exam_question_words_description eqwd on eqwd.exam_id = logs.exam_id and eqwd.exam_question_id = logs.exam_question_id and eqwd.word_id = logs.exam_question_word_id
            WHERE logs.exam_code = ?
            AND logs.exam_question_code = ?    
            AND logs.student_code = ?    
            AND logs.type = ?
            GROUP BY logs.exam_question_word_id
            ORDER BY logs.query_time
            ";
            //WHERE e.exam_code = ?
            //AND eq.question_code = ?    
            $results = $this->_pdo->query($querySQL,[$exam_code,
                                                     $exam_question_code,
                                                     $student_code,
                                                     LogType::WORD_QUERY_EVENT
                                                     ])->all();
            return json_encode($results);

        }



    }

    //取得查詢單字次數總計
    public function getStudentQueryWordCount($exam_code,$exam_question_code,$student_code){
        

        $querySQL = "SELECT *
        FROM student_query_logs        
        WHERE exam_code = ? 
        AND exam_question_code = ? 
        AND student_code = ? 
        AND type = ?";        

        $results = $this->_pdo->query($querySQL,[$exam_code,
                                                      $exam_question_code,
                                                      $student_code,
                                                      LogType::WORD_QUERY_EVENT
                                                      ])->all();
        
        return count($results);
    }

}
