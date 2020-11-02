var _db;

export function initFirebase(){
    firebase.auth()
    .onAuthStateChanged((user)=>{
        if(user){
            console.log("Signed in");
        }else{
            console.log("No user");
            _db = '';
        }
    })
}

export function signIn(callback){
    firebase.auth()
    .signInAnonymously()
    .then(function (result){
        _db = firebase.firestore();
        callback();
        displayAllAlbums();
    })
    
}

export function displayAllAlbums(){
    $(".albumTitle").html("");
    $(".albumTitle").append("Albums");

    _db
    .collection("Albums")
    .get()
    .then(function(querySnapshot){
        querySnapshot.forEach(function(doc){
            let album = doc.data();
            $(".content").append(`<div class="album">
           <div> <p class="title">${album.AlbumName}</p>
            <p class="artist">${album.ArtistName}</p>
            <p class="genre">${album.Genre}</p></div>
            <img src="${album.AlbumPhoto}"></img>
            </div>`)
        })
    })

}

export function getAlbumByGenre(genre){
    $(".albumTitle").html("");
    $(".content").html("");

    _db
    .collection("Albums")
    .where("Genre", "==", genre)
    .get()
    .then(function(querySnapshot){
        
        querySnapshot.forEach(function(doc){
            let album = doc.data();
            $(".albumTitle").html(`${album.Genre}`);
            $(".content").append(`<div class="album">
           <div> <p class="title">${album.AlbumName}</p>
            <p class="artist">${album.ArtistName}</p>
            <p class="genre">${album.Genre}</p></div>
            <img src="${album.AlbumPhoto}"></img>
            </div>`)
        })
    })
}