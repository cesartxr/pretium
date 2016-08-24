<!--
Copyright (C) 2016 Cesar Bezerra Teixeira - cesar.txr@outlook.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
-->

<?php

class Log
{
	public $nr;
	public $id;
	public $sn;
	public $time;
	public $fw;
	public $pri;
	public $c;
	public $m;
	public $msg;
	public $sess;
	public $n;
	public $usr;
	public $src;
	public $dst;

	public function parsear($cadena, $buscar1, $buscar2)
	{
		$char = strlen($cadena);
		$char1 = strlen($buscar1);
		$number1 = strpos($cadena, $buscar1);
		$number2 = strpos($cadena, $buscar2);
		$cadena1 = substr($cadena, $number1 + $char1);
		$number2 = $char - $number2;
		$number2 = $number2;
		$number2 = "-".$number2;
		$cadena1 = substr($cadena1, 0, $number2);
		return $cadena1;
	}

	public function lerLogs ()
	{
		$a = fopen ("logs.txt","r");
		
		while (!feof($a))
		{
			$linha 		= fgets ($a,4096);
			
			$this->nr 	= $this->parsear ($linha,"<",">");
			
			//$this->id 	= $this->parsear ($linha,"id=","sn=");
			//$this->sn 	= $this->parsear ($linha,"sn=","time=");
			//$this->time= $this->parsear ($linha,"time=","fw=");
			//$this->fw 	= $this->parsear ($linha,"fw=","pri=");
			//$this->pri = $this->parsear ($linha,"pri=","c=");
			//$this->c 	= $this->parsear ($linha,"c=","m=");
			//$this->m 	= $this->parsear ($linha,"m=","msg=");
			$this->msg = $this->parsear ($linha,'msg="','src=');
			//$this->sess= $this->parsear ($linha,"sess=","n=");
			//$this->n 	= $this->parsear ($linha,"n=","usr=");
			//$this->usr = $this->parsear ($linha,"usr=","src=");
			//$this->src = $this->parsear ($linha,"src=","dst=");
			//$this->dst = $this->parsear ($linha,"dst=",'"');
			//echo $this->nr."<br>";
			echo $this->msg."<br>";
		}
	}
	
	public function procurar($texto,$expressao)
	{
		 $expressao = "/".$expressao."/";
		 echo $expressao;
		 $verifica = preg_match( $expressao,  $texto);
		 return $verifica;
	}
}

$log = new Log;
$log->lerLogs();
//$r = $log->procurar("a casa e grande","casa");
//echo $r;

?>
