<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( ) );

dvwaDatabaseConnect();

if( isset( $_POST[ 'Login' ] ) ) {
	// Anti-CSRF
	if (array_key_exists ("session_token", $_SESSION)) {
		$session_token = $_SESSION[ 'session_token' ];
	} else {
		$session_token = "";
	}

	checkToken( $_REQUEST[ 'user_token' ], $session_token, 'login.php' );

	$user = $_POST[ 'username' ];
	$user = stripslashes( $user );
	$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $user ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	$pass = $_POST[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$pass = md5( $pass );

	// Validate that username and password are not empty (improved login validation).
	if( trim( $user ) === '' || trim( $_POST[ 'password' ] ) === '' ) {
		dvwaMessagePush( 'Isi nama pengguna dan kata sandi untuk masuk.' );
		dvwaRedirect( 'login.php' );
		exit;
	}

	$query = ("SELECT table_schema, table_name, create_time
				FROM information_schema.tables
				WHERE table_schema='{$_DVWA['db_database']}' AND table_name='users'
				LIMIT 1");
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query );
	if( mysqli_num_rows( $result ) != 1 ) {
		dvwaMessagePush( "Siapkan basis data terlebih dahulu melalui halaman pengaturan." );
		dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'setup.php' );
	}

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '.<br />Try <a href="setup.php">installing again</a>.</pre>' );
	if( $result && mysqli_num_rows( $result ) == 1 ) {    // Login Successful...
		dvwaMessagePush( "Anda masuk sebagai '{$user}'" );
		dvwaLogin( $user );
		dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'index.php' );
	}

	// Login failed
	dvwaMessagePush( 'Nama pengguna atau kata sandi salah.' );
	dvwaRedirect( 'login.php' );
}

$messagesHtml = messagesPopAllToHtml();

Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
Header( 'Content-Type: text/html;charset=utf-8' );      // TODO- proper XHTML headers...
Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );     // Date in the past

// Anti-CSRF
generateSessionToken();

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Masuk | Website Kementerian</title>
	<link rel="icon" href="favicon.ico">
	<link rel="stylesheet" href="dvwa/css/portal.css">
	<link rel="stylesheet" href="dvwa/css/login.css">
</head>
<body class="login-page">
	<header class="masthead">
		<div class="masthead-inner">
			<div class="brand"><span class="monogram" aria-hidden="true">WK</span>Website Kementerian</div>
		</div>
	</header>
	<main class="login-panel">
		<?php require DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/portalReport.inc.php'; ?>
		<section class="login-form" aria-labelledby="login-title">
			<h2 id="login-title">Akses pegawai</h2>
			<p>Masuk untuk mengelola layanan kementerian.</p>
			<form action="login.php" method="post">
				<label for="user">Nama pengguna</label>
				<input id="user" type="text" name="username" autocomplete="username">
				<label for="pass">Kata sandi</label>
				<input id="pass" type="password" name="password" autocomplete="off">
				<?php echo tokenField(); ?>
				<button type="submit" name="Login" value="Login">Masuk <span aria-hidden="true">&rarr;</span></button>
			</form>
			<?php echo $messagesHtml; ?>
		</section>
	</main>
	<footer class="login-footer">Website Kementerian <span>Portal layanan</span></footer>
</body>
</html>
