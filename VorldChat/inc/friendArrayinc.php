<?php
    include("friendArrayqueryinc.php");
    if (in_array($user,$friendArray)){
        ?>
        <button type="submit" class="btn btn-outline-danger btn-sm" name="removefriend" value="unfriend" title="Delete Friend"><i class="fa fa-remove"></i> Unfriend</button>
        <button type="submit" class="btn btn-outline-info btn-sm" name="sendmsg" value="message" title="Send Message"><i class="fa fa-send"></i> Message</button>
        <button type="submit" class="btn btn-outline-secondary btn-sm" name="poke" value="poke" title="poke"><i class="fa fa-hand-o-right"></i> Poke</button>
        <?php
    }
    else{
        ?>
        <button type="submit" class="btn btn-outline-primary btn-sm" name="addfriend" value="addfriend">Add as Friend</button>
        <button type="submit" class="btn btn-outline-info btn-sm" name="sendmsg" value="message"><i class="fa fa-send"></i></button>
        <?php
    }
    echo $addAsFriend;
?>