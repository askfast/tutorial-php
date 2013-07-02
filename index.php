<?php
  require_once("askfast/AskFast.php");
  
  // Before start uncomment following and fill in your credentials
  //define("PUBLIC_KEY", "");
  //define("PRIVATE_KEY", "");
  
?>

<html>
<head>
    <title>AskFast Bidding Demo</title>
</head>

<body>
<h1>AskFast Bidding Demo</h1>

<h2>HTC One auction</h2>
<img src="img/htc-one.png" /><br />

<form method="post" src="index.php">
<?php
    if(!isset($_POST["phone"])) {
?>
    <div>Please place a bid by entering your phonenumber:</div>
    PhoneNumber: <input type="text" name="phone"><br />
    <input value="Bid" type="submit">
<?php
    } else {
        $address = $_POST["phone"];
        $af = new AskFast(PUBLIC_KEY, PRIVATE_KEY);
        $resp = $af->call($address, 'agent.php');
        
        if(isset($resp->error)) {
            echo "<div>Failed to call because: ".$resp->error->message." (".$resp->error->code.")</div>";
        } else {
            echo "<div>You are being called!</div>";
            echo '<input value="New Bid" type="submit">';
        }
     }
?>
</form>
</body>
</html>