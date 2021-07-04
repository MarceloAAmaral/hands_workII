<?PHP 
class Metodos{	
	var $Sec_cont_id;
	var $Dados;
function getListaEventos(){
		$layout = "";
		$tabela = 'evento,img';
		$campos = '';
#filtro		
		if($this->Dados['url']['estrutura']['sec'] == 'resultados'){	
			$this->Dados['filtros']['evento_data']= " < CURDATE()";
		}else{			
			$this->Dados['filtros']['evento_data']= " >= CURDATE()";
		}
		$filtros = $this->Dados['filtros'];
		$order = "";

		if($dados_tabela = consultaDadosTabela($tabela,$campos,$filtros,$order)){			
			$class_layout = new estrutura_layout;
			$class_layout->Dados = $this->Dados;
			$class_layout->Dados['grupos']= $class_layout->consultaGrupos($this->Dados['estrutura']['sec_cont_grupo_id']);
				for($b=0;$b<count($dados_tabela);$b++){
					$class_layout->Dados['dados'] = $dados_tabela[$b];
					$layout .= $class_layout->estrutura_layout();
				}
		}
		return $layout;	
}

function status_inscr(){
	global $dados;
	$atleta = explode(" ",$dados['dados']['atleta']);
	$atleta = ucfirst(strtolower($atleta[0]));
	if(isset($dados['dados']['movimento_status_id']) AND !empty($dados['dados']['movimento_status_id'])){
		if(!empty($dados['dados']['erros'])){
			$layout = erro_processamento($dados['dados']);
		}else{
		$layout = "<div class='item-param'>";	
		$layout .= "<article><b>$atleta</b>, ficamos contentes em saber que vais participar! Confira abaixo os dados de sua inscrição.<br>";	
		$layout .= "<br>Modalidade: <b>".$dados['dados']['modalidade']."</b><br>";
		$layout .= "Evento: <b>".$dados['dados']['evento_titulo']."</b>";
		$layout .= "<br>Percurso: <b>".$dados['dados']['perc_titulo']."</b>";
		$layout .= "<br>Opção de pagamento: <b>".$dados['dados']['movimento_origem_titulo']."</b><br>";
		$layout .= "Total: <b>".mascaraDados('moeda',$dados['dados']['movimento_total_vlr'])."</b><br>";
		if($dados['dados']['movimento_origem_id']==='2'){
		$layout .= "Link para impressão do boleto:<b><a target='_blank' href='".$dados['dados']['link_boleto']."'> imprimir boleto</a></b><br>";	
		}
		$layout .= "</article>";
#status		
		$layout .= "<article>Status: <b>{$dados['dados']['movimento_status_titulo']}</b>";
		if($dados['dados']['movimento_status_id'] =='1'){	
		$layout .= "<br> Sua inscrição ainda <b>NÃO</b> está confirmada em nosso servidor.";
		if($dados['dados']['movimento_origem_id'] =='2' OR $dados['dados']['movimento_origem_id'] =='3'){
		$layout .= 	" Assim que recebermos a confirmação da instituição financeira, enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição com todos os dados para sua conferência.<br>";
		// $layout .=  statusTransacao($dados);
	}
	if($dados['dados']['movimento_origem_id'] =='1'){
	$layout .= 	" Lembre-se de enviar seu comprovante de depósito para nós. Assim que recebermos enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição.<br>  ";
		}
	if($dados['dados']['movimento_origem_id'] ==='4' OR $dados['dados']['movimento_origem_id'] ==='5'){
		$layout .= " Dependemos da vista e confirmação do agenciador e do Organizador do evento. Assim que recebermos este retorno deles enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição.<br> ";	
	}
	if($dados['dados']['movimento_origem_id'] ==='6'){
	$layout .= 	" Lembre-se de enviar seu comprovante de transferência para nós. Assim que recebermos enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição.<br>  ";	
	}
	}elseif($dados['dados']['movimento_status_id'] === '2'){
		$layout .= "<p>Sua inscrição está confirmada em nosso servidor.";
		if($dados['dados']['email_confirmacao'] ==='0'){
			$layout .=" Você deve ter recebido um e-mail de confirmação da sua inscrição no <b>{$dados['dados']['email']}</b>. Se isto ainda não ocorreu  por gentileza solicitar reenvio.</p>";
		}
	}elseif($dados['dados']['movimento_status_id']==='3'){
		$layout .= "<br><br><p> Sua inscrição ainda <b>NÃO</b> está confirmada em nosso servidor.</p>";
		if($dados['dados']['movimento_origem_id'] ==='1'){
		$layout .= 	"<p>Lembre-se de enviar seu comprovante de depósito para nós. Assim que recebermos enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição.<br> </p> ";
		}
		if($dados['dados']['movimento_origem_id'] =='2' OR $dados['dados']['movimento_origem_id'] =='3'){
		$layout .= 	" Assim que recebermos o OK da instituição financeira, enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição com todos os dados para sua conferência.<br>";
		}
	}	
	$layout .= "<br>Se estiver com dúvidas entre em contato pelo e-mail <b>contato@maissport.com.br</b> que estaremos de prontidão para atendê-lo.<br>Grande abraço,<br>MaisSport Eventos Esportivos <br>";
	$layout .= "</article></div>";
}
	}else{
		
	}
	
return $layout;
}	
function getPgto(){
	global $dados;
	$layout = '';
	
	if(isset($dados['dados']['inscritos_id'])){
		
			if($dados['dados']['movimento_status_id'] !== "2"){	
			$subgrupo = consultaDadosTabela('sec_cont_subgrupo','sec_cont_subgrupo_id',array('subgrupo'=>$dados['dados']['movimento_origem']),'');			
			$layout .= estrutura_subgrupo(consultaSubgrupos($subgrupo[0]['sec_cont_subgrupo_id']));
			
			/*
				
				
			//$href = $dados['href'].$dados['dados']['inscritos_id'];
			//header("Location:$href");			
			*/
			}else{
				print "<br> Pagamento com Status de confirmado. Dúvidas favor entrar em contato com a organização.<br>";
			}	
			
	}

	return $layout;
}
function erro_processo(){
	global $dados;	
	$layout = erro_processamento($dados['dados']);
	return $layout;
}

function getInscricao(){
	$layout = '';
	$tabela = 'inscricao,evento,inscricao_modalidade,perc,modalidade';
	$campos = '';
	$filtros = $this->Dados['filtros'];
	$order = "";
	if($dados_tabela = consultaDadosTabela($tabela,$campos,$filtros,$order)){			
			$class_layout = new estrutura_layout;
			$class_layout->Dados = $this->Dados;
			$class_layout->Dados['grupos']= $class_layout->consultaGrupos($this->Dados['estrutura']['sec_cont_grupo_id']);
				for($b=0;$b<count($dados_tabela);$b++){
					$class_layout->Dados['dados'] = $dados_tabela[$b];
					$layout .= $class_layout->estrutura_layout();
				}
		}
	return $layout;
}
function getDados(){
	$form =  $this->Dados['filtros']['form'];
	$dados_form = consultaDadosTabela('form','',array('form'=>$form),'');
	$tabela_form = explode(',',$dados_form[0]['tabela_form']);
	$Db = new Db;			
	for($a=0;$a<count($tabela_form);$a++){		
		if(isset($this->Dados['post'][$tabela])){
			$Db->Tabela = $tabela = $tabela_form[$a];
			foreach($this->Dados['post'][$tabela] as $id => $dados){	
			$Db->Id = $id;
			$Db->Campos = $dados;				
				if($Db->grava_dados()){
					
				}else{
					exit;
				}
			}
		}	
	}
	header("Location:{$this->Dados['url']['href']}");
}
function getUsuario(){
	if(!isset($this->Dados['usuario']['usuario_id'])){
		print "Ops! parece que você não está logado. A partir deste ponto é importante que esteja logado, para sua segurança e a de seus dados.
		 Por gentileza, acesse o menu principal, seção USUÁRIO, para logar-se e fazer parte desta comunidade de amantes do esporte. 
		 <br> Aloha!";
		 EXIT;
	}
}
function getAtletavinculado(){
	$tabela = 'atleta';
	$campos = '';
	$filtros = array('usuario_id'=>$this->Dados['usuario']['usuario_id'];
	$order = "";
	if($dados_tabela = consultaDadosTabela($tabela,$campos,$filtros,$order)){
     	$this->Dados['dados'] = $dados_tabela;
	}else{
		print "Pois é! parece que nenhuma atleta está vinculado ao seu usuário. Até você está de fora desta!:( .<br>
		Vamos resolver isso clicando em  Inscrever novo atleta.<br>
 		Confia, que vai dar tudo certo! ;) ";
	}
}
function getAtleta(){
	if(isset($this->Dados['dados']['atleta_id']){
		$tabela = 'atleta';
		$campos = '';
		$filtros = array('atleta_id'=>$this->Dados['dados']['atleta_id']);
		$order = "";
		if($dados_tabela = consultaDadosTabela($tabela,$campos,$filtros,$order)){
			$this->Dados['dados'] = $dados_tabela[0];
		}
	}	
}
}