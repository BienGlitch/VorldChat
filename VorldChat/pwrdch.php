<?php
    // Include Header file
    include("inc/header.php");

    if(isset($_GET['id'])){
        $UID = @$_GET['id'];
    }
    else{
        $UID = null;
    }

    $get_user = "SELECT * FROM `users` WHERE `id` = '$UID'";
    $get_user_sqli = mysqli_query($connect, $get_user);
    $UInfo = mysqli_fetch_array($get_user_sqli);
    $fname = $UInfo['first_name'];
    $lname = $UInfo['last_name'];
    $email = $UInfo['email'];
    $phone = $UInfo['phone'];
    $name = $lname .' '. $fname;

    $sec_check = "SELECT * FROM `checkU` WHERE `email` = '$email'";
    $sec_check_sqli = mysqli_query($connect, $sec_check);
    $count = mysqli_num_rows($sec_check_sqli);

    $sec_check2 = "SELECT * FROM `fpwrd` WHERE `email` = '$email'";
    $sec_check2_sqli = mysqli_query($connect, $sec_check2);
    $count2 = mysqli_num_rows($sec_check2_sqli);

    if ($count2 == 0  && $count < 1) {
        # code...
        ?>
        <div class="mt-5 bg-light text-black pt-5 pb-5 p-3 shadow">
            <div class="container">
                <h3 class="text-warning">
                    <b>OOPS THE PAGE YOU'RE LOOKING FOR DOESN'T EXIST OR HAS BEEN MOVED!!!</b>
                    <div class="text-center text-danger"><i class="fa fa-frown-o"></i></span>
                </h3>
            </div>
        </div>        
        <?php
        exit();
    }

    $err = "";
    $sub = @$_POST['submit'];
    $pwrd = @$_POST['pwrd'];
    $cpwrd = @$_POST['cpwrd'];

    if ($sub) {
        # code...
        if ($pwrd && $cpwrd != "") {
            # code...
            $pwrd = md5($pwrd);
            $pwrd_update = "UPDATE `users` SET `password` = '$pwrd' WHERE `email` = '$email'";
            $pwrd_update_sqli = mysqli_query($connect, $pwrd_update);

            $rem_block = "DELETE FROM `checkU` WHERE `email` = '$email'";
            $rem_block_sqli = mysqli_query($connect, $rem_block);

            $rem_fp = "DELETE FROM `fpwrd` WHERE `email` = '$email'";
            $rem_fp_sqli = mysqli_query($connect, $rem_fp);
            
            $err = ('
                <div class="alert alert-success">
                    <span class="fa fa-check-circle-o"></span> Password successfully changed
                    <a href="index">Continue to Login</a>
                </div>
            ');
        }
        else{
            $err = ('
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> Please fill in the required fiels!!!
                </div>
            ');
        }
    }

?>

<div class="mt-2 mt-md-4 bg-light text-black p-3 shadow">
    <div class="container">
        <h3><b><?php echo $name; ?></b></h3>
    </div>
</div>
<br />
<div class="container-fluid card bg-dark pwrdch text-white pt-3 pb-3">
    <form action="" method="post" validate>
        <span class="text-capitalize text-danger"><h4><b>Please Fill in the required fields:</b></h4></span>
        <div class="pt-3 col-md-6">
            <span class="text-primary">*Required Fields</span>
            <div class="row">
                <div class="pt-3 col-md-6 p-0">
                    <div class="d-flex input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-lock"></i></span>
                        <input type="password" name="pwrd" class="form-control bg-dark text-white" placeholder="Input new password" required />
                        <h4 class="input-group-append text-primary">*</h4>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="pt-3 col-md-6 p-0">
                    <div class="d-flex input-group input-group-sm">
                        <span class="flex-grow"><i class="fa fa-lock"></i></span>
                        <input type="password" name="cpwrd" class="form-control bg-dark text-white" placeholder="Confirm new password" required />
                        <h4 class="input-group-append text-primary">*</h4>
                    </div>
                </div>
            </div>
            <br />
            <div class="row pl-0">
                <button type="submit" class="btn btn-info" name="submit" value="submit">Submit</button>    
            </div>
            <br />
            <div class="row pl-0">
                <?php echo $err; ?>
            </div>
        </div>
    </form>
</div>