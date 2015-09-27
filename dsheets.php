<?php
include("dsession.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-O-W Delivery</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="dBackDiv">
    <div id="dHead">
        <div id="dHeadLeft">
            <a href="#"<button type="link" id="emergencyButton" class="btn btn-default">    Emergency    </button></a>
        </div>
        <div id="dHeadRight">
            <a href="dhome.php"<button type="link" id="logoutButton" class="btn btn-default">               Back                </button></a>
        </div>
    </div><br>
    <div id="dWireFrame">
        <b align="center" id="welcome">Driver : <?php echo $login_name; ?></b><br>
        <p><?php date_default_timezone_set("America/New_York"); $date = date("Y-m-d"); print $date; ?></p>
        <div id="dInsideFrame">
            <?php
            include('connection.php');

            if(empty($_POST["cID"]))
            {
            }
            else
            {
                $id = trim($_POST['cID']);
            }
            $data = mysql_query("SELECT * FROM `clients` WHERE `clients`.`cID` = $id") or die(mysql_error());

            Print "<table border cellpadding = 3 class=\"driverDetailsSheets\">";
            while ($info = mysql_fetch_array($data)) {
                Print "<th width=\"80%\">Client</th>";
                Print "<tr><td id=\"dDSClient\">" . $info['cFirstName'] . ' ' . $info['cLastName'] . "</td></tr>";
                Print "<th width=\"80%\" id=\"dDSAddress\">Address</th>";
                Print "<tr><td id=\"dDSAddress1\">" . $info['cAddress1'] . ' ' . $info['cAddress2'] . "</td></tr>";
                Print "<tr><td id=\"dDSAddress2\">" . $info['cCity'] . ', ' . $info['cState'] . ' ' . $info['cZip'] . "</td></tr>";
                Print "<th width=\"80%\" id=\"dDSAddress\">Delivery Notes</th>";
                Print "<tr><td id=\"dDSNotes\">" . $info['cDeliveryNotes'] . "</td></tr>";
                Print "<th width=\"80%\">Food Restrictions</th>";
                if($info['cFoodRestrictions'] == 0)
                {
                    Print "<tr><td id=\"dDSNotes\">No</td></tr>";
                }
                else
                {
                    Print "<tr><td id=\"dDSNotes\">Yes</td></tr>";
                }
                Print "<th width=\"80%\">Food Allergies</th>";
                if($info['cFoodAllergies'] == 0)
                {
                    Print "<tr><td id=\"dDSNotes\">No</td></tr>";
                }
                else
                {
                    Print "<tr><td id=\"dDSNotes\">Yes</td></tr>";
                }
            }
            Print "</table>";
            mysql_close($connection);
            ?>
        </div>
    </div>
</div>
</body>
</html>