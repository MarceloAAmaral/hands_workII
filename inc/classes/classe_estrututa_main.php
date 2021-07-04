<?PHP
class estruturaLayout{
	public $Sub_id;
	public $dados;
	public $DadosMetodos;
	public $Grupos;
	public $Subgrupos;
	public $secao;
	public function __construct(){
			$this->setSecao();
	}
	public function dados($dados){
		$this->setDados($dados);
	}
	private function setDados($dados){
		$this->dados = $dados;
	}
	private function getDados(){
		return $this->dados;
	}
	
public function inicio(){
		$layout =	"<main>";
		$layout .= $this->estruturaSecao();
		$layout .=	"</main>";	
		return $layout;
	}
private function setSecao(){
	if(isset($this->Dados['url']['estrutura']['sec'])){
		$this->secao = $this->Dados['url']['estrutura']['sec'];
	}else{
		$this->secao = 'home';
	}
}
private function getSecao(){
	return $this->secao;
}
function estruturaSecao(){
	$layout = '';
	$sec = $this->getSecao();
	$tabela = 'sec';
	$campos = '';
	$filtros = array('sec'=>$sec,'ativo'=>'1','admin'=>'0');
	$order = '';
	$retorno_dados_sec = consultaDadosTabela($tabela,$campos,$filtros,$order);
	if(count($retorno_dados_sec)>0){
#classe estruturaLayout
		include "classe_estrutura_layout.php";	
#classe Metodos
		include "classe_metodos.php";	
		for ($a=0;$a<count($retorno_dados_sec);$a++) {
			$dados =$retorno_dados_sec[$a];
			$this->Dados['filtros']['sec_id'] = $dados['sec_id'];
			$layout .= "<{$dados['estrutura']} id='{$dados['sec']}' class='ativo";
#classes			
			if (!empty ($dados['classes'])) {
				$layout .= " {$dados['classes']}>";
			}
			$layout .= "'>";
#identifica subsecao			
			if(!empty($dados['sub_id'])){
				$this->setSubsecao($dados['sub_id']);
				$this->Dados['filtros']['sub_id'] = $dados['sub_id'];				
			}
			$layout .= $this->subsecao();
			$layout .= "</{$dados['estrutura']}>";
		}
	}	
	return $layout;
}	
function setSubsecao($dados){
    $layout = '';
	$tabela = 'sub';
	$campos='';
	$filtros = array('ativo'=>'1','admin'=>'0');
	$order = '';
	
	if(isset($this->Dados['url']['estrutura']['sub'])){
		$filtros['sub']=$this->Dados['url']['estrutura']['sub'];
	}else{
		$filtros['sub_id']=$this->Dados['filtros']['sub_id'];
		$order = array('sub_id'=>$filtros['sub_id']);
	}
	 
	$retorno_dados_sub = consultaDadosTabela($tabela,'',$filtros,$order);
		if(count($retorno_dados_sub)>0){			
			for ($a = 0; $a < count($retorno_dados_sub); $a++) {
#dados			
				$dados =$retorno_dados_sub[$a];
				$sub = $dados['sub'];
				if(!empty($dados['sec_pai'])){
					$sec_pai = consultaDadosTabela('sec','sec',array('sec_id'=>$dados['sec_pai'],''),'');
					$this->Dados['url']['href'] = $this->Dados['url']['href'].'/'.$sec_pai[0]['sec'];
				}				
				$this->Dados['filtros']['sub_id'] = $dados['sub_id'];
#alvo				
				if(!empty($dados['alvo'])){						
				 $this->Dados['url']['href'] = $this->Dados['url']['href']."/".$dados['alvo'];	
				 $param_url_alvo=consultaDadosTabela('sub','param_url',array('sub'=>$dados['alvo']),'');
				 $this->Dados['url']['alvo']['param_url']=explode(',',$param_url_alvo[0]['param_url']);						 
				}else{
				 //$this->Dados['url']['href'] = $this->Dados['url']['href']."/".$sub;	
				}			
#param_url
			if(isset($this->Dados['url']['estrutura']['filtros']) AND !empty($dados['param_url'])){
				$param_url = explode(',',$dados['param_url']);
				foreach($param_url as $ord => $param){
					$this->Dados['filtros'][$param] = $this->Dados['url']['estrutura']['filtros'][$ord];
				}	
			}
				$layout .= "<div id='sub-$sub'";
#estilos
				if(!empty($dados['classes'])){
				$layout .= " class='{$dados['classes']}'";
				}
				$layout .= ">";
				if(!empty($dados['sec_cont_id'])){
					$this->Dados['filtros']['sec_cont_id'] = $dados['sec_cont_id'];
					$layout .= $this->sec_cont();
				}
				$layout .= "</div>";
			}
		}
	return $layout;
}




}