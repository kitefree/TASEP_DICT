<?php
namespace Services;

use stdClass;

class ApiService{

    private $_pdo;

    
    public function __construct($pdo) {
        $this->_pdo = $pdo;
    }

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

    public function queryWord()
    {
        //待寫
        // $headerAuth = $request->header('Authorization', '');
        // $formData = $request->all();
        // $created_at = new DateTime();

        // $queryData = DB::table('exam_question_words')
        //     ->selectRaw('word,meta_keyword')
        //     ->where('exam_id', '=', $formData["exam_id"])
        //     ->where('exam_question_id', '=', $formData["exam_question_id"])
        //     ->where('id', '=', $formData["exam_question_word_id"])               
        //     ->first();

        // try {

        //     $log = [
        //         'created_at' => $created_at,
        //         'exam_code' => $formData["exam_code"],
        //         'exam_question_code' => $formData["exam_question_code"],
        //         'student_code' => $formData["student_code"],
        //         'exam_id' => $formData["exam_id"],
        //         'exam_question_id' => $formData["exam_question_id"],
        //         'exam_question_word_id' => $formData["exam_question_word_id"],
        //         'word' => $queryData->word,
        //         'meta_keyword' => $queryData->meta_keyword,
        //         'type' => "2",
        //         'query_word' => $formData["query_word"],
        //         'query_time' => $created_at,
        //     ];
        //     $queryStatus = $this->InsertQueryLog($log);

        //     if ($queryStatus) {

        //         DB::commit();

        //         $word = DB::table('exam_question_words')
        //             ->where('exam_question_words.id', '=', $formData["exam_question_word_id"])
        //             ->first();

        //         $words_description = DB::table('exam_question_words_description')
        //             ->select('part_of_speech', 'chinese_description')
        //             ->where('exam_question_words_description.word_id', '=', $formData["exam_question_word_id"])
        //             ->get();

        //         $data = [
        //             "id" => $word->id,
        //             "word" => $word->word,
        //             "meta_keyword" => $word->meta_keyword,
        //             "words_description" => $words_description->toArray()
        //         ];

        //         return response()->json($data);
        //     } else {
        //         DB::rollBack();
        //         return response()->json("N");
        //     }
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     echo $e;
        //     // something went wrong
        // }
    }

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