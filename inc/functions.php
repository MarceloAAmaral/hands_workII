<?PHP
function testaBrowserIE() {
    $browser = $_SERVER['HTTP_USER_AGENT'];
//    $lista_navegadores = array('MSIE', 'Firefox', 'Chrome', 'Safari');
////Aqui devemos colocar a lista dos navegadores
//
//    foreach ($lista_navegadores as $valor_verificar) {
//        if (strrpos($navegador_usado, $valor_verificar)) {
//            $navegador = $valor_verificar;
//        }/
//    }
//

    if (preg_match('/^MSIE/', $browser)) {
        return $browser;
    }
}
function listaSubsecao($secao, $natureza) {
    $return = "";
    $sql = "SELECT * FROM `vm_subsecao` WHERE secao='$secao' ORDER BY ordem ASC";
    $result = executaSQL($sql);
    $count = count($result);
    if ($count > 0) {
        for ($a = 0; $a < $count; $a++) {
            $subsecao = $result[$a]['subsecao'];
            $titulo = $result[$a]['label'];
            $id_subsecao = $result[$a]['id_subsecao'];
            $return .= "<div class='box-sw-12 secao subsecao $subsecao'>";
            $id_secao = consultaIdTable('secao', 'secao', $secao, '', '');
            $return .= pegaImagens($id_secao, $natureza, $id_subsecao);
            if ($id_secao === '8') {
                $return .= "<h2>$titulo</h2>";
                $return .= "<nav><a href='index.php?p=ambientes&s=$subsecao' class='link'></a>"
                        . "<span><label>Mais detalhes</label></span></nav>";
                $return .= "<div class='container-galeria'></div>";
                $return .= "<div class='orcamento'>"
                        . "<a href='index.php?p=cliente'>Solicite seu orçamento</a>"
                        . "</div>";
                $return .= "<div class='bg op-06'></div>";
            }
            $return .= article($id_secao, $id_subsecao);

            if ($id_secao === '1') {
                $return .= "<nav><a href='index.php?p=ambientes&s=$subsecao' target='_parent' nofollow=''>mais detalhes</a></nav>";
                $return .= "<div class='bg op-2'></div>";
            }
            $return .= "</div>";
        }
    }
    return $return;
}
function menu() {
	global $db,$url_inicio;
	$layout = "";
	$dados_menu = consultaDadosTabela('sec','',array('menu'=>'1'),'');
	$n_items = count($dados_menu);
	if($n_items>0){
	$layout .= "<nav id='menu_principal'>";
	$layout .= "<div id='logo_secundario' class='item'>
            <a href='{$url_inicio}'><img src='{$url_inicio}img/logo_bsrun_branco.png' alt='BSRUN Assessoria Esportiva' title='BSRUN Assessoria Esportiva'/></a>
        </div>";
	$layout .= "<ul><li class=''><span data-icon='menu' class='bt-menu left'>MENU</span></li>";	
		for($a=0;$a<count($dados_menu);$a++){
			if($dados_menu[$a]['sec'] != 'home'){
				$layout .= "<li class='w{$n_items}'><a href='{$url_inicio}{$dados_menu[$a]['sec']}'>{$dados_menu[$a]['sec_titulo']}</a></li>";    
			}
		}
	$layout .= "</ul></nav>";	
	}
	return $layout;
}
function pesquisaSubsecao($id_secao) {
    $array = array();
    $sql = "SELECT * FROM `vm_subsecao` WHERE id_secao ='$id_secao' ORDER BY ordem";
    $result = executaSQL($sql);
    $count = count($result);
    if (count($count) > 0) {
        for ($a = 0; $a < $count; $a++) {
            foreach ($result[$a] as $key => $value) {
                $array[$a][$key] = $value;
            }
        }
        return $array;
    } else {
        return;
    }
}
function tagEsp($val) {
    $return = "";
    $array = array('input', 'img', 'textarea');
    $key = in_array($val, $array);
    if ($key == TRUE) {
        if ($val === 'textarea') {
            $return .= "></$val>";
        } else {
            $return .= "/>";
        }
    }
    return $return;
}
function consultaId($tag, $id_tag) {
    global $db, $mysqli;
    $return = "";
    $sql = "SELECT `id` FROM `{$db}id` WHERE 1";
    if (!empty($tag)) {
        $sql .= " AND `tag` = '$tag'";
    }
    if (!empty($id_tag)) {
        $sql .= "AND `id_tag` = '$id_tag'";
    }
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $r = fetch_array($result);
        $id = $r[0];
        $return .= "id='$id'";
    }
    return $return;
}
function consultaData($tag, $id_tag) {
    global $db, $mysqli;
    $return = "";
    $sql = "SELECT `param_data` FROM `{$db}param_data` WHERE 1";
    if (!empty($tag)) {
        $sql .= " AND `tag` ='$tag'";
    }
    if (!isset($id_tag)) {
        $sql .= " AND `id_tag` ='$id_tag'";
    }
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $r = fetch_array($result);
        $return .= "data-param='$r[0]'";
    }

    return $return;
}
function consultaFor($tag, $id_tag) {
    global $db, $mysqli;
    $return = "";
    $sql = "SELECT paramfor FROM `{$db}paramfor` WHERE 1";
    if (!empty($tag)) {
        $sql .= " AND `tag` = '$tag'";
    }
    if (!empty($id_tag)) {
        $sql .= " AND `id_tag` = '$id_tag'";
    }

    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $r = fetch_array($result);
        $return .= "for='{$r[0]}'";
    }

    return $return;
}
function obtemId($tabela, $campo, $param) {
    $sql = "SELECT id_$campo from `vm_$tabela` WHERE $tabela = '$param'";
    $query = mysql_query($sql);
    $i = mysql_fetch_array($query);
    $id = $i['id_' . $tabela];
    return $id;
}
function pegaIdSecao($secao) {
    $sql = "SELECT id_secao FROM `vm_secao` WHERE secao = '$secao' AND view =1 ";
    $result = mysql_query($sql);
    $ret = mysql_fetch_array($result);
    if (count($ret) > 0) {
        return $ret[0];
    } else {
        return;
    }
}
function obtemTag($id) {
    $sql = "SELECT tag from tag WHERE id_tag = '$id'";
    $query = mysql_query($sql);
    $i = mysql_fetch_array($query);
    $retorno = $i['tag'];
    return $retorno;
}
function arrayValue($array) {
    foreach ($array as $key => $value) {
        echo $value;
    }
}
function buscaSecao($secao) {
    $return = "";
    $sql = "SELECT * FROM `vm_secao` WHERE view =1 AND secao='$secao[0]' ORDER BY ordem";
    $query = mysql_query($sql);
    $pag = mysql_fetch_array($query);
    $secao = $pag['secao'];
    $id_secao = $pag['id_secao'];
    $array['secao'] = $secao;
    $array['id_secao'] = $id_secao;
    $return .= estruturaPagina($array);
    if ($pag['secao'] === 'ambientes') {
        $return .= "<footer class='conhecaAmbientes'>";
        $return .= "<div class='container-promo'>";
        $return .= pegaImagens($id_secao, "4", '');
        $return .= "</div>";
        $return .= conhecaAmbientes();
        $return .= "</footer>";
    }
    return $return;
}
function navSlide($id_secao) {
    $return = "";
    $sql = "SELECT COUNT(src) FROM `vm_img` WHERE `id_secao`='$id_secao' AND (`tipo_img` <>'3')";
    $result = executaSQL($sql);
    $count = $result[0]['COUNT(src)'];
    if ($count > 0) {
        $return .= "<nav class='nav-slide op-8'>";
        for ($a = 0; $a < $count; $a++) {
            $return .= "<span class='flex' data-param='$a'><b></b></span>";
        }
        $return .= "</nav>";
    }
    return $return;
}
function estruturaSite($pagina) {
    global $db;
    $return = "";
    $sql = "SELECT * FROM `{$db}secao` WHERE 1 AND";
    $set = "";
    for ($a = 0; $a < count($pagina); $a++) {
        $set .= " `secao`='$pagina[$a]' OR";
    }
    $sql .= trim($set, 'OR');
    $sql .= " AND `menu`='1' AND `view`='1' ORDER BY ordem";
    $dados = executaSql($sql);
    for ($a = 0; $a < count($dados); $a++) {
        $secao = $dados[$a]['secao'];
        $id_secao = $dados[$a]['id_secao'];
        $estrutura = $dados[$a]['estrutura'];
        $return .= "<$estrutura id='$secao' ";
        $return .= "class='" . consultaClasses($estrutura, $id_secao, '', '', '');
        $return .= " ativo'>";
//        $return .= estruturaSecao($secao, $id_secao);
        $return .= "</$estrutura>";
    }
    return $return;
}
function carregarPagina($pag) {
    $return = require "html/$pag.php";
    return $return;
}
function montarTags($arrTags, $id_tag, $id_secao, $id_subsecao, $id_secao_conteudo, $tipo_img, $ordenacao, $limite) {
    $return = "";
    $parametros = array('id_secao' => $id_secao, 'id_subsecao' => $id_subsecao, 'id_secao_conteudo' => $id_secao_conteudo);
    for ($a = 0; $a < count($arrTags); $a++) {
        $tag = $arrTags[$a];
        $dados = "";
        $existe = existeTabela($tag);
        if ($existe) {
//            $return .= "TAG = " . $tag;
            if (!empty($id_tag) && $a === 0) {
                $parametros['id_' . $tag] = $id_tag;
                $dados = listaCampoTabela("*", $tag, $parametros, '', '');
                $dados[0]['limit_caracter'] = "0";
                unset($parametros['id_' . $tag]); // evitar que influencie o próximo looping 
            } else {
                if ($a === 0) {
                    $dados = listaCampoTabela("*", $tag, $parametros, $ordenacao, $limite);
                } else {
                    $dados = listaCampoTabela("*", $tag, $parametros, $ordenacao, '');
                }
                if ($limite !== "0") {
                    $parametros['id_pai'] = $dados[0]['id_' . $tag];
                }
            }
            $arrTags[$a] = $dados;
            for ($y = 0; $y < count($dados); $y++) {
                $arrTags[$a][$y]['tag'] = $tag;
            }
        } else {
            $arrTags[$a] = array('0' => array('id_' . $tag => '0', 'tag' => $tag,
                    'id_secao_conteudo' => $id_secao_conteudo, 'id_pai' => '0', $tag => ''));
        }
    }
    $return .= criarTagsConteudo($arrTags);

    return $return;
}
function criarTagsConteudo($arrTags) {
    $return = "";
    $countTags = count($arrTags);
    $a = 0;
    for ($b = 0; $b < count($arrTags[$a]); $b++) {
        $tag = $arrTags[$a][$b]['tag'];
        $id_tag = $arrTags[$a][$b]['id_' . $tag];
        $id_pai = $id_tag;
//        $return .= "<br> Tag0 = " . $tag . " com id_pai= " . $id_pai;
        $id_secao_conteudo = $arrTags[$a][$b]['id_secao_conteudo'];
        $parametros = parametros($tag, $id_tag, $id_secao_conteudo);
        $tagEsp = tagEsp($tag);
        if (empty($tagEsp)) {
            $return .= "<$tag $parametros>";
            if (isset($arrTags[$a][$b][$tag]) && isset($arrTags[$a][$b]['limit_caracter'])) {
                $lim = $arrTags[$a][$b]['limit_caracter'];
                $texto = textos($arrTags[$a][$b][$tag], $lim);
                $return .= $texto;
            } else {
                $return .= $arrTags[$a][$b][$tag];
            }
        } else {
            $return .= "<$tag $parametros $tagEsp";
        }

        if (1 < $countTags && empty($tagEsp)) {
            $elemFilho = numElemFilho($arrTags[1], $id_pai); // filtra os elementos filhos
            for ($d = 0; $d < (count($elemFilho)); $d++) {
//                if ($arrTags[1][$d]['id_pai'] === $id_pai) {
                $tag1 = $elemFilho[$d]['tag'];
//                    $return .= "<br> Tag1 = " . $tag1 . " com id_pai= " . $id_pai;
                $id_tag1 = $elemFilho[$d]['id_' . $tag1];
                $parametros = parametros($tag1, $id_tag1, $id_secao_conteudo);
                $tagEsp1 = tagEsp($tag1);
                if (empty($tagEsp1)) {
                    $id_pai1 = $id_tag1;
                    $return .= "<$tag1 $parametros>";
                    if (isset($elemFilho[$d][$tag1]) && isset($elemFilho[$d]['limit_caracter'])) {
                        $lim = $elemFilho[$d]['limit_caracter'];
                        $texto = textos($elemFilho[$d][$tag1], $lim);
                        $return .= $texto;
                    } else {
                        if ($tag1 !== 'img') {
                            $return .= $elemFilho[$d][$tag1];
                        }
                    }
                } else {
                    if ($id_tag === '0') {
                        $id_pai1 = $id_tag1;
                    } else {
                        $id_pai1 = $id_tag;
                    }
                    $return .= "<$tag1 $parametros $tagEsp1";
//                        if (intval(current($arrTags[1])) === count($arrTags[1])) {
//                            $tagEsp1 = "";
//                            $return .= "fim de Tag1";
//                        }
                }

                if (2 < $countTags && (empty($tagEsp1) || ($d === count($elemFilho) - 1))) {
                    $elemFilho2 = numElemFilho($arrTags[2], $id_pai1); // filtra os elementos filhos
                    for ($e = 0; $e < count($elemFilho2); $e++) {
//                        if ($arrTags[2][$e]['id_pai'] === $id_pai1) {
                        $tag2 = $elemFilho2[$e]['tag'];
                        $id_tag2 = $elemFilho2[$e]['id_' . $tag2];
//                            $return .= "<br> Tag2 = " . $tag2 . " com id_pai1= " . $id_pai1;
//                        $return .= "<hr>Tag2 => ".$tag2;
                        $parametros = parametros($tag2, $id_tag2, $id_secao_conteudo);
                        $tagEsp2 = tagEsp($tag2);
                        if (empty($tagEsp2)) {
                            $id_pai2 = $id_tag2;
                            $return .= "<$tag2 $parametros>";
                            if (isset($elemFilho2[$e][$tag2]) && isset($elemFilho2[$e]['limit_caracter'])) {
                                $lim = $elemFilho2[$e]['limit_caracter'];
                                $texto = textos($elemFilho2[$e][$tag2], $lim);
                                $return .= $texto;
                            } else {
                                if ($tag2 !== 'img') {
                                    $return .= $elemFilho2[$e][$tag2];
                                }
                            }
                        } else {
                            if ($id_tag === '0') {
                                $id_pai2 = $id_tag1;
                            } else {
                                $id_pai2 = $id_tag;
                            }
                            $return .= "<$tag2 $parametros $tagEsp2";
//                                    if (intval(current($arrTags[2])) === count($arrTags[2])) {
//                                        $tagEsp2 = "";
//                                         $return .= "fim de Tag2";
//                                    }
                        }
                        if (3 < $countTags && (empty($tagEsp2) || ($e === count($elemFilho2) - 1))) {
                            $elemFilho3 = numElemFilho($arrTags[3], $id_pai2); // filtra os elementos filhos
                            for ($f = 0; $f < count($elemFilho3); $f++) {
//                                if ($arrTags[3][$f]['id_pai'] === $id_pai2) {
                                $tag3 = $elemFilho3[$f]['tag'];

//                                    $return .= "<br> Tag3 = " . $tag3 . " com id_pai2=" . $id_pai2;
                                $id_tag3 = $elemFilho3[$f]['id_' . $tag3];
                                $parametros = parametros($tag3, $id_tag3, $id_secao_conteudo);
                                $tagEsp3 = tagEsp($tag3);
                                if (empty($tagEsp3)) {
                                    $id_pai3 = $id_tag3;
                                    $return .= "<$tag3 $parametros>";
                                    if (isset($elemFilho3[$f][$tag3]) && isset($elemFilho3[$f]['limit_caracter'])) {
                                        $lim = $elemFilho3[$f]['limit_caracter'];
                                        $texto = textos($elemFilho3[$f][$tag3], $lim);
                                        $return .= $texto;
                                    } else {
                                        if ($tag3 !== 'img') {
                                            $return .= $elemFilho3[$f][$tag3];
                                        }
                                    }
                                } else {
                                    if ($id_tag === '0') {
                                        $id_pai3 = $id_tag1;
                                    } else {
                                        $id_pai3 = $id_tag;
                                    }
                                    $return .= "<$tag3 $parametros $tagEsp3";
//                                        if (intval(current($arrTags[3])) === count($arrTags[3])) {
//                                            $tagEsp3 = "";
//                                            $return .= "fim da tag3";
//                                        }
                                }
                                if (4 < $countTags && (empty($tagEsp3) || ($f === count($elemFilho3) - 1))) {
                                    $elemFilho4 = numElemFilho($arrTags[4], $id_pai3); // filtra os elementos filhos
                                    for ($g = 0; $g < count($elemFilho4); $g++) {
                                        if ($elemFilho4[$g]['id_pai'] === $id_pai3) {
                                            $tag4 = $elemFilho4[$g]['tag'];
//                                            $return .= "<br> Tag4 = " . $tag4 . " com id_pai3=" . $id_pai3;
                                            $id_tag4 = $elemFilho4[$g]['id_' . $tag4];
                                            $parametros = parametros($tag4, $id_tag4, $id_secao_conteudo);
                                            $tagEsp4 = tagEsp($tag4);
                                            if (empty($tagEsp4)) {
                                                $return .= "<$tag4 $parametros>";
                                                if (isset($elemFilho4[$g][$tag4]) && isset($elemFilho4[$g]['limit_caracter'])) {
                                                    $lim = $elemFilho4[$g]['limit_caracter'];
                                                    $texto = textos($elemFilho4[$g][$tag4], $lim);
                                                    $return .= $texto;
                                                } else {
                                                    if ($tag4 !== 'img') {
                                                        $return .= $elemFilho4[$g][$tag4];
                                                    }
                                                }
                                            } else {
                                                $return .= "<$tag4 $parametros $tagEsp4";
                                            }
                                            if (empty($tagEsp4)) {
                                                $return .= "</$tag4>";
                                            }
                                        }
                                    }
                                }
//                                            
                                if (empty($tagEsp3)) {
                                    $return .= "</$tag3>";
                                }
//                                }
                            }
                        }

                        if (empty($tagEsp2)) {
                            $return .= "</$tag2>";
                        }
//                        }
                    }
                }

                if (empty($tagEsp1)) {
                    $return .= "</$tag1>";
                }
//                }
            }
        }
        if (empty($tagEsp)) {
            $return .= "</$tag>";
        }
    }
    return $return;
}
function listaCampoTabela($dados, $table, $parametros, $ordenacao, $limite) {
    global $db;
    if (is_array($dados)) {
        $campos = "`" . implode('`,`', $dados) . "`";
    } else {
        $campos = $dados;
    }
    if ($table === 'select') {
        $table = 'select_option';
    }
    $sql = "SELECT $campos FROM `{$db}$table` WHERE 1 ";
    if (count($parametros) > 0) {
        foreach ($parametros as $key => $dado) {
            $sql .= " AND `$key` = '$dado'";
        }
    }
    if (!empty($ordenacao)) {
        $sql .= " ORDER BY $ordenacao DESC";
    }
    if (!empty($limite)) {
        $sql .= " LIMIT $limite;";
    }
//    if($table === 'textarea'){
//        echo $sql;
//    }
    return executaSQL($sql);
}
function textos($texto, $lim) {
    $return = "";
    if ($lim !== "0") {
        $return .= limit_caracter($texto, $lim, $quebra = true);
    } else {
        $return .= $texto;
    }
    return $return;
}
function countRegistrosTabela($tabela, $column_name, $param) {
    $sql = "SELECT COUNT($column_name) FROM `vm_$tabela`";
    if (!empty($param)) {
        $sql .= " WHERE `$column_name` = '$param'";
    }
    $result = executaSQL($sql);
    return $result[0]["COUNT($column_name)"];
}
function consultaNomeCampo($tabela, $campo, $tabela_id) {
    global $db, $mysqli;
    $sql = "SELECT `$campo` FROM `{$db}$tabela` WHERE `{$tabela}_id`='$tabela_id'";
	// echo "<br>ConsultaNomeCampo -> $sql";
    $result = $mysqli->query($sql);
    $c = $result->fetch_array();
    $dados = $c[$campo];
    return $dados;
}
function consultaDadoCampo($tabela,$campo,$filtro){
	global $db;
    $return = "";
	$sql = "SELECT `$campo` FROM `{$db}$tabela` ";
	if(!empty($filtro)){
		$sql .=" WHERE ";
		if(is_array($filtro)){
			$temp = "";
			foreach($filtro as $key => $dado){
			$temp .= "`$key`='$dado',";
			}
			$sql .= trim($temp,",");
		}else{
		$sql .= "$filtro";
	}
	// echo "<br>Sql consultaDadoCampo -> $sql";
	$result = executaSQL($sql);
    if (count($result) > 0) {
        $return = $result[0][$campo];
    }
    return $return;
	
}
}
function consultaIdTable($tabela, $filtro) {
    global $db;
    $return = "";
    $sql = "SELECT `{$tabela}_id` FROM `{$db}$tabela` WHERE 1 ";
	if(!empty($filtro)){
		if(is_array($filtro)){
			
		}else{
		$sql .= " AND `$tabela`='$filtro'";
	}
	}
	// echo "<hr> consultaIdTable => $sql";
    $result = executaSQL($sql);
    if (count($result) > 0) {
        $return = $result[0]["{$tabela}_id"];
    }
    return $return;
}
function updateTable($tabela,$campo,$dado,$filtro){
	global $db;
	$return = "";
	$sql = "UPDATE `{$db}$tabela` SET";
	if(is_array($campo)){
	foreach($campo as $key => $value){
	$sql .= " `$key`='$value',";
	}	
	$sql = trim($sql,',');
	}else{
	$sql .= " `$campo`='$dado'";	
	}
	if(!empty($filtro)){
	$sql .= " WHERE `{$tabela}_id`='$filtro'";
	}
	//echo "function UPDATE -> $sql";
	$result = executaSQL($sql);
	if($result){
	return TRUE;
	}
}
function existeTabela($tag) {
    global $db;
    $sql = "SHOW TABLES LIKE '{$db}$tag'";
    $result = executaSQL($sql);
    $count = count($result);
    if ($count > 0) {
        return true;
    }    
}
function limit_caracter($texto, $limite, $quebra = true) {
    $tamanho = strlen($texto);
// Verifica se o tamanho do texto é menor ou igual ao limite
    if ($tamanho <= $limite) {
        $novo_texto = $texto;
// Se o tamanho do texto for maior que o limite
    } else {
// Verifica a opção de quebrar o texto
        if ($quebra == true) {
            $novo_texto = trim(substr($texto, 0, $limite)) . ' ...';
// Se não, corta $texto na última palavra antes do limite
        } else {
// Localiza o útlimo espaço antes de $limite
            $ultimo_espaco = strrpos(substr($texto, 0, $limite), ' ');
// Corta o $texto até a posição localizada
            $novo_texto = trim(substr($texto, 0, $ultimo_espaco)) . ' ...';
        }
    }
// Retorna o valor formatado
    return $novo_texto;
}
//Novas funcoes
function sec_cont($sec_cont_id){
	global $db,$dados;
    $layout = "";	
	$tabela = "sec_cont";
	$campos = "";	
	$filtros = array("ativo"=>'1',"sec_cont_id"=>$sec_cont_id);
	$order = array("sec_cont_id"=>$sec_cont_id);
	
	$dados_sec_cont = consultaDadosTabela($tabela,$campos,$filtros,$order);
	if(count($dados_sec_cont)>0){		
			$href = $dados['href'];
			for ($a = 0; $a < count($dados_sec_cont); $a++) {
				$dados['estrutura']['sec_cont']= $dados_sec_cont[$a];
				$dados['filtros']['sec_cont_id'] = $dados_sec_cont[$a]['sec_cont_id'];
				$sec_cont = $dados_sec_cont[$a]['sec_cont'];				
#alimenta dados				
			$dados['dados'] = "";	
			foreach($dados_sec_cont[$a] as $chave=> $dado){
				if(!empty($dado)){					
		//		$dados['dados'][$chave] = '';	
				}
			}
			$dados['dados'] = $dados_sec_cont[$a];

				$layout .= "<div id='sec_cont_{$sec_cont}' ";
				if(!empty($dados_sec_cont[$a]['classes'])){
				$layout .=" class='{$dados_sec_cont[$a]['classes']}'";	
				}
				$layout .= ">";	
#titulo da header sec_cont
					if(isset($dados_sec_cont[$a]['sec_cont_header'])){
						$layout .= "<div class='sec_cont_header'>{$dados_sec_cont[$a]['sec_cont_header']}</div>";
					}
#tabela da sec_cont
	if(!empty($dados_sec_cont[$a]['sec_cont_tabela_dados'])){ 
					$arr_set_tabela = explode(",",$dados_sec_cont[$a]['sec_cont_tabela_dados']);
					$dados['tabelas'] = $arr_set_tabela;
					$tabelaMae = $arr_set_tabela[0];		
#filtros especificos
 					
			if(!empty($dados_sec_cont[$a]['sec_cont_filtro_dados'])){				
				$filtros = array();
				
				$dados_filtros = explode(",",$dados_sec_cont[$a]['sec_cont_filtro_dados']);						
					for($f=0;$f<count($dados_filtros);$f++){
						$filtro = $dados_filtros[$f];
						$filtros[$filtro] = '';
							if($norma = normatizacao($dados['estrutura']['sec'],$filtro)){
								$filtros[$filtro]=$norma;
							}else{
								if(isset($dados['estrutura']['filtros'][$f])){
									$filtros[$filtro] = $dados['estrutura']['filtros'][$f];
									$dados['filtros'][$filtro] = $dados['estrutura']['filtros'][$f];
									$dados['href'] = $dados['href'].$dados['estrutura']['filtros'][$f]."/";
								}									
							}
						}
						
				}
# CONSULTA DE DADOS !! 
		$dados_tab_sec_cont = consultaDadosTabela($dados_sec_cont[$a]['sec_cont_tabela_dados'],'',$filtros,'');	
	
	if(count($dados_tab_sec_cont)>0){		
	$href = $dados['href'];		
#ORGANIZANDO OS DADOS		
		if(!empty($dados_sec_cont[$a]['metodos'])){ 
			$arr_metodos = explode(',',$dados_sec_cont[$a]['metodos']);
			include "classes/classe_metodos.php";
			$mtd = new Metodos;		
			$mtd->Sec_cont_id = $dados['filtros']['sec_cont_id'];
				for($b=0;$b<count($dados_tab_sec_cont);$b++){
				$dados['dados']['ordem']=$b;
				$dados['dados'] = $dados_tab_sec_cont[$b];
					foreach($arr_metodos as $metodo){	
					$layout .= $mtd->$metodo();	
					}				
				}
		}else{		
			for($b=0;$b<count($dados_tab_sec_cont);$b++){
#alimenta dados
				$dados['dados']['ordem']=$b;
				#$dados['dados'] = $dados_tab_sec_cont[$b];
/*
			foreach($dados_tab_sec_cont[$b] as $chave=>$dado){
				if(!empty($dado)){
				$dados['dados'][$chave] = $dado; 
				}
			}
*/
			
# alimenta href	
				$hrefTemp = "";
				if(isset($dados_tab_sec_cont[$b][$tabelaMae]) AND !empty($dados_tab_sec_cont[$b][$tabelaMae])){
					$hrefTemp .= $dados_tab_sec_cont[$b][$tabelaMae]."/";
				}elseif(isset($dados_tab_sec_cont[$b][$tabelaMae."_id"])){
					$hrefTemp .= $dados_tab_sec_cont[$b][$tabelaMae."_id"]."/";
				}
				$dados['href'] = $href.$hrefTemp;								
# estruturando o layout	
				$layout .= estruturaLayout($dados);					
			}		
					
		}
	}else{
		$layout .="<div class='row txt-center'>nenhum registro encontrado!</div>";
	}
	}else{
		$layout .= estruturaLayout($dados['dados']);
	}
						
	}
	}
	return $layout;
	}
