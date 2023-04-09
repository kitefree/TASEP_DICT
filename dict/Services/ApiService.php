<?php
namespace Services;

use stdClass;
use DateTime;
class ApiService{

    private $_pdo;
    private $_logService;
    
    public function __construct($pdo,$logService) {
        $this->_pdo = $pdo;
        $this->_logService = $logService;
    }

    //取得字典單字清單
    public function getDictWordsList($exam_id,$exam_question_id)
    {
        $querySQL = "SELECT id as value,word as label
        FROM exam_question_words        
        WHERE exam_id = ? 
        AND exam_question_id = ?
        UNION
        SELECT id as value,word as label
        FROM exam_question_words        
        WHERE exam_id = ? 
        AND exam_question_id = ?
        AND meta_keyword is not null
        ORDER BY value        
        ";



        $results = $this->_pdo->query($querySQL,[$exam_id,
                                                 $exam_question_id,
                                                 $exam_id,
                                                 $exam_question_id])->all();

        

        return json_encode($results);
    }

    //使用者查詢單字
    public function queryWord($exam_id,$exam_code,$exam_question_id,$exam_question_code,$exam_question_word_id,$query_word,$student_code)
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
            'student_code' => $student_code,
            'exam_id' => $exam_id,
            'exam_question_id' => $exam_question_id,
            'exam_question_word_id' => $exam_question_word_id,
            'word' => $queryData->word,
            'meta_keyword' => $queryData->meta_keyword,
            'type' => "2",
            'query_word' => $query_word,
            'query_time' => $created_at,
        ];

        //$rowCount = $this->InsertQueryLog($log);
        $rowCount = $this->_logService->InsertQueryLog($log);
        
        if ($rowCount) {

            $querySQL = "SELECT part_of_speech,chinese_description
            FROM exam_question_words_description as eqwd    
            WHERE exam_id = ? 
            AND exam_question_id = ?              
            AND eqwd.word_id = ?                        
            ";

            $queryData02 = $this->_pdo->query($querySQL,[$exam_id,
                                                         $exam_question_id,                                                 
                                                         $exam_question_word_id])->all();
            // print_r($queryData02);
            // exit;
            // $words_description = DB::table('exam_question_words_description')
            //     ->select('part_of_speech', 'chinese_description')
            //     ->where('eqwd.word_id', '=', $exam_question_word_id")
            //     ->get();

            $data = [
                "id" => $exam_question_word_id,
                "word" => $queryData->word,
                "meta_keyword" => $queryData->meta_keyword,
                "words_description" => $queryData02
            ];
            //print_r( json_encode($data));
            //exit;
            return json_encode($data);
        } else {
            //DB::rollBack();
            //return response()->json("N");
        }     
    }

    //取得使用者查詢單字歷史清單
    public function getQueryWordHistoryList($exam_id,$exam_question_id,$student_code)
    {

        $querySQL = "SELECT *
        FROM student_query_logs        
        WHERE exam_id = ? 
        AND exam_question_id = ?
        AND student_code = ?
        AND type = 2        
        ORDER BY created_at desc
        ";

        $words = $this->_pdo->query($querySQL,[$exam_id,
                                    $exam_question_id,
                                    $student_code])->all();
        
        if(count($words) == 0)
        {
            return "0";
        }
        else {
            foreach($words as $word)
            {
                $querySQL = "SELECT part_of_speech,chinese_description
                FROM exam_question_words_description as eqwd       
                WHERE eqwd.exam_id = ? 
                AND eqwd.exam_question_id = ?
                AND eqwd.word_id = ?
                ORDER BY created_at desc
                ";
        
                $words_description = $this->_pdo->query($querySQL,[$exam_id,$exam_question_id,$word->exam_question_word_id])->all();
                $resData[] = [
                    "exam_question_word_id" => $word->exam_question_word_id,
                    "word" => $word->word,
                    "meta_keyword" => $word->meta_keyword,
                    "words_description" => $words_description];
            }
        }

        return json_encode($resData);

    }

}
