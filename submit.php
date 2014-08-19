<?php
require "database.php";
#$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
#First, check to make sure all is kosher

$invite = $_POST["invite"];
$house_num = $_POST["house_num"];
$message = mqs($_POST["message"]);
#Make sure ID and house_num are both just integers
if (!is_numeric($invite))
    {
    err("Program error: Invitation ID is not numeric.");
    }
if (!is_numeric($house_num))
    {
    err("Program error: House number is not numeric.");
    }

#Make sure they exist in the database
$search = $db->prepare("SELECT * FROM invitation as i WHERE i.id=? AND i.house_num=?");
$worked = $search->execute(array($invite,$house_num));
$fetch_inv = $search->fetch(PDO::FETCH_ASSOC);
$max_guests = $fetch_inv["max_guests"];
if (!$worked)
    {
    err("Program error: Cannot find invitation in database");
    }

$search = $db->prepare("SELECT * FROM invitation as i, guest as g WHERE i.id=? AND g.id=? and g.invitation_id=i.id");



#For each pre-named guest:
#Check to make sure the guest is associated with this invite
#Make sure it's less than the max guests
$i = 0;
$named_guests = array();
while (isset($_POST["guestid$i"]))
    {
    if ($i > $max_guests)
        {
        err("Program error: More guests than permitted on this invitation");
        }
    $guest_id=$_POST["guestid$i"];
    $coming=$_POST["coming$i"];
    $guest = array("id"=>$guest_id,"coming"=>$coming);
    $named_guests[] = $guest;
    $worked = $search->execute(array($invite,$guest_id));
    if (!$worked)
        {
        err("Program error: Cannot find guest in database or incorrect invitation id in guest.");
        }
    $i++;
    }

#Next go through unnamed guests to see how many there are, and make sure it's within the limit
$unnamed_guests = array();
$i = 0;
while (((count($named_guests) + count($unnamed_guests)) <= $max_guests) && isset($_POST["newguestname$i"]))
    {
    $newguest = mqs($_POST["newguestname$i"]);
    if (!empty($newguest))
        $unnamed_guests[] = $newguest;
    $i++;
    }
unset ($guest);

$ip = $_SERVER['REMOTE_ADDR'];
$event = $db->prepare("INSERT INTO event (guest, description, IP) VALUES (?,?,?)");
$in_event = $db->prepare("INSERT INTO in_event (invitation, description, IP) VALUES (?,?,?)");

#Update named guests as set.
$updateguest = $db->prepare("UPDATE guest SET coming=? WHERE guest.id=? LIMIT 1");
foreach ($named_guests as $guest)
    {
    if ($guest["coming"] == "on")
        {
        #echo "Setting guest ".$guest["id"]." to on.";
        $updateguest->execute(array(1,$guest["id"])) or err("Program error: Failed to update database with guest status.");
        $event->execute(array($guest["id"],"Set as attending",$ip));
        }
    else
        {
        #echo "Setting guest ".$guest["id"]." to off.";
        $updateguest->execute(array(0,$guest["id"])) or err("Program error: Failed to update database with guest status.");
        $event->execute(array($guest["id"],"Set as NOT attending",$ip));
        }
    }


#Add new guests for filled-in ones

$addguest = $db->prepare("INSERT INTO guest (submitted_name, coming, invitation_id) VALUES ( ?, 1, ?)");
foreach ($unnamed_guests as $guest)
    {
    $addguest->execute(array($guest,$invite)) or err("Program error: Failed to insert new guest into database.");
    $newguestid = $db->lastInsertId();
    $event->execute(array($newguestid,"Created with name " . $guest,$ip));
    }

#Update invite record to responded
$setresponded = $db->prepare("UPDATE invitation SET responded = '1',special_message=? WHERE id=? LIMIT 1");
$setresponded->execute(array($message,$invite)) or err("Program error: Failed to set invitation as RSVP'd.");
$in_event->execute(array($invite,"Set as responded with message = " . $message,$ip));


require "header.php";
?> <!-- <?
#print_r($_POST);
#print_r($named_guests);
#echo count($named_guests);
#print_r($unnamed_guests);
?> -->
<h1>RSVP Successful:</h1>
<?
$listguests = $db->prepare("SELECT * FROM guest WHERE invitation_id=? AND coming=1");
$listguests->execute(array($invite));
$attendees = $listguests->fetchAll(PDO::FETCH_ASSOC);
?>
<p>You have successfully RSVPd to our wedding.  Can't wait to see you!<?
if (count($attendees) > 0)
    {
    ?> The following people will be coming:</p><?
    }
else
    {
    ?> Sorry you couldn't make it!</p><?
    }?>
<ul>
<?
foreach ($attendees as $guest)
    {
    echo "<li>";
    if (!empty($guest["submitted_name"]))
        echo htmlspecialchars($guest["submitted_name"]);
    else
        echo $guest["first_name"] . " " . $guest["last_name"];

    echo "</li>";
    }

?>
</ul>
<?
if (!empty($message))
    {
    ?><p>Your special message for the bride and groom is:</p>
    <p class="message"><?=htmlspecialchars($message)?></p><?
    }
?>
<p>You can make changes up until the RSVP date by returning to the application or by returning to <a href="invitation.php?invite=<?=$invite?>&house_num=<?=$house_num?>">your invitation</a>.</p>
<?
require "footer.php";

?>
