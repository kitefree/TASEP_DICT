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

```html
http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=FT2247&exam_question_code=R22018&exam_sub_question_code=R22018-1&student_code=s111123450017
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

1. SQL LOG 實作
   
   > 已完成
   
2. 檔案拆分架構會再調整
   
   >部分完成，未來考慮加入composer、controller 等做法。
   
3. 廠商mockup進行套版
   
   >部分完成，`表格`、`智能提示清單` style樣式待廠商提供
   
4. autocomplete 套件置換
    目標：使用者可以使用「向下鍵↓」，進行選項選擇的套件。
    
    >已完成


### 1.2.3. Todo list

1 .資料表結構有可能會再調整
2 .會先預做一隻清db log table 的php，因為現在我無法操作資料庫，要測試功能會有些麻煩。
