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

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <title>*** INFERENCE ANSWERS ***</title>
  <style>
		body 
		{
			font-family: Calibri,Verdana;
			font-size: 0.9em;
			letter-spacing: 0.1em;
			line-height: 1.3em;
			margin: 50px 100px 50px 100px;
			text-align: justify;	
		}
		button
		{
			width: 7em;
			height: 2.5em;
			text-decoration: none;
			font-family: "Comic Sans MS";
			font-size: 0.9em;
			text-align: center;
			padding: 3px;	
		}
	</style>
</head>

<body>		
	<h1>
		<?php
			echo $_REQUEST["titulo"];
		?>
	</h1>		
	<fieldset>
	<?php
		echo $_REQUEST["mensagem"];		
	?>
	<br/><br/>
	<form action="index.html" method="post">
		<button type="submit">VOLTAR</button>
	</form>
	</fieldset>
</body>

</html>
