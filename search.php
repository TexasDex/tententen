<?php

require "database.php";

#do search
$first_name = mqs($_GET["first_name"]);
$last_name = mqs($_GET["last_name"]);
$house_num = mqs($_GET["house_num"]);

$search = $db->prepare("SELECT * FROM guest, invitation WHERE guest.invitation_id = invitation.id AND guest.first_name LIKE ? AND guest.last_name LIKE ? AND invitation.house_num = ?");

$worked = $search->execute(array($first_name, $last_name, $house_num));
$result = $search->fetch();

if ($search->rowCount() > 0) //if search succeeded
    {
    #redirect to invitation page
    $special_meta = "<meta http-equiv=\"refresh\" content=\"2;url=invitation.php?invite=" . $result["invitation_id"] . "&house_num=" . htmlspecialchars($house_num) . "\">";
    require "header.php";
    ?><h1>Invitation found!</h1>
    <p>Your browser will redirect you in a second, or you can click on <a href="invitation.php?invite=<?=$result["invitation_id"]?>&house_num=<?=htmlspecialchars($house_num)?>">your invitation</a>.</p><?
    require "footer.php";
    }
else
    {
    require "header.php";
    echo "<h1>Please enter the following information:</h1>";
    require "form1.php";
    echo "<p class=\"error\">So sorry, but we could not find your invitation.  Please enter your information exactly as it is shown on the invitation.  If you still have trouble you may RSVP via phone.</p>";
    require "footer.php";
    }
?>
