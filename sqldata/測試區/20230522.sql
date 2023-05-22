/* 新增 冠詞 */
INSERT INTO code_lists (created_at,modify_at,code_key,code_display_name,code_value,order_num,code_level,code_parent_id,description) VALUES
	 ('2023-05-22 00:00:00',NULL,'pos','冠詞','art.',20,NULL,NULL,NULL);
     
/* 修正代號 */
update exams set exam_code = 'FT2312' where exam_code ='FT1212';