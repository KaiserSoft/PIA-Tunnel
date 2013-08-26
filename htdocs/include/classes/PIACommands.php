<?php
/**
 * class to interact with the various pia-* commands
 *
 * @author MirkoKaiser
 */
class PIACommands {
  private $_files;
  private $_settings;
  
  
  /**
   * pass the global $settings object
   * @param object $settings
   */
  function set_settings(&$settings){
    $this->_settings = $settings;
  }  
  
  
  /**
   * pass the global $_files object
   * @param object $_files
   */
  function set_files(&$files){
    $this->_files = $files;
  }  
  
  
  /**
   * returns the current status of pia-daemon started by this webUI
   * @return string set to
   * <ul>
   * <li>'running'</li>
   * <li>'offline'</li>
   * </ul>
   */
  function status_pia_daemon(){
    
    //check the return from screen -ls
    $ex = array();
    exec('ps aux | grep -c "pia-daemon"', $ex);
    if( array_key_exists('0', $ex) === true && (int)$ex[0] > 2 ){ // 2 for command and grep itself
      return 'running';
    }else{
      return 'offline';
    }
  }
  
  
  /**
   * method will run a check if tun0 return an IP and assume "UP" when one is found
   * @return booolean TRUE if VPN is up or FALSE if VPN is down
   */
  function is_vpn_up(){
    $ret = array();
     exec('/sbin/ip addr show tun0 2>/dev/null', $ret);
     if( array_key_exists( '0', $ret) !== true ){
       return false;
     }
     
     return true;
  }
  
  
  /**
   * start /pia/pia-start with passed argument
   * @param string $ovpn name of connection or 'daemon' to use MYVPN array
   */
  function pia_connect( $ovpn ){
    $arg = escapeshellarg($ovpn);
    
    //delete old session.log
    $f = '/pia/cache/session.log';
    $this->_files->rm($f);
    
    //add header to new session.log
    if( $ovpn === 'daemon' ){
      $set = $this->_settings->get_settings();
      $s = $set['MYVPN[0]'];
      $c = "connecting to $s\n\n";
      $_SESSION['connecting2'] = $s; //store for messages
    }else{
      $c = "connecting to $arg\n\n";
      $_SESSION['connecting2'] = $arg; //store for messages
    }
    $this->_files->writefile( $f, $c ); //write file so status overview works right away

    //time to initiate the connection
    //using bash allows this to happen in the background
    // EDIT: this open the door for the UI to run any command as root. need to remove bash calls!!
    exec("sudo bash -c \"/pia/pia-start $arg &> /pia/cache/php_pia-start.log &\" &>/dev/null &");
  }
  
 
  /**
   * interact with /pia/pia-daemon commnad
   * @param string $command string containing command
   * <ul>
   * <li>stop</li>
   * <li>start</li>
   * </ul>
   */
  function pia_daemon( $command ){
    switch( $command ){
      case 'stop':
        $foo = array();
        exec('sudo /pia/pia-daemon stop', $foo);
        break;
      case 'start':
        exec('sudo bash -c "/pia/pia-daemon &>/pia/cache/pia-daemon.log &" &>/dev/null &');
        break;
    }
  }
  
  
  
}
?>
