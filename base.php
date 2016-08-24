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

class Base
{
	public $clausulas 	= array();
	public $regras 		= array();
	public $hipoteses	= array();
	public $fatos		= array();
	public $respostas	= array();
	
	/**************************************************************************************
	CARREGARBC()
	
	Método responsável por ler o arquivo "base.gse" e colocar no array muldimensional
	$clausulas". As clausulas tem o seguinte formato:
	número(s1)		==>	Identificador da cláusula
	propriedade(s2)	==>	Elemento do domínio do conhecimento
	conectivo(s3)		==>	(=,>,<)
	valor(s4)			==>	Valor atribuído à propriedade integrante da cláusula
	tipo_clausula(s5) ==> (no,oi,of)
	tipo_valor(s6)	==>	(n,s)
	resposta(s7)		==> Resposta a ser dada após inferência
	acao(s8)			==>	Não usado no momento
	***************************************************************************************/
	
	public function carregarBC()
	{
		$bc = "base.gse";
		$elementos 	= 	array();

		if (!file_exists($bc)) die('It´s not possible to load the Knowledge Base...');
		$a = fopen($bc,'r');
		if ($a == false) die('It´s not possible to load the Knowledge Base ...');

		while (!feof($a))
		{
			$aux =  fgets ($a,4096);
			if ($aux[0] == '*') continue;
			if ($aux[0] == '#') break;
		
			$s1  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s2  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s3  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s4  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s5  = substr($aux,0,strpos($aux,";"));
			
			//Monta a lista de hipóteses com os objetivos finais
			if ($s5 == "of")
			{
				array_push($this->hipoteses,$s1);				
			}
			
			$aux = substr (strstr($aux,';'),1); 
			$s6  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s7  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s8  = substr($aux,0,strpos($aux,";"));
			
			$elementos = array ($s1,$s2,$s3,$s4,$s5,$s6,$s7,$s8);
			array_push($this->clausulas, $elementos);		
		}
		while (!feof($a))
		{
			$aux =  fgets ($a,4096);
			if ($aux[0] == '*') continue;
			if ($aux[0] == '#') break;
		
			$s1  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s2  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s3  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s4  = substr($aux,0,strpos($aux,";"));
			
			$elementos = array ($s1,$s2,$s3);
			array_push($this->regras, $elementos);	
		}
		fclose ($a);		
	}
	
	/**************************************************************************************
	CARREGARBF()
	
	Método responsável por ler o arquivo "base.fat" e colocar no array muldimensional
	$fatos". Os fatos são cláusulas com o seguinte formato:
	número(s1)		==>	Identificador da cláusula
	propriedade(s2)	==>	Elemento do domínio do conhecimento
	conectivo(s3)		==>	(=,>,<)
	valor(s4)			==>	Valor atribuído à propriedade integrante da cláusula
	tipo_clausula(s5) ==> (no,oi,of)
	tipo_valor(s6)	==>	(n,s)
	resposta(s7)		==> Resposta a ser dada após inferência
	acao(s8)			==>	Não usado no momento
	**************************************************************************************/
	
	public function carregarBF()
	{
		$bf = "base.fat";
		$elementos 	= 	array();

		if (!file_exists($bf)) die('It´s not possible to load the Facts Base ...');
		$a = fopen($bf,'r');
		if ($a == false) die('It´s not possible to load the Facts Base ...');

		while (!feof($a))
		{
			$aux =  fgets ($a,4096);
			if ($aux[0] == '*') continue;
			if ($aux[0] == '#') break;
		
			$s1  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s2  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s3  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s4  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s5  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s6  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s7  = substr($aux,0,strpos($aux,";"));
			$aux = substr (strstr($aux,';'),1); 
			$s8  = substr($aux,0,strpos($aux,";"));
			
			$elementos = array ($s1,$s2,$s3,$s4,$s5,$s6,$s7,$s8);
			array_push($this->fatos, $elementos);		
		}
		fclose ($a);
	}

	
	/**********************************************************************************
	CONSULTA()
	
	Carrega as bases de conhecimento e de fatos
	Percorre as hipotéses, entregando-as ao motor de inferência.
	Armazena no array "respostas" as hipóteses validadas.
	Apresenta as respostas.
	***********************************************************************************/
	
	public function consulta()
	{
		$this->carregarBC();
		$this->carregarBF();
		$c = count ($this->hipoteses);
		for ($i = 0; $i < $c; $i++) 
		{
			$h = $this->retornaClausula ($this->hipoteses[$i]);
			$resposta = $this->clausulas[$h][6];
			if ($h == 999) 	continue;	
			if ($this->infere ($h))
			{
				//if (!in_array($resposta, $this->respostas))
				//{
					array_push($this->respostas,$h);
				//}
			}			
		}		
		$this->apresentarRespostas();
	}
	
	/*******************************************************************************/
	
	public function retornaClausula ($num)
	{
		$c = count($this->clausulas);
		for ($i=0; $i<$c; $i++)
		{
			$n = $this->clausulas[$i][0];
			if ($n == $num)
			{
				return $i; //índice no vetor de clausulas
			}
		}
		return 999;
	}
	
	/*********************************************************************************
	INFERE()
	
	Recebe o índice de uma cláusula no array de clausulas.
	Se a clausula for um fato está provada
	Se a clausula não for fato mas for conclusão de regra empilha as premissas
	e as entrega ao motor de inferencia, se todas as premissas forem provadas, 
	a cláusula é um fato.
	se a cláusula não é fato e nem premissa de regra não está provada.
	**********************************************************************************/
	
	public function infere($h)
	{
		
		//Se a hipótese for fato está provada
		if ($this->comparaFato($h)) return 1;
		
		$premissas = array();
		$premissas =$this->montaPremissas($h);
		$c = count ($premissas);
		
		if ($c > 0)
		{
			for ($j=0;$j<$c;$j++)
			{
				$aux = $premissas[$j];
				$d 	 = count($aux);
				$flag = 1;
				for ($i=0; $i<$d; $i++)
				{					
					$haux = $aux[$i];
					if ($this->infere($haux) == 0) $flag = 0;
				}
				if ($flag == 1) return 1;
			}
		}
		
		return 0;
	}
	
	/*********************************************************************************/
	
	public function comparaFato($h)
	{
		$c = count($this->fatos);

		for ($i=0; $i<$c; $i++)
		{
			$pfatpro = $this->fatos[$i][1];
			$pfatval = $this->fatos[$i][3];
						
			$pclapro 	= $this->clausulas[$h][1];
			$pclaval 	= $this->clausulas[$h][3];
			
			$conectivo 	= $this->clausulas[$h][2];
			$tipo    	= $this->clausulas[$h][5];
			
			if ($pfatpro != $pclapro) 	continue;
			
			if ($tipo == "s")
			{
				if ($pfatval != $pclaval) 	return 0;
				return 1;
			}
			
			if ($tipo == "n")
			{
					
				if ($conectivo == "=")
				{
					
					if ($pfatval != $pclaval) return 0;
					return 1;
				}
				if ($conectivo == ">")
				{
					if ($pfatval <= $pclaval) return 0;
					return 1;
				}
				if ($conectivo == "<")
				{
					if ($pfatval >= $pclaval) return 0;
					return 1;
				}
			}
		}
		return 0;		
	}
	
	/********************************************************************************/
	
	public function montaPremissas($h)
	{
		$aux1 	= 	array();		
		$cla	=	$this->clausulas[$h][0];//nº da cláusula
		$c		= 	count ($this->regras);	//qte de regras
		$i 		= 0;
		
		while (true)
		{
			if ($i >= $c) break;
			$regcon = $this->regras[$i][2];		//conclusão da regra
			if ($regcon == $cla)
			{
				$aux2	=	array();
				$regnum = $this->regras[$i][0]; //nº da regra
				while (true)
				{
					if ($i >= $c) break;
					if ($this->regras[$i][0] != $regnum) break;										
					$clausula = $this->retornaClausula($this->regras[$i][1]);
					array_push($aux2,$clausula);	
					$i++;
				}
				array_push($aux1,$aux2);
			}			
			$i++;			
		}
		return $aux1;
	}
	
		
	/*********************************************************************************
	EXIBIÇÔES
	**********************************************************************************/
	
	public function verificarBase()
	{
		$this->carregarBC();
		$this->carregarBF();
		//$this->listarClausulas();
		//$this->listarRegras();
		$this->listarFatos();
		$this->listarHipoteses();
	}
	
	public function exibirClausulas($h)
	{
		echo $this->clausulas[$h][0];echo " | ";
		echo $this->clausulas[$h][1];echo " | ";
		echo $this->clausulas[$h][2];echo " | ";
		echo $this->clausulas[$h][3];echo " | ";
		echo $this->clausulas[$h][4];echo " | ";
		echo $this->clausulas[$h][5];echo " | ";
		echo $this->clausulas[$h][6];echo " | ";
		echo $this->clausulas[$h][7];echo " | ";
		echo "<br>";echo "<br>";						
	}
	
	/*********************************************************************************/
	
	public function exibirHipoteses($h)
	{
		echo $this->clausulas[$h][0];echo " | ";
		echo $this->clausulas[$h][1];echo " | ";
		echo $this->clausulas[$h][2];echo " | ";
		echo $this->clausulas[$h][3];echo " | ";
		echo $this->clausulas[$h][4];echo " | ";
		echo $this->clausulas[$h][5];echo " | ";
		echo $this->clausulas[$h][6];echo " | ";
		echo $this->clausulas[$h][7];echo " | ";
		echo "<br>";echo "<br>";						
	}
	
	/*********************************************************************************/
	
	public function exibirRegras($h)
	{
		echo $this->regras[$h][0];
		echo $this->regras[$h][1];echo " | ";
		echo $this->regras[$h][2];echo " | ";
		echo "<br>";echo "<br>";						
	}
	
	/*********************************************************************************/
	
	public function exibirFatos($h)
	{
		echo $this->fatos[$h][0];echo " | ";
		echo $this->fatos[$h][1];echo " | ";
		echo $this->fatos[$h][2];echo " | ";
		echo $this->fatos[$h][3];echo " | ";
		echo $this->fatos[$h][4];echo " | ";
		echo $this->fatos[$h][5];echo " | ";
		echo $this->fatos[$h][6];echo " | ";
		echo $this->fatos[$h][7];echo " | ";
		echo "<br>";echo "<br>";						
	}
	
	/**************************************************************************************/
	
	public function listarClausulas()
	{
		echo "CLAUSULAS<br><br>";
		$count = count ($this->clausulas);
		for ($i = 0; $i < $count; $i++) 
		{
			$this->exibirClausulas($i);
		}		
	}
	
	/**************************************************************************************/
	
	public function listarHipoteses()
	{
		echo "HIPOTESES<br><br>";
		$count = count ($this->hipoteses);
		for ($i = 0; $i < $count; $i++) 
		{
			$this->exibirHipoteses($i);
		}	
	}
	
	/*************************************************************************************/
	
	public function listarRegras()
	{
		echo "REGRAS<br><br>";
		$count = count ($this->regras);
		for ($i = 0; $i < $count; $i++) 
		{
			$this->exibirRegras($i);
		}
	}	
		
	/*********************************************************************************/
	
	public function listarFatos()
	{
		echo "FATOS<br><br>";
		$count = count ($this->fatos);
		for ($i = 0; $i < $count; $i++) 
		{
			$this->exibirFatos($i);
		}		
	}
	
	/******************************************************************************/
	
	public function listarArray($aux)
	{
		echo "*** ARRAY ***<br><br>";
		$c = count ($aux);
		for ($i=0;$i<$c;$i++) 
		{
			echo $aux[$i]."<br>";
		}
	}
	
	/*******************************************************************************
	 APRESENTARESPOSTAS()
	 
	 Lista as respostas obtidas pelo motor de inferência, na IHM ou entrega a 
	 um outro programa. Os campos da clausula validada como resposta, estão 
	 disponíveis para outros usos. 
	 *******************************************************************************/
	
	public function apresentarRespostas()
	{		
		$c = count ($this->respostas);
		if ($c == 0)
		{
			
			$linha='Location: mensagem.php?titulo=Respostas&mensagem='; 
			$linha=$linha.'Nothing can be concluded with data provided !!!';
			header ($linha);
			return;
		}
		$linha='Location: mensagem.php?titulo=Respostas&mensagem='; 
		for ($i = 0; $i < $c; $i++) 
		{
			$h = $this->respostas[$i];
			$linha = $linha.$this->clausulas[$h][6]."<br>"; 
		}		
		header ($linha);
	}	
}

?>
