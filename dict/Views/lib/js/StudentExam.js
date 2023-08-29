tasep_dict_global = {
    //記錄查詢使用者單字查詢記錄
    studentQueryWordHistoryList : [],
    //記錄字典資料
    dataSource : null
};


window.onload = async function () {

    //字典資料啟用緩存機制，減少存取資料庫次數
    await dictUseCache(true);
    
    //設定智能提示清單
    await setAutocomplete(tasep_dict_global.dataSource);

    //2.取得學生查詢單字記錄
    tasep_dict_global.studentQueryWordHistoryList = await getStudentQueryWordHistoryList();
    //整理格式 將字串轉為JSON物件
    tasep_dict_global.studentQueryWordHistoryList.forEach(function (value, index, array) {
        array[index]["descriptions"] = JSON.parse(array[index]["descriptions"]);
    });    
    
    //渲染單字表格
    renderWordListRows(tasep_dict_global.studentQueryWordHistoryList);


    //3.渲染更新剩餘數量
    let elementQueryLimit = document.getElementById("query_limit");
    let elementQueryCount = document.getElementById("query_count");

    intQueryLimit = parseInt(elementQueryLimit.value,10);
    intQueryCount = parseInt(elementQueryCount.value,10);

    renderQueryQuota(intQueryLimit, intQueryCount);


}

//字典資料緩存機制
async function dictUseCache(enable){
    if(enable == true)
    {
        let exam_code = document.getElementById("exam_code").value;
        let exam_question_code = document.getElementById("exam_question_code").value;
        let diff_min = 10 * 60 * 1000;//10 min

        if(localStorage.getItem(`tasep_dict_${exam_code}_${exam_question_code}_data`) == null){
            //1.取得字典單字
            tasep_dict_global.dataSource = await getDictWordsList();
            
            //整理格式 將字串轉為JSON物件
            tasep_dict_global.dataSource.forEach(function (value, index, array) {
                array[index]["descriptions"] = JSON.parse(array[index]["descriptions"]);
            });
    
            let expiryTime = (new Date()).getTime() + diff_min;
            const data = {
                "expiryTime":expiryTime,
                "data":tasep_dict_global.dataSource
            };
            localStorage.setItem(`tasep_dict_${exam_code}_${exam_question_code}_data`, JSON.stringify(data));        
            //console.log("init localstorage");
            
        }
        else{

            //console.log("load data from localstorage");
            // 從 localStorage 中取回資料並解析為 JSON 格式
            const storedData = localStorage.getItem(`tasep_dict_${exam_code}_${exam_question_code}_data`);
            const parsedData = JSON.parse(storedData);        

            // 取得當前時間
            let now = new Date();
            
            let expiryTime = parsedData.expiryTime;
            
            if (expiryTime - now.getTime() >0) {
                tasep_dict_global.dataSource = await parsedData.data;
                //console.log("load data from locastorage2");
            } else {            
                //console.log("localstorage data expiry");
                // 清空 localStorage 中的所有資料
                localStorage.clear();
                await dictUseCache(enable);
            }

            
        }
    }
    else{
        //1.取得字典單字
        tasep_dict_global.dataSource = await getDictWordsList();

        //整理格式 將字串轉為JSON物件
        tasep_dict_global.dataSource.forEach(function (value, index, array) {
            array[index]["descriptions"] = JSON.parse(array[index]["descriptions"]);
        });

    }
    
    

}

//取得學生查詢單字記錄
async function getStudentQueryWordHistoryList() {

    let formData = getFormDataFormat();

    const response = await axios.get('../api.php?api_name=getStudentQueryWordHistoryList', {
        params: formData
    });

    return response.data;
}


function getFormDataFormat(record,query_word = '') {
    
    let exam_id = document.getElementById("exam_id").value;
    let exam_question_id = document.getElementById("exam_question_id").value;
    let exam_question_word_id = '';
    let exam_code = document.getElementById("exam_code").value;
    let exam_question_code = document.getElementById("exam_question_code").value;
    let exam_sub_question_code = document.getElementById("exam_sub_question_code").value;
    let student_code = document.getElementById("student_code").value;
    

    if (record !== undefined) {            
        exam_question_word_id = record.word_id;
    }

    let formData = {
        "exam_id": exam_id,
        "exam_code": exam_code,//施測編號        
        "exam_question_id": exam_question_id,
        "exam_question_code": exam_question_code,//題組編號                
        "exam_sub_question_code": exam_sub_question_code,//子題編號        
        "student_code": student_code,//考生編號        
        "exam_question_word_id": exam_question_word_id,
        "query_word": query_word,//使用者查詢單字
    }


    return formData;
}