function normatizacao($sec,$dado){
	// echo "<br>nomatização sec = $sec e dado = $dado";
	 if($dado == 'evento_data'){
		if($sec == 'resultados'){	
			return " `$dado` < CURDATE()";
		}else{
			
			return " `$dado` >= CURDATE()";
		}
	 }
	 if($dado == 'ativo'){
		 return 1;
	 }
	 if($dado == 'rodape'){
		 return 1;
	 }
}
function param_normatizado($param){
	$params = array('evento_data');
	if(in_array($param,$params)){
		return $param;
	}
}
function tag($tabela,$parametros,$filtro_param,$filtro_id,$tag){
	global $db,$url;
	$return = "";
	$sql = "SELECT ";
		$dados_param = "";
			$arr_parametros = explode(",",$parametros);
				foreach($arr_parametros as $dado){
					$dados_param .= "`$dado`,";			
				}
	$sql .= trim($dados_param,",")." FROM `{$db}$tabela` WHERE 1";
		 if(!empty($filtro_param)){
			 $sql .= " AND `$filtro_param`='$filtro_id'";
		 }
	$dados = executaSQL($sql);
	if(count($dados)>0){
		for($a=0;$a<count($dados);$a++){
				if($tag === 'img'){
					$return .= "<figure>";
					if(!empty($dados[$a]['href'])){
					$return .= "<a href='{$url}{$dados[$a]['href']}'></a>";	
					}
					$return .= "<$tag";
					$atr = "";
					foreach($arr_parametros as $dado){
						if($dado !== 'href'){
							if($dado === 'src'){
								$return .= " $dado = '{$url}{$dados[$a][$dado]}'";
							}
							$return .= " $dado='{$dados[$a][$dado]}'";
						}
					}
					$return .= " />";
					$return .= "</figure>";
				}else{
					$return .= "<$tag";
					foreach($dados[$a] as $key => $param){
						if($key === $tabela){
						$return .= " data-{$key}='{$param}'";	
						}
					}
					foreach($dados[$a] as $key => $dado){
						if($key !== $tabela){
						$return .= ">$dado";
						}
					}
					$return .= "</$tag>";
				}				
		}	
	}
		return $return;
}
function mascaraDados($tipo,$dado){
	if($tipo === 'moeda'){
	$dado = "R$ ".number_format($dado, 2, ',', '.'); 
	}
	if($tipo === 'data'){
		$dado = date('d/m/Y', strtotime($dado));
	}
	if($tipo === 'diaMes'){
		if($dado !== '0000-00-00'){
		$dia = date('d', strtotime($dado));
		$mes = mesPersona(date('m', strtotime($dado)));
		$dado = $dia."/".$mes;
		}
	}
	return $dado;
}
function mesPersona($dado){
	$meses = array("01"=>"","1"=>"Jan","2"=>"Fev","3"=>"Mar","4"=>"Abr","5"=>"Mai","6"=>"Jun","7"=>"Jul","8"=>"Ago","9"=>"Set","10"=>"Out","11"=>"Nov","12"=>"Dez");
	return $meses[$dado];
}
function calculaIdade($nascimento,$dataRef){	
$arr_nasc = explode("-",$nascimento);
$nascimento = $arr_nasc[0]."-".intval($arr_nasc[1])."-".intval($arr_nasc[2]);
$arr_ref = explode("-",$dataRef);
$dataRef = $arr_ref[0]."-".intval($arr_ref[1])."-".intval($arr_ref[2]);
$date = new DateTime($nascimento); // data de nascimento
$interval = $date->diff( new DateTime($dataRef) ); // data referência
$idade = intval($interval->format( '%Y' )); // em Anos
return $idade;
}
function consultaFaixa_etaria($idade){
	global $db;
	$sql = "SELECT `faixa_etaria_id` FROM `{$db}faixa_etaria` WHERE  $idade>=`idade_inic` AND $idade<=`idade_fim`";
	 echo "<br>consultaFaixa_etaria=> SQL = $sql";
	if($faixa = executaSQL($sql)){
		return $faixa[0]['faixa_etaria_id'];
	};
}
function infoInscritos($dados){
	
	global $db;
	$layout = "";
	$sql_sexo = "SELECT * FROM `{$db}sexo";
	$dados_sexo = executaSQL($sql_sexo);
	for($a=0;$a<count($dados_sexo);$a++){
		$layout .= "<div class='item-param-sexo'>";
		$layout .="<h2>{$dados_sexo[$a]['sexo_titulo']}</h2>";
		$sql_perc = "SELECT * FROM {$db}perc WHERE 1
			AND `perc_id` IN ({$dados['perc_id']})";
			
			$dados_perc = executaSQL($sql_perc);			
			for($b=0;$b<count($dados_perc);$b++){
				$layout .= "<div class='data-param-perc'>";
				$sql_inscr = "SELECT count(inscritos_id) as n_inscritos FROM {$db}inscritos WHERE `sexo_id`={$dados_sexo[$a]['sexo_id']}
					AND `perc_id`='{$dados_perc[$b]['perc_id']}' AND `evento_id`='{$dados['evento_id']}' 
					AND (`pgto_status_id`='2' OR `pgto_status_id`='3') ";
					
					$dados_inscr = executaSQL($sql_inscr);
					
					for($c=0;$c<count($dados_inscr);$c++){
					$layout .="<div  class='circle stroke-{$dados_perc[$b]['perc']}'>";
					$layout .= "<svg width='84' height='84'>
                                    <circle r='38' cy='41' cx='41'></circle>
                                    <circle r='38' cy='41' cx='41' stroke-linejoin='round' stroke-linecap='round' class='circle-progress' style='animation: donut-show-0 900ms ease 0s 1 normal forwards running;'></circle>
                                </svg>";	
					$layout .= "<div class='item-inscritos'>";
					if($dados['evento_id']==='1' OR $dados['evento_id']==='2'){
					if($dados_sexo[$a]['sexo_id'] === '1'){
						if($dados_perc[$b]['perc_id'] === '2'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos']+10;	
						}
						if($dados_perc[$b]['perc_id'] === '4'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos']+7;	
						}
						if($dados_perc[$b]['perc_id'] === '5'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos']+10;	
						}
						if($dados_perc[$b]['perc_id'] === '8'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos'];	
						}
					}
					
					if($dados_sexo[$a]['sexo_id'] === '2'){
						if($dados_perc[$b]['perc_id'] === '2'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos']+5;	
						}
						if($dados_perc[$b]['perc_id'] === '4'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos']+5;	
						}
						if($dados_perc[$b]['perc_id'] === '5'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos']+5;	
						}
						if($dados_perc[$b]['perc_id'] === '8'){
						$layout .= (int)$dados_inscr[$c]['n_inscritos'];	
						}
					}
					}else{
						$layout .= $dados_inscr[$c]['n_inscritos'];	
					}
					$layout .= "</div>";
					$layout .="</div>";
					}				
					$layout .= "<div class='item-perc-titulo'>{$dados_perc[$b]['perc_titulo']}</div>";		
					$layout .= "</div>";
			}
			$layout .= "</div>";
	}
	return $layout;
}
function funcoes_param($nome,$dados){
	$return ="";
	switch ($nome) {
		case 'infoInscritos':
		$return = infoInscritos($dados);
		break;
		case 'listaSiteMap':
		$return = listaSiteMap($dados);
		break;
		case 'infoPagamento':
		$return = infoPagamento($dados);
		break;
		case 'status_inscr':
		$return = status_inscr($dados);
		break;
		case 'erro_inscricao':
		$return = erro_inscricao($dados);
		break;
		case 'list_modl_inscr':
		$return = list_modl_inscr($dados);
		break;
		case 'descr_pagamento':
		$return = descr_pagamento($dados);
		break;
		case 'inicia_pagseguro':
		$return = inicia_pagseguro();
		break;
		case 'idSessaoPagSeguro':
		// $return = idSessaoPagSeguro();
		break;
		case 'percursos':
		$return = percursos($dados);
		break;
		case 'erro_processamento':
		$return = erro_processamento($dados);
		break;
		case 'campos_ordenacao':
		$return = campos_ordenacao();
		break;
		case 'total_geral':
		$return = total_geral();
		break;
		
	}
	return $return ;
}
function pesquisa($tabela,$campos,$filtros){
	$layout ="";
	$dados = consultaDadosTabela($tabela,$campos,$filtros,'','');
	$layout .= "<div class='resultado_pesquisa'><ul>";
	for($a=0;$a<count($dados);$a++){
		$dado = $dados[$a][$campos];
		$layout .= "<li>$dado</li>";
	}
	$layout .= "</ul></div>";
	
	return $layout;
}
function pagueseguro($inscrito_id,$dados){
	echo "<br>funcao pagueseguro";
	global $url;
	$return = "";
	$vlr_inscr = number_format($dados['inscricao_vlr'], 2, '.', '');
	/* if($dados['almoco_vlr']>0){
	 $vlr_almoco = number_format($dados['almoco_vlr'], 2, '.', '');
		 }*/
	$evento_titulo = consultaNomeCampo('evento','evento_titulo',$dados['evento_id']);
	$inscricao_titulo = consultaNomeCampo('inscricao','inscricao_titulo',$dados['inscricao_id']);
	$kit_titulo = consultaNomeCampo('kit','kit_titulo',$dados['kit_id']);
	$xml = "<?xml version='1.0'?>
			<checkout>
				<sender>
				<name>{$dados['nome']}</name>
				<email>{$dados['email']}</email>
				</sender>
				<currency>BRL</currency>
				<items>
					<item>
						<id>$inscrito_id</id>
						<description>Inscrição no evento $evento_titulo, $inscricao_titulo - $kit_titulo</description>
						<amount>$vlr_inscr</amount>
						<quantity>1</quantity>
					</item>";
		
		$xml .= "</items>
				<notificationURL>https://www.maissport.com.br/pagamento/notificacao.php</notificationURL>
				<reference>$inscrito_id</reference>
				<receiver><email>falecom@marceloamaral.com.br</email></receiver>
				<shippingAddressRequired>false</shippingAddressRequired>
				<enableRecovery>false</enableRecovery>
				</checkout>";
				return  enviopagseguro($xml,$inscrito_id);
}
function enviopagseguro($xml,$inscrito_id){
	echo "<br> funcao enviopagseguro";
$email = "falecom@marceloamaral.com.br";
// $token = "D564EEA054A84A69A2327BD9468DF18D";
$token = "FE760A7F37F14821845B1D86636BA7D4"; // Sandbox 	
	/*$url_xml = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions/?email=&token=";// Sandbox	
	$url_xml = "https://ws.pagseguro.uol.com.br/v2/sessions/?email=&token=";
	$data['email']=$email;
	$data['token']=$token;
	$curl = curl_init();
 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'));
	#curl_setopt($curl, CURLOPT_URL, "https://ws.pagseguro.uol.com.br/v2/sessions");
	curl_setopt($curl, CURLOPT_URL, "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions");//SANDBOX
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
	$resp = curl_exec($curl);
	curl_close($curl);
	$code= simplexml_load_string($resp); //Transforma em array
	return $code->id; 
*/
	#$url = "https://ws.pagseguro.uol.com.br/v2/checkout/?email=$email&token=$token";
	$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/checkout/?email=$email&token=$token";// SANDBOX
	$retornoPagSeguro = envia_curl($url,$xml);
	if($retornoPagSeguro){
	return $retornoPagSeguro;
	}
}
function notificacoesPagseguro($post){
	$notificationCode = preg_replace('/[^[:alnum:]-]/','',$_POST["notificationCode"]);
	$data['email'] = "falecom@marceloamaral.com.br";	
	#$data['token'] = "D564EEA054A84A69A2327BD9468DF18D";
	$data['token'] = "FE760A7F37F14821845B1D86636BA7D4"; // Sandbox 	
	
	$data = http_build_query($data);	
	$url = 'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/'.$notificationCode.'?'.$data;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$xml = curl_exec($curl);
	curl_close($curl);
	
	$xml = simplexml_load_string($xml);

	$reference = $xml->reference;
	$status = $xml->status;
	
	if($reference && $status){
		echo "<br>Ref =$reference"; 
		echo "<br>Status =$status"; 
	 	//$result = updateTable('inscritos','status_pgto',$status,$ref);
		//if($result){
			//echo "atualizado para status $status o id $ref";
		//}
	}
}
function atualizaTabela($tabela,$campo,$valor,$id){
	global $db;
	$sql = "UPDATE `{$db}$tabela` SET ";
	if(is_array($campo)){
		foreach($campo as $key => $dado){
			$sql .= " `$key`='$dado',";
		}
		$sql = trim($sql,",");
	}else{
	 $sql .= "`$campo`='$valor'";
	}
	$sql .= " WHERE `{$tabela}_id`='$id'";	
	echo "<br>atualizaTabela->$sql";
	 // return executaSQL($sql);
}
function list_modl_inscr($dados){
	global $db;
	$layout = "";
	$sql = "SELECT `inscricao_titulo`,`inscricao`,`inscricao_id` FROM `{$db}inscricao` WHERE 1";
	if(isset($dados['inscricao_id'])){
	$sql .=	" AND `inscricao_id` IN ({$dados['inscricao_id']}) ORDER BY FIELD (`inscricao_id`,{$dados['inscricao_id']})";
	}elseif(isset($dados['inscricao'])){
		$sql .=	" AND `inscricao`='{$dados['inscricao']}'";
	}
	$result = executaSQL($sql);
	if($result){
		for($a=0;$a<count($result);$a++){
			$layout .= "<div class='item-param'>";
			$layout .=  "<h3 data-inscricao_titulo='{$result[$a]['inscricao']}'>{$result[$a]['inscricao_titulo']}</h3>";
			$layout .= "</div>";
		}
	}
	return $layout;
}
function consulta_transacao($code,$token,$email){
	$url = "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/$code?email=$email&token=$token";
	#$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/notifications/$code?email=$email&token=$token";//SANDBOX
	$curl = envia_curl($url,'');
	$dados = simplexml_load_string($curl); //Transforma em array
	if($curl == 'Unauthorized'){
		echo "Erro em consulta_transacao : Unauthorized <br>";
		exit;
	}elseif(count($curl -> error) > 0){
		echo "Erro em consulta_transacao :<br>";
	}else{
		
	}
}
function envia_curl($url,$xml){
	echo "<br> funcao envia_curl";
	$ch = curl_init($url);//Ignora certificado SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// precisamos de uma resposta do servidor 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//Informando que estamos enviando um XML com encoding utf-8
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Content-Type:application/xml; charset=ISO-8859-1'));//Enviando o XML como POST
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);//Manda executar a requisição
	$return = curl_exec($ch);
	curl_close($ch);
	
	$return = simplexml_load_string($return); //Transforma em array
	return $return;
}
function video($dados){
	$layout = "";
	if($dados['evento_id']==='1'){
		$id_video = "1C9Z84TdF1Q";
		$layout .= "<article>Este percurso é dos 7km. Em breve publicaremos o de 15km</article>";
	}else{
		$id_video = "Mf_r1AWOKs4";
	}
	$layout .=  "<div id='ytplayer'></div>";
	$layout .= "
<script>
  // Load the IFrame Player API code asynchronously.
  var tag = document.createElement('script');
  tag.src = 'https://www.youtube.com/player_api';
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // Replace the 'ytplayer' element with an <iframe> and
  // YouTube player after the API code downloads.
  function onYouTubePlayerAPIReady() {
	  var player;
    player = new YT.Player('ytplayer', {
      'videoId': '$id_video',	  
	  playerVars: { 'autoplay': 1, 'controls': 0, 'showinfo':0, 'modestbranding':1, 'fs':1 ,'iv_load_policy':3,'rel':0},
		events: {
      'onReady': 'onPlayerReady',
      'onPlaybackQualityChange': 'onPlayerPlaybackQualityChange',
      'onStateChange': 'onPlayerStateChange',
      'onError': 'onPlayerError'
    }
    });
  }
</script>";
 return $layout;
}
function descr_pagamento($dados_param){
	global $dados;
$return = "";
if($dados_param['param']==='deposito'){	
		// $vlr = total_geral($dados);
		$return .="<article>
		<p>Abaixo estão os dados para o depósito na conta JURÍDICA:<br>
		Banco: Sicoob Maxi Crédito <br>
		Código Banco: 756<br>
		Agência: 3069-4 <br>
		Conta: 117.109-7 <br>
		Titular: Jose Adelino Correia<br>
		</p>
		<p>Após realizar o depósito enviar imagem do comprovante para o e-mail contato@maissport.com.br
		</p></article> ";
	
}
if($dados_param['param']==='transferencia'){	
		// $vlr = total_geral($dados);
		$return .="<article>
		<p>Abaixo estão os dados para a transferência na conta JURÍDICA:<br>
		Banco: Sicoob Maxi Crédito <br>
		Código Banco: 756<br>
		Agência: 3069-4 <br>
		Conta: 117.109-7 <br>
		Titular: Jose Adelino Correia<br>
		CNPJ: </p>
		<p>Após realizar o depósito enviar imagem do comprovante para o e-mail contato@maissport.com.br
		</p></article> ";
	
}
if($dados_param['param']==='isento'){
	$return .="<article>
		Esta opção deve ser utilizada apenas por aqueles que receberam da MaisSport Eventos Esportivos negociação formal de acordo da isenção do valor da inscrição referida.<br>
		Esta inscrição será confirmada após a vista e aprovação do Organizador do evento. Se validada será enviado para o e-mail cadastrado a confirmação formal da participação do evento.
		</article>";
}
if($dados_param['param']==='boleto'){
	$return .="<article>
		Para a opção boleto bancário será adicionado o valor de R$ 1,00 como taxa a ser cobrada pela instituição financeira.
		</article>";
}
if($dados_param['param']==='carteira'){
	$return .="<article>
		Esta opção deve ser utilizada apenas por aqueles que se inscrevem com os agenciadores da MaisSport Eventos Esportivos.<br>
		Esta inscrição será confirmada após a vista e aprovação do agenciador e o organizador do evento. Se validada será enviado para o e-mail cadastrado a confirmação formal da participação do evento.
		</article>";
}
if($dados_param['param']==='cartao'){
	$return .= "<article class='descr'>A partir deste ponto os dados são de <b>quem está realizando o pagamento</b>. No caso de pagamento com cartão de crédito estes dados são do proprietário do cartão.<br><i>Este é um ambiente seguro.  Por razões de segurança a MaisSport não guarda informações pessoais financeiros de seus atletas.</i></article>";
}
return $return;
}
function obtemDadosInscrito($id){
	global $db;
	$return = array();
	$sql = "SELECT * FROM `{$db}inscritos` WHERE `inscritos_id`='$id'";
	$result = executaSQL($sql);
	foreach($result[0] as $key => $value){
		$return[$key] = $value;
	}
	$return['evento']= consultaNomeCampo('evento', 'evento', $result[0]['evento_id']);
	$return['evento_titulo']= consultaNomeCampo('evento', 'evento_titulo', $result[0]['evento_id']);
	$return['perc_titulo']= consultaNomeCampo('perc', 'perc_titulo', $result[0]['perc_id']);
	$return['inscricao_titulo']= consultaNomeCampo('inscricao', 'inscricao_titulo', $result[0]['inscricao_id']);
	$return['sexo_titulo']= consultaNomeCampo('sexo', 'sexo_titulo', $result[0]['sexo_id']);
	$return['faixa_etaria']= consultaNomeCampo('faixa_etaria', 'faixa_etaria_titulo', $result[0]['faixa_etaria_id']);
	$return['kit_titulo']= consultaNomeCampo('kit', 'kit_titulo', $result[0]['kit_id']);
	return $return ;	
	
}
function statusTransacao($dados){
	$layout = "";
	$statusPagseguro = $dados['dados']['statuspagseguro_id'];
	$titulo = consultaNomeCampo('statuspagseguro', 'statuspagseguro_titulo', $statusPagseguro);
	$layout .= " O status de sua transação é <b>'$titulo'</b>.";
	if($statusPagseguro === '1' OR $statusPagseguro === '2'){
		$layout .= " Assim que recebermos a aprovação, enviaremos para seu e-mail, o <b>{$dados['dados']['email']}</b>,  a confirmação de sua inscrição.<br>";
	}
	if($statusPagseguro === '3'){
		$layout .= "
			 Agora pedimos um pouco mais de sua paciência pois  enviaremos para seu e-mail , o <b>{$dados['dados']['email']}</b>, a confirmação de sua inscrição.<br>";
	}
	if($statusPagseguro === '7'){
		$return .= "
		Sentimos muito :( , sua transação foi cancelada pela instituição financeira.<br>Quando o atleta opta por pagar com débito online ou boleto bancário e não finaliza o pagamento, a transação assume este status. Isso também ocorre quando o atleta escolhe pagar com um cartão de crédito e o pagamento não é aprovado pela operadora.";
	}
	
	return $layout;
	exit;
}
function percursos($dados){
	$return = "";
	if($dados['evento_id']==='1'){
		$arr_perc = explode(",",$dados['perc_id']);
		foreach($arr_perc as $key){
			if($key === '4'){
			$return .= "<h3>Os 15k ( 15km )</h3>";
			$return .= "<iframe class='video' width='560' height='315' src='https://www.youtube.com/embed/YFpKTV-eNG8' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
			}
			if($key === '2'){
				$return .= "<h3>Os 7k ( 7km )</h3>";
				$return .=  "<iframe class='video' width='560' height='315' src='https://www.youtube.com/embed/1C9Z84TdF1Q' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
			}
		}
	}
	if($dados['evento_id']==='2'){
		$id_video = "Mf_r1AWOKs4";
				$return .= "<h3>Os 40k ( 40km )</h3>";
				$return .=  "<div id='ytplayer'></div>";
				$return .= "<script>
  var tag = document.createElement('script');
  tag.src = 'https://www.youtube.com/player_api';
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // Replace the 'ytplayer' element with an <iframe> and
  // YouTube player after the API code downloads.
  function onYouTubePlayerAPIReady() {
	  var player;
    player = new YT.Player('ytplayer', {
      'videoId': '$id_video',	  
	  playerVars: { 'autoplay': 1, 'controls': 0, 'showinfo':0, 'modestbranding':1, 'fs':1 ,'iv_load_policy':3,'rel':0},
		events: {
      'onReady': 'onPlayerReady',
      'onPlaybackQualityChange': 'onPlayerPlaybackQualityChange',
      'onStateChange': 'onPlayerStateChange',
      'onError': 'onPlayerError'
    }
    });
  }
</script>";
	}
	return $return;
}
function enviaEmail($tipo,$dados){
	if($tipo='erro'){
		$assunto = "Erro na inscrição de ".$dados['nome'];
		$texto = "Código de erro: <br>";
		$texto .= "Mensagem de erro: <br>";
		
	}
}
function erro_processamento($dados){
	global $url;
	$return = "";
	$return .= "<article> {$dados['atleta']}, desculpe-nos!<br>
	Houve uma falha no processamento de seu pagamento. Sentimos por isso :( .<br>
	Nosso Webmaster foi notificado.<br>
	<p> Talvez seja apenas uma instabilidade na conexão ou comunicação. Que tal tentarmos novamente? ;)  Siga o link abaixo e refaça seu processo de pagamento. Se não obter sucesso, então por gentileza, aguarde-nos que entraremos em contato após avaliar qual foi a falha e sua origem. <br>
	Novamente pedimos desculpas.</p>";
	if(!empty($dados['erros'])){
		$return .= erro_inscricao($dados);
	}
	$return .= "<p>
	<b><a href='{$url}eventos/pagamento/{$dados['inscritos_id']}'>Quero tentar novamente!</a></b>
	</p>
	
	Se por ventura desejar entrar em contato pode usar o canal:<br>
	e-mail : contato@maissport.com.br<br>
	</article>";
	return $return;
}
function consultaColumns($tabela) {
	global $db;
	$return = "";
	$campo = array();	
		$arr_tabela = explode(",",$tabela);
		foreach($arr_tabela as $tabela){
		//echo " consultaColumns-> Tabela = $tabela<br>";
		$result = executaSQL("SHOW COLUMNS FROM `{$db}$tabela`");
			if(count($result)>0){
				for($a=0;$a<count($result);$a++){
				$campo[] = $result[$a]['Field'];
				};
			}
		}
		return $campo;
}
function existeColuna($tabela,$coluna) {
    global $db;
    $sql = "SHOW COLUMNS FROM `{$db}$tabela`";
	// echo "<hr><br>Function existeColuna -> Campo <b>$coluna</b> em tabela <b>$tabela</b> : <br>$sql";
	if($result = executaSQL($sql)){
	for($a=0;$a<count($result);$a++){
			if($result[$a]['Field'] === $coluna){
				// echo "<br><b> Resposta</b> Sim<br>";
				return true;
			}
	}	
	}		
}
function existeDadoTabela($tabela,$dado,$filtros){
	global $db;
	$sql = "SELECT * FROM `$db{$tabela}` WHERE ";
	if(is_numeric($dado)){
		$sql .= "`{$tabela}_id`='$dado' ";
	}else{
		$sql .= "`{$tabela}`='$dado' ";
	}
	if(!empty($filtros)){
		if(is_array($filtros)){
			foreach($filtros as $key => $dado){
				if(existeColuna($tabela,$key)){
				$sql .="AND `$key`='$dado' ";
				}
			}
		}else{
			$sql .= " $filtros";
		}
	}
	if($dados = executaSQL($sql)){
		 // echo "<br>existeDadoTabela ->  SQL = $sql<br>";
		return $dados[0];
		
	};
}
function estruturaArray($str,$dados,$indice){
	$arr = explode($str,$dados);
	foreach($arr as $key => $dado){
		if(!empty($indice)){
			$ret[$indice]=$dado;
		}else{
			$ret[]=$dado;	
		}
	}
	if(count($ret)>0){
		return $ret;
	}
}
function seleciona_param($params_id){
	$arr_valor= explode(",",$params_id);
	$filtro_params = "";
		foreach($arr_valor as $value){
		$filtro_params .= " `sec_cont_param_id`='$value' OR";
		}
		$filtro_params = trim($filtro_params,"OR");
		return consultaDadosTabela('sec_cont_param','',$filtro_params,$params_id);
}
function consultaDadosTabela($tabela,$arr_campos,$arr_filtros,$order){
global $db;
$result = "";
$campos = "";
$setTabela = "";
$arr_setOn = array();
$arr_SetFiltro = array();
$arr_setOn = array();		
		$arr_tabela = explode(",",$tabela);
		$tabelaMae = $arr_tabela[0];
		for($a=0;$a<count($arr_tabela);$a++){
		$tabela = $arr_tabela[$a];		
		if(!empty($arr_campos)){
			if(is_array($arr_campos)){
				if(isset($arr_campos[$tabela])){
				foreach($arr_campos as $campo){
					$campos .= "`$tabela`.`$campo`,";		
			    	}
				}
			}else{
				$arr_campos = explode(",",$arr_campos);
				foreach($arr_campos as $campo){
					$campos .=	"`$tabela`.`$campo`,";	
				}
			}
		}else{
			$campos .= "`$tabela`.*,";
		}

		if($a>0){
$setTabela .= " JOIN ";
		}else{
$setTabela .= " FROM ";			
		}		
$setTabela .=" `{$db}$tabela` as `$tabela`";			
#SetOn
		if($tabela !== $tabelaMae){			
			 if(existeColuna($tabelaMae,$tabela."_id")){
				$arr_setOn[] = " `$tabelaMae`.`{$tabela}_id`=`{$tabela}`.`{$tabela}_id`";
			 }elseif(existeColuna($tabela,$tabelaMae."_id")){
				 $arr_setOn[] = " `$tabelaMae`.`{$tabelaMae}_id`=`{$tabela}`.`{$tabelaMae}_id`";
			 }else{
				 for($b=0;$b<count($arr_tabela);$b++){
					 $tb = $arr_tabela[$b];
					 if($tb !== $tabela AND $tb !== $tabelaMae){
						 if(existeColuna($tb,$tabela."_id")){
							 $arr_setOn[] = " `$tb`.`{$tabela}_id`=`{$tabela}`.`{$tabela}_id`";
						 }
					 }
				 }
			 }
		}
# os filtros
	if(!empty($arr_filtros) AND (count($arr_filtros)>0)){
		foreach($arr_filtros as $filtro => $dado){
			if(!empty($dado)){
				
				//if(!in_array($filtro,$arr_SetFiltro)){
				
					 if(existeColuna($tabela,$filtro)){					 
						if(strpos($dado,",")){
							$sql_add = "`{$tabela}`.`$filtro` IN (";
							$arr_filtroDado = explode(",",$dado);
							foreach($arr_filtroDado as $value){
								$sql_add .= "'$value',";
							}
							$arr_SetFiltro[$filtro] = 	trim($sql_add,",").")";
						}else{
							if(param_normatizado($filtro)){							
							$arr_SetFiltro[$filtro] = "`{$tabela}`.`$filtro` $dado";		
							}else{								
							$arr_SetFiltro[$filtro] = " `{$tabela}`.`$filtro`='$dado' ";	
							}
						}
					}
				//}
			}
		}
	}
}

$sql = "SELECT ";
if(!empty($campos)){
$sql .= trim($campos,',');
}
$sql .= $setTabela ;

if(count($arr_setOn)>0){
	$sql .= " ON (";
	foreach($arr_setOn as $setOn){
		$sql .= " $setOn AND"; 
	}
	$sql = trim($sql,"AND").")";
}

if(count($arr_SetFiltro)>0){
	$sql .= " WHERE ";
	foreach($arr_SetFiltro as $valor){
		$sql .= " $valor AND";
	}
	$sql = trim($sql,"AND");
}
		
	if(!empty($order)){
		$sql .= " ORDER BY ";
		if(is_array($order)){
			foreach($order as $campo => $dadoCampo){
				if(stripos($dadoCampo,',')){
					$sql .= "FIELD (`$campo`,";
					$arr_dadoCampo = explode(",",$dadoCampo);
					foreach($arr_dadoCampo as $value){
						$sql .= "'$value',";
					}
					$sql = trim($sql,',').")";
				}else{
					$sql .= " `$campo` ";
				}
			}
		}else{
			$dadoCampo = explode(",",$order);
				foreach($dadoCampo as $campo){
				 $sql .= "`$campo`,";
				}
				$sql = trim($sql,',');
		}
			
	}
	// echo "<br>consultaDadosTabela => $sql<br>";   
    $result = executaSQL($sql);
	return $result;
}
function campos_ordenacao(){
	global $dados;
	$layout = "";
	if(isset($dados['estrutura']['sec_cont'])){
		$arr_params = consultaDadosTabela("sec_cont,sec_cont_grupo","params_id",array('sec_cont'=>$dados['estrutura']['sec_cont']),'');
		$layout .= "<select name='ordem'>";
	for($a=0;$a<count($arr_params);$a++){
		$arr_param = consultaDadosTabela("sec_cont_param","param,titulo,type",array("sec_cont_param_id"=>$arr_params[$a]['params_id']),'');
		for($b=0;$b<count($arr_param);$b++){
			if($arr_param[$b]['type'] !== "submit"){
			$layout .= "<option value='{$arr_param[$b]['param']}'>{$arr_param[$b]['titulo']}</option>";
		}
		}
	}
	$layout .= "</select>";
	}	
		return $layout;
	}
function identificaDoisReg($tabela,$campo,$filtros){
	global $db;
	$sql = "SELECT `$campo` FROM {$db}$tabela";
	if(!empty($filtros)){
			$sql .= " WHERE";
			if (is_array($filtros) AND count($filtros)>0) {
				foreach ($filtros as $key => $value) {
					if(!is_numeric($key)){
				   $sql .= " $tabela.`$key`='$value' AND";
					}else{
					$sql .= " $value AND";	
					}
				}
				$sql = trim($sql,"AND");
			}else{
				if(!empty($filtros)){
					$sql .= "$filtros";	
				}
			}
	}
	 return executaSQL($sql);
}
function descontos(){
	global $dados;

	$layout = "";
	$desc = calculaDesconto($dados['dados']);	
	 return  "<div class='dado'>".mascaraDados('moeda',$desc)."</div>";
}
function total_geral(){
	global $dados;
	$desc = calculaDesconto($dados['dados']);
	return "<div class='dado'>".mascaraDados("moeda",($dados['dados']['inscricao_vlr']-$desc))."</div>";
}
function erro_inscricao($dados){
	global $url_inicio;	
	$return = "";
	$return .="<br> Por gentileza, verifique os itens abaixo.";
	$arr = explode(',',$dados['erros']);
	$return .= "<ul class='lista'>";
	foreach($arr as $key => $dado){
		$dado_erro = consultaDadosTabela('codigo_erro','codigo_erro,codigo_erro_titulo',array('codigo_erro'=>$dado),'');
		$return .= "<li class='item'>";
		foreach($dado_erro[0] as $param => $valor){
		$return .="$valor<br>";
		}
		$return = trim($return,'=').".</li>";
	}
	$return .= "</ul>";
	$return .= "<p>Conseguiu identificar e gostaria de tentar novamente?</p>";
	return $return;
}
function aviso($origem,$msg){
	if($origem === 'db'){
	return "<div style='font-size:12pt; position:absolute; width: 50%; left: 25%; top: 25%; text-align: center;'>
<div class='lds-heart'><div></div></div>
<p style='font-size: 10pt'>$msg</p></div>";
	}
}
function camiseta($dados_param){
	global $dados;
	$layout = "";	
	if(isset($dados['dados']['inscricao_modalidade_id'])){
		if($dados['dados']['inscricao_modalidade_id'] ==='2'){
			$parametros = parametrizacao($dados_param);
			$layout .= "<select $parametros>";
			$dados_param['tag'] = 'option';
			$layout .= "<option value=''>Camiseta</option>";
		
			$arr =consultaDadosTabela('camiseta','','','');
			for($a=0;$a<count($arr);$a++){
				$dados_param['dados'] = "";
				foreach($arr[$a] as $campo => $dado){
				$dados_param['dados'][$campo] = $dado;	
				}
				$layout .= estruturaTag($dados_param);
			}			
			$layout .= "</select>";
		}else{
			$layout .= "Sem camiseta";
		}
	}
	return $layout;
}
function executaSQL($sql) {
    global $mysqli;
    $object = array();
    $result = $mysqli->query($sql);
    if ($result) {
        if (isset($result->num_rows) > 0) {			
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
        }
    } else {
        $erro = $mysqli->error;
        $return_err = "";
        $return_err .= "<br><hr>Erro na execução da Query: SQL =  $sql";
        $return_err .= "<br> Erro = $erro";
        return $return_err;
    }
}