	<?PHP 
class Db {
    var $Campos;
	var $Campo_retorno;
    var $Tabela;
	var $Id;
	var $Filtros;
	var $Acao;
	var $Form;
	var $Db = 'bs';
	var $Erro = '';

	function grava_dados(){
#define a hora e data de gravação
		date_default_timezone_set('America/Sao_Paulo');
		$data_hora = date("Y-m-d H:i:s");
		$this->Campos['data']= $data_hora;
		$campos = "";
		$dados = "";
#alimenta o id do registro ant.		
		if(isset($this->Id_retorno){
			foreach($this->Id_retorno as $campo=>$dado){
				$this->Campos[$campo] = $dado;
			}
		}
#identifica a Ação		
		if(isset($this->Id)){
			if($this->Id == '0'){
				$this->Acao = 'incluir';
				$sql = "INSERT INTO ";
			}else{
				$this->Acao = 'editar';
				$sql = "UPDATE ";
			}				
		}else{
			print "<br> Erro ao informação a ação no Db<br>";
			exit
		}
		 $sql .= "`{$this->Db}`.`{$this->Tabela}` ";
#valida campos		 
			foreach($this->Campos as $campo => $dado){
				if(existeColuna($this->Tabela,$campo)){
					$campos .= "`$campo`,";
					$dados .= "'$dado',";		
				}
			}
			$campos = trim($campos,',');
			$dados = trim($dados,',');
			$sql .= "($campos)VALUES($dados)";
			echo "<hr># SQL incluir = $sql<hr>";
#executa a ação dos dados no banco		
		 if(executaSQL($sql)){
			$sql_id = "SELECT `{$this->Tabela}_id` FROM `$db{$this->Tabela}` WHERE `data`='$data_hora'";
					if($return_sql_id = executaSQL($sql_id)){
						$this->Id_retorno["{$this->Tabela}_id"] = $return_sql_id[0]["{$this->Tabela}_id"];
					}	
		 }else{
			 print $this->Erro;
		 }
		 
		 
		 
		 
		
		if($this->Form =='finalizar' OR $this->Form=='pagamento'){
#acessa a classe de pagamento				
			include "classe_pagamentos.php";	
			$pgto = new Pagamentos;
#verifica se existe movimento para esta inscrição
			$this->Id = $pgto->verificaPgto($this->Campos['inscritos_id']);		
			if(isset($this->Id)){
				$this->Acao = 'editar';
			}			
		}
		if($this->Form=='finalizar'){
#origem do pagamento
			if(isset($this->Campos['movimento_origem_id'])){
				//print "<br> sim tenho movimento_origem_id = {$this->Campos['movimento_origem_id']}<br>";
					$origem = consultaDadosTabela('movimento_origem','movimento_origem',array('movimento_origem_id'=>$this->Campos['movimento_origem_id']),'');
					$this->Campos = $pgto->{$origem[0]['movimento_origem']}($this->Campos);
			}
			
		}
		
		
		
	
	}
	function grava_inscrever(){	
#faixa etária
			$arr = consultaDadosTabela('evento','evento_data',array("evento"=>$this->Campos[$this->Tabela]['evento']),'');
				if($idade = calculaIdade($this->Campos['atleta']['nascimento'],$arr[0]['evento_data'])){
					$this->Campos[$this->Tabela]['faixa_etaria_id']= consultaFaixa_etaria($idade);
				}
			return	$this->grava_dados();
		
	}
	function grava_movimento(){
		$dados_tabela = consultaDadosTabela('movimento','movimento_id',array('inscritos_id'=>$this->Campos['inscritos']['inscritos_id']),'');
		
		if(count($dados_tabela)>0){
			$this->Acao = 'editar';
		}else{
			$this->Acao = 'incluir';
		}
		return  grava_dados();
	}
	function grava_movimento_lancamento(){
		$dados_tabela = consultaDadosTabela('movimento_lancamento','movimento_lancamento_id',array('movimento_id'=>$this->Campos['movimento_id']),'');
		if($this->Form == 'inscrever'){
			$dados_inscricao = consultaDadosTabela('inscricao','inscricao_vlr',array('inscricao_id'=>$this->Campos['inscritos']['inscricao_id']),'');				
			$this->Campos['vlr'] = $dados_inscricao[0]['inscricao_vlr'];
			$this->Campos['cod_lanc'] = 'inscr';
			if(count($dados_tabela)>0){
				foreach($dados_tabela as $campos){
					if($campos['cod_lanc']=='inscr'){
						$this->Acao = 'editar';
					}else{
						$this->Acao = 'incluir';
					}
				}
			}else{
				$this->Acao ='incluir';
			}		
			if($id_return = grava_dados()){
				$dataRef = consultaDadosTabela('evento','evento_data',array('evento_id'=>$this->Campos['inscritos']['evento_id']),'');
				$idade = calculaIdade($this->Campos['insritos']['nascimento'],$dataRef);
				
				if($idade>=60){
					$this->Campos['cod_lanc']='idoso';
					$this->Campos['vlr'] = $this->Campos['vlr']*0.5;
					if(count($dados_tabela)>0){
						foreach($dados_tabela as $campos){
							if($campos['cod_lanc']=='idoso'){
								$this->Acao = 'editar';
							}else{
								$this->Acao = 'incluir';
							}
						}
					}
					return grava_dados();
				}else{
					return $id_return;
				}
			}
		}
	}
	function executaSQL($sql) {
    $result = $mysqli->query($sql);
    if ($result) {
        if (isset($result->num_rows) > 0) {
			$object = array();			
            $x = 0;
            $num_fields = $result->field_count;
            $finfo = mysqli_fetch_fields($result);
            while ($row = $result->fetch_array()) {
                for ($j = 0; $j < $num_fields; $j++) {
                    $name = $finfo[$j]->name;
                    $object[$x][$name] = $row[$name];
                }
                $x++;
            }
            return $object;
        }else{
			return TRUE
		}		
    } else {     
        $this->Erro = "<br><hr>Erro na execução da Query: SQL =  $sql";
        $this->Erro .= "<br>Erro = {$mysqli->error}<br>";
		// echo $return_err;
    }
}
}