//取得字典單字
async function getDictWordsList() {
    let formData = getFormDataFormat();

    const response = await axios.get('../api.php?api_name=getDictWordsList', {
        params: formData
    });

    return response.data;
}


//設定智能提示清單
async function setAutocomplete(dataSource) {


    //todo set tasep_dict_global.dataSource in localstorage
    $("#txtSearch").autocomplete({
        minLength: 1,//設定關鍵字觸發字元數量
        maxResults: 10,//設定清單出現數量
        source: tasep_dict_global.dataSource,
        // source: async function (request, response) {
            
        //     try{
        //         let results = $.ui.autocomplete.filter(dataSource, request.term);
                
        //         await response(results.slice(0, this.options.maxResults));

        //         // let results02 = results.slice(0, this.options.maxResults); 
        //         // let results03 = $.map( results02, function( item ) {
        //         //     let formatLabel = item.label;

        //         //     if(item.label.indexOf('...') >-1)
        //         //     {
        //         //         formatLabel = item.label.split('...')[0] + "      " + item.label.split('...')[1];
        //         //     }

        //         //     return {
        //         //         exam_code:item.exam_code,
        //         //         exam_question_code:item.exam_question_code,
        //         //         word:item.word,
        //         //         word_id:item.word_id,
        //         //         descriptions:item.descriptions,
        //         //         label: formatLabel,
        //         //         value: item.value
        //         // }}); 
        //         // await response(results03);

                
                
        //     }                
        //     catch(error)
        //     {
        //         console.log("AutoComplete Source Error occurred: " + error.message);
        //     }
        // },

        focus: function (event, ui) {
            event.preventDefault();
            // $('#txtSearch').val(ui.item.label);
            // console.log(ui.item.label);
            // console.log(ui.item.value);
        },
        select: async function (event, ui) {
            
            event.preventDefault();

            //1.優先檢查後端查詢數量
            let isQueryCountCorrect = await checkQueryCountCorrect();
            if (isQueryCountCorrect == false)
            {
                alert('剩餘查詢次數異常，請點擊【確定】後，將自動刷新頁面。');
                // 強制瀏覽器重新下載資源，並刷新頁面
                location.href = location.href;
                return;
            }
            //2. 儲存當下使用者查詢之單字
            $('#query_word').val($('#txtSearch').val());
            //3. 字串處理
            if (ui.item.label.indexOf('(') > -1 && ui.item.label.indexOf(')') > -1) {
                let endindex = ui.item.label.indexOf('(') -1;// 最後有多一個空白，再減1
                $('#txtSearch').val(ui.item.label.substring(0,endindex));
            }
            else {
                $('#txtSearch').val(ui.item.label);
            }

            //4. 在client檢查是否曾經查詢過此單字
            let hasSearchedBefore = checkSearchedBefore(ui.item);
            
            if (hasSearchedBefore == false) {            
                addStudentQueryWordRecord(ui.item,$('#query_word').val());                
                return;
            }

        }
    });

    //實作搜尋批配方式
    $.ui.autocomplete.filter = function (array, term) {        
        //長度低於2，不查詢
        if(term.length < 2)
        {
            return;
        }
        //首字符號才撈選出清單。
        var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(term), "i");

        return $.grep(array, function (value) {

            value.label = $.trim(value.label);

            //判斷是否為片語，特性為字單間「空白」 或 「...」 或「…」
            if(value.label.split(' ').length >1   || 
               value.label.split('...').length >1 || 
               value.label.split('…').length >1)
            {                
                let splitBySymbol = '';
                if(value.label.split(' ').length >1)
                {
                    splitBySymbol = ' ';
                }
                else if(value.label.split('...').length >1){
                    splitBySymbol = '...';
                }
                else if(value.label.split('…').length >1){
                    splitBySymbol = '…';
                }

                let arrPhrase = value.label.split(splitBySymbol);
                let hasFound = false;               
                arrPhrase.forEach(function(pharseWord) {
                    if(matcher.test(pharseWord))
                    {
                        hasFound = true;                        
                    }
                });
                if(hasFound == true)
                {
                    return true;
                }
            }

            //優先比對label文字，有則直接返回
            if(matcher.test(value.label))
            {
                return true;
            }
            
            //衍生字為空，沒有東西可以比對，直接返回結果
            if(value.meta_keyword == null){
                return false;
            }
            
            //衍生字不為空時，進行比對             
            //衍生字會有多個單字，故需要處理每個單字的比對
            let arrKeywords = value.meta_keyword.split(';');
            let findResult = false;
            arrKeywords.forEach(function(element) {
                element = element.trim();
                if(matcher.test(element))
                {
                    findResult = true;
                }
            });
            
            return findResult;

        });
    };

}


//檢查考生查詢剩餘數量
async function checkQueryCountCorrect()
{
    let formData = getFormDataFormat();
    
    const response = await axios.get('../api.php?api_name=getStudentQueryWordCount', {
        params: formData
    });
    
    let serverRecordQueryCount = response.data;
    let elementQueryCount = document.getElementById("query_count");
    let intQueryCount = parseInt(elementQueryCount.value, 10);

    if (serverRecordQueryCount != intQueryCount)
    {
        return false;
    }
    else {
        return true;
    }
    
}

function checkSearchedBefore(ui_item)
{  
    
    //無歷史記錄 
    if(tasep_dict_global.studentQueryWordHistoryList.length == 0) return false;

    let isFound = false;

    //有記錄，返回true
    tasep_dict_global.studentQueryWordHistoryList.find(function (item, index, array) {
        let this_item_id = parseInt(item.word_id, 10);
        let selected_item_id = parseInt(ui_item.word_id,10);
        if( this_item_id == selected_item_id)
        {
            isFound = true;
        }
    });

    
    return isFound;

}

//增加查單字記錄
function addStudentQueryWordRecord(record,query_word) {
    let elementQueryLimit = document.getElementById("query_limit");
    let elementQueryCount = document.getElementById("query_count");

    elementQueryCount.value = parseInt(elementQueryCount.value, 10) + 1;
    let intQueryLimit = parseInt(elementQueryLimit.value,10);
    let intQueryCount = parseInt(elementQueryCount.value,10);

    //client update
    tasep_dict_global.studentQueryWordHistoryList.push(record);
    renderWordListRow(record);
    renderQueryQuota(intQueryLimit, intQueryCount);
    
    //server update
    sendStudentQueryWordRecord(record,query_word);
    

}



//使用者送出查詢單字
function sendStudentQueryWordRecord(record,query_word) {
    let formData = getFormDataFormat(record,query_word);
    axios.get('../api.php?api_name=queryWord', {
        params: formData
    })
        .then(function (response) {                        

            let count = response.data;
            //渲染更新剩餘數量
            let elementQueryLimit = document.getElementById("query_limit");
            let elementQueryCount = document.getElementById("query_count");
            elementQueryCount.value = count;

            let intQueryLimit = parseInt(elementQueryLimit.value,10);
            let intQueryCount = parseInt(elementQueryCount.value,10);
            
            renderQueryQuota(intQueryLimit,intQueryCount);
            //renderWordListRow(wordInfo);
        })
        .catch((error) => console.log(error))
}

//渲染更新剩餘數量
function renderQueryQuota(intQueryLimit, intQueryCount) {

    let elementQueryLimitText = document.getElementsByClassName("tasep_dict_query_limit_text")[0];
    let quota = intQueryLimit - intQueryCount;
    elementQueryLimitText.innerHTML = `剩餘查詢次數：<span class="tasep_dict_query_limit_number">${quota}</span>&nbsp;次`;

    if (quota == 0) {
        document.getElementById("txtSearch").disabled = true;
    }

}


//渲染單字表格
function renderWordListRows(wordsInfo) {
    if (wordsInfo == 0) return;
    
    wordsInfo.forEach(function (wordInfo, index, array) {
        renderWordListRow(wordInfo);
    });
}

//重新渲染單字清單
function renderWordListRow(wordInfo) {
    let table = document.querySelector("#wordList tbody");
    let htmlDescriptionCell = renderWordDescriptionCell(wordInfo.descriptions);
    let meta_keyword = (wordInfo.meta_keyword == null ? '&nbsp;' : wordInfo.meta_keyword);
    let priorRow = table.innerHTML;
    let row = `
    <tr>
        <td>${wordInfo.word}</td>
        <td>${htmlDescriptionCell}</td>
        <td>${meta_keyword}</td>
    </tr>  
    `;

    table.innerHTML = row + priorRow;

}

//重新渲染單字清單詞性、中文
function renderWordDescriptionCell(words_description) {

    let words_description_cell = '';

    words_description.forEach(function (value, index, array) {

        if (index >= 1) {
            words_description_cell += '<br>';
        }

        words_description_cell +=
            `<span>${array[index]["part_of_speech"]}</span>&nbsp;&nbsp;&nbsp;<sapn>${array[index]["chinese_description"]}</span>`;

    });

    return words_description_cell;
}