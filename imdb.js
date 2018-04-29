
function getIMDB(){
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "https://theimdbapi.org/api/movie?movie_id=tt0418279", false);
    xhr.send();
}


