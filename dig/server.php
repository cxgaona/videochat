<?php
set_time_limit(0);
require 'clases/class.PHPWebSocket.php';
function wsOnMessage($clientID, $message, $messageLength, $binary) 
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}
	if ( sizeof($Server->wsClients) == 1 )
		$Server->wsSend($clientID, "");
	else
		foreach ( $Server->wsClients as $id => $client )
			//if ( $id != $clientID )
				//$Server->wsSend($id, "Visitor $clientID ($ip) said \"$message\"");
				//aqui recibimos la accion con los demas parametros e identificadores
				$Server->wsSend($id,$message);
}
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$Server->log( "" );
	foreach ( $Server->wsClients as $id => $client )
		if ( $id != $clientID )
			$Server->wsSend($id, "");
}
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$Server->log( "" );
	foreach ( $Server->wsClients as $id => $client )
		$Server->wsSend($id, "");
}
function servidor(){
	$Server = new PHPWebSocket();
	$Server->bind('message', 'wsOnMessage');
	$Server->bind('open', 'wsOnOpen');
	$Server->bind('close', 'wsOnClose');
	if($_SERVER['REMOTE_ADDR']=="::1"){
 		$ipServidor="127.0.0.1";
	}else{
		$ipServidor=$_SERVER['REMOTE_ADDR'];
	}
	$Server->wsStartServer($ipServidor,12345);
}

//$Server->wsStartServer('192.168.43.206',12345);
