
/* 新增施測 */
INSERT INTO exams (id,created_at,modify_at,exam_code,exam_name) VALUES (9,'2023-05-22 15:34:25',NULL,'FT2309','2023年5月預試');


/* 步驟2-新增新的題組，代號：R22018、R22019 */
INSERT INTO exam_questions (id,created_at,modify_at,exam_id,question_code,question_title,question_content,query_limit) VALUES
	 (11,'2023-05-22 16:17:57',NULL,9,'R22018','2023年5月預試','2023年5月預試',15),
	 (12,'2023-05-22 16:18:18',NULL,9,'R22019','2023年5月預試','2023年5月預試',15);

/* 步驟3-單字表複製 */
/* FT2309 - R22018 */
insert into exam_question_words (created_at,exam_id,exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword)    
select '2023-05-22 19:33:00' as created_at,'9' as exam_id,'11' as exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword
from exam_question_words
where exam_id = 1 and exam_question_id  = 1;

/* FT2309 - R22019 */
insert into exam_question_words (created_at,exam_id,exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword)    
select '2023-05-22 19:33:00' as created_at,'9' as exam_id,'12' as exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword
from exam_question_words
where exam_id = 1 and exam_question_id  = 2;


/* 步驟4-詞性表複製 */
/* FT2309 - R22018 */
insert into exam_question_words_description(created_at,exam_id,exam_question_id,word_id,word,part_of_speech,chinese_description)
select '2023-05-22 19:33:00' as created_at,'9' as exam_id,'11' as exam_question_id,word_id,word,part_of_speech,chinese_description
from exam_question_words_description
where exam_id = 1 and exam_question_id = 1;	

/* FT2309 - R22019 */
insert into exam_question_words_description(created_at,exam_id,exam_question_id,word_id,word,part_of_speech,chinese_description)
select '2023-05-22 19:33:00' as created_at,'9' as exam_id,'12' as exam_question_id,word_id,word,part_of_speech,chinese_description
from exam_question_words_description
where exam_id = 1 and exam_question_id = 2;


/* 步驟5-更新word_id */
/* FT2309 - R22018 */
update exam_question_words_description eqwd		
join exam_question_words eqw on eqwd.exam_id =eqw.exam_id  and eqwd.exam_question_id = eqw.exam_question_id and eqwd.word = eqw.word 
set eqwd.word_id = eqw.id
where eqwd.exam_id = 9 and eqwd.exam_question_id = 11;	 

/* FT2309 - R22019 */
update exam_question_words_description eqwd		
join exam_question_words eqw on eqwd.exam_id =eqw.exam_id  and eqwd.exam_question_id = eqw.exam_question_id and eqwd.word = eqw.word 
set eqwd.word_id = eqw.id
where eqwd.exam_id = 9 and eqwd.exam_question_id = 12;
