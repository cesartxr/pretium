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
include ('base.php');

if(isset($_REQUEST["teste"])) 
{
	verificarCampos();
	gravarFatos();	
	consultarBase();	
}

/*************************************************************************************************/

function verificarCampos()
{
	clearstatcache(); 
	$poder 	= $_REQUEST["poder"];
	$anos	= $_REQUEST["anos"];
	if (($poder=='')||($anos==''))
	{
	  $linha='Location: mensagem.php?titulo=Erro&mensagem=E necessario preeencher todos os campos'; 
	  header ($linha);
	}
}

/*************************************************************************************************/

function gravarFatos()
{
	$poder 	= $_REQUEST["poder"];
	$anos	= $_REQUEST["anos"];
	
	$a = fopen("base.fat",'w');
	
	$linha = "0;anos;=;".$anos.";0;n;0;0;\n";
	fwrite($a,$linha,strlen($linha));
	$linha = "0;economico;=;".$poder.";0;s;0;0;";
	fwrite($a,$linha,strlen($linha));
	
	fclose($a);
}

/*************************************************************************************************/

function consultarBase()
{
	$base = new Base;
	$base->consulta();	
}

?>
