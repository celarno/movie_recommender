<?php
session_start();
if (!isset($_SESSION['username'])){
    header('Location: index.php');
}

require('console_log.php');
require('api_keys.php');
require('connect.php');
require('user_picture.php');

$sql = "SELECT * FROM users WHERE username='". $username ."'";
$userdata = mysqli_fetch_assoc(mysqli_query($connection, $sql));

mysqli_close($connection);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

    <link rel="stylesheet" type="text/css" href="style_main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <div class="navbar-header">
                <span class="navbar-brand"><a href="#"><i class="fas fa-tv"></i></a></span>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="mymovies.php">My Movies <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">Recommendations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse Movies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/admin_movies.php">Admin</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><span class="nav-link"><img class="profile_pic" style="height:20px;width:20px;" src="<?php echo $p ?>"> Welcome, <?php echo $username?></span></li>
                    <li class="nav-item"><a class="nav-link" href='logout.php'><i class="fas fa-external-link-alt"></i> Logout</button></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="main">
    <table>
    <tr>
        <td id="profile_pic" style="text-align:center;float:left;height:100%;vertical-align: middle">
            <h2>My Profile</h2><br>
            <img class="profile_pic" src="<?php echo $p ?>">
            <BR><BR>
            <button id="change_pic" style="display: none" type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#uploadModal">Change</button>

            <!-- Modal -->
            <div id="uploadModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Profile picture</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <!-- Form -->
                            <form method='post' action='' enctype="multipart/form-data">
                                <input type='file' name='file' id='file' class='form-control'><br>
                                <input type='button' class='btn btn-primary' value='Upload' id='upload'>
                            </form>
                            <!-- Preview-->
                            <div id='preview'></div>
                        </div>
                    </div>
                </div>
            </div>

        </td>
        <td id="profile_form">
        <br>
        <form>
            <div class="form-group row">
                <label for="username" class="col-3 col-form-label">Username</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <input id="username" name="username" value="<?php echo $userdata["username"] ?>" type="text" class="form-control here" required="required" disabled>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-3 col-form-label">Password</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-key"></i>
                        </div>
                        <input id="password" name="password" value="<?php echo $userdata["password"] ?>" type="password" class="form-control here" required="required" disabled>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="mail" class="col-3 col-form-label">Mail</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-at"></i>
                        </div>
                        <input id="mail" name="mail" value="<?php echo $userdata["email"] ?>" type="email" class="form-control here" required="required" disabled>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div align="right" class="col-11">
                    <button id="profile_form_submit" style="display: none" name="submit" type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="form-group row">
                <div align="right" class="col-11">
                    <button id="btn_cancel" style="float: right" name="change" type="button" class="btn btn-secondary">Edit profile info</button>
                </div>
            </div>

        </form>
        </td>
    </tr>
    </table>
    </div>
</div>
<script>
    $(document).ready(function(){

        var p = $('#password').val();
        var e = $('#mail').val();

        $('#btn_cancel').click(function(){
            $('#profile_form_submit').toggle();
            $('#change_pic').toggle();

            if($('#profile_form_submit').is(':visible')){
                $(this).text('Cancel');
                $('#mail').prop('disabled', false);
                $('#password').prop('disabled', false);
            } else {
                $('#password').val(p).prop('disabled', true);
                $('#mail').val(e).prop('disabled', true);
                $(this).text('Edit profile info');
            }
        });

        $('#profile_form_submit').click(function(){
            var p = $('#password').val();
            var e = $('#mail').val();

            $.ajax({
                type: "POST",
                url: "update_profile.php",
                data: {e:e,p:p},
                dataType: "JSON",
                success: function(r){
                    $('#profile_form').append(r);
                    $('#btn_cancel').click();
                    $('#mail').val(e);
                    $('#password').val(p);

                    $('.alert').fadeOut(3000, function() {
                        $(this).remove();
                    });

                }, error: function (e) {
                    console.log(e);
                }
            });
        });

        $('#upload').click(function(){
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file',files);

            $.ajax({
                url: 'upload.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response !== 0){
                        $("#upload").hide();
                        $(".profile_pic").attr("src",response);
                        $('#preview').html("<p>Profile picture successfully uploaded.</p>");
                    }else{
                        $('#preview').html("<p>File not uploaded.</p>");
                    }
                }
            });
        });
    });
</script>
</body>
</html>