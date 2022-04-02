<?php
class P2PClient{
  private $apiUrl;
  private $userId;
  private $corrected_user_ids = array(
    '#.*\/\/freestyleguest\.swigit\.com.*#' => 'freestyleguest_swigit',
    '#.*\/\/freestyle\.swigit\.com.*#' => 'freestyle_swigit'
  ); //special-case variable
  function __construct($userId, $apiUrl)
  {
    $this->userId = $userId;
    $this->apiUrl = $apiUrl;
    $this->correct_user_id();
  }
  private function correct_user_id() //special case since swigappmanager is being used by two frontends, not part of the original class
  {
    $origin = $_SERVER['HTTP_REFERER'];
    foreach ($this->corrected_user_ids as $regex => $userid)
    {
      if (preg_match($regex, $origin))
      {
        $this->userId = $userid;
        break;
      }
    }
  }
  public static function parseResponse($resp)
  {
    return json_decode($resp, true);
  }
  public static function output($output)
  {
  	header('Content-Type: application/json');
  	echo json_encode($output);
  	exit();
  }
  public function get_response($query)
  {
  	$resp = file_get_contents($this->apiUrl."?id=".$this->userId."&".$query);
  	return $this->parseResponse($resp);
  }
  public function get_token($remote_ip = "")
  {
    if (!$remote_ip && isset($_SERVER['REMOTE_ADDR'])) $remote_ip = $_SERVER['REMOTE_ADDR'];
    if ($remote_ip)
    {
    	$json = $this->get_response("a=token&ip=".rawurlencode($remote_ip));
    	if ($json && isset($json['status']) && $json['status'])
    	{
    		return $json['output'];
    	}
    }
  	return false;
  }
  public function process_metric($method = "POST", $ip = false)
  {
      if (!$ip) $ip = $_SERVER['REMOTE_ADDR'];
      $request = strtolower($method) == "post"? $_POST : $_GET;
      if (!(isset($request['client_id']) && isset($request['d']) && isset($request['u']))) return false;
      $origin = rawurlencode($_SERVER['HTTP_REFERER']);
      $token = rawurlencode($request['client_id']);
      $download = intval($request['d']);
      $upload = intval($request['u']);
      return $this->output($this->get_response("a=update&ip=".rawurlencode($ip)."&token=".$token."&d=".$download."&u=".$upload."&origin=".$origin));
  }
  public static function generateConfig($token)
  {
    return array ('loader' => array ('trackerAnnounce' => array (0 => 'wss://rendezvoyeur1.cymtv.com:8000/?client_id='.$token,),
      'rtcConfig' => array ('iceServers' => array ( 0 => array ('urls' => 'stun:rendezvoyeur1.cymtv.com:3478?transport=udp',),),),),);
  }
}
?>
