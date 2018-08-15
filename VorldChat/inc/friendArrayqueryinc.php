<?php include("connect.php");
    $friendArray = "";
    $countFriends = "";
    $friendArray12 = "";
    $addAsFriend = "";
    $selectFriend_query = "SELECT `friend_array` FROM `users` WHERE `username` = '$username'";
    $selectFriend_query_sqli = mysqli_query($connect,$selectFriend_query);
    $friendRow = mysqli_fetch_assoc($selectFriend_query_sqli);
    $friendArray = $friendRow['friend_array'];
    if($friendArray != "" || $friendArray = []){
        $friendArray = explode(",",$friendArray);
        $countFriends = count($friendArray);
        $friendArray12 = array_slice($friendArray, 0, 12);
    }
    $i = 0;

    $selectUser_query = "SELECT `friend_array` FROM `users` WHERE `username` = '$user'";
    $selectUser_query_sqli = mysqli_query($connect,$selectUser_query);
    $userfriendRow = mysqli_fetch_assoc($selectUser_query_sqli);
    $userfriendArray = $userfriendRow['friend_array'];
    if($userfriendArray != "" || $userfriendArray = []){
        $userfriendArray = explode(",",$userfriendArray);
        $countUserFriends = count($userfriendArray);
        $userfriendArray12 = array_slice($userfriendArray, 0, 12);
    }
    $i = 0;