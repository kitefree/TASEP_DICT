# 1. TASEP_DICT


## 1.1. 相關說明

### 1.1.1. DB連線參數
```
Config\GlobalConfig.php
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

### 2023.06.07

協助更新資料庫資料(新增字典for`FT2346`施測備用)，請參考檔案`dump-tasep_dict-202306072055.sql`。

### 2023.05.29

協助更新資料庫資料(新增字典for`FT2318` `FT2319` `FT2325`施測使用)，請參考檔案`dump-tasep_dict-202305290839.sql`。

### 2023.05.23

1. 針對使用者查詢歷史單字依查詢時間進行排序。

2. 協助更新`apiservice.php`。

### 1.2.1. 2023.05.22

1. 協助更新`apiservice.php`，修改查詢條件依據單字長度進行排序。


### 1.2.2. 2023.05.20

1. 協助匯入`正式區`sql檔案，請參考資料夾`sqldata\正式區`，`20230522_FT2307.sql`、`20230522_FT2309.sql`共二個檔案。

2. 協助匯入`測試區`sql檔案，請參考資料夾`sqldata\測試區`，`20230522.sql`共一個檔案。

### 1.2.3. 2023.05.17

1. 客戶需求，參考之前某某人建立之字典資料。

2. 協助匯入`20230517.sql`檔，檔案中指令共有10個步驟(二個施測)，再麻煩依照順序執行。

3. 測試時，客戶會使用施測代號為`FT2319`及`FT2320`，題組代號皆為`E22082`進行測試。

   ```html
   http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=FT2319&exam_question_code=E22082&exam_sub_question_code=R23023-02-NE230&student_code=A001
   ```

### 1.2.4. 2023.05.10

1. 客戶需求，參考之前某某人建立之字典資料。
2. 協助匯入`20230510.sql`檔，檔案中指令共有10個步驟(二個施測)，再麻煩依照順序執行。
3. 測試時，客戶會使用施測代號為`FT2313`及`FT1212`，題組代號皆為`E22082`進行測試。

### 1.2.5. 2023.05.07

1. 客戶需求，參考之前某某人建立之字典資料。

2. 協助匯入`20230507.sql`檔，檔案中指令共有5步驟，再麻煩依照順序執行。

3. 測試時，客戶會使用施測代號為`FT2311`，題組代號為`E22082`進行測試，預計URL結構如下，請參考：

   ```HTML
   http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=FT2311&exam_question_code=E22082&exam_sub_question_code=R23023-02-NE230&student_code=A001
   ```

### 1.2.6. 2023.05.03

1.更新檔案清單如下：

![image-20230503112307643](https://i.imgur.com/hQfR0xJ.png)

2.修正以下問題

- 修正字典清單高度，250px→300px。

- 修正同一個單字查詢2次，被扣次數問題。

### 1.2.7. 2023.05.01

1. 更新檔案清單如下：

   ![image-20230501132619288](https://i.imgur.com/Q2gqLyG.png)

2. 當查詢5個單字後，會自動出現捲軸。

3. 當查詢衍生字時，也能被查詢。



### 1.2.8. 2023.04.27

1. 更新檔案清單如下：

![image-20230427200652576](https://i.imgur.com/RbXrXYF.png)

2. 協助匯入`20230427.sql`檔，檔案中指令共有5步驟，再麻煩依照順序執行。

3. 驗收時，客戶會使用施測代號為`MS2316`，題組代號為`R23023`進行測試，預計URL結構如下，請參考：

    ```HTML
    http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=MS2316&exam_question_code=R23023&exam_sub_question_code=R23023-02-NE230&student_code=A001	
    ```



### 1.2.9. 2023.04.25 v8

#### 1.2.9.1. 更新檔案清單如下：

![image-20230425211914076](https://i.imgur.com/gLOkfGj.png)

#### 1.2.9.2. 更新內容如下：

1. autocomplete 搜尋改以首字字母開始尋找。
2. 當查詢7個單字後，會自動出現捲軸。
3. 更新資料庫資料，for拍攝說明影片使用。

#### 1.2.9.3. 請廠商協助以下事項：

1. 請廠商協助在`預覽`時先hardcode 寫死`exam_code=tasep_test`、`exam_question_code=R23023`兩個參數，`exam_sub_question_code` 、`student_code`動態或任意填寫沒關係，如下：

```html
http://localhost/TASEP_DICT/dict/views/StudentExam.php?exam_code=tasep_test&exam_question_code=R23023&exam_sub_question_code=R22018-1&student_code=A001
```

補充說明，在`正式考生考試介面`時這四個參數會是動態的。

2. 協助更新資料庫，建了些假單字(拍影片用)。匯入之檔名稱為`dump-tasep_dict-202304252111.sql`
3. 協助更新程式。

### 1.2.10. 2023.04.19 v7

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



### 1.2.11. 2023.04.09 v2

1. 學生查詢字典之LOG 紀錄實作

2. 檔案拆分架構

   部分完成，未來考慮加入composer、controller 等做法。

3. 廠商mockup進行套版

   部分完成，`表格`、`智能提示清單` style樣式待廠商提供

4. 更新autocomplete 套件
   使用者可以使用「向下鍵↓」，進行挑選項目。

### 1.2.12. 2023.04.05 v1

## 1.3. Todo list

1 .資料表結構有可能會再調整
2 .會先預做一隻清db log table 的php，因為現在我無法操作資料庫，要測試功能會有些麻煩。

3.檔案拆分架構會再調整

>部分完成，未來考慮加入composer、controller 等做法。
