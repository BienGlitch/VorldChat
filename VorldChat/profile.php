<?php
    // Include Connection String file
    include("inc/header.php");

    date_default_timezone_set("Africa/Lagos");

    $UID = "";

    if(isset($_GET['id'])){
        #code...
        // Get its value and store it in $UID
        $UID = @$_GET['id']; ## Assign URL Paremeter 'id' to Variable $id
    }
    else{
        echo "BAD REQUEST";
        exit();
    }

?>

<?php
    //Check if user exists
    $get_user = "SELECT * FROM `users` WHERE `id` = '$UID'";
    $get_user_mysqli = mysqli_query($connect, $get_user);
    $get = mysqli_fetch_array($get_user_mysqli);
    $bio = $get['bio'];
    $username = $get['username'];
    $fname = $get['first_name'];
    $lname = $get['last_name'];

    $name = $fname . ' ' . $lname;
    
    //Get profile photo of current user
    $check_pic = "SELECT `profile_pic` FROM `users` WHERE `id` = '$UID'";
    $check_pic_sqli = mysqli_query($connect,$check_pic);
    $get_pic_row = mysqli_fetch_assoc($check_pic_sqli);
    $db_profile_pic = $get_pic_row['profile_pic'];

    if($db_profile_pic == ""){
        $profile_pic = "userdata/profile_pics/default/default_pP.png";
    }
    else{
        $profile_pic = $db_profile_pic;
    }
    
    if($db_profile_pic == "userdata/profile_pics/default/default_pP.png"){
        $profile_pic = "userdata/profile_pics/default/default_pP.png";
    }
    
    $check_picUser = "SELECT `profile_pic` FROM `users` WHERE `username` = '$user'";
    $check_picUser_sqli = mysqli_query($connect,$check_picUser);
    $get_picUser_row = mysqli_fetch_assoc($check_picUser_sqli);
    $db_profile_picUser = $get_picUser_row['profile_pic'];

    if($db_profile_picUser == ""){
        $profile_picUser = "userdata/profile_pics/default/default_pP.png";
    }
    else{
        $profile_picUser = $db_profile_picUser;
    }
    
    if($db_profile_picUser == "userdata/profile_pics/default/default_pP.png"){
        $profile_picUser = "userdata/profile_pics/default/default_pP.png";
    }

    $errmsg = "";
    $msg = @$_POST['sendmsg'];
    $friend_request = @$_POST['addfriend'];
    $delete_friend = @$_POST['removefriend'];

    if(isset($msg)){
        echo "<meta http-equiv =\"refresh\" content=\"0; url = http://localhost/test/send_msg.php?i d=$UID\">";
    }
    if($friend_request){
        
        $id_query = "SELECT * FROM `users` WHERE `username` = '$username' Limit 1";
        $id_query_sqli = mysqli_query($connect,$id_query);
        $userCoun = mysqli_num_rows($id_query_sqli);//Count number of rows returned
        
        if($userCoun == 1){
            $id_fetch = mysqli_fetch_assoc($id_query_sqli);
            $id_to = $id_fetch['username'];
        }
            
        $add_query = "SELECT * FROM `users` WHERE `username`='$user'";
        $add_query_sqli = mysqli_query($connect,$add_query);
        $fetch = mysqli_fetch_assoc($add_query_sqli);
        $user_id = $fetch['username'];

        $create_request = "INSERT INTO `friend_requests` VALUES('', '$user_id', '$id_to')";
        $create_request_sqli = mysqli_query($connect,$create_request);
        $errmsg = "
            <div class='alert alert-warning text-capitalize'>
                <i class='fa fa-exclamation-triangle'></i>
                Friend request successfully sent to $username
            </div>
        ";
    }

    if($delete_friend){
        $friend1 = "";
        $friend2 = "";
        //Friend array for loggend in user
        $add_friend_check = "SELECT `friend_array` FROM `users` WHERE `username`='$user'";
        $add_friend_check_sqli = mysqli_query($connect,$add_friend_check);
        $get_friend_row = mysqli_fetch_assoc($add_friend_check_sqli);
        $friend_array = $get_friend_row['friend_array'];
        $friend_array_explode = explode(",",$friend_array);
        $friend_array_count = count($friend_array_explode);
        
        //Friend array for user who owns the profile
        $add_friend_check_username = "SELECT `friend_array` FROM `users` WHERE `username`='$username'";
        $add_friend_check_username_sqli = mysqli_query($connect,$add_friend_check_username);
        $get_friend_row_username = mysqli_fetch_assoc($add_friend_check_username_sqli);
        $friend_array_username = $get_friend_row_username['friend_array'];
        $friend_array_explode_username = explode(",",$friend_array_username);
        $friend_array_count_username = count($friend_array_explode_username);
        
        $usernameComma = ",".$username;
        $usernameComma2 = $username.",";
        
        $userComma = ",".$user;
        $userComma2 = $user.",";

        if(strstr($friend_array,$usernameComma)){
            $friend1 = str_replace($usernameComma,"",$friend_array);
        }
        else
        if(strstr($friend_array,$usernameComma2)){
            $friend1 = str_replace($usernameComma2,"",$friend_array);
        }
        else
        if(strstr($friend_array,$username)){
        $friend1 = str_replace($username,"",$friend_array);
        }

        //Remove logged in user from other persons array.
        if(strstr($friend_array_username,$userComma)){
            $friend2 = str_replace($userComma,"",$friend_array_username);
        }
        else
        if(strstr($friend_array_username,$userComma2)){
            $friend2 = str_replace($userComma2,"",$friend_array_username);
        }
        else
        if(strstr($friend_array_username,$user)){
            $friend2 = str_replace($user,"",$friend_array_username);
        }
        /*$friend3 = str_replace($user,"",$friend_array_username);
        $errmsg = "$friend2";
        */
        $removefriend_sql = "UPDATE `users` SET `friend_array`='$friend1' WHERE `username`='$user'";
        $removefriend_sqli = mysqli_query($connect,$removefriend_sql);
        $removefriend_username_sql = "UPDATE `users` SET `friend_array`='$friend2' WHERE `username`='$username'";
        $removefriend_username_sqli = mysqli_query($connect,$removefriend_username_sql);
        $errmsg = "
            <div class='alert alert-warning text-capitalize'>
                <i class='fa fa-exclamation-triangle'></i>
                You have unfriended $username 
            </div>
        ";
    }
    //Poke code
    $poke = @$_POST['poke'];
    if($poke){
        $check_poke = "SELECT * FROM `poke` WHERE `user_to`='$username' AND `user_from`='$user'";
        $check_poke_sqli = mysqli_query($connect,$check_poke);
        $num_poke_found = mysqli_num_rows($check_poke_sqli);
        if($num_poke_found == 1){
            $errmsg = "
                <div class='alert alert-warning text-capitalize'>
                    <i class='fa fa-exclamation-triangle'></i>
                    You must wait for $username to poke you back!!!
                </div>
            ";
        }
        else{
            $poke_sql = "INSERT INTO `poke` VALUES('','$user','$username')";
            $poke_sqli = mysqli_query($connect,$poke_sql);
            $errmsg = "
                <div class='alert alert-info text-capitalize'>
                    <i class='fa fa-exclamation-circle'></i>
                    $username has been poked
                </div>
            "; 
        }
    }
?>

<div class="profileBody container-fluid">
    
    <?php
        if(!$user){
            ?>
            <div class="offset-md-1 col-md-10 alert alert-warning text-center animated flash mt-5">
                <i class="fa fa-exclamation-triangle"></i> 
                Please, Kindly <a class="" href="index">LOGIN</a> to view this page!!!
            </div>
            <?php
            exit();
        }
    ?>
    <?php
        if ($username == $user) {
            # code...
            ?>                
            <div class="offset-md-2 col-md-8 p-0">
                <div class="row">
                    <a class="col-12 p-0" href="<?php echo "profile?id=$id"; ?>">
                        <div class="profilebg col-12 d-block flex-fill" style="height:400px; background: url(././img/ghost.jpg) round scroll;">
                            <div class="col-7 col-sm-4 col-xl-3 profiledp p-1">
                                <div class="bg-white">
                                    <span class=""><img class="img-thumbnail" src="<?php echo $profile_pic ?>" width="100%" /></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="row pt-1">
                    <div class="col-md-12 p-0 px-md-3">
                        <?php
                            if ($user !== $username) {
                                # code...
                                ?>
                                <div class="d-flex justify-content-around bg-dark p-1 card flex-row">
                                    <?php
                                        include("inc/friendArrayinc.php");
                                    ?>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-4 card shadow profile pt-0 px-4 text-center">
                        <?php
                            if ($username == $user) {
                                # code...
                                ?>
                                <span class="text-capitalize text-muted">your friends</span>
                                <?php
                            }
                            else{
                                ?>
                                <span class="text-capitalize text-muted"><?php echo "$username's"; ?> Friends</span>
                                <?php
                            }
                        ?>
                        <div class="row flex-row">
                            <?php
                                include("inc/friendarrayqueryinc.php");
                                if($countFriends != 0){
                                    foreach($userfriendArray12 as $key => $value){
                                        $i++;
                                        $getFriendQuery = "SELECT * FROM `users` WHERE `username`='$value' LIMIT 12";
                                        $getFriendQuery_sqli = mysqli_query($connect, $getFriendQuery);
                                        $getFriendRow = mysqli_fetch_assoc($getFriendQuery_sqli);
                                        $friendId = $getFriendRow['id'];
                                        $friendUsername = $getFriendRow['username'];
                                        $friendProfilePic = $getFriendRow['profile_pic'];
                                        
                                        if($friendProfilePic == ""){
                                            ?>
                                            <div class="col-4 py-2 p-1">
                                                <a href="<?php echo "profile?id=$friendId"; ?>">
                                                    <img src="img\default_pP.png" alt="<?php echo "$friendUsername's profile" ?>" title="<?php echo "$friendUsername's profile" ?>"  width="100%" />
                                                    <?php echo $friendUsername; ?>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                        else{
                                            ?>
                                            <div class="col-4 py-2 p-1">
                                                        <a href="<?php echo "profile?id=$friendId"; ?>">
                                                            <img src="<?php echo $friendProfilePic ?>" alt="<?php echo "$friendUsername's profile" ?>" title="<?php echo "$friendUsername's profile" ?>" width="100%" />
                                                            <small><?php echo $friendUsername; ?></small>
                                                        </a>
                                                    </div>
                                            <?php                            
                                        }
                                    }
                                }
                                else{
                                    ?>
                                    <?php echo "$username " ?> has no friends yet.
                                    <?php
                                    exit();
                                }
                            ?>
                        </div>
                        <?php
                            if ($countFriends > 12) {
                                # code...
                                if ($user == $username) {
                                    # code...
                                    ?>
                                    <a href="" class="nav-link">View All</a>
                                    <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        elseif ($user !== $username) {
            # code...
            ?>
            <div class="offset-lg-2 col-lg-8 p-0">
                <div class="row">
                    <a class="col-12 p-0" href="<?php echo "profile?id=$id"; ?>">
                        <div class="profilebg col-12 d-block flex-fill" style="height:400px; background: url(././img/ghost.jpg) round scroll;">
                            <div class="col-7 col-sm-4 col-xl-3 profiledp p-1">
                                <div class="bg-white">
                                    <span class=""><img class="img-thumbnail" src="<?php echo $profile_pic ?>" width="100%" height="100px" /></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="jumbotron jumbotron-fluid shadow bg-white row p-1 mb-0">
                    <div class="col-12">
                        <div class="row pt-1">
                            <div class="col-md-12 p-0">
                                <form class="" action="" method="POST">
                                    <?php
                                        if ($user !== $username) {
                                            # code...
                                            ?>
                                            <div class="d-flex justify-content-around bg-dark p-1 card flex-row shadow">
                                                <?php
                                                    include("inc/friendArrayinc.php");
                                                ?>
                                            </div>
                                            <?php
                                                echo $errmsg;
                                        }
                                    ?>
                                </form>
                            </div>
                        </div>
                        <div class="row p-0">
                            <div class="col-md-12 p-0">
                                <div class="d-flex justify-content-around bg-light p-1 card flex-row shadow">
                                    <a href="">About</a>
                                    <a href="">Photos</a>
                                    <a href="">Friends</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-light p-1 shadow-sm row">
                    <div class="col-md-7  border-md-left">
                        <div class="row">
                            <div class="container-fluid p-0">
                                <div class="col-12 card p-2 text-center text-black-50 shadow-lg">
                                    <h4><b><?php echo "$username's"; ?> Bio</b></h4>
                                </div>
                                <br />
                            </div>
                            <div class="container d-flex flex-column align-items-center">
                                <h5 class="text-uppercase"><i class="fa fa-user-o"></i> <?php echo "$username"; ?></h5>
                                <h6 class="text-muted"><i class="fa fa-newspaper-o"></i> <?php echo "$bio"; ?></h6>
                                <h6 class="text-uppercase"><?php echo "$name"; ?></h6>
                            </div>
                        </div>
                        <br />
                        <br />
                    </div>
                    <div class="col-md-5 order-md-first border-md-right">
                        <div class="row">
                            <div class="container-fluid p-0">
                                <div class="col-12 p-2 card text-center text-black-50 shadow-lg">
                                    <h4><b>About <?php echo $username; ?></b></h4>
                                </div>
                                <br />
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><span class=""><i class="fa fa-home"></i></span></td>
                                            <td><span class="">Lives in: </span></td>
                                        </tr>
                                        <tr>
                                            <td><span class=""><i class="fa fa-map-marker"></i></span></td>
                                            <td><span class="">From: <?php echo "$username"; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><span class=""><i class="fa fa-heart"></i></span></td>
                                            <td><span class="">Relationship: <?php echo "$username"; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><span class=""><i class="fa fa-clock-o"></i></span></td>
                                            <td><span class="">Joined: <?php echo "$username"; ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-4 pFriends p-0">
                        <div class="col-12 p-0">
                            <div class="card shadow photo">
                                <?php
                                    include("inc/friendarrayqueryinc.php");
                                    if ($username == $user) {
                                        # code...
                                        ?>
                                        <span class="text-capitalize text-muted">your friends</span>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <div class="pl-3 pt-3">
                                            <i class="bg-primary rounded-circle img-thumbnail fa fa-group"></i>
                                            <span class="text-capitalize text-muted"><?php echo "$username's"; ?> Friends</span>
                                            <?php echo $countFriends; ?>
                                        </div>
                                        <hr>
                                        <?php
                                    }
                                ?>
                                <div class="row flex-row px-4 text-left">
                                    <?php
                                        if($countFriends != 0){
                                            foreach($friendArray12 as $key => $value){
                                                $i++;
                                                $getFriendQuery = "SELECT * FROM `users` WHERE `username`='$value' LIMIT 12";
                                                $getFriendQuery_sqli = mysqli_query($connect, $getFriendQuery);
                                                $getFriendRow = mysqli_fetch_assoc($getFriendQuery_sqli);
                                                $friendId = $getFriendRow['id'];
                                                $friendUsername = $getFriendRow['username'];
                                                $friendProfilePic = $getFriendRow['profile_pic'];
                                                        
                                                if($friendProfilePic == ""){
                                                    ?>
                                                    <div class="col-4 py-2 p-1">
                                                        <a href="<?php echo "profile?id=$friendId"; ?>">
                                                            <img src="img\default_pP.png" alt="<?php echo "$friendUsername's profile" ?>" title="<?php echo "$friendUsername's profile" ?>"  width="100%" />
                                                            <small><?php echo $friendUsername; ?></small>
                                                        </a>
                                                    </div>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <div class="col-4 py-2 p-1">
                                                        <a href="<?php echo "profile?id=$friendId"; ?>">
                                                            <img src="<?php echo $friendProfilePic ?>" alt="<?php echo "$friendUsername's profile" ?>" title="<?php echo "$friendUsername's profile" ?>" width="100%" />
                                                            <small><?php echo $friendUsername; ?></small>
                                                        </a>
                                                    </div>
                                                    <?php                            
                                                }
                                            }
                                        }
                                        else{
                                            ?>
                                            <?php echo "$username " ?> has no friends yet.
                                            <?php
                                            exit();
                                        }
                                    ?>
                                </div>
                                <?php
                                    if ($countFriends > 9) {
                                        # code...
                                        if ($user !== $username) {
                                            # code...
                                            ?>
                                            <hr>
                                            <a href="" class="nav-link text-black-50">See All Friends ></a>
                                            <?php
                                        }
                                    }
                                    elseif ($countFriends < 10) {
                                        # code...
                                        if ($user !== $username) {
                                            # code...
                                            ?>
                                            <hr>&nbsp;
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-md-0 mt-4 col-md-8 card shadow p-0">
                        <div class="pl-3 pt-3">
                            <i class="bg-success rounded-circle img-thumbnail fa fa-picture-o"></i>        
                            <span class="text-capitalize text-muted"> Photos of <?php echo "$username"; ?></span>
                        </div>
                        <hr>
                        <div class="col-12 p-0">
                            <div class="row flex-row px-4 text-left">
                                <div class="container p-0">
                                    *Photos to be displayed here!!!
                                </div>
                            </div>
                            <hr>
                            <a href="" class="nav-link text-black-50 mt-2">See All Photos ></a>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-4 d-none d-md-block contents p-0">
                        <div class="card shadow-sm">
                            <div class="row pl-4 p-0 text-left">
                                <div class="container p-0">   
                                    <?php print_r($_SESSION); ?>     
                                    *Contents to be displayed here!!!
                                    *Contents to be displayed here!!! *Contents to be displayed here!!! *Contents to be displayed here!!!
                                    *Contents to be displayed here!!! *Contents to be displayed here!!! *Contents to be displayed here!!!
                                    *Contents to be displayed here!!! *Contents to be displayed here!!! *Contents to be displayed here!!!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 p-0">
                        <?php
                            
                            //Varible declaration for post form
                            $err = "";
                            $send = @$_POST['send'];
                            $post = @$_POST['post'];
                            
                            ##If post button is clicked/selected
                            if($send){
                            
                                #If post field isn't empty
                                if($post != ""){
                                    $date_added = date("Y-m-d");
                                    $time_added = date("Y-m-d h:i:sa");
                                    $added_by = "$user";
                                    $user_posted_to = "$username";
                                    
                                    $sqlCommand = "INSERT INTO `posts` VALUES('', '$post', '$date_added', '$time_added', '$added_by', '$user_posted_to')";
                                    $query = mysqli_query($connect,$sqlCommand) or die (mysql_error());
                                    //header("location:$username");
                                }
                                else{
                                    $err = "<h4>Type in a post to send it!!!</h4>";
                                }
                            }
                        ?>
                        <form action="" method="post">
                            <div class="input-group input-group-sm">
                                <textarea name="post" id="post" class="pl-5 pt-4 form-control" style="height:85px;width:90%" placeholder="What's Ony Your Mind"></textarea>
                                <span class="input-group-append">
                                <button type="submit" name="send" value="post" class="btn btn-dark btn-group-lg" style="width:60.90px;height:85px">Post</button>
                                </span>
                                <?php echo $err; ?>
                            </div>
                        </form>
                        <br />
                        <h5 class="text-black-50">Posts</h5>
                        <br />
                        <div class="container-fluid p-0" style="//border:1px solid black">
                        <?php
                            //Get 5 posts with details from database
                            $getposts = "SELECT * FROM `posts` WHERE `user_posted_to` = '$username' ORDER BY `id` DESC LIMIT 5" or die(mysql_error);
                            $getposts_sqli = mysqli_query($connect, $getposts);
                            $countPosts = mysqli_num_rows($getposts_sqli);
                            
                            if ($countPosts == 0) {
                                # code...
                                echo '
                                    <div class="alert alert-info text-center">
                                        <i class="fa fa-exclamation-circle"></i>
                                        '."$username".' has no posts on his timeline!!!<br />
                                        <span class="text-danger">Be the first to post...</span>
                                    </div>
                                ';
                            }


                            while ($row = mysqli_fetch_assoc($getposts_sqli)){
                                $id = $row['id'];
                                $body = $row['body'];
                                $date_added = $row['date_added'];
                                $time_added = $row['time_added'];
                                $added_by = $row['added_by'];
                                $user_posted_to = $row['user_posted_to'];
                                
                                ##Get profile pic of User who posted
                                $get_userinfo_sql = "SELECT * FROM `users` WHERE `username`='$added_by'";
                                $get_userinfo_sqli = mysqli_query($connect,$get_userinfo_sql);
                                $get_info = mysqli_fetch_assoc($get_userinfo_sqli);
                                $postedID = $get_info['id'];
                                $profilepic_info = $get_info['profile_pic'];

                                

                                $d = date("Y-m-d h:i:s");

                                $start = new DateTime("$d");
                                $interval = $start ->diff(new DateTime("$time_added"));
                                $min = $interval->days * 24 * 60;
                                $min += $interval->h *60;
                                $min += $interval->i;
                                $sec = $interval->i *60;
                                $sec += $interval->s;
                                $hour = $interval->h;
                                $days = $interval->days;
                                $months = intval($days/30.5);
                                $years = $interval->y;
                                
                                if (($sec) < 6) {
                                    # code...
                                    $interval = "Just now";
                                }
                                elseif (($sec) > 5 && ($sec) < 61) {
                                    # code...
                                    $interval = $sec."secs";
                                }
                                elseif (($sec) > 60 && ($min) > 0) {
                                    # code...
                                    $interval = $min."min";
                                }
                                elseif (($min) > 1 && ($min) < 61) {
                                    # code...
                                    $interval = $min."mins";
                                }
                                elseif (($min) > 60 && ($hour) > 0) {
                                    # code...
                                    $interval = $hour."hr";
                                }
                                elseif (($hour) > 1 && ($hour) < 25) {
                                    # code...
                                    $interval = $hour."hrs";
                                }
                                elseif (($hour) > 23 && ($days) > 0) {
                                    # code...
                                    $interval = $days."day";
                                }
                                elseif (($days) > 1 && ($days) < 367) {
                                    # code...
                                    $interval = $day."days";
                                }
                                elseif (($days) < 364 && ($months) > 0) {
                                    # code...
                                    $interval = $months."month";
                                }
                                elseif (($months) > 1 && ($months) < 13) {
                                    # code...
                                    $interval = $months."months";
                                }
                                elseif (($months) > 11 && ($years) > 0) {
                                    # code...
                                    $interval = $years."yr";
                                }
                                elseif (($years) > 1) {
                                    # code...
                                    $interval = $years."yrs";
                                }
                                
                                
                                if($profilepic_info == ""){
                                    $profilepic_info = "userdata/profile_pics/default/default_pP.png";
                                }
                                else{
                                    $profilepic_info = $profilepic_info;
                                }
                                if($profilepic_info == "userdata/profile_pics/default/default_pP.png"){
                                    $profilepic_info = "userdata/profile_pics/default/default_pP.png";
                                }
                                
                                ?>
                                <div class="container-fluid shadow-sm card bg-transparent">
                                    <div class="col-12 p-0 pt-3 pl-1">
                                        <div class="row">
                                            <div class="col-2 pr-2">
                                                <img class="rounded-circle" src="<?php echo $profilepic_info; ?>" alt="<?php echo $added_by; ?>" width="100%" height="60px">
                                            </div>
                                            <div class="pl-0 col-10">
                                                <a href="<?php echo "profile?id=$postedID" ?>"><?php echo $added_by ?></a><br />
                                                <small><?php echo $interval." <sup><b>.</b></sup>"; ?> <i class="fa fa-globe"></i></small>
                                            </div>
                                        </div>
                                        <div class="row mt-2 mb-2">
                                            <div class="col-12 pr-2">
                                                <?php echo $body; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    ?>
</div>