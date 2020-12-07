

function homeInit(){
   $("button").click((e)=>{
      let buttonID = e.currentTarget.id;
      console.log(buttonID);
      $.get(`views/${buttonID}/${buttonID}.html`, (pageData)=>{
          $("#content").html(pageData);
  })
});
};

homeInit();