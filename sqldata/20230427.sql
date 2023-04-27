/* 2023.04.27 data  */
/* step1 新增測驗代號 */
INSERT INTO `exams` VALUES (2,'2023-04-27 11:14:00',NULL,'MS2316','112年5月預試');

/* step2 新增題組代號 */
INSERT INTO `exam_questions` VALUES (3,'2023-04-27 11:16:37',NULL,2,'R23023','0428驗收用','0428驗收用',15);

/* step3 複製單字表 */
insert into exam_question_words (created_at,exam_id,exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword)
select '2023-04-27 19:38:00' as created_at,'2' as exam_id,'3' as exam_question_id,word,word_frequency_source_01,word_frequency_source_02,meta_keyword
from exam_question_words
where exam_id = 999 and exam_question_id = 903;

/* step4 複製詞性表 */
insert into exam_question_words_description(created_at,exam_id,exam_question_id,word_id,word,part_of_speech,chinese_description)
select '2023-04-27 19:38:00' as created_at,'2' as exam_id,'3' as exam_question_id,word_id,word,part_of_speech,chinese_description
from exam_question_words_description
where exam_id = 999 and exam_question_id = 903;

/* step5 更新詞性表word_id */
update exam_question_words_description eqwd		
join exam_question_words eqw on eqwd.exam_id =eqw.exam_id  and eqwd.exam_question_id = eqw.exam_question_id and eqwd.word = eqw.word 
set eqwd.word_id = eqw.id
where eqwd.exam_id = 2 and eqwd.exam_question_id = 3;	