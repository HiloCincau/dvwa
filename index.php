<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Dashboard layanan' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'home';

ob_start();
require DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/portalReport.inc.php';
$page[ 'body' ] .= ob_get_clean();

dvwaHtmlEcho( $page );
