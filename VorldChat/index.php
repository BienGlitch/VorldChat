<?php
    // Include Header file
    include("inc/header.php");

    // Script for Login Action

    $log = @$_POST['login']; ## Assign login button to variable $log
    $err=""; ## Declare variable $err as an empty string

    // If login button is clicked/touched, perform the following
	if($log){
        # code...
        // If User inputs email or phone and password
		if(isset($_POST["user_login"]) && isset($_POST["user_password"])){
            # code...
            ## Assign the inputted email or phone to $user_login variable
            $user_login = $_POST["user_login"];

            ## Remove special characters and Assign the inputted password to $password_login variable
            $password_login = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["user_password"]);
            
            ## Encrypt the password using its md5 hash
            $password_login_md5 = md5($password_login);
            
            // Query1.1: Select all from table `users` where column `email` = inputted email and password = inputted password simultaneously
            $sql = "SELECT * FROM `users` WHERE `email` = '$user_login' AND  `password` = '$password_login_md5'";
            $sqli = mysqli_query($connect, $sql);

            ## Count number of rows where query1.1 is true
            $userCount = mysqli_num_rows($sqli);

            ## Fetch an associative array of query1.1
            $row = mysqli_fetch_array($sqli);
            $id = $row["id"]; ## Assign `id` to $id
            $name = $row["last_name"]; ## Assign `last_name` to $name
            $email = $row["email"]; ## Assign `email` to $email
               
            // Query1.2: Select all from table `users` where column `phone` = inputted phone and password = inputted password simultaneously
            $sql2 = "SELECT * FROM `users` WHERE `phone` = '$user_login' AND  `password` = '$password_login_md5'";
            $sqli2 = mysqli_query($connect, $sql2);

            ## Count number of rows where query1.2 is true
            $userCount2 = mysqli_num_rows($sqli2);

            // Query1.3: Select all from table `users` where column `email` = inputted email or phone = inputted phone
            $block = "SELECT * FROM `users` WHERE `email` = '$user_login' OR `phone` = '$user_login'";
            $block_sqli = mysqli_query($connect, $block);

            ## Count number of rows where query1.3 is true
            $count = mysqli_num_rows($block_sqli);

            ## Fetch an associative array of query1.3
            $get = mysqli_fetch_array($block_sqli);
            $id = $get["id"]; ## Assign `id` to $id         
            $name = $get["last_name"]; ## Assign `last_name` to $name
            $email = $get["email"]; ## Assign `email` to $email   

            // Query1.4: Select all from table `checku` where column `user` = inputted email or phone
            $check = "SELECT * FROM `checkU` WHERE `user` = '$user_login'";
            $check_sqli = mysqli_query($connect, $check);

            ## Count number of rows where query1.4 is true
            $count_check = mysqli_num_rows($check_sqli);
            
            // If $count: number of rows where query1.3 is true; != 0 and $count_check: number of rows where query1.4 is true; <= 3
            if ($count !== 0 and $count_check <= 3) {
                # code...
                // Insert the following values into the respective fields of table: `checku`
                $block_in = "INSERT INTO `checkU` VALUES('', '$user_login', '$email')";
                $block_in_sqli = mysqli_query($connect, $block_in);
            }
            
            // If $count_check: number of rows where query1.4 is true; > 2
            if ($count_check > 2) {
                # code...
                // Redirect to the following URL
                echo "<meta http-equiv =\"refresh\" content=\"4; url = http://localhost/vorldchat.com/sec?id=$id&spageid=1\">";
            }

            // If $count_check: number of rows where query1.4 is true; <= 3
            if ($count_check <= 3) {
                # code...
                
                /* If $userCount: number of rows where query1.1 is true; = 1 and $userCount2: number of rows where query1.2 is true; = 1
                * and $count_check: number of rows where query 4 is true; !> 2
                * N.B: This means that the user inputs matches one row in queries1.1 & 1.2 but doesn't in query1.4; which means successful login
                */  
                if ($userCount || $userCount2 > 0) {
                    # code...
                    $_SESSION["user_login"] = $user_login; //Initialize a session using "user_login" --> Email
                    ## Assign message: Login Successful... ; to variable $err
                    $err = "
                        <div class='alert alert-success'>
                            <span class='fa fa-check-circle-o'></span> Login Successful...
                        </div>                    
                    ";
                    // Delete all data from table `checku` where column `user` = inputted email or phone
                    $rem_block = "DELETE FROM `checkU` WHERE `user` = '$user_login'";
                    $rem_block_sqli = mysqli_query($connect, $rem_block);
                    
                    // Redirect to the followin URL {After a successful Login}
                    echo "<meta http-equiv =\"refresh\" content=\"2; url = http://localhost/vorldchat.com/home\">";
                }

                /* elseIf $userCount: number of rows where query1.1 is true; = 0 and $userCount2: number of rows where query1.2 is true; = 0
                * and $count_check: number of rows where query1.4 is true; !> 2
                * N.B: This means that the user inputs doesn't match at least one row in queries1.1 & 1.2 but doesn't in query1.4; which means 
                * login not successful;.
                * ; Perform the following:
                */  
                else{
                    // If $count_check: number of rows where query1.4 is true; > 2
                    if ($count_check > 2) {
                        # code...
                        ## Assign message: Sorry you have exceeded your login limit for this user: "user email"; to variable $err
                        $err = '
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle"></i> Sorry you have exceeded your login limit for this user: '. "$email".'!!!
                            </div>                    
                        ';
                    }
                    // If the above condition isn't true,
                    else{
                        ## Assign message: The given Login detail is incorrect!!, Please try again...; to variable $err
                        $err = '
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle"></i> The given Login detail is incorrect!!, Please try again...
                            </div>                  
                        ';
                    }
                }
            }
            else{
                ## Assign message: Sorry you have exceeded your login limit for this user: "user email"; to variable $err
                $err = '
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i> Sorry you have exceeded your login limit for this user: '. "$email".'!!!
                    </div>
                ';
            }
                    
	    }
    }
?>
<!-- Div Container of body -->
<div class="container">
    
    <!-- Div container: contains Login Form, Modal & Forgot Password Form -->
    <div class="pt-5 offset-md-2 col-md-8">
        <div class="text-center"><img src="./img/fav-icon.png" class="img-fluid rounded-circle animated slideInDown" width="90" /></div>
        <br /><br />
        
        <?php
            if($user){
                ?>
                <div class="offset-md-1 col-md-10 alert alert-warning text-center animated flash">
                    <i class="fa fa-exclamation-triangle"></i> 
                    Please, Kindly <a class="" href="logout">LOGOUT</a> to view this page!!!
                </div>
                <?php
                exit();
            }
        ?>
        
        <!-- Login Form -->
        <form action="#" method="POST" class="form" validate>
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-user-circle"></i></span>
                        <input type="text" class="form-control" name="user_login" placeholder="Input Email or Phone Number" required />
                    </div>
                </div>
            </div>
            <br />
            <div class="row text-center">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-lock"></i>&nbsp;</span>
                        <input type="password" class="form-control" name="user_password" placeholder="Input your password" required />
                    </div>
                    <div class="float-right">
                        <a href="#" type="button" data-toggle="modal" data-target="#myModal" class="nav-link">
                            <span class="mr-2"><b>FORGOT PASSWORD?<b></span>
                        </a>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <span class="offset-md-1 col-md-10 animated zoomIn">
                    <button class="w-100 btn btn-primary" type="submit" name="login" value="login"> Login </button>
                    <br /><br />
                    <span class="text-center">
                        <div class="col-sm-12">
                            <h6>New to V-CHAT? &nbsp;
                                <b><a href="signup">CREATE AN ACCOUNT</a></b>
                            </h6>
                        </div>
                    </span>
                    <!-- Code to display messages in variable $err when required -->
                    <?php echo $err; ?>
                </span>
            </div>
        </form><!--  End of Login form -->

        <!-- Modal & Modal Form -->
        <form action="#" method="POST" validate>
            <div class="modal animated zoomIn" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Forgot Pasword</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            
                            <span class="text-capitalize text-danger"><h4><b>Please fill in the field as required:</b></h4></span>
                            <div class="pt-3 col-md-12">
                                <span class="text-primary">*Required Fields</span>
                                <div class="row">
                                    <div class="pt-3 col-md-10 p-0">
                                        <div class="d-flex input-group input-group-sm">
                                            <span class="flex-grow"><i class="fa fa-envelope-open-o"></i></span>
                                            <input type="email" name="email" class="form-control bg-white text-dark" placeholder="Input registered email" required />
                                            <h4 class="input-group-append text-primary">*</h4>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row pl-0">
                                    <button type="submit" class="btn btn-dark" name="fpwrd" value="fpwrd">Submit</button>    
                                </div>

                                <!-- Script for forgot password action -->
                                <?php
                                    $em = @$_POST['email']; ## Assign user input for email to variable $em
                                    $err2 = ""; ## Declare variable $err2 as an empty string
                                        
                                    // Query2.1: Select all form table `users` where column `email` = inputted email
                                    $fp_check = "SELECT * FROM `users` WHERE `email` = '$em'";
                                    $fp_check_sqli = mysqli_query($connect, $fp_check);

                                    ## Count number of rows where query2.1 is true
                                    $count = mysqli_num_rows($fp_check_sqli);

                                    ## Fetch an associative array of query2.1
                                    $get = mysqli_fetch_array($fp_check_sqli);
                                    $id = $get['id'];
                                            
                                    // If Submit button is clicked
                                    if (@$_POST['fpwrd']) {
                                        # code...

                                        // If input field for email isn't empty
                                        if ($em != "") {
                                            # code...

                                            /* If $count: number of rows where query2.1 is true; > 0
                                             * N.B: This means that the user inputs matches one row in queries2.1; Which means inputted email * exists in the database.
                                             */
                                            if ($count > 0) {
                                                # code...    
                                                // Insert the following values into the respective fields of table: `fpwrd`
                                                $in_fpwrd = "INSERT INTO `fpwrd` VALUES('', '$em')";
                                                $in_fpwrd_sqli = mysqli_query($connect, $in_fpwrd);

                                                ## Assign message: Please Wait...; to variable $err2
                                                $err2 = '  
                                                    <div class="alert alert-success">
                                                        <i class="fa fa-check-circle"></i> Please Wait... <i class="fa fa-spinner fa-spin"></i>
                                                    </div>
                                                ';

                                                // Redirect to the following URL
                                                echo "<meta http-equiv =\"refresh\" content=\"4; url = http://localhost/vorldchat.com/sec?id=$id\">";
                                            }

                                            /* eslseif $count: number of rows where query2.1 is true; !> 0
                                             * N.B: This means that the user inputs matches one row in queries2.1; Which means inputted email * doesn't exist in the database.
                                             */
                                            else{
                                                ## Assign message: The inputted email doesn't exist: "inputed email"; to variable $err
                                                $err2 = ('
                                                    <div class="alert alert-danger">
                                                        <i class="fa fa-exclamation-circle"></i> The inputted email doesn'."'".'t exist: '. "$em".'!!!
                                                    </div>
                                                ');
                                            }
                                        }
                                    }
                                ?>
                                <br />
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="offset-md-2 col-md-8">
                <!-- Code to display messages in variable $err2 when required -->
                <?php echo $err2; ?>
            </div>
        </form><!--  End of Modal form -->
    </div><!-- End of Div container for Login Form, Modal & Forgot Password Form -->
</div><!-- End of Div Container of body -->