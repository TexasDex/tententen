<?php
require "database.php";

$invite = $_GET["invite"];
$house_num = $_GET["house_num"];

#Make sure ID and house_num are both just integers
if (!is_numeric($invite))
    {
    err("Program error: Invitation ID is not numeric.");
    }
if (!is_numeric($house_num))
    {
    err("Program error: House number is not numeric.");
    }


$search = $db->prepare("SELECT * FROM invitation AS i, guest AS g WHERE i.id=? AND i.house_num=? and g.invitation_id=i.id");
$worked = $search->execute(array($invite,$house_num));
if (!$worked)
    {
    err("Program error: Cannot find invitation in database");
    }

$result = $search->fetchAll(PDO::FETCH_ASSOC);
require "header.php";
?>
<h1>Welcome <?=$result[0]['name']?>!</h1>
<?
if ($result[0]["responded"])
    echo "<p>You have already RSVPd to our wedding.  If you need to change your RSVP you may do so below.</p>";
$message = $result[0]["special_message"];
$comment = $result[0]["comment"];
$unnamed_guests = $result[0]["max_guests"] - count($result);

if (!empty($comment))
    {?>
    <p><?=$comment?></p>
    <?}?>
<p>Please let us know who will be able to attend.  Check the boxes of the people who can come<?
if ($unnamed_guests  == 1 )
    echo ", fill out the name of your guest (if applicable),";
if ($unnamed_guests > 1 )
    echo ", fill out the names of your guests (if applicable),";
?> and press Ok.</p>
<form name="guestform" method="post" action="submit.php">
<input type="hidden" name="invite" value="<?=$invite?>"/>
<input type="hidden" name="house_num" value="<?=$house_num?>"/>
<?

$form_index = 0;
#loop through guest array
foreach ($result as $guest)
    {
    ?>
    <p class="guest"><input type="checkbox" name="coming<?=$form_index?>" <?=$guest["coming"] ? "checked" :"" ?>/> <input type="hidden" name="guestid<?=$form_index?>" value="<?=$guest["id"]?>"/> <?
    if ($guest["first_name"] != "")
        echo $guest["first_name"] . " " . $guest["last_name"] ;
    else
        echo htmlspecialchars($guest["submitted_name"]);
    ?></p><?
    $form_index++;
    }

#See if there are any unnamed guests:
if ($unnamed_guests < 0)
    err("Program error: More guests in database than permitted");

for($i=0; $i < $unnamed_guests; $i++)
    {
    ?>
    <p class="guest"> Guest: <input type="text" name="newguestname<?=$i ?>" value=""/> </p><?
    }

?>
 <input type="submit" name="Ok" value="Ok"/>
 <p>Got a special message for the bride and groom?  Food allergy or other special needs? Put it here:</p>
 <textarea rows="3" cols="70" name="message"><?=htmlspecialchars($message)?></textarea>
</form>
<?

?> <!-- <? print_r($result); ?> --> <?

require "footer.php";
?>
