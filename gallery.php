<!DOCTYPE html>
<html>
<head>
<title>Gallery</title>
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
    <div style="display:inline-block"> 
        <br>
        <h1><i>Diepdale Gallery</i></h1>
        <span><strong>Select Category:</strong></span>
        <div class="btn-group">
            <button class="btn btn-info" name="all" onclick="javascript: document.location = 'gallery?category=all';">All</button>
            <button class="btn btn-info" onclick="javascript: document.location = 'gallery?category=sports';">Sports</button>
            <button type="button" class="btn btn-info" onclick="javascript: document.location = 'gallery?category=academic';">Academic</button>
            <button type="button" class="btn btn-info" onclick="javascript: document.location = 'gallery?category=events';">Events</button>
            <button type="button" class="btn btn-info" onclick="javascript: document.location = 'gallery?category=school';">School</button>
        </div>  
        <br>
        <?php
        if(isset($_GET['category'])){
        $c = $_GET['category'];
            if($c == 'all'){
                require_once 'app_config.php';
                $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
            
                //Query to view all messages
                $sql = "SELECT * FROM gallery ORDER BY gal_id DESC";
                $result = mysqli_query($connection, $sql);
            
                if(mysqli_num_rows($result) > 0){
                    echo "<div class='row'>";
                    while($row = mysqli_fetch_assoc($result)){
                        echo"<div class='column'>
                            <h4>".$row['gal_title']."</h4>
                            <i>".$row['gal_date']."</i>
                            <p>".$row['gal_des']."</p>
                            <img class='img-fluid' src='".$row['gal_path']."' alt='".$row['gal_title']."' width='350' height='350'> 
                            </div>
                        ";
                    }
                    echo "</div>";
                }
                else{
                    echo "There are no images yet.";
                }

                mysqli_close($connection);
            }
            if($c == 'sports'){
                require_once 'app_config.php';
                $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
            
                //Query to view all messages
                $sql = "SELECT * FROM gallery WHERE gal_category = 'sports' ORDER BY gal_id DESC";
                $result = mysqli_query($connection, $sql);
            
                if(mysqli_num_rows($result) > 0){
                    echo "<div class='row'>";
                    while($row = mysqli_fetch_assoc($result)){
                        echo"<div class='column'>
                            <h4>".$row['gal_title']."</h4>
                            <i>".$row['gal_date']."</i>
                            <p>".$row['gal_des']."</p>
                            <img class='img-fluid' src='".$row['gal_path']."' alt='".$row['gal_title']."' width='350' height='350'> 
                            </div>
                        ";
                    }
                    echo "</div>";
                }
                else{
                    echo "There are no images yet.";
                }

                mysqli_close($connection);
            }

            if($c == 'academic'){
                require_once 'app_config.php';
                $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
            
                //Query to view all messages
                $sql = "SELECT * FROM gallery WHERE gal_category = 'academic' ORDER BY gal_id DESC";
                $result = mysqli_query($connection, $sql);
            
                if(mysqli_num_rows($result) > 0){
                    echo "<div class='row'>";
                    while($row = mysqli_fetch_assoc($result)){
                        echo"<div class='column'>
                            <h4>".$row['gal_title']."</h4>
                            <i>".$row['gal_date']."</i>
                            <p>".$row['gal_des']."</p>
                            <img class='img-fluid' src='".$row['gal_path']."' alt='".$row['gal_title']."' width='350' height='350'> 
                            </div>
                        ";
                    }
                    echo "</div>";
                }
                else{
                    echo "There are no images yet.";
                }

                mysqli_close($connection);
            }
            if($c == 'events'){
                require_once 'app_config.php';
                $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
            
                //Query to view all messages
                $sql = "SELECT * FROM gallery WHERE gal_category = 'events' ORDER BY gal_id DESC";
                $result = mysqli_query($connection, $sql);
            
                if(mysqli_num_rows($result) > 0){
                    echo "<div class='row'>";
                    while($row = mysqli_fetch_assoc($result)){
                        echo"<div class='column'>
                            <h4>".$row['gal_title']."</h4>
                            <i>".$row['gal_date']."</i>
                            <p>".$row['gal_des']."</p>
                            <img class='img-fluid' src='".$row['gal_path']."' alt='".$row['gal_title']."' width='350' height='350'> 
                            </div>
                        ";
                    }
                    echo "</div>";
                }
                else{
                    echo "There are no images yet.";
                }

                mysqli_close($connection);
            }
            if($c == 'school'){
                require_once 'app_config.php';
                $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
            
                //Query to view all messages
                $sql = "SELECT * FROM gallery WHERE gal_category = 'school' ORDER BY gal_id DESC";
                $result = mysqli_query($connection, $sql);
            
                if(mysqli_num_rows($result) > 0){
                    echo "<div class='row'>";
                    while($row = mysqli_fetch_assoc($result)){
                        echo"<div class='column'>
                            <h4>".$row['gal_title']."</h4>
                            <i>".$row['gal_date']."</i>
                            <p>".$row['gal_des']."</p>
                            <img class='img-fluid' src='".$row['gal_path']."' alt='".$row['gal_title']."' width='350' height='350'> 
                            </div>
                        ";
                    }
                    echo "</div>";
                }
                else{
                    echo "There are no images yet.";
                }

                mysqli_close($connection);
            }
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