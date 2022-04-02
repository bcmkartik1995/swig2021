<?php 
include_once('../../../web-config.php'); 
include_once('../../../includes/classes/P2PClient.php');
include_once('../../../includes/functions/common.php');
cors();
$p2p_client = new P2PClient(P2P_USERID, P2P_API_URL);
$p2p_client->process_metric();
?>