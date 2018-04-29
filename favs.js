$(function() {

    var favs = [];

    $("#search").autocomplete({
        source: 'search.php'
    });

    $("#search").on("autocompleteselect", function () {
        $('#add').toggle();
    });

    $("#add").click( function(e) {
        e.preventDefault();
        favs.push($("#search").val());
        var str = "<p>" + $("#search").val() + "</p>";
        $("#favmovies").append(str);
        $("#search").val("");
        $("#add").toggle();
    } );

    $("#save").click(function () {
        $.ajax({
            url: "update_fav.php",
            type: 'POST',
            data: favs,
            dataType: json
        }
    }
});