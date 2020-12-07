function initListeners(){
    $("nav a").click((e)=>{
        let buttonID = e.currentTarget.id;
        console.log(buttonID);
        $.get(`views/${buttonID}/${buttonID}.html`, (pageData)=>{
            $("#content").html(pageData);
    })
});
}


function initViews(){
    $.get("views/home/home.html", (homePageData)=>{
        $("#content").html(homePageData);
        initListeners();
    })
}

$(document).ready(()=>{
    initViews();
})