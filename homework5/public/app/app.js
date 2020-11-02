import * as Model from "../model/model.js";

function initListeners(){
    $("#genre").change(function(){
        console.log(this.value);
        Model.getAlbumByGenre(this.value);
    })
}



$(document).ready(function(){
    Model.initFirebase();
    Model.signIn(initListeners);
})