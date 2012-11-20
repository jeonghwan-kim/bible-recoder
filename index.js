/*********************************************
Blog Class
*********************************************/

function Bible(num, title, total, written, display) {
    this.num = num; // db 번호
    this.title = title; // 성경 이름
    this.total = total; // 총 장수
    this.written = written; // 입력한 장인가?
    this.display = display; // 화면에 나타내기/가리기
}

/*********************************************
Global varialble / commands
*********************************************/

// bible 배열
var bibles = new Array();

// ajax 요청 객체
var ajaxReq = new AjaxRequest();

// ajax 요청 보내기
ajaxReq.send("get", "bible-list.php", bible_list_handler);

/*********************************************
Function Definition
*********************************************/

function bible_list_handler() {
    if (ajaxReq.getReadyState() == 4 && ajaxReq.getStatus() == 200) {
        ajaxReq.send("get", "bible.xml", bible_xml_hander);
    }
}

// 로딩후 ajax 핸들러
function bible_xml_hander() {
    if (ajaxReq.getReadyState() == 4 && ajaxReq.getStatus() == 200) {
        var xmlElement = ajaxReq.getResponseXML();  

        // 블로그 객체 signiture 저장
        var entries = xmlElement.getElementsByTagName("entry");

        for (var i = 0; i < entries.length; i++) {
            var num     = getText(entries[i].getElementsByTagName("num")[0]);
            var title   = getText(entries[i].getElementsByTagName("title")[0]);
            var total   = getText(entries[i].getElementsByTagName("total")[0]);
            var written = getText(entries[i].getElementsByTagName("written")[0]);
            var display = (i < 10) || (i > 38 && i < 49) ? "block" : "none";
            bibles.push(new Bible(num, title, total, written, display));
        }
        
        setBibleList();
    }
}

// ajax respons 후 성경리스트 출력
function setBibleList() {
    var text = "<ul>";

    // 구약리스트 출력
    for (var i = 0; i < 39; i++) {
        text += "<li style='display:" + bibles[i].display + ";'><a href='#'>" 
        + bibles[i].title + " " + bibles[i].written + "/" + bibles[i].total + "</a></li>";
    }
    document.getElementById("oldbiblelist").innerHTML = text + "</ul>";

    // 신약리스트 출력
    text = "<ul>";
    for (var i = 39; i < 66; i++) {
        text += "<li style='display:" + bibles[i].display + ";'><a href='#'>" 
        + bibles[i].title + " " + bibles[i].written + "/" + bibles[i].total + "</a></li>";
    }
    document.getElementById("newbiblelist").innerHTML = text + "</ul>";

}

// element 로 부터 nodeValue 얻어내기. 없으면 "" 반환
function getText(elem) {
    var text = "";
    if (elem) {
        if (elem.childNodes) {
            var child = elem.childNodes[0];
            if (child.nodeValue) text = child.nodeValue;
            else alert("error in getText()");
        }
    }
    return text;
}

// more 버튼 클릭시 추가 성경목록을 보여줌
function more(bible, btn) {
    var hiddenElem = document.getElementsByTagName("li");
    if (hiddenElem) {
        if (bible == "oldbible") 
            for (var i = 0; i < 39; i++) 
                hiddenElem[i].style.display = "block"; // 성경 목록 보이기
        else if (bible == "newbible") 
            for (var i = 39; i < 66; i++) 
                hiddenElem[i].style.display = "block"; // 성경 목록 보이기
        else alert("error in more()");
        btn.style.display = "none"; // 버튼 숨기기
    }
}
