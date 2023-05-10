/* 客戶需求，參考之前某某人建立之字典資料 */
/* copy from exam_id=2 and exam_question_id=3 */


/* 步驟1-新增新的施測，代號：FT2313 */
INSERT INTO tasep_dict.exams
(id, created_at, modify_at, exam_code, exam_name)
VALUES(4, '2023-05-10 19:22:31', NULL, 'FT2313', '202305預試試前題型檢測-資訊老師專用');

/* 步驟2-新增新的題組，代號：E22082 */
INSERT INTO tasep_dict.exam_questions
(id, created_at, modify_at, exam_id, question_code, question_title, question_content, query_limit)
VALUES(5, '2023-05-10 19:23:51', NULL, 4, 'E22082', '客戶需求', '客戶需求', 15);

/* 步驟3-單字表複製 */
insert into exam_question_words (created_at,exam_id,exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword)    
select '2023-05-10 19:33:00' as created_at,'4' as exam_id,'5' as exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword
from exam_question_words
where exam_id = 2 and exam_question_id  = 3;

/* 步驟4-詞性表複製 */
insert into exam_question_words_description(created_at,exam_id,exam_question_id,word_id,word,part_of_speech,chinese_description)
select '2023-05-10 19:33:00' as created_at,'4' as exam_id,'5' as exam_question_id,word_id,word,part_of_speech,chinese_description
from exam_question_words_description
where exam_id = 2 and exam_question_id = 3;	

/* 步驟5-更新word_id */
update exam_question_words_description eqwd		
join exam_question_words eqw on eqwd.exam_id =eqw.exam_id  and eqwd.exam_question_id = eqw.exam_question_id and eqwd.word = eqw.word 
set eqwd.word_id = eqw.id
where eqwd.exam_id = 4 and eqwd.exam_question_id = 5;



/* 步驟1-新增新的施測，代號：FT1212 */
INSERT INTO tasep_dict.exams
(id, created_at, modify_at, exam_code, exam_name)
VALUES(5, '2023-05-10 19:22:31', NULL, 'FT1212', '202305預試試前題型檢測-資訊老師專用');

/* 步驟2-新增新的題組，代號：E22082 */
INSERT INTO tasep_dict.exam_questions
(id, created_at, modify_at, exam_id, question_code, question_title, question_content, query_limit)
VALUES(6, '2023-05-10 19:23:51', NULL, 5, 'E22082', '客戶需求', '客戶需求', 15);

/* 步驟3-單字表複製 */
insert into exam_question_words (created_at,exam_id,exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword)    
select '2023-05-10 19:33:00' as created_at,'5' as exam_id,'6' as exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword
from exam_question_words
where exam_id = 2 and exam_question_id  = 3;

/* 步驟4-詞性表複製 */
insert into exam_question_words_description(created_at,exam_id,exam_question_id,word_id,word,part_of_speech,chinese_description)
select '2023-05-10 19:33:00' as created_at,'5' as exam_id,'6' as exam_question_id,word_id,word,part_of_speech,chinese_description
from exam_question_words_description
where exam_id = 2 and exam_question_id = 3;	

/* 步驟5-更新word_id */
update exam_question_words_description eqwd		
join exam_question_words eqw on eqwd.exam_id =eqw.exam_id  and eqwd.exam_question_id = eqw.exam_question_id and eqwd.word = eqw.word 
set eqwd.word_id = eqw.id
where eqwd.exam_id = 5 and eqwd.exam_question_id = 6;