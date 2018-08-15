<?php
    // Include Header file
    include("inc/header.php");
    
    // If URL Parameter 'id' is set
    if(isset($_GET['id'])){
        #code...
        // Get its value and store it in $UID
        $UID = @$_GET['id']; ## Assign URL Paremeter 'id' to Variable $id
    }
    ## else if not set,
    else{
        // Set $UID = null 
        $UID = null;
        exit(); ## Exit current script
    }

    
    $get_user = "SELECT * FROM `users` WHERE `id` = '$UID'";
    $get_user_sqli = mysqli_query($connect, $get_user);
    $UInfo = mysqli_fetch_array($get_user_sqli);
    $fname = $UInfo['first_name'];
    $lname = $UInfo['last_name'];
    $email = $UInfo['email'];
    $phone = $UInfo['phone'];
    $name = $lname .' '. $fname;

    $get_qa = "SELECT * FROM `sec_qa` WHERE `email` = '$email'";
    $get_qa_sqli = mysqli_query($connect, $get_qa);
    $qa = mysqli_fetch_array($get_qa_sqli);
    $question = $qa['ques'];

    $sec_check = "SELECT * FROM `checkU` WHERE `email` = '$email'";
    $sec_check_sqli = mysqli_query($connect, $sec_check);
    $count = mysqli_num_rows($sec_check_sqli);
    
    $sec_check2 = "SELECT * FROM `fpwrd` WHERE `email` = '$email'";
    $sec_check2_sqli = mysqli_query($connect, $sec_check2);
    $count2 = mysqli_num_rows($sec_check2_sqli);
    
    //if ($PID != 2) {
        if ($count2 == 0 && $count < 1) {
            # code... 
            ?>
            <div class="mt-5 bg-light text-black pt-5 pb-5 p-3 shadow">
                <div class="container">
                    <h3 class="text-warning">
                        <b>lOOPS THE PAGE YOU'RE LOOKING FOR DOESN'T EXIST OR HAS BEEN MOVED!!!</b>
                        <div class="text-center text-danger"><i class="fa fa-frown-o"></i></span>
                    </h3>
                </div>
            </div>        
            <?php
            exit();
        }
    //}


    if(strpos($question, "?") == false){
        $question = str_ireplace($question, "$question?", $question);
    }

    $sub = @$_POST['submit'];
    $ans = strip_tags(@$_POST['answer']);
    $err = "";

    if ($sub) {
        if ($sub != "") {
            # code...
            $check_ans = "SELECT * FROM `sec_qa` WHERE `answer` = '$ans'";
            $check_ans_sqli = mysqli_query($connect, $check_ans);
            $decide = mysqli_num_rows($check_ans_sqli);

            if($decide == 1){
                $err = ('
                    <div class="alert alert-success">
                        <span class="fa fa-check-circle-o"></span> Success
                        <a href="pwrdch?id='.$UID.'">Click Here to change your password</a>
                    </div>
                ');
            }
            else{
                $err = ('
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i> The given answer is incorrect, please try again...
                    </div>
                ');
            }
        }
        else{
            $err = ('
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> Please input your answer...
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
<div class="container-fluid card bg-dark text-white pt-3 pb-3">
    <form action="" method="post" validate>
        <span class="text-capitalize text-danger"><h4><b>Please Answer the security question below:</b></h4></span>
        <div class="pt-3 col-md-10">
            <div class="row">
                <div class="col-md-10 p-0">
                    <label for="question"><span class="flex-grow"><i class="fa fa-lock"></i>&nbsp;</span> Security Question:</label>
                    <div class="form-text text-warning">
                        <?php echo $question; ?>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-10 p-0">
                    <textarea name="answer" id="" rows="5" placeholder="Input your answer here..." style="width:100%" required></textarea>
                </div>
            </div>
            <br />
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