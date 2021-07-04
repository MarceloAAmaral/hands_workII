<?PHP

class Pagamentos{
		
function verificaPgto($inscritos_id){
	print "<br> verifica pagamento ".$inscritos_id;
	if(!empty($inscritos_id)){
		if($mov = consultaDadosTabela('movimento','movimento_id,movimento_origem_id,movimento_status_id',array('inscritos_id'=>'21'),'')){
			if($mov[0]['movimento_status_id'] !== '0'){
				$status = consultaDadosTabela('movimento_status','movimento_status',array('movimento_status_id'=>$mov[0]['movimento_status_id']),'');
			print "<br> Já existe um movimento financeiro em andamento com status".$status[0]['movimento_status']." para esta inscrição. Por gentileza entrar em contato com a organização do evento. <br>";
			exit;	
			}else{
				return $mov[0]['movimento_id'];
			}
		}
	}
	
}
public function calculaDesconto($dados){
	$desc = 0;		
	if(isset($dados['cod_voucher']) AND !empty($dados['cod_voucher'])){
		$arr_desc = consultaDadosTabela('desconto','',array('cod_voucher'=>$dados['cod_voucher']),'');
		if(!empty($arr_desc[0]['desconto_taxa'])){
				$desc=$dados['inscricao_vlr']*($arr_desc[0]['desconto_taxa']/100);
			}
			if(!empty($arr_desc[0]['desconto_vlr'])){
				$desc=$desc+$arr_desc[0]['desconto_vlr'];
			}
		}else{
		$idade = calculaIdade($dados['nascimento'],$dados['evento_data']);
		if($idade>='65'){
			$desc=$dados['inscricao_vlr']*0.5;
		}
	}
	return $desc;
}
function taxa_adm($base,$org_id){
	$dados = consultaDadosTabela('organizacao','',array('organizacao_id'=>$org_id),'');
	$return = 0;
		if(!empty($dados[0]['taxa_transacao'])){
			 $return = $base*($dados[0]['taxa_transacao']/100);
		}
		if(!empty($dados[0]['tarifa_transacao'])){
			$return = $return+$dados[0]['tarifa_transacao'];
		}
		if(!empty($dados[0]['tarifa_fixa'])){
			$return = $return+$dados[0]['tarifa_fixa'];
		}
	return $return;
}
function deposito($dados){
	// email($dados);
	/*
	$arr_dados = consultaDadosTabela('inscritos,inscricao,evento,atleta','',array('inscritos_id'=>$dados['inscritos_id']),'');
		foreach($arr_dados[0] as $campo => $dado){
			if($campo !== 'data'){
			$dados[$campo]=$dado;
			}
		}
	*/
	//$dados['desconto'] = calculaDesconto($dados);
	$dados['movimento_vlr'] = $dados['inscricao_vlr'];
	//$dados['movimento_taxa_adm'] = taxa_adm($dados['movimento_vlr'],1);	
	$dados['movimento_tipo_id'] = 2; 
	$dados['movimento_natureza_id'] = 1;
	$dados['movimento_total_vlr'] = $dados['movimento_vlr']-($dados['desconto']);		
	$dados['movimento_status_id'] = 3;
	return  $dados;			
}
function boleto($dados){	
	include "classe_pagseguro.php";
	$pagseguro = new Pagseguro;
	$pagseguro->setAmbientePagueSeguro('sandbox');
	$pagseguro->paramsPagSeguro($dados);
}
function cartao($dados){	
	include "classe_pagseguro.php";
	$pagseguro = new Pagseguro;
	$pagseguro->setAmbientePagueSeguro('sandbox');
	$pagseguro->paramsPagSeguro($dados);
}
public function email($dados){
		require "classe_email.php";
		$envia_email = new Email;
		$arr = consultaDadosTabela('atleta,inscritos,evento','',array('inscritos_id'=>$dados['inscritos_id']),'');
		$dados = $arr[0];
			$assunto = "Pagamento de {$dados['atleta']}, id {$dados['inscritos_id']}";
			$texto = "Evento:".$dados['evento_titulo']."<br>";
			$texto .= "Atleta:".$dados['atleta']."<br>";
			$texto .= "Id inscrição:".$dados['inscritos_id']."<br>";
			$envia_email -> smtp_envio('marceloamaralfpolis@gmail.com',$assunto,$texto);
	}
}