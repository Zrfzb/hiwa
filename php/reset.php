<?php
require 'config.phplib'; 
?>
<html>
<head>
<title>HIWA Recover Password</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<?php require 'header.php';

if (array_key_exists("username", $_POST)) {

	if (array_key_exists("confirmed", $_POST)) {
		print($_POST['confirmed']);                        /* XSS output sanitation. It means a developer must remove HTML control characters
                                                                      from the text prior to sending it to the browser.*/
		if ($_POST['confirmed'] == "on") {
			print("<p>A password reset link has been emailed to $_POST[username] </p>");  // XSS output sanitation
			print("<p>If you did not receive an email in a few minutes, please check your Spam folder.</p>");
			exit();
		}
	}

	$query = "select role from users where login=$1," array($_POST[username]) /*'$_POST[username]'*/; // SQL parameter binding
	$conn = pg_connect('user='.$CONFIG['username'].
		' dbname='.$CONFIG['database']);
	$res = pg_query($conn, $query);
	if (pg_num_rows($res) == 1) {
		print('<P>By continuing this process, you will reset the password of <span style="font-weight:bold">'.
			$_POST['username'].'</span>.</p>');                             // XSS output sanitation
		print("<p>To continue, check the box and hit the Submit button.</p>");
		print('<FORM method="post">');
		print('<input type="hidden" name="username" value="'.$_POST['username'].'">');   // XSS output sanitation
		print('<input type="checkbox" name="confirmed">');
		print('<input type="submit">');
		print('</form>');
		exit();
	
	} else {
		print('Invalid login!');
	}
}
?>
<p>To begin the password reset process, please enter your username in the secure field below.</p>
<form method="POST">
<input type="password" name="username" width="30" placeholder="Enter your login">
<p><input type="submit"></p>
</form>
</body>
</html>


