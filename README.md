# 1. TASEP_DICT

[toc]

## 1.1. 相關說明

### 1.1.1. DB連線參數
```
Config\database.php
```
### 1.1.2. SQLDATA

```
sqldata\
```

### 1.1.3. 字典功能影片錄製

```
movies\
```

### 1.1.4. 測試URL

正式考生url

```html
http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=FT2247&exam_question_code=R22018&exam_sub_question_code=R22018-1&student_code=s111123450017
```

後台預覽url (for 測試用)

```html
http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=tasep_test&exam_question_code=R22018&exam_sub_question_code=R22018-1&student_code=A001
```



### 1.1.5. 協助傳入資訊

```PHP
//施測編碼   
$sunnetData->exam_code              = $_GET["exam_code"];
//題組代號編碼   
$sunnetData->exam_question_code     = $_GET["exam_question_code"];
//子題代號編碼
$sunnetData->exam_sub_question_code = $_GET["exam_sub_question_code"];
//考生編號
$sunnetData->student_code           = $_GET["student_code"];
```



## 1.2. 日誌記錄

### 1.2.1. 2023.04.05 v1

### 1.2.2. 2023.04.09 v2

1. 學生查詢字典之LOG 紀錄實作
   
2. 檔案拆分架構
   
   部分完成，未來考慮加入composer、controller 等做法。
   
3. 廠商mockup進行套版
   
   部分完成，`表格`、`智能提示清單` style樣式待廠商提供
   
4. 更新autocomplete 套件
    使用者可以使用「向下鍵↓」，進行挑選項目。

### 1.2.3. 20230.04.19 v7

1. 修正pdo資源釋放

   使用完pdo物件後，應透過null方式關閉mysql connection 連結。

2. 修正CSS樣式

   針對`表格`樣式進行調整，`智能提示清單`樣式暫時不調整。表格樣式調整如下：

    ```css
    .tasep_dict_table thead th{
        background-color:#4568a0;
        color:#FFFFFF;
    }
    .tasep_dict_table tr:nth-child(even) {
        background: #f5f5f5;
    }
    .tasep_dict_table tr:nth-child(odd) {
   
        background: #fdfdfd;
    }
    ```

3. 修正同時開二個分頁bug之問題

   每次送出單字前，優先判斷server端的實際剩餘數量。如果數量不一致，則網頁會進行提示比重新整理。

4. 針對js、css緩存問題進行改善

   程式版更時，造成程式無法正常執行之問題，因此，優先將客製化部分進行修正，調整如下：

   ```html
       <link href="./lib/css/studentexam.css?v=<?php echo $randomVersionNumber;?>" rel="stylesheet">
       <script src="./lib/js/StudentExam.js?v=<?php echo $randomVersionNumber;?>"></script>
   ```


5. 題組預覽字典之功能，假資料調整與修正。

   for `exam_code=tasep_test&exam_question_code=R22018`之條件，資料庫補齊假資料。

6. LOG 檔 新增`子題代號`欄位

   透過get 參數`exam_sub_question_code=R22018-1`取得子題代號，並存入資料庫。

7. 隱藏放大鏡ICON 功能

   放大鏡功能在流程上不會使用到，先隱藏。

8. 時區設定調整

   在`configs\GlobalConfig.php`中加入`date_default_timezone_set('Asia/Taipei');`，log時間與台灣時間切齊。

9. 調整架構

   1. 使用`spl_autoload_register` 取代`include`方式。
   2. `LogType` 抽離至`Enums`資料夾下進行共用。

10. 調整下拉選單出現的格式

    原為`word...words` 改為`word (words)`

11. 調整mysql索引

    1. `student_query_logs` table

       add index ：`exam_code`、`exam_question_code`、`student_code`、`type`

    2. `exam_question_words` table

       add index: `id`、`exam_id`、`exam_question_id`

    3. `exam_question_words_description` table

       add index: `exam_id`、`exam_question_id`、`word_id`

12. 新增字典緩存機制

    透過`localstorage`方式緩存10分鐘字典資料。超過10分鐘則重新與伺服器再取得資料。



### 1.2.3. Todo list

1 .資料表結構有可能會再調整
2 .會先預做一隻清db log table 的php，因為現在我無法操作資料庫，要測試功能會有些麻煩。

3.檔案拆分架構會再調整

>部分完成，未來考慮加入composer、controller 等做法。
