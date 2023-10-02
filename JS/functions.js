var DataAPI = 'http://localhost/Fire_mesage/PHP/API/';
var errordata = ["連線失敗", "沒有接收到參數", "重複資料", "格式不符", "林偉倫在搞", "查無資料", "狀態不符合"];

window.onload = function () {
    var Menu = document.getElementById("Menu"),
        Fire_Data = document.getElementById("Fire_Data");
}


function Menu_Home_draggable() {
    $(function () {
        // 選單列表拖動功能
        $('#Menu_Home').draggable({
            axis: "y",
            containment: "body" // 在父元素內限制拖動範圍 
        });
    })
}

function Rectangle_on() { //選單按鈕
    $('#Menu_Menu').animate({ left: '13vw' }, 500);     //滑動效果
    $('#Menu').animate({ left: '0vw' }, 500);          //滑動效果
    $('#Menu_Home').animate({ left: '-50vw' }, 500);     //滑動效果

    Menu.focus();
}
function Rectangle_chear() {    //初始狀態
    $('#Menu_Menu').animate({ left: '-50vw' }, 500);   //滑動效果
    $('#Menu').animate({ left: '-50vw' }, 500);      //滑動效果
    $('#Menu_Home').animate({ left: '0vw' }, 500); //滑動效果
    $('#Fire_Data').animate({ left: '-50vw' }, 500);
    /* Menu_Home.style.display='flex';   */
}
function Notify_on() {  //點集滅火器資訊
    $('#Fire_Data').animate({ left: '0vw' }, 500);          //滑動效果
    $('#Menu_Home').animate({ left: '-50vw' }, 500);     //滑動效果
    ShowNotify();
    Fire_Data.focus();
}

// 查詢滅火器資訊
function ShowNotify() {
    fetch(DataAPI + 'getDefectiveFire.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            var fire_Powder_Report = document.getElementById("fire_Powder_Report");
            if (data["status"] == "fail") {
                fire_Powder_Report.innerHTML = "<h1>" + errordata[data["data"]] + "</h1>";
            } else {
                fire_Powder_Report.children[1].innerText = data["Expire"]
                fire_Powder_Report.children[3].innerText = data["Repair"];
            }
        })
}

//菜單
function MenuNumber(index_number) {
    var Menu_Select_Mesage = document.getElementById("Menu_Select_Mesage");
    Menu_Select_Mesage.children[index_number].className = "Menu_Select_Mesage_dd_Onclick";
    Menu_Select_Mesage.children[index_number].onclick = false; //禁用按鈕
}



//文字超出處理
function OverNotesHover(index_hover) {
    if (index_hover.innerText.length > 10) {
        var OverNotesHu = index_hover.children[0];
        OverNotesHu.style.display = 'block';
        OverNotesHu.innerHTML = index_hover.innerText;
    }
}
function OverNotesHoverOut(index_hover) {
    var OverNotesHu = index_hover.children[0];
    OverNotesHu.innerHTML = "";
    OverNotesHu.style.display = 'none';
}

//判斷頁數
function PageNumber() {
    if (endPage == 1) {  //判定是不是只有一頁
        page_button[0].disabled = true;
        page_button[0].style = "cursor: not-allowed";  //游標改為禁用
        page_button[1].disabled = true;
        page_button[1].style = "cursor: not-allowed";
    } else if (Page == endPage) {  //判定是不是最後一頁
        page_button[0].disabled = false; //啟動按鈕
        page_button[0].style = "cursor: pointer";
        page_button[1].disabled = true; //禁用按鈕
        page_button[1].style = "cursor: not-allowed";
    } else if (Page == 1) {                           //判定是不是第一頁
        page_button[0].disabled = true;
        page_button[0].style = "cursor: not-allowed";
        page_button[1].disabled = false;
        page_button[1].style = "cursor: pointer";
    } else {                                       //判定是否為中間頁
        page_button[0].disabled = false;
        page_button[0].style = "cursor: pointer";
        page_button[1].disabled = false;
        page_button[1].style = "cursor: pointer";
    };
}
function PageOn1(se_ma = 0) { //上一頁
    Page = eval(Page - "1");
    Selectform(Page);
    if (se_ma == 0) {
        Importcheckbox();
    }
    console.log("第 " + Page + " 頁");

}
function PageOn2(se_ma = 0) {  //下一頁
    Page = String(parseInt(Page) + 1);
    //正常加法會被當成字串相加，須將 Page 轉數字相加再轉成文字

    Selectform(Page);
    if (se_ma == 0) {
        Importcheckbox();
    }
    console.log("第 " + Page + " 頁");
}

