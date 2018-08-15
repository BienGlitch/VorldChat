<?php
    // Include Connection String file
    include("connect.php");
    
    $id = ""; ## Declare variable $id as an empty string.
    $uid = ""; ## Declare variable $uid as an empty string.
    $user = ""; ## Declare variable $user as an empty string.
    $user_main = ""; ## Declare variable $user_main as an empty string.
    
    // Resume existing session
    session_start();

    // If session is set to 'user_login': email or phone of user.
    if (isset($_SESSION['user_login'])) {
        # code...
        $user_login = @$_SESSION['user_login']; ## Assign session variables to variable $user_login.

        $get_user = "SELECT * FROM `users` WHERE `email` = '$user_login' OR `phone` = '$user_login'";
        $get_user_sqli = mysqli_query($connect, $get_user);
        $get = mysqli_fetch_assoc($get_user_sqli);
        $id = $get['id'];
        $user_main = $get['username']; ## Assign `username` to variable $user_main.

        $user = $user_main; ## Assign variable $user_main to variable $user.

        if (isset($_GET['id'])) {
            # code...
            $uid = @$_GET['id'];
        }
        $get_current_username = "SELECT * FROM `users` WHERE `id` = '$uid'";
        $gCU_sqli = mysqli_query($connect, $get_current_username);
        $gCU = mysqli_fetch_assoc($gCU_sqli);
        $cUsername = $gCU['username'];

        if ($cUsername == $user) {
            # code...
            $cUsername = "Your";
        }
        else{
            $cUsername = str_replace($cUsername, "$cUsername's", $cUsername);
        }
    }
?>
<!DOCTYPE html>
<html>

<?php
    //Send request to server to get http or https address
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	//Set $currentURL as the http or https address
    $currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];

    // Perform specified actions if $currentURL contains any of the following links.
    if (strpos($currentURL, "http://localhost:90/vorldchat.com/index")!==false){
        # code..
        $title = "V-Chat: Home";
    }
    elseif (strpos($currentURL, "http://localhost:90/vorldchat.com/login")!==false) {
        # code...
        $title = "V-Chat: Login";
    }
    elseif (strpos($currentURL, "http://localhost:90/vorldchat.com/signup")!==false) {
        # code...
        $title = "V-Chat: Signup";
    }
    elseif (strpos($currentURL, "http://localhost:90/vorldchat.com/profile?id=$uid")!==false) {
        # code...
        if ($user) {
            # code...
            $title = "V-Chat: $cUsername Profile";
        }
        else {
            $title = "V-Chat: BAD_REQUEST";
        }
    }
    else{
        # code..
        $title = "&copy;2018 &nbsp; V-Chat";
    }
?>

<head>
    <!-- PageTitle -->
    <title><?php echo $title; ?></title>
    
    <!-- Meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title Bar Icon -->
	<link rel="icon" type="img/png" href="./img/fav-icon.png">
	
		
    <!-- Icon CSS Link -->
    <link rel="stylesheet" type="text/css" href="./style/custom/font-awesome.min.css" />

    
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="./style/bootstrap/css/bootstrap.min.css" />
    
    <!-- Extra Styling -->
    <!--<link rel="stylesheet" type="text/css" href="./style/custom/w3.css" />-->
    <!--<link rel="stylesheet" type="text/css" href="./style/custom/sidenav.css" />-->
    <link rel="stylesheet" type="text/css" href="./style/custom/myStyle.css" />
    <link rel="stylesheet" type="text/css" href="./style/custom/animate.css" />
    
    <!-- JScripts -->
    <script src="./js/popper.min.js"></script>
    <script src="./js/jquery.min.js"></script>
    <script src="./style/bootstrap/js/bootstrap.min.js"></script>
</head>

<?php
    // Display specific link if $currentURL = specified link.
    if (strpos($currentURL, "http://localhost:90/vorldchat.com/sec")!==false ) {
        ?>
        <!-- Navbar Brand and Toggler -->
        <nav class="navbar navbar-expand navbar-dark bg-primary fixed-top">
            <a href="" class="navbar-brand animated flash">VCONNECT</a>
            <button type="button" class="navbar-toggler justify-content-end" data-toggle="collapse" data-target="#navCollapsed">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse justify-content-center" id="navCollapsed">
                <ul class="navbar-nav">
                    <li class="nav-item ml-sm-5">
                        <a class="nav-link sec_check" href=""><h2><i class="fa fa-lock"></i> SECURITY CHECK</h2></a>
                    </li>
                </ul>
            </div>
        </nav>
        <?php
    }
    elseif (strpos($currentURL, "http://localhost:90/vorldchat.com/pwrdch")!==false ) {
        ?>
        <!-- Navbar Brand and Toggler -->
        <nav class="navbar navbar-expand navbar-dark bg-primary fixed-top">
            <a href="" class="navbar-brand animated flash">VCONNECT</a>
            <button type="button" class="navbar-toggler justify-content-end" data-toggle="collapse" data-target="#navCollapsed">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse justify-content-center" id="navCollapsed">
                <ul class="navbar-nav">
                    <li class="nav-item ml-sm-5">
                        <a class="nav-link sec_check" href=""><h2><i class="fa fa-lock"></i> PASSWORD RESET</h2></a>
                    </li>
                </ul>
            </div>
        </nav>
        <?php
    }
    else{
        ?>
        <!-- Navbar Brand and Toggler -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-primary fixed-top">
            <a href="" class="navbar-brand animated slideInLeft">VCONNECT</a>
            <button type="button" class="navbar-toggler justify-content-end" data-toggle="collapse" data-target="#navCollapsed">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navCollapsed">
                <ul class="navbar-nav">
                    <?php
                        // Display specific links if $currentURL = specified links respectively.
                        if (strpos($currentURL, "http://localhost:90/vorldchat.com/index")!==false) {
                            if ($user) {
                                # code...
                                ?>
                                <li class="nav-item mr-sm-5 animated slideInUp">
                                    <a class="nav-link" href="signup"><i class="fa fa-unlock-alt"></i> Signup</a>
                                </li>
                                <li class="nav-item mr-sm-5 animated slideInUp">
                                    <a class="nav-link" href="logout"><i class="fa fa-sign-out"></i> Logout</a>
                                </li>
                                <?php
                            }
                            elseif (!$user) {
                                # code...
                                ?>
                                <li class="nav-item mr-sm-5 animated slideInUp">
                                    <a class="nav-link" href="signup"><i class="fa fa-unlock-alt"></i> Signup</a>
                                </li>
                                <?php
                            }
                        } 
                        elseif (strpos($currentURL, "http://localhost:90/vorldchat.com/signup")!==false) {
                            if ($user) {
                                # code...
                                ?>
                                <li class="nav-item mr-sm-5 animated slideInDown">
                                    <a class="nav-link" href="index"><i class="fa fa-sign-in"></i> Login</a>
                                </li>
                                <li class="nav-item mr-sm-5 animated slideInDown">
                                    <a class="nav-link" href="logout"><i class="fa fa-sign-out"></i> Logout</a>
                                </li>
                                <?php
                            }
                            elseif (!$user) {
                                # code...
                                ?>
                                <li class="nav-item mr-sm-5 animated slideInDown">
                                    <a class="nav-link" href="index"><i class="fa fa-sign-in"></i> Login</a>
                                </li>
                                <?php
                            }
                        } 
                        elseif (strpos($currentURL, "http://localhost:90/vorldchat.com/sec")!==false) {
                            ?>
                            <?php
                        } 
                        else {
                            if ($user) {
                                # code...
                                ?>
                                <li class="nav-item mr-sm-5">
                                    <a class="nav-link" href="Logout"><i class="fa fa-sign-out"></i> Logout</a>
                                </li>
                                <?php 
                            } 
                        }
                    ?>
                    <!-- 
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>    -->
                </ul>
            </div>
        </nav>
        <?php
    }
?>
<body class="">