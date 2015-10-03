<?php
include("dsession.php");
$id=$_SESSION['customer_id'];
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
    <script src="js/bootstrap.min.js"></script>

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
            <a href="demergency.php"<button type="link" id="emergencyButton" class="btn btn-default"> Emergency </button></a>
        </div>
        <div id="dHeadRight">
            <a href="dsheets.php"<button type="link" id="logoutButton" class="btn btn-default"> Back </button></a>
        </div>
    </div><br>
    <div id="dWireFrame">
        <b align="center" id="welcome">Driver : <?php echo $login_name; ?></b><br>
        <p><?php date_default_timezone_set("America/New_York"); $date = date("Y-m-d"); print $date; ?></p>
        <div id="dInsideFrame">
            <?php
            include('connection.php');
            $data = mysql_query("SELECT * FROM `clients` WHERE `clients`.`cID` = $id") or die(mysql_error());

            Print "<table border cellpadding = 3 class=\"driverDetailsSheets\">";
            while ($info = mysql_fetch_array($data)) {
                Print "<th width=\"80%\">Client</th>";
                Print "<tr><td id=\"dDSClient\">" . $info['cFirstName'] . ' ' . $info['cLastName'] . "</td></tr>";
                Print "<th width=\"80%\" id=\"dDSAddress\">Address</th>";
                Print "<tr><td id=\"dDSAddress1\">" . $info['cAddress1'] . ' ' . $info['cAddress2'] . "</td></tr>";
                Print "<tr><td id=\"dDSAddress2\">" . $info['cCity'] . ', ' . $info['cState'] . ' ' . $info['cZip'] . "</td></tr>";
                Print "<th width=\"80%\" id=\"dDSAddress\">Phone</th>";
                Print "<tr><td id=\"dDSNotes\">" . $info['cPhone'] . "</td></tr>";
                Print "<th width=\"80%\" id=\"dDSAddress\">Status</th>";
                Print "<form action=\"dcheckout.php\" method=\"post\">";
                Print "<div class=\"btn-group\" data-toggle=\"buttons\"><tr><td id=\"dDSStatus\">
                <div id=\"checkoutText1\"><b>Complete Delivery</b></div>
                <div id=\"checkoutText2\"><input type=\"radio\" id=\"option1\" name=\"options\" autocomplete=\"off\" value=\"1\"><label for=\"option1\"><span></span> </label></div>
                </td></tr>";
                Print "<tr><td id=\"dDSReschedule\">
                <div id=\"checkoutText1\"><b>Reschedule Delivery</b></div>
                <div id=\"checkoutText2\"><input type=\"radio\" id=\"option2\" name=\"options\" autocomplete=\"off\" value=\"2\"><label for=\"option2\"><span></span> </label></div>
                </td></tr></div>";
                Print "</table>";
                Print "<tr><td></tr><button type=\"submit\" id=\"buttonComplete\" class=\"btn btn-default\">Finalize</button></td></tr>";
                Print "</form>";
            } ?>

            <?php
            if (isset($_POST['options']))
            {
                include "connection.php";
                $option = $_POST['options'];
                if($option == 1)
                {
                    $data = mysql_query("SELECT * FROM `routes`, `drivers`, `clients` WHERE `routes`.`cID` = `clients`.`cID` AND `routes`.`dID` = `drivers`.`dID` AND `routes`.`rDate` = '$date' AND `drivers`.`dUsername` = '$login_session' AND `clients`.`cID` = '$id'") or die(mysql_error());
                    $info = mysql_fetch_array($data);
                    $rID = $info['rID'];
                    $query = "UPDATE `routes` SET `routes`.`rSuccess` = '1' WHERE `routes`.`rID` =  '$rID'";
                    $data = mysql_query ($query)or die(mysql_error());
                    if($data)
                    {
                        Print "<div id=\"emergencyConfirmation\"><p>Complete</p></div>";
                        header('Location: dhome.php');
                    }
                }
                if($option == 2)
                {
                    $data = mysql_query("SELECT * FROM `routes`, `drivers`, `clients` WHERE `routes`.`cID` = `clients`.`cID` AND `routes`.`dID` = `drivers`.`dID` AND `routes`.`rDate` = '$date' AND `drivers`.`dUsername` = '$login_session' AND `clients`.`cID` = '$id'") or die(mysql_error());
                    $info = mysql_fetch_array($data);
                    $rID = $info['rID'];
                    $query = "UPDATE `routes` SET `routes`.`rReschedule` = '1' WHERE `routes`.`rID` =  '$rID'";
                    $data = mysql_query ($query)or die(mysql_error());
                    if($data)
                    {
                        Print "<div id=\"emergencyConfirmation\"><p>Recheduled</p></div>";
                        header('Location: dhome.php');
                    }
                }
            }
            ?>

            <form class="form-inline" action="dcheckout.php" method="post">
                <table><tr id="noteRow"><td width="80%">
                <div id="noteLeft">
                    <input type="text" name="comment" class="form-control" id="noteField" placeholder="Note">
                        </td><td width="5%">
                    <label> Flag
                        <input type="checkbox" name="flag" id="noteCheck">
                    </label>
                </div></td><td width="20%">
                <div id="noteRight">
                     <button type="submit" id="noteButton" class="btn btn-default">Add Note</button>
                </div></td></tr>
                <?php
                if (isset($_POST['comment']))
                {
                    include "connection.php";
                    $comment = $_POST['comment'];
                    if (isset($_POST['flag']))
                    {
                        $query = "INSERT INTO `notes` (nDate, cID, dID, nComment, nUrgent) VALUES ('$date','$id','$login_id','$comment', 1)";
                        $data = mysql_query ($query)or die(mysql_error());
                        if($data)
                        {
                            Print "<div id=\"emergencyConfirmation\"><p>Note Added</p></div>";
                        }
                    }
                    else
                    {
                        $query = "INSERT INTO `notes` (nDate, cID, dID, nComment, nUrgent) VALUES ('$date','$id','$login_id','$comment', 0)";
                        $data = mysql_query ($query)or die(mysql_error());
                        if($data)
                        {
                            Print "<div id=\"emergencyConfirmation\"><p>Note Added</p></div>";
                        }
                    }
                }
                ?>
            </form>


            <?php
            $data2 = mysql_query("SELECT * FROM `notes` WHERE `notes`.`cID` = $id ORDER BY `nDate` DESC LIMIT 10") or die(mysql_error());
            if (mysql_num_rows($data2) == 0)
            {

            }
            else
            {
            Print "<table border cellpadding = 3 class=\"driverNotesSheets\">";
            {
                Print "<th width=\"75%\">Note</th>" . "<th width=\"5%\">Flag</th>" . "<th width=\"30%\">Date</th>";
            }
            while ($info = mysql_fetch_array($data2)) {
                Print "<tr><td id=\"dDSNNote\">" . $info['nComment'] . "</td>";
                if($info['nUrgent'] == 0)
                {
                    Print "<td id=\"dDSNFlag\"> </td>";
                }
                else
                {
                    Print "<td id=\"dDSNFlag\">&#10003</td>";
                }
                Print "<td id=\"dDSNDate\">" . $info['nDate'] . "</td></tr>";
            }
            Print "</table>";}
            ?>
            
        </div>
    </div>
    <?php mysql_close($connection); ?>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</body>
</html>