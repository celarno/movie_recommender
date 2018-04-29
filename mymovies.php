<?php
session_start();
require('connect.php');
require('api_keys.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Movies</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="style_main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-xl navbar-dark navbar-custom">
    <div class="container-fluid">
        <div class="navbar-header">
            <span class="navbar-brand"><a href="#"><i class="fas fa-tv"></i></a></span>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">My Movies <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myprofile.php">My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="recommendations.php">Recommendations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="browse.php">Browse Movies</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><span class="nav-link"><i class="fas fa-user-circle"></i> Welcome, <?php echo $username?></span></li>
                <li class="nav-item"><a class="nav-link" href='logout.php'><i class="fas fa-external-link-alt"></i> Logout</button></a></li>
            </ul>
        </div>
    </div>
</nav>
    <div id="main">
        <h2>My Movies</h2>
        <div class="ui-widget">
            <label for="search">Add new favorite movie: </label>
            <input id="search" width="auto">
            <a id="adding" href="#" style="display: none"><i class="fas fa-plus-circle"></i> add to favorites</a>
        </div>
        <div id="favmovies_new" style="display: none;"><h3>New Favorites</h3></div>
        <button id="save" style="display: none;"><i class="fas fa-save"></i> save to favorites</button>
        <p id="results" style="margin-top:1em;color: #9d1206"></p>
        <h3>Current Favorites</h3>
            <div id="favmovies">
                <table id="movieTable" class="table table-hover table-sm table-responsive-lg">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Year</th>
                        <th>Genres</th>
                        <th>Rating</th>
                        <th>Favorite</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        //$("#favmovies").html('loading favorites ...');
        var table = $('#movieTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "fav_table.php"
        });

        $('#movieTable tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            window.location.href = "movie.php?id=" + data[0];
        });

        $('#movieTable tbody tr').hover(function () {
            $(this).css("cursor","pointer");
        });

        var favs = [];

        $("#search").autocomplete({
            source: 'search.php'
        });

        $("#search").on("autocompleteselect", function () {
            $("#adding").toggle();
        });

        $("#adding").click( function(e) {
            e.preventDefault();
            $("#favmovies_new").show();
            favs.push($("#search").val());
            var str = "<p><i class='fas fa-plus-circle'></i> " + $("#search").val() + "</p>";
            $("#favmovies_new").append(str);
            $("#search").val("");
            $("#adding").toggle();
            $("#save").show();
        });

        $("#save").click(function(){
            favs.forEach(function(f){
                $.ajax({
                    type: "POST",
                    url: "update_fav.php",
                    data: {fav:f},
                    dataType: "JSON",
                    success: function(data) {
                        $("#results").html(data);
                        location.reload();
                    },
                    error: function(err) {
                        $("#results").html(data);
                    }
                });
            });
        });
    });
</script>
</body>
</html>