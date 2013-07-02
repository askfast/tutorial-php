<?php
  require_once("askfast/AskFast.php");
  
  // Before start uncomment following and fill in your credentials
  //define("PUBLIC_KEY", "");
  //define("PRIVATE_KEY", "");
  
?>

<html>
<head>
    <title>AskFast Demo</title>
</head>

<body>
<h1>AskFast Demo</h1>

<form method="post" src="index.php">
<?php
    if(!isset($_POST["phone"])) {
?>
    <div>To receive a call fill in your phonenumber</div>
    PhoneNumber: <input type="text" name="phone">
    <input type="submit">
<?php
    } else {
        $address = $_POST["phone"];
        $af = new AskFast(PUBLIC_KEY, PRIVATE_KEY);
        $resp = $af->call($address, 'agent.php');
        
        if(isset($resp->error)) {
            echo "<div>Failed to call because: ".$resp->error->message." (".$resp->error->code.")</div>";
        } else {
            echo "<div>You are being called!</div>";
        }
     }
?>
</form>
</body>
</html>