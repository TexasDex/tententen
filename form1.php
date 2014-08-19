<?php
if (!$global)
  require "global.php";

$first_name = htmlspecialchars(mqs($_GET['first_name']));
$last_name = htmlspecialchars(mqs($_GET['last_name']));
$house_num = htmlspecialchars(mqs($_GET['house_num']));
?>

<form method="get" action="search.php" enctype="multipart/form-data">
<p>
<label for="first_name">First name:</label>
<input type="text" name="first_name" id="first_name" value="<?php echo $first_name ?>"/>
</p>
<p>
<label for="last_name">Last name:</label>
<input type="text" name="last_name" id="last_name" value="<?php echo $last_name ?>"/>
</p>
<p>
<label for="house_num">House number:</label>
<input type="text" name="house_num" id="house_num" value="<?php echo $house_num ?>"/>
</p>
<input type="submit"/ name="Go" value="Search"/>
</form>
