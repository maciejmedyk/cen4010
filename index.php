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
       <div id="indexHead">
           <button type="link" id="indexActiveButton" class="btn btn-default disabled">      Driver      </button>
           <button type="link" class="btn btn-default">      Admin       </button>
       </div>
       <div id=""indexLogin">
       <form id="inputForm" action="#" method="post">
           <img src="img/mowlogo.png" height=45px alt="Meals On Wheels Logo"><br>
           <img src="img/mowdelivery.png" height=35px alt="Delivery Logo"><br><br>
           <label for="username">Username</label><br><input autofocus class="form-control" type="email" id="username" name="username" required><br>
           <label for="password">Password</label><br><input class="form-control" type="password" id="password" name="password" required><br>
           <button type="submit" class="btn btn-default">      Login      </button>
       </form>
       </div>
       <?php ?>
   </body>
</html>