gQueryIDList = [];
gDataSource = null;
gQueryWordsHistory = [];

window.onload = function() {
    iniUI();
}

function iniUI() {
    setAutocomplete();
    updateQueryQuota();
    eventReg();
    renderQueryWordList();
}

function renderQueryWordList() {
    let formData = setFormData("", "");
    axios.get('../api.php?api_name=getQueryWordHistoryList', {
            params: formData
        })
        .then(function(response) {
            updateQueryQuota();

            gQueryWordsHistory = response.data;
            gQueryWordsHistory.forEach(function(wordInfo, index, array) {
                gQueryIDList.push(wordInfo.exam_question_word_id.toString());
                addWordListRow(wordInfo);
            });

        })
        .catch((error) => console.log(error))
}

function eventReg() {
    // Get the input field
    var txtSearch = document.getElementById("txtSearch");

    // Execute a function when the user presses a key on the keyboard
    txtSearch.addEventListener("keypress", function(event) {
        // If the user presses the "Enter" key on the keyboard
        if (event.key === "Enter") {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("btnQuery").click();
        }
    });
}

function handleQuery() {

    let txtSearch = document.getElementById("txtSearch").value;
    var findWord = gDataSource.find(function(item, index, array) {
        return item.label == txtSearch;
    });

    let exam_question_word_id = findWord.value;
    let query_word = findWord.label;

    document.getElementsByClassName("dropdown-menu").item(0).classList.remove("show");

    if (canQueryWord() == false) {
        return;
    }

    var findQueryID = gQueryIDList.find(function(item, index, array) {
        return parseInt(item, 10) == exam_question_word_id;
    });

    if (findQueryID > -1) {
        return;
    }

    addQueryRecord(exam_question_word_id);

    let formData = setFormData(exam_question_word_id, query_word);
    reqQueryWord(formData);

    //comfortable
}

function setFormData(exam_question_word_id, query_word) {
    let formData = {
        "exam_id": document.getElementById("exam_id").value,
        "exam_code": document.getElementById("exam_code").value,
        "exam_question_id": document.getElementById("exam_question_id").value,
        "exam_question_code": document.getElementById("exam_question_code").value,
        "exam_question_word_id": exam_question_word_id,
        "query_word": query_word,
        "student_code": document.getElementById("student_code").value,
        //"Authorization": document.getElementById("Authorization").value,
    }
    return formData;
}

function setAutocomplete() {
    axios.get('../api.php?api_name=getDictWordsList', {
            params: {
                exam_id: document.getElementById("exam_id").value,
                exam_question_id: document.getElementById("exam_question_id").value
            }
        })
        .then(function(response) {
            console.log(response);
            gDataSource = response.data;
            const ac = new Autocomplete(document.getElementById('txtSearch'), {
                data: gDataSource,
                treshold: 1,
                maximumItems: 8,
                onSelectItem: ({
                    label,
                    value
                }) => {
                    let exam_question_word_id = value;
                    let query_word = label;

                    if (canQueryWord() == false) {
                        return;
                    }

                    if (gQueryIDList.indexOf(exam_question_word_id) > -1) {
                        return;
                    }

                    addQueryRecord(exam_question_word_id);

                    let formData = setFormData(exam_question_word_id, query_word);

                    reqQueryWord(formData);

                }
            });
        })
        .catch((error) => console.log(error))
}

function reqQueryWord(formData) {

    axios.get('/StudentExam/queryWord', {
            params: formData
        })
        .then(function(response) {
            updateQueryQuota();
            console.log(response);
            let wordInfo = response.data;
            addWordListRow(wordInfo);
        })
        .catch((error) => console.log(error))
}

function canQueryWord() {
    let elementQueryCount = document.getElementById("query_count");
    let elementQueryLimit = document.getElementById("query_limit");
    if (elementQueryLimit.value > elementQueryCount.value) {
        return true;
    } else {
        return false;
    }
}

function addQueryRecord(exam_question_word_id) {
    let elementQueryCount = document.getElementById("query_count");
    let query_count = elementQueryCount.value;
    ++query_count;
    elementQueryCount.value = query_count;

    gQueryIDList.push(exam_question_word_id.toString());
}

function updateQueryQuota() {
    let elementQueryCount = document.getElementById("query_count");
    let elementQueryLimit = document.getElementById("query_limit");
    let elementSpanQueryLimit = document.getElementById("span_query_limit");

    let quota = elementQueryLimit.value - elementQueryCount.value;
    elementSpanQueryLimit.innerHTML = `剩餘${quota}次`;

    if (quota == 0) {
        document.getElementById("txtSearch").disabled = true;
        document.getElementById("btnQuery").disabled = true;
    }

}



function addWordListRow(wordInfo) {
    let table = document.querySelector("#wordList tbody");
    let htmlDescriptionCell = renderWordDescriptionCell(wordInfo.words_description);
    let meta_keyword = (wordInfo.meta_keyword == null ? '&nbsp;' : wordInfo.meta_keyword);
    let priorRow = table.innerHTML;
    let row = `
    <tr>
        <td>${wordInfo.word}</td>
        <td>${htmlDescriptionCell}</td>
        <td>${meta_keyword}</td>
    </tr>  
    `;
    
    //table.innerHTML += row;
    table.innerHTML = row + priorRow;

}

function renderWordDescriptionCell(words_description) {

    let words_description_cell = '';

    words_description.forEach(function(value, index, array) {

        if (index >= 1) {
            words_description_cell += '<br>';
        }

        words_description_cell +=
            `<span>${array[index]["part_of_speech"]}</span>&nbsp;&nbsp;&nbsp;<sapn>${array[index]["chinese_description"]}</span>`;

    });

    return words_description_cell;
}