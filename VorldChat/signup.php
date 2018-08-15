<?php
    // Include Connection String file
    include("inc/header.php");

    //declaring variables to prevent errors
    $err = "";
    $errfn = "";
    $errln = "";
    $errun = "";
    $errpwrd = "";
    $errq = "";
    $erra = "";
    $erraall = "";
    $errnames = "";
    $errem = "";
    $errpnum = "";
    $errf = "";

    $q = strip_tags(@$_POST['question']);
    $a = strip_tags(@$_POST['answer']);

    $fn = ""; //First Name
    $ln = ""; //Last Name
    $un = ""; //UserName
    $gender = ""; //Gender
    $em = ""; //Email
    $dCode = "";//Dial Code
    $pnum = ""; //Phone Number
    $pwrd = ""; //Password
    $d = ""; //Sign up Date
    $u_check = ""; // Check if user: Firstname and Lastname exists
    $e_check = ""; // Check if email exists
    $p_check = ""; // Check if Phone exists
    
    //Registration Form
    $fn = strip_tags(@$_POST['fname']); ## Assign inputted First Name to variable $fn
    $ln = strip_tags(@$_POST['lname']); ## Assign inputted Last Name to variable $ln
    $un = strip_tags(@$_POST['uname']); ## Assign inputted UserName to variable $un
    $gender = strip_tags(@$_POST['gender']); ## Assign inputted gender to variable $gender
    $em = strip_tags(@$_POST['email']); ## Assign inputted email to variable $em
    $dCode = strip_tags(@$_POST['dCode']); ## Assign inputted Dialpad Code to variable $dCode
    $pnum = strip_tags(@$_POST['pnum']); ## Assign inputted Phone Number to variable $pnum
    $pwrd = strip_tags(@$_POST['pwrd']); ## Assign inputted Password to variable $pwrd
    $d = date("Y-m-d"); ## Date Variable
    
?>
<div class="container">
    
    <!-- Div container: contains Registration Form -->
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
        <!-- Script for Registration action -->
        <?php   
            // If Submit button is clicked
            if (@$_POST['reg']) {
                # code...
                                        
                // If all required fields are not empty
                if ($fn && $ln && $un && $gender && $em && $pnum && $pwrd !== "") {
                    # code...

                    switch ($fn) {
                        case strlen($fn) <= 3  || strlen($fn) > 25:
                            # code...
                            if (strlen($fn) <= 3) {
                                $errfn = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> Firstname < 3; must be between 2 - 25 characters in length!!!
                                    </div>
                                ';
                            }
                            elseif (strlen($fn) > 25) {
                                $errfn = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> Firstname > 25; must be between 2 - 25 characters in length!!!
                                    </div>
                                ';
                            }
                            continue;
                                                
                        default:
                            # code...
                            continue;
                    }
                    switch ($ln) {
                        case strlen($ln) <= 3  || strlen($ln) > 25:
                            # code...
                            if (strlen($ln) <= 3) {
                                $errln = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> Lastname < 3; must be between 2 - 25 characters in length!!!
                                    </div>
                                ';
                            }
                            elseif (strlen($ln) > 25) {
                                $errln = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> Lastname > 25; must be between 2 - 25 characters in length!!!
                                    </div>
                                ';
                            }
                            continue;
                                                
                        default:
                            # code...
                            continue;
                    }
                    switch ($un) {
                        case strlen($un) <= 3  || strlen($un) > 12:
                            # code...
                            if (strlen($un) <= 3) {
                                $errun = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> Username < 4; must be between 4 - 12 characters in length!!!
                                    </div>
                                ';
                            }
                            elseif (strlen($un) > 12) {
                                $errun = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> Lastname > 25; must be between 4 - 12 characters in length!!!
                                    </div>
                                ';
                            }
                            continue;
                                                
                        default:
                            # code...
                            continue;
                    }
                    
                    // Query1.1: Select column `first_name` and column `last_name` FROM table `users` WHERE `first_name` = Inputted first name AND `last_name`= inputted last name simultaneously
                    $u_check_sql = "SELECT `first_name`, `last_name` FROM `users` WHERE `first_name` = '$fn' AND `last_name`='$ln'";
                    $u_check = mysqli_query($connect, $u_check_sql);

                    ## Count number of rows where query1.1 is true
                    $u_check_rows = mysqli_num_rows($u_check);

                    // Query1.2: Select column `email` from table `users` where column `email` = inputted email
                    $e_check_sql = "SELECT `email` FROM `users` WHERE `email` = '$em'";
                    $e_check = mysqli_query($connect, $e_check_sql);

                    ## Count number of rows where query1.2 is true
                    $e_check_rows = mysqli_num_rows($e_check);

                    // Query1.3: Select column `dial_code` and column `phone` FROM table `users` WHERE column `dial_code` = Selected dialcode an column `phone` = inputted phone number simultaneously
                    $p_check_sql = "SELECT `dial_code`, `phone` FROM `users` WHERE `dial_code` = '$dCode' AND `phone`='$pnum'";
                    $p_check = mysqli_query($connect, $p_check_sql);

                    ## Count number of rows where query1.3 is true
                    $p_check_rows = mysqli_num_rows($p_check);
                
                    // Query1.4: Select column `username` from table `users` where column `username` = inputted username
                    $un_check_sql = "SELECT `username` FROM `users` WHERE `username` = '$un'";
                    $un_check = mysqli_query($connect, $un_check_sql);

                    ## Count number of rows where query1.4 is true
                    $un_check_rows = mysqli_num_rows($un_check);

                    /* If $count: number of rows where query2.1 is true; > 0
                     * N.B: This means that the user inputs matches one row in queries2.1; Which means inputted email * exists in the database.
                     */
                    if (($u_check_rows && $e_check_rows && $p_check_rows && $un_check_rows) == 0) {
                        # code...

                        switch ($pwrd) {
                            case strlen($pwrd) < 8 || strlen($pwrd) > 32:
                                # code...
                                if (strlen($pwrd) < 8) {
                                    $errpwrd = '
                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-circle"></i> Inputted password is below required length: 8 characters!!!
                                        </div>
                                    ';
                                }
                                elseif (strlen($pwrd) > 32) {
                                    $errpwrd = '
                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-circle"></i> Inputted password is above maximum length: 32 characters!!!
                                        </div>
                                    ';
                                }
                                continue;
                                                    
                            default:
                                # code...
                                continue;
                        }
                        switch ($q) {
                            case $q == "Select":
                                # code...
                                $errq = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> 
                                        Security Question and answer is required to complete this registration!!!
                                    </div>
                                ';
                                continue;
                                                    
                            default:
                                # code...
                                continue;
                        }
                        switch ($a) {
                            case $a == "":
                                # code...
                                $erra = '
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle"></i> 
                                        Security Question and answer is required to complete this registration!!!
                                    </div>
                                ';
                                continue;
                                                    
                            default:
                                # code...
                                continue;
                        }
                    }
                    else {
                        # code...
                        $pwrd = md5($pwrd);

                        // Insert appropriate data in their respective fields for table `users`.
                        $reg_sql = "INSERT INTO `users` VALUES('', '$un', '$fn', '$ln', '$gender', '$em', '$dCode', '$pnum', '$pwrd', '', '', '', '$d', '0')";
                        $reg = mysqli_query($connect, $reg_sql);

                        $qa_sql = "INSERT INTO `sec_qa` VALUES('', '$em', '$pnum', '$q', '$a')";
                        $qa = mysqli_query($connect, $qa_sql);

                        $err = '  
                            <div class="alert alert-success">
                                <span class="fa fa-check-circle-o"></span> Registration Successful
                                <a href="index">Continue to Login</a>
                            </div>
                        ';
                    }
                }                                                
                elseif (($u_check_rows && $e_check_rows && $p_check_rows && $un_check_rows) > 0) {
                    # code...
                    $errall = '
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> 
                            Error: Firstname, Lastname, Email, Phone & Username already exists!!!
                        </div>
                    ';
                } 
                else {
                    if ($u_check_rows !== 0) {
                        # code...
                        $errnames = '
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle"></i> Inputted Firstname & Lastname already exists!!!<br />
                                <span class="text-primary">Solution: Change both or change one of both...</span>
                                </div>
                        ';
                    } 
                    elseif ($e_check_rows !== 0) {
                        # code...
                        $errem = '
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle"></i> This email is not available!!!
                                <span class="text-info">Reason: It is already in use by another user</span>
                            </div>
                        ';
                    } 
                    switch ($p_check_rows) {
                        case $p_check_rows !== 0:
                            # code...
                            if (strlen($p_check_rows) !== 0) {
                                $errpnum = '
                                    <div class="alert alert-danger">
                                        <i class="fa fa-exclamation-circle"></i> Inputted phone number already exists!!!
                                    </div>
                                ';
                            }

                            // no break
                        default:
                            # code...
                            continue;
                    }
                    switch ($un_check_rows) {
                        case $un_check_rows != 0:
                            # code...
                            $c = intval($un_check_rows);
                            if (strlen($un_check_rows) != 0) {
                                $errun = ' '. $c . '
                                    <div class="alert alert-danger">
                                        <i class="fa fa-exclamation-circle"></i> Username not accepted!!!
                                        <span class="text-info">Reason: Username already taken</span>
                                    </div>
                                ';
                            }
                                                                                
                        default:
                            # code...
                            continue;
                    }    
                }
            }
            else {
                # code...
                $errf = '
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-circle"></i> 
                        Please fill in all required fields <i class="text-primary">*</i>
                    </div>
                ';
            }
                                    
        ?>
        <!-- Login Form -->
        <form action="signup" method="POST" class="form-contol" validate>
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-user-circle"></i></span>
                        <input type="text" class="form-control" name="fname" placeholder="First Name" required />
                        <h4 class="input-group-prepend text-primary">*</h4>
                    </div>
                    <small class="pl-3 form-text text-muted">This field must be between 3-25 characters or longer.</small>
                    <?php echo $errfn; ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="pl-3 input-group input-group-sm">
                        <input type="text" class="form-control" name="lname" placeholder="Last Name" required />
                        <h4 class="input-group-prepend text-primary">*</h4>
                    </div>
                    <small class="pl-3 form-text text-muted">This field must be between 3-25 characters or longer.</small>
                    <?php echo $errln; ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="pl-3 input-group input-group-sm">
                        <input type="text" class="form-control" name="uname" placeholder="UserName" required />
                        <h4 class="input-group-prepend text-primary">*</h4>
                    </div>
                    <?php echo $errun; ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-transgender"></i></span>
                        <div class="pl-4 ml-1 custom-radio">
                            <input type="radio" name="gender" value="male" required/>
                            <label class="pl-sm-2 custom-label">MALE</span>
                            <input type="radio" class="ml-5" name="gender" value="female" required/>
                            <label class="pl-sm-2 custom-label">FEMALE<i class="text-primary">*</i></label>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-envelope-o"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" required />
                        <h4 class="input-group-prepend text-primary">*</h4>
                    </div>
                    <small class="pl-3 form-text text-muted">We'll never share your email with anyone else.</small>
                    <?php echo $errem ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm input-group-append">
                        <div class="col-5 col-sm-3 col-md-3 col-lg-2 p-0">
                            <div class="input-group">
                                <span class="flex-grow"><i class="fa fa-phone"></i></span>
                                <select class="ml-1 custom-select p-0" name="dCode">
                                    <option>+234</option>
                                    <option>+1</option>
                                    <option>+44</option>
                                    <option>+234</option>
                                    <option>+234</option>
                                    <option>+234</option>
                                </select>
                            </div>
                            <small class="pl-3 form-text text-muted">Dial Code</small>
                        </div>
                        <div class="m-0 p-0 col-7 col-sm-9 col-md-9 col-lg-10 pl-2">
                            <div class="input-group input-group-sm">
                                <input type="tel" name="pnum" class="form-control" placeholder="Phone" required/>
                                <h4 class="input-group-prepend text-primary">*</h4>
                            </div>
                            <small class="form-text text-muted">Phone shouldn't start with <k class="text-primary">'0'</k> after the dialing code</small>
                        </div>
                    </div>
                    <?php echo $errpnum; ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="offset-md-1 col-md-10 animated slideInRight">
                    <div class="input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-lock"></i>&nbsp;</span>
                        <input type="password" class="form-control" name="pwrd" placeholder="Input your password" required />
                        <h4 class="input-group-prepend text-primary">*</h4>
                    </div>
                    <small class="pl-3 form-text text-muted">Password must be 8 - 32 characters in length</small>
                    <?php echo $errpwrd; ?>
                </div>
            </div>
            <div class="modal animated zoomIn" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">SECURITY QUESTION</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            
                            <span class="text-capitalize text-danger"><h4><b>Please fill in the field as required:</b></h4></span>
                            <div class="pt-3 col-md-12">
                                <span class="text-primary">*Required Fields</span>
                                <div class="row">
                                    <div class="pt-3 col-12 p-0">
                                        <div class="d-flex input-group input-group-sm" data-toggle="tooltip" data-placement="top" title="Select your security question">
                                            <span class="col-12 col-sm-3 flex-grow"><i class="fa fa-question-circle"></i> Question</span>
                                            <select name="question" class="custom-select" required>
                                                <option>Select...</option>
                                                <option>What was the name of your first pet</option>
                                                <option>What is your favourite  word</option>
                                                <option>What Street did you live in at age 6</option>
                                                <option>What is your favourite pet</option>
                                                <option>Who is dearest to you</option>
                                                <option>Name your favourite food</option>
                                            </select required>
                                            <h4 class="input-group-append text-primary">*</h4>
                                        </div>
                                    </div>
                                </div>
                                <br /><div class="row">
                                    <div class="pt-3 col-12 p-0">
                                        <div class="d-flex input-group input-group-sm" data-toggle="tooltip" data-placement="top" title="Input you secret security answer">
                                            <span class="col-12 col-sm-3 flex-grow"><i class="fa fa-question-circle-o"></i> Answer:</span>
                                            <input type="text" name="answer" class="form-control bg-white text-dark" placeholder="Input you secret answer" required />
                                            <h4 class="input-group-append text-primary">*</h4>
                                        </div>
                                        <small class="pl-3 form-text text-muted">This must be kept secret as it will be used in case your password is forgotten.</small>
                                    </div>
                                </div>
                                <br />
                                <div class="row pl-3">
                                    <button type="submit" class="btn btn-dark" name="reg" value="register">Submit</button>    
                                </div>

                                
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
            <br />
            <div class="row">
                <span class="offset-md-1 col-md-10 animated zoomIn">
                    <button class="w-100 btn btn-primary" type="button" data-toggle="modal" data-target="#myModal"> Continue </button>
                    <br /><br />
                    <?php echo $errq; ?>
                    <?php echo $erra; ?>
                    <?php echo $errf; ?>
                    <span class="text-center">
                        <div class="col-sm-12">
                            <h6>ALREADY HAVE AN ACCOUNT? &nbsp;
                                <b><a href="index">LOGIN</a></b>
                            </h6>
                        </div>
                    </span>
                    <!-- Code to display messages in variable $err when required -->
                    
                </span>
            </div>
        </form><!--  End of Login form -->
    </div>
</div>