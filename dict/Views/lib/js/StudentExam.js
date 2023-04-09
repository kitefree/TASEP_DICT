tasep_dict_global = {
    //記錄查詢使用者單字查詢記錄
    studentQueryWordHistoryList : [],
    //記錄字典資料
    dataSource : null
};


window.onload = async function () {

    //1.取得字典單字
    tasep_dict_global.dataSource = await getDictWordsList();

    //整理格式 將字串轉為JSON物件
    tasep_dict_global.dataSource.forEach(function (value, index, array) {
        array[index]["descriptions"] = JSON.parse(array[index]["descriptions"]);
    });

    //設定智能提示清單
    setAutocomplete(tasep_dict_global.dataSource);

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
    renderQueryQuota(elementQueryLimit, elementQueryCount);


}

//取得學生查詢單字記錄
async function getStudentQueryWordHistoryList() {

    let formData = getFormDataFormat();

    const response = await axios.get('../api.php?api_name=getStudentQueryWordHistoryList', {
        params: formData
    });

    return response.data;
}


function getFormDataFormat(record) {
    
    let exam_id = document.getElementById("exam_id").value;
    let exam_question_id = document.getElementById("exam_question_id").value;
    let exam_question_word_id = '';
    let exam_code = document.getElementById("exam_code").value;
    let exam_question_code = document.getElementById("exam_question_code").value;
    let student_code = document.getElementById("student_code").value;
    let query_word = '';

    if (record !== undefined) {    
        query_word = record.word;
        exam_question_word_id = record.word_id;
    }

    let formData = {
        "exam_id": exam_id,
        "exam_code": exam_code,//施測編號        
        "exam_question_id": exam_question_id,
        "exam_question_code": exam_question_code,//題組編號                
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
function setAutocomplete(dataSource) {


    //todo set tasep_dict_global.dataSource in localstorage
    $("#txtSearch").autocomplete({
        minLength: 1,//設定關鍵字觸發字元數量
        maxResults: 10,//設定清單出現數量
        //source: tasep_dict_global.dataSource,
        source: function (request, response) {
            var results = $.ui.autocomplete.filter(dataSource, request.term);
            response(results.slice(0, this.options.maxResults));
        },

        focus: function (event, ui) {
            event.preventDefault();
            // $('#txtSearch').val(ui.item.label);
            // console.log(ui.item.label);
            // console.log(ui.item.value);
        },
        select: function (event, ui) {

            event.preventDefault();

            if (ui.item.label.indexOf("...") > -1) {
                $('#txtSearch').val(ui.item.label.split('...')[0]);
            }
            else {
                $('#txtSearch').val(ui.item.label);
            }

            let hasSearchedBefore = checkSearchedBefore(ui.item);
            
            if (hasSearchedBefore == false) {            
                addStudentQueryWordRecord(ui.item);
                
                return;
            }

        }
    });
}

function checkSearchedBefore(ui_item)
{  
    
    //無歷史記錄 
    if(tasep_dict_global.studentQueryWordHistoryList.length == 0) return false;

    let isFound = false;

    //有記錄，返回true
    tasep_dict_global.studentQueryWordHistoryList.find(function (item, index, array) {
        if(parseInt(item.word_id, 10) == parseInt(ui_item.word_id,10))
        {
            isFound = true;
        }
    });

    
    return isFound;

}

//增加查單字記錄
function addStudentQueryWordRecord(record) {
    let elementQueryLimit = document.getElementById("query_limit");
    let elementQueryCount = document.getElementById("query_count");

    elementQueryCount.value = parseInt(elementQueryCount.value, 10) + 1;

    //client update
    tasep_dict_global.studentQueryWordHistoryList.push(record);
    renderWordListRow(record);
    renderQueryQuota(elementQueryLimit, elementQueryCount)
    
    //server update
    sendStudentQueryWordRecord(record);
    

}



//使用者送出查詢單字
function sendStudentQueryWordRecord(record) {
    let formData = getFormDataFormat(record);
    axios.get('../api.php?api_name=queryWord', {
        params: formData
    })
        .then(function (response) {                        
            let wordInfo = response.data;
            //renderWordListRow(wordInfo);
        })
        .catch((error) => console.log(error))
}

//渲染更新剩餘數量
function renderQueryQuota(elementQueryLimit, elementQueryCount) {

    let elementQueryLimitText = document.getElementsByClassName("tasep_dict_query_limit_text")[0];
    let quota = parseInt(elementQueryLimit.value,10) - parseInt(elementQueryCount.value,10);
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