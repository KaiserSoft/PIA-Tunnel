<?php
/* @var $_settings PIASettings */
/* @var $_pia PIACommands */
/* @var $_files FilesystemOperations */
/* @var $_services SystemServices */

$disp_body = "<body>\n";
//$disp_body .= "<h1>PIA-Tunnel Management Interface</h1>";


/* always include the menu on top of the page */
$disp_body .= load_menu();



$disp_body .= "<div class=\"content\">\n";
/* check which pages needs to be processed */
$plen = mb_strlen($_REQUEST['page']); //sanity check
if( $plen > 1 && $plen < 20 && isset($_REQUEST['page']) ){
  switch( $_REQUEST['page'] ){
    case 'config':
      require_once $inc_dir.'logic_config.php';
      break;
    case 'tools':
      require_once $inc_dir.'logic_tools.php';
      break;
    case 'setup-admin_account':
      require_once $inc_dir.'logic_admin_account.php';
      break;
    case 'logout':
      require_once $inc_dir.'logic_logout.php';
      break;
    case 'setup-wizard':
      require_once $inc_dir.'logic_wizard.php';
      break;
    case 'ping':
      $UNLOCKED='byPIA';
      //require_once '/var/www/tools/ping.php';
      require_once $settings['HTDOCS_PATH'].'/tools/ping.php';
      unset($UNLOCKED);
      break;
    case 'command_runner':
      $UNLOCKED='byPIA';
      require_once $settings['HTDOCS_PATH'].'/tools/command_runner.php';
      //require_once $settings['HTDOCS_PATH'].'/tools.php';
      unset($UNLOCKED);
      break;
    case 'tc':
      $UNLOCKED='byPIA';
      #require_once '/var/www/tools/tc.php';
      require_once $settings['HTDOCS_PATH'].'/tools/tc.php';
      unset($UNLOCKED);
      break;
    default:
      require_once $inc_dir.'logic_overview.php';
  }

}else{
  //default to overview
  require_once $inc_dir.'logic_overview.php';
}
$disp_body .= "</div>\n";



?>
