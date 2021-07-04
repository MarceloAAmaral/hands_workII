<?PHP
$layout = "";

if (!is_null($get)) {

	foreach($get as $key => $dado){		
		if(!empty($dado)){			
			if($key=='filtros'){				
				$dados['url']['estrutura'][$key] = explode('/',$dado);			
			}else{
				$dados['url']['estrutura'][$key] = $dado;		
			}
		}
	}
} else {
        $dados['url']['estrutura']['sec'] = "home" ;
}

#segurança
$dados['filtros']['atleta_id'] = '';

include "inc/classes/classe_estrututa_main.php";
$estrutura_main = new estruturaLayout;
$estrutura_main->dados($dados); 
$layout .= $estrutura_main->inicio();


echo $layout;