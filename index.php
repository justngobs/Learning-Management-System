<!DOCTYPE html>
<html>
<head>
<title>Home Page</title>
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
<!-- The content of the website -->
    <br>
    <div style="text-align:center;background-color:yellow"> 
        <hr>
        <br>
        <h1>Welcome to Diepdale</h1>
        <h5><b>Secondary School</b></h5>
        <br>
        <hr>
    </div>
    <br>

    <div style="width:90%;margin-left:5%">
    <br>
        <h3>Our Mission</h3>
        <div class="card">
            <div class="card-body">
                We at Diepdale Secondary School shall endeavor to:<br>
                <ul>
                    <li>Foster a culture of learning and teaching.</li>
                    <li>Develop learners and educators in discovering their full potential in a safe and attractive environment.</li>
                    <li>Engage the community, stakeholders and various parties of interest through active, positive and open communication.</li>
                </ul>   
            </div>
        </div>
    </div>
    <br>

    <div style="width:90%;margin-left:5%">
    <br>
        <h3>School News</h3>
        <div class="card bg-light text-dark">
            <div class="card-body">
                <!-- The three latest posts show up here -->
                <div class="list-group">
                    <?php

                    require_once 'app_config.php';
                    $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

                    $sql = "SELECT * FROM newsandevents WHERE ne_type = 'news' ORDER BY ne_id DESC LIMIT 3";
                    $result = mysqli_query($connection, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<a href='newsandevents.php?item=".$row['ne_id']."' class='list-group-item list-group-item-action' style='color:blue'><u>".$row['ne_title']."</u></a>";
                        }
                    }
                    else{
                        echo "No news items have been posted yet.";
                    }
                    mysqli_close($connection);
                    ?>
                </div>
            </div>
        </div>
        <br>

        <h3>Our Matrics</h3>

                <div class="row">
                    <div class="column" style="background-color:lightgrey;">
                        <h6 style="text-align:center;background-color:black;color:white">Don't blame the darkness, light the candle.</h6>
                        <img class="img-fluid" src="images/logoofdiepdale.png" alt="Logo of diepdale secondary school">
                    </div>
                    <div class="column" style="background-color:lavenderblush;">
                    <h4><b>Matric 2018</b></h4>
                    Congratulations to the matrics of 2018 for obtaining a passrate of [insert rate] which was an improvement of [insert rate] from the passrate achieved in 2017.<br><br>
                    <h5>2018 Grade 12 Top Learners.</h5>
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> 1</td>
                                <td> Lucy</td>
                                <td> Moroti</td>
                                <td> 80% + Average</td>
                            </tr>
                            <tr>
                                <td> 2</td>
                                <td> Perseviarance</td>
                                <td> Ngobeni</td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td> 3</td>
                                <td> Khensani</td>
                                <td> Mareana</td>
                                <td> </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

    <div style="background-color: #555;color: white;text-align:center">
        <br>
        <h4 style="color:black"><b>Subscribe to our newsletter</b></h4>
        <form method="post" action="subscribe.php">
            <input type="text" name="name" required="required" placeholder="name">
            <input type="email" name="email" required="required" placeholder="E-mail">
            <input type="submit" value="Subscribe" style="background-color:black;color:white;border-radius:5px">
        </form>
    </div>
  
<!-- The end of body content of the website -->
</div>

<div style="text-align:center">
    <?php require "footer.html"; ?>
</div>

</body>
</html>