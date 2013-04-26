<?php



    class DCCChat {

        public $nick;

        public $dip;

        public $dport;

        public $ip;

        public $port;

        public $sock;

        public $main;

        public $type;

        public $tmpsock;

        public $islisten;



        public function DCCChat($nick,$ip,$port,$type="request") {

        	$this->main=&$GLOBALS['oServer'];

        	$this->type=$type;

        	if ($type=="accept") {

        		$this->nick=$nick;

        		$this->dip=long2ip($ip);

        		$this->dport=$port;

        		$this->ConnectTo();

        	}     

        	elseif ($type=="request") {

        		$this->nick=$nick;

        		$this->dip=ip2long($ip);

        		$this->RequestTo();

        	}   		

        }

        

        public function ConnectTo() {

        	echo "Connecting to {$this->dip}:{$this->dport}\n";

        	$this->sock=fsockopen($this->dip,$this->dport);

        	stream_set_blocking($this->sock,0);

        }

        

        public function RequestTo() {

        	$this->sock=socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

     if(function_exists('socket_set_option')) {
      socket_set_option($this->sock,SOL_SOCKET,SO_REUSEADDR,1);
     }
     else {
      socket_setopt($this->sock,SOL_SOCKET,SO_REUSEADDR,1);
     }
        	
        	$this->port=1024;

        	while (!(socket_bind($this->sock,"0.0.0.0",$this->port)))

        		$this->port++;

        	socket_listen($this->sock);

        	socket_set_nonblock($this->sock);

        	$this->islisten=true;

        	//$this->main->Send("dccchat",array($this->nick,$this->main->long,$this->port));
        	$this->main->vBufferAddLine('PRIVMSG '.$this->nick.' :'.chr(1).'DCC CHAT chat '.$this->dip.' '.$this->port.chr(1));

        }        	

        

        public function Read() {

        	if ($this->sock) {

        		if ($this->type=="accept")

        			$this->DCCCHATParse(fgets($this->sock));

        		else {

        			if ($this->islisten) {

        				if (($this->tmpsock=socket_accept($this->sock))) {

        					socket_close($this->sock);

        					$this->sock=&$this->tmpsock;

        					$this->islisten=false;

        				}

        			}

        			else

        				$this->DCCCHATParse(socket_read($this->sock,1024,PHP_NORMAL_READ));

        		}        		

        	}	

        }

        

        public function Send($data) {

        	if ($this->sock) {

        		if ($this->type=="accept")

        			fputs($this->sock,$data);

        		else

        			socket_write($this->sock,$data,strlen($data));

        	}

        }

        

        public function DCCCHATParse($data) {

        	if (strlen($data)>0)

        		$this->Send($data);

        }

    }



?>