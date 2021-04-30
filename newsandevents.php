<!DOCTYPE html>
<html>
<head>
<title>News and events</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/navigation.css">
</head>
<body style="background-color:  rgb(223,233,190);">

<br>
<div style="text-align:center">
    <?php require 'navigation.html'; ?>
</div>

<div style="width:90%;margin-left:5%;background-color:white">
    <br>
    <div style="display:inline-block;margin-left:3% "> 
        <?php
            if(isset($_GET['item'])){

                require_once 'app_config.php';
                $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
                $id = $_GET['item'];
                if(is_numeric($_GET['item'])){
                    $sql = "SELECT * FROM newsandevents WHERE ne_id = '$id'";
                    $result = mysqli_query($connection, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "
                                <article>
                                    <h4>".$row['ne_title']."</h4>
                                    <i>".$row['ne_date']."</i>
                                    <br>
                                    <br>
                                    <div class='row'>
                                        <div class='col-sm-7'>
                                            <p>".$row['ne_description']."</p>
                                        </div>
                                        <div class='col-sm-5'>
                                            <img src='".$row['ne_path']."' width='300' height='300'>
                                        </div>
                                    </div>
                                </article>";
                        }
                    }
                }
                if(is_numeric($_GET['item']) || $_GET['item'] == 'all'){
                    echo "<br><br><h2>Latest School News</h2><br>";

                    $sql = "SELECT * FROM newsandevents WHERE ne_type = 'news' ORDER BY ne_id DESC";
                    $result = mysqli_query($connection, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['ne_id'] == $_GET['item']){

                            }
                            else{
                            echo "
                            <a href='newsandevents.php?item=".$row['ne_id']."'>
                            <div class='row'>
                                <div class='col-sm-3'>
                                    <img src='".$row['ne_path']."' width='150' height='150'>
                                </div>
                                <div class='col-sm-5'>
                                    <h5>".$row['ne_title']."</h5>
                                    <i>".$row['ne_date']."</i>
                                    <p>".substr($row['ne_description'], 0, -30)."...</p>
                                </div>
                            </div></a><hr>";
                            }
                        }
                    }
                    else{
                        echo "There are no news posts yet.";
                    }
                }

                echo "<br><br><h2>School Events</h2>";

                $sql = "SELECT * FROM newsandevents WHERE ne_type = 'event' ORDER BY ne_id DESC";
                $result = mysqli_query($connection, $sql);

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo "
                        <div class='card'>
                            <div class='card-body'>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                    <b>Title:</b> ".$row['ne_title']."<br><br>
                                    <b>Description:</b> ".$row['ne_description']."<br><br>
                                    <b>Date:</b> ".$row['ne_date']."<br>
                                    </div>
                                    <div class='col-sm-3'>
                                        <img src='".$row['ne_path']."' width='300' height='180'>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                }
                else{
                    echo "No events have been posted yet.";
                }

                mysqli_close($connection);

            }
        ?>
    </div>
    <br>
    <br>
</div>
<div style="text-align:center">
    <?php require "footer.html"; ?>
</div>

</body>
</html>