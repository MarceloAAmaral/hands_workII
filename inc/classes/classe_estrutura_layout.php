<?PHP
class Estrutura_layout{
	var $Sub_id;
	var $dados;
	var $dado;
	var $DadosMetodos;
	var $dados_parametros;
	public $grupos;
	var $Subgrupos;
	public $params;
	public $tag;
	public $tagFilha;
	public $layout;
	
function sec_cont(){
    $layout = "";	
	$tabela = "sec_cont";
	$campos = "";	
	$filtros = array("ativo"=>'1',"sec_cont_id"=>$this->Dados['filtros']['sec_cont_id']);
	$order = array("sec_cont_id"=>$this->Dados['filtros']['sec_cont_id']);	
	$retorno_dados_sec_cont = consultaDadosTabela($tabela,$campos,$filtros,$order);
	if($retorno_dados_sec_cont){		
		for ($a = 0; $a < count($retorno_dados_sec_cont); $a++) {
			$mtd = new Metodos;
#dados
			$dados = $retorno_dados_sec_cont[$a];			
#grupos de parametros
			$this->setGrupos($dados['sec_cont_grupo_id']);		
			$sec_cont = $dados['sec_cont'];	
			$layout .= "<div id='sec_cont_{$sec_cont}' ";
			if(!empty($dados['classes'])){
				$layout .=" class='{$dados['classes']}'";	
			}
			$layout .= ">";	
#header
			if(isset($dados['sec_cont_header'])){
				$layout .= "<div class='sec_cont_header'>{$dados['sec_cont_header']}</div>";
			}
			
			$layout .= "<div class='container-items'>"; 
#metodos
			if(!empty($dados['metodos'])){				
				$arr_metodos = explode(',',$dados['metodos']);
				foreach($arr_metodos as $metodo){ 
					$mtd->$metodo();
				}		
			}
			if($return_dados=$mtd->getDadosMetodo()){
				for($b=0;$b<count($return_dados);$a++){
					$this->setDados($return_dados[$a]);
					$layout .= $this->estrutura_layout();
				}
			}else{
				$layout .= $this->estrutura_layout();
			}
		
			$layout .= "</div>"; 
		}
	}		
	return $layout;
}		

function estrutura_layout(){
	$layout='';
# a estrutura de dados tem uma tag container, como é o caso de form
	if(isset($this->Dados['container'])){
		$layout .= "<".$this->Dados['container']['tag'];
		foreach($this->Dados['container']['parametros'] as $param=>$dado){
			$layout.= " $param='$dado'";
		}
	}
	$layout .= $this->estrutura_grupos();
#fecha o container e trata suas especificidades	
	if(isset($this->Dados['container'])){
		if($this->Dados['container']['tag'] == 'form'){
			$layout .= " <input type='submit' name='acoes' value='{$this->Dados['container']['botao']}'>";
		}
		
		$layout .= "</".$this->Dados['container']['tag'].">";		
	}	
	return $layout;		
}
public function getGrupos(){
	return $this->grupos;
}
public function setGrupos($dados){
	
	if(!empty($dados)){
		$tabela = 'sec_cont_grupo';
		$campos = '';
		$filtros = array("sec_cont_grupo_id"=>$dados);
		$order = array("sec_cont_grupo_id"=>$dados);
		if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){
			$this->grupos = $retorno;
		}				
	}
}
function estrutura_grupos(){
	$layout = '';	
	if($grupos = $this->getGrupos()){
		for($a=0;$a<count($grupos);$a++){
		$dados = $grupos[$a];	
		$this->setParams($dados['params_id']);
		$layout .= "<div id='grupo_{$dados['grupo']}'";
		if(!empty($dados['classes'])){
			$layout .= " class='{$dados['classes']}' ";
		}
		$layout .=">";
		if(!empty($dados['grupo_titulo'])){
		$layout .= "<h3>{$dados['grupo_titulo']}</h3>";
		}
		if(!empty($dados['descr'])){
		$layout .= "<article class='descr'>{$dados['descr']}</article>";
		}	
# acaso o grupo tenha uma requisição de dados		
		if(!empty($dados['grupo_filtros'])){
			if(!empty($this->Dados['dados'][$dados['grupo_filtros']])){
				$tabela = $dados['grupo_tabela'];
				$campos = '';
				$filtros = array($dados['grupo_filtros']=>$this->Dados['dados'][$dados['grupo_filtros']]);
				$order = '';
				$retorno = consultaDadosTabela($tabela,$campos,$filtros,$order);
				for($b=0;$b<count($retorno);$b++){
					$this->Dados['dados'] = $retorno[$b];
					$layout .=	$this->estrutura_param($dados['params']);	
				}
#subgrupos 
				if(isset($dados['subgrupos'])){
					$layout .= $this->estrutura_subgrupo($dados['subgrupos']);
				}
			}
		}else{
			$layout .=	$this->estrutura_param();
#subgrupos 
			if(isset($dados['subgrupos'])){
				$layout .= $this->estrutura_subgrupo($dados['subgrupos']);
			}
		}
		$layout .= "</div>";
	}
	}
		
	return $layout;	
}
public function setParams($dados){
	if(!empty($dados)){	
		$tabela = 'param';
		$campos = '';
		$filtros = array('param_id'=>$dados);
		$order = array('param_id'=>$dados);
			if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){
				$this->params = $retorno;
			}
	}
}
public function getParams(){
	return $this->params;
}
function estrutura_param(){	
	$layout ="";
	if($params = $this->getParams()){
		for($f=0;$f<count($params);$f++) {
			$this->setDadosParametro($this->Dados);
			$dados_param= $params[$f];
			$param = $dados_param['param'];
			$this->setParam($dados_param['param']);
			$this->setTag($dados_param['tag'],$dados_param['html']);
			$this->setTagFilha($dados_param['tag_filha']);
			$this->setParametros();
			$this->setDado();
			
#layout			
				$layout .= "<div data-param='$param'";
				if(!empty($dados_param['classes'])){ 
					$layout .= " class='{$dados_param['classes']}'";
				}
				$layout .= ">";
				if(!empty($dados_param["titulo"]) AND $dados_param['html'] !== 'img'){
					$layout .= "<span>{$dados_param["titulo"]}</span>";		
				}
#dúvidas na execução do trecho abaixo 
/*				
				if($param == 'tabela'){
					$param = $this->Dados['dados'][$param];
					$dados_param = consultaDadosTabela('param','',array('param'=>$param),'');
					$dados = $dados_param[0];						
				}
#clone
				if(!empty($dados_param['clone'])){
					$param = $dados_param['clone'];
				}
*/
#Dado = define como dado(se existir)será criado e apresentado: pela tag HTML ou por input 
				
				
#obter dados a nível de tag									
				//$dados_param['param'] = $dados_param['tabela'];	
				if(!empty($dados_param['campo'])){
					$tabela = $dados_param['tabela'];
					$campos = $dados_param['campo'];
					$filtros = $this->Dados['filtros'];
					if(!empty($dados_param['filtro'])){
						$filtros[$dados_param['filtro']] = $this->Dados['dados'][$dados_param['filtro']];
					}
					
					$order = '';		
					if($dados = consultaDadosTabela($tabela,$campos,$filtros,$order)){
						
						if($this->getTagFilha()){
							$this->setDadosParametro($dados);
							$this->estruturaTag();														
						}else{
							for($a=0;$a<count($dados);$a++){
								$this->setDadosParametro($dados[$a]);
								$layout .= $this->estruturaTag($dados_param['tag'],'');
							}
						$this->dados_parametro = $dados_param['dados'];
						$layout .= $this->estruturaTag($dados_param['tag'],$dados_param['tagfilha']);
					}
					}
#estruturaTag
					
				}else{
#estruturaTag
					$layout .= $this->estruturaTag();
				}				
					
				$layout .= "</div>";
		}
	}

	return $layout;
}
function estruturaTag(){
	$this->layout("<".$this->getTag());
	$this->estruturaParametros();
	$this->layout(">");
	if(getTagFilha()){
		$this->setTag = getTagFilha();
		if($dados = getDadosParametro()){
			for($a=0;$a<$dados;$a++){	
				foreach($dados[$a] as $valores){
					$this->setDadosParametro($valores);
					$this->setParametros();
					$layout .= estruturaTag();
				}
			}
		}else{
			$layout .= $this->estruturaTag();
		}
	}
		/*		
#data-icon	
		if(!empty($dados_param['data-icon'])){
			if(!empty($dados_param['tabela']) AND isset($dados_param['dados'][$dados_param['tabela']])){
			$layout .= " data-icon='{$this->Dados['dados'][$dados_param['tabela']]}'";
			}else{
			$layout .= " data-icon='{$dados_param['param']}'";
			}
		}
		
#data-param
		if(!empty($dados_param['data-param'])){
			$layout .= " data-{$dados_param['tabela']}='{$this->Dados['dados'][$dados_param['tabela']]}'";
		}		
		$layout .= ">";
*/
	

# fim do laço da tag
	if($tag != 'input'){
		if(!empty($this->dado)){
		$layout .= "{$this->dado}";
		}
		$layout .= "</$tag>";
	}
#label	
	if($dados['label']){
		$layout .= "<label for='{$dados['dado']}'>{$dados['dado'][$dados['tabela'].'_titulo']}</label>";	
	}	
	return $layout;
}
function setParametros() {
    $layout = "";
	$tag = $this->getTag();
    $array = array();
		switch ($tag) {
			case "a":
				$array['parametros']['href'] = $this->Dados['url']['href'];
				if(isset($this->Dados['url']['alvo']['param_url'])){
					foreach($this->Dados['url']['alvo']['param_url'] as $param_alvo){
						$array['parametros']['href']=$array['parametros']['href']."/".$this->Dados['dados'][$param_alvo];
					}
				}				
				$array['parametros']['target'] = $dados['target'];
				if(!empty($dados['ancora'])){
					$array['dado']=$dados['ancora'];
				}
				break;
			case "img":
				$array['parametros']['src'] = $this->Dados['url']['inicio'].'/img/'.$this->Dados['dados']['src'];
				$array['parametros']['title'] = $this->Dados['dados']['title'];
				$array['parametros']['alt'] = $this->Dados['dados']['alt'];
				break;
			case "input":			
				$array['parametros']['name'] = $dados['tabela_param']."[{$dados['param']}]";
				$array['parametros']['type'] = $dados['type'];
				$array['parametros']['placeholder'] = $dados['placeholder'];
				 if(isset($this->Dados['dados'][$dados['param']])){
					$array['parametros']['value'] = $this->Dados['dados'][$dados['param']];
					}
				$array['parametros']['required'] = $dados['required'];
				$array['parametros']['checked'] = $dados['checked'];
				$array['parametros']['min'] = $dados['min'];
				$array['parametros']['max'] = $dados['max'];
				$array['parametros']['autocomplete'] = $dados['autocomplete'];
				//$array['parametros']['pattern'] = $dados['pattern'];
				$array['parametros']['disabled'] = $dados['disabled'];
				$array['parametros']['maxlength'] = $dados['maxlength'];				
				 if(isset($dados['dados'][$dados['param'].'_id'])){
					$array['parametros']['id'] = $this->Dados['dados'][$dados['param'].'_id'];
					}
				break;
			case "button":
				$array['parametros']['type'] = $dados['type'];
				break;
			case "iframe":
				$array['parametros']['src'] ='';
				$array['parametros']['frameborder'] = '';
				break;
			case "select":
				$array['parametros']['name'] = $dados['tabela_param'];
				if(isset($this->Dados['dados']["{$dados['tabela_param']}_id"])){
				$array['parametros']['name'] =$array['parametros']['name']."[{$this->Dados['dados'][$dados['tabela_param'].'_id']}]";
				}else{
					$array['parametros']['name'] =$array['parametros']['name']."[0]";
				}
				$array['parametros']['name'] = "[{$dados['param']}_id]";
				$array['parametros']['required'] = $dados['required'];
				break;
			case "option":
				$array['parametros']['value'] = $dados['dados'][$dados['param']."_id"];
				$array['parametros']['id'] =  $this->Dados['dados'][$dados['param']."_id"];
				break;
			case "textarea":
				$array['parametros']['name'] = $dados[$dados['param']];
				$array['parametros']['placeholder'] = $dados['placeholder'];
				$array['parametros']['required'] = $dados['required'] ;
				$array['dado'] =$this->Dados['dados'][$dados['param']];
				break;
			case "form":
			    $array['parametros'] = $this->getParametrosForm();
				/*$array['parametros']['action'] = $this->Dados['dados']['action'];
				$array['parametros']['method'] = $this->Dados['dados']['method'];
				$array['parametros']['enctype'] = $this->Dados['dados']['enctype'];*/
				break;
		}
		$this->parametros = $array['parametros'];
		
    return $layout;
}
public function getParametros(){
	return $this->parametros;
}
public function estruturaParametros(){
	
	$dados = $this->getParametros();
	foreach($dados as $key => $param_dado){
			if(!empty($param_dado)){
				$this->layout($key='$param_dado');	
			}			
	}
}
public function layout($l){
	$this->layout .= $l;
}
function consultaSubgrupos($subgrupos_id){
		 $dados_subgrupos = consultaDadosTabela('sec_cont_subgrupo','',array("sec_cont_subgrupo_id"=>$subgrupos_id),'');
				for($c=0;$c<count($dados_subgrupos);$c++){
					if(!empty($dados_subgrupos[$c]['params_id'])){
					 $dados_subgrupos[$c]['params'] = consultaDadosTabela('param','',array('param_id'=>$dados_subgrupos[$c]['params_id']),array('param_id'=>$dados_subgrupos[$c]['params_id']));		
					}
				}
				return $dados_subgrupos;
}
function estrutura_subgrupo($dados_subgrupos){
		$layout = "";
		for($a=0;$a<count($dados_subgrupos);$a++){
			$dados = $dados_subgrupos[$a];
			$layout .="<div id='subgrupo_{$dados['subgrupo']}'";
			if(!empty($dados['classes'])){
				$layout .=" class='{$dados['classes']}' ";
			}
			$layout .=">";
			if(!empty($dados['subgrupo_filtros'])){
			if(!empty($this->Dados['dados'][$dados['subgrupo_filtros']])){
				$tabela = $dados['subgrupo_tabela'];
				$campos = '';
				$filtros = array($dados['subgrupo_filtros']=>$this->Dados['dados'][$dados['subgrupo_filtros']]);
				$order = '';
				$retorno = consultaDadosTabela($tabela,$campos,$filtros,$order);
				for($b=0;$b<count($retorno);$b++){
					$this->Dados['dados'] = $retorno[$b];
					$layout .=	$this->estrutura_param($dados['params']);	
				}
			}
		}else{
			$layout .=	$this->estrutura_param($dados['params']);
		}
			$layout .= "</div>";
		}
	return  $layout;
}
function estruturaTagOption($dados_param){
#estruturaTag option
	$dados_param['tag']='option';
	$layout .= "<{$dados_param['tag']} value=''>{$dados_param['titulo']}</{$dados_param['tag']}>";
	$qtde = count($dados_param['dados']);
	unset($dados_param['dados']);
	for($a=0;$a<$qtde;$a++){	
		foreach($dados_param[$a] as $param_dado => $dado){
			$dados_param['dado'] = $dado;
			$layout .= $this->estruturaTag($dados_param);
		}
	}
	return $layout;
}
public function ola(){
	return "ola mundo";
}
function getParametrosForm(){
	$tabela = 'form';
	$campos = '';
	$filtros = array('sec_cont_id'=>$this->Dados['filtros']['sec_cont_id']);
	 if($dados_tabela = consultaDadosTabela($tabela,$campos,$filtros,$order)){		
		 if($arr[0]['alvo']=='acoes'){
			$dados_tabela[0]['action']="{$this->Dados['url']['inicio']}/{$this->Dados['url']['estrutura']['sec']}/acoes/{$arr[0]['form']}";	 
			
		 }elseif(isset($this->Dados['url']['href'])){
			$dados_tabela[0]['action']= $this->Dados['url']['href']; 
			
		 }
		 if(isset($this->Dados['url']['href'])){
			 $dados_tabela[0]['botao'] = 'avançar';
		 }
				 
		  return  $dados_tabela[0];
	 }	
		
			
}
public function getDadosMetodo(){
	if(isset($this->dadosMetodos)){
		return $this->dadosMetodos;
	}
}
public function setTag($tag,$html){
	if(isset($this->Dados[$this->getParam()])){
		if(isset($this->Dados['acao']) AND $this->Dados['acao'] != 'editar'){
			$this->tag = $html;	
		}
	}		
}
public setTagFilha($t){
	
	$this->tagFilha = $t;	
	
}
public getTagFilha(){
	if(!empty($this->tagFilha)){
	return $this->tagFilha;
	}
}
public function setParam($p){
	$this->param = $p;
}
public function getParam(){
	return $this->param;
}
public function getDado(){
	return $this->dado ;
}
public function setDado(){
	$param = $this->getParam();
	if(isset($this->Dados[$param])){
		$this->dado = mascaraDados($dados_param['mascara'],$this->Dados[$param]);						
	}
}
public setDadosParametro($dados){
	$this->dados_parametro = $dados;
}
public function getDadosParametro(){
	return $this->dados_parametro;
}
}