<?php 
	date_default_timezone_set("Africa/Lagos");
	
	session_start();
	if (isset($_SESSION['username'])) {
		// echo $_SESSION['username'];
		$username = @$_SESSION['username'];
	}
	
	$conn = new mysqli('localhost', 'root', '59crumuagholu', 'vorldchat');
	if ($conn->connect_error) {
		die("Connection error: " . $conn->connect_error);
	}

	$result = $conn->query("SELECT * FROM posts  WHERE `user_posted_to` = '$username' ORDER BY `id` DESC");
	if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
			$id = ['id'];
			$body = $row['body'];
			$date_added = $row['date_added'];
            $time_added = $row['time_added'];
            $added_by = $row['added_by'];
			$user_posted_to = $row['user_posted_to'];
			
			$get_userinfo_sql = $conn->query("SELECT * FROM `users` WHERE `username`='$added_by'");
            $get_info = $get_userinfo_sql->fetch_assoc();
            $postedID = $get_info['id'];
            $profilepic_info = $get_info['profile_pic'];
            
            $d = date("Y-m-d h:i:sa");
			
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
            elseif (($sec) > 60 && ($min) < 2) {
                # code...
                $interval = $min."min";
            }
            elseif (($min) > 1 && ($min) < 61) {
                # code...
                $interval = $min."mins";
            }
            elseif (($min) > 60 && ($hour) < 2) {
                # code...
                $interval = $hour."hr";
            }
            elseif (($hour) > 1 && ($hour) < 25) {
                # code...
                $interval = $hour."hrs";
            }
            elseif (($hour) > 23 && ($days) < 2) {
                # code...
                $interval = $days."day";
            }
            elseif (($days) > 1 && ($days) < 367) {
                # code...
                $interval = $days."days";
            }
            elseif (($days) > 365 && ($months) < 2) {
                # code...
                $interval = $months."month";
            }
            elseif (($months) > 1 && ($months) < 13) {
                # code...
                $interval = $months."months";
            }
            elseif (($months) > 11 && ($years) < 2) {
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
	}
?>
