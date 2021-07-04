$(document).ready(function () {
    var $window = $(window);
    $header = $('#header');
    $windowH = $window.height();
    console.log("$windowH = " + $windowH);
    $windowW = $window.width();
    headerH = $header.height();
    console.log("headerH = " + headerH);
    scrollHabilita = "nao";
    secao = "";
    subsecao = "";
    url = "http://localhost/ms/";
	// url = "https://www.maissport.com.br/";
	$("#content-main").on('submit', "#sec_cont_formulario_pesquisa", formPesquisa);
	$("#content-main").on('click', '.bt-menu', menu);
	$("input[type='text']").focus(function(){
		var objPai = $(this).closest('[data-param]');
		var objPesq = $('.resultado_pesquisa',objPai);
		if(objPesq.length){	
			$("#content-main").on('keyup',"input[type='text']",resultado_campo_pesquisa);
		}
	})
	$formInscr = $('form#sec_cont_formulario-inscricao');
	$("#content-main").on('click',"input[type='checkbox']",liberaSubmit);
	$("#content-main").on('click',"#sec_cont_inscricao [data-inscricao_titulo]",alternaInscricao);
	$("#content-main form").submit(function(){
		var $objPai = $(this).closest('form');
		var $objFilho = $('[data-param]',$objPai);
		var pendente = '';
		$objFilho.each(function(){
			if($(this).hasClass('pendente')){
				$(this).css('border','solid 1px red');	
				 pendente = '1';
			}else{
				$(this).css('border','none');	
			}
		});
	if(pendente.length>0){		
		return false;
	}
	});
// ACAO OPÇÃO DE PAGAMENTO
	$("#content-main").on('click',"#grupo_formas-pagamento  input[type='radio']",function(){
		var opcao = $(this).attr('id');
		$objPai = $("#grupo_formas-pagamento");
		$objAlvo = $('.itemativo',$objPai);
		if($objAlvo.attr('id') !== opcao){
			$objAlvo.removeClass('itemativo').removeAttr('checked');
		}
		$(this).attr('checked','checked').addClass('itemativo');
		var $obj = $('.ativo',$objPai).removeClass('ativo');
		$('#grupo_formas-pagamento #subgrupo_'+opcao).addClass('ativo');
		if(opcao==='cartao'){
			insereRequired($('#grupo_formas-pagamento #subgrupo_'+opcao));
		}else{
			removeRequired($('#grupo_formas-pagamento #subgrupo_cartao'));
		}
	});
	 $inputpgto = $(".subgrupo-formas-pgto input[value='boleto']");
	 $inputpgto.closest('.item-param').css('display','none');
	 $("#content-main").on('submit',"#sec_cont_pagamento", formPagamento);
	// $("#content-main").on('keyup',"input[name='nascimento']",calculaIdade); 
	$("#content-main").on('keyup',"input",function(){
		if($(this).closest('[data-param]').hasClass('valida')){
			 validacoes($(this).closest('[data-param]'));
		}
	}); 
	if(isset($("#content-main [data-param='total_vlr']"))){
		// atualizaValores($("#content-main [data-param='total_vlr']"));
	}
    inicio();
});
function rolagemSuave(link){
	// e.preventDefault();
	targetOffset = link.offset().top;
	$('html, body').animate({ 
		scrollTop: targetOffset
	}, 500);
}
function insereRequired($objPai){		
		$objPai.each(function(){
			var $objfilho = $('input',$objPai);
			$objfilho.attr("required", "true");
		});
}
function removeRequired($objPai){
		$objPai.each(function(){
			var $objinput = $('input',$objPai);
			$objinput.removeAttr('required');
			var $objselect = $('select',$objPai);
			$objselect.removeAttr('required');
		});
}

function inicio() {
    var ajuste = false;
    $secao = $('section.ativo');
    secao = $secao.attr('id');
    ajuste = ajustaSecao($secao);
	   if (ajuste === true) {
			setTimeout(function() { 
				$secao.css({
				opacity: '1',
				WebkitTransition: '1s'
				});   
			}, 1500); 		   
		}
		
		if(secao==='home'){
			rolagemDestaques();
		}
		if(secao==='resultados'){
			removeRequired($secao);
		}
		//# informações de pagamento
		if($('#grupo_formas-pagamento').length){
			ativaOpcaoPgto($('#grupo_formas-pagamento'));
		}
		layout(secao);
		
//    pushState(secao);

}
function ativaOpcaoPgto($objPai){
	var $objSelec = $("[data-param='movimento_origem_id']",$objPai);
	var $obFilho = $('input',$objSelec).eq(0);
	$obFilho.addClass('itemativo');
	$obFilho.attr('checked', 'checked');
	var $objSelec = $('#subgrupo_'+$obFilho.attr('id'),$objPai).addClass('ativo');
}
function layout(secao) {
	if($windowW <='360'){
		$imgs = $('#sec_cont_destaque img');
		$imgs.each(function(){
			var src= $(this).attr('src');
			console.log("layout -> src = "+src);
			var caminho = src.replace("/\/g, '/'");
			var arquivo = caminho.substring(caminho.lastIndexOf('/') + 1);
			var extensao= arquivo.substring(arquivo.lastIndexOf('.') + 1);
			var nome = arquivo.replace("."+extensao,"")+"-360px";
			$(this).attr('src',"img/"+nome+"."+extensao);
			// return {arquivo:Arquivo, extensao:Extensao};
		});
	}
	if(secao==='resultados'){
		var w_item = $('.lista .item').width();
		$('#sec_cont_lista_resultado figure').css('max-height',w_item*0.759417);
	}
	imgBg($secao);
	var w_obj = $("[data-param='percursos']").width();
	$('.video').css('height', w_obj*0.566);
	
	function imgBg(destino) {
		destino.find('.box-bg img').each(function () {
        var src = $(this).attr('src');
		$obj = $(this).closest('.item-param');
        $obj.css({'background': 'url(' + src + ')no-repeat'});
		$(this).remove();
		});
	}
}
function acao(event) {
    var link = $(this).hasClass('link');
    if (!link) {
        event.preventDefault();
        scrollHabilita = "nao";
        var href = $(this).attr('href');
        console.log("function acao => href = " + href);
        var estrutura = serializa(href);
        if (estrutura) {
            console.log("function acao => estrutura = " + estrutura);
            $secao = $('#' + secao);
            var secaoativa = $secao.hasClass('ativo');
            if (!secaoativa) {
                console.log("function acao => secao NÃO tem class ativo");
                carregar("ajax/index.php?s=" + secao, $secao);
            }
            if (subsecao !== "") {
                $subsecao = $(".subsecao-" + subsecao, $secao);
                if ($subsecao) {
                    if (secaoConteudo !== "")
                        $secaoConteudo = $(".container-" + secaoConteudo, $subsecao);
                        if (id !== "") {
                            carregar("ajax/index.php?s=" + secao + "&b=" + subsecao+"&sc=" + secaoConteudo+"&id="+id, $secaoConteudo);
                        }
                }
            }
        }

    }
    $('header#topo nav').css('margin-left', '-100%');
}
function serializa() {
	console.log("funcao ->serializa");
	estrutura_url = ["secao", "subsecao", "filtro_sub"];	
	serial = [];
	var href = window.location.href;
    var estrutura = href.replace(url, "");
    var nivel = estrutura.split("/");
	console.log("funcao serializa=> nivel.length = " + nivel.length);
	for(var a=0;a<estrutura_url.length; a++){
		console.log("funcao serializa=> estrutura = " + estrutura_url[a]);
		for (var i = 0; i < nivel.length; i++) {
			if(i===a){
			serial[estrutura_url[i]].push(nivel[i]);
			}
		}
	}
		console.log("funcao serializa=> serial = " + serial);
}
function pagina() {
    console.log("function pagina  --- INÍCIO ---")
    $("a.loadNav").click(function (event) {
        event.preventDefault();
        var j = $(this).attr('href');
        carregar(j);
        pushState(j);
    });
    $("a.loadMenu").click(function (event) {
        event.preventDefault();
        var m = $(this).attr('href');
        pushState(m);
    });
    console.log("function pagina  --- FIM ---")
}

function pushState(key) {
    console.log('function=> pushState = key= ' + key);
//    if (typeof ($.getUrlVar('s')) !== 'undefined') {
//        key = $.getUrlVar('s');
//    } else if (typeof ($.getUrlVar('b')) !== 'undefined') {
//        var subsecao = $.getUrlVar('b');
//        if (typeof ($.getUrlVar('sc')) !== 'undefined') {
//            var secaoConteudo = $.getUrlVar('sc');
//        }
//    } else {
//        var pathName = location.pathname;
//        key = pathName.replace("/ma/", "");
//        if (key === "") {
//            key = "home";
//        }
//    }
//    
    window.history.replaceState(key, 'title', key);
    return key;
}
function ajustaSecao($secao) {
    var ajusteCompleto = false;
	ajusteCompleto = ajusteResponsividade($secao);
	if (ajusteCompleto) {
            console.log("ajusteSecao => ajusteCompleto = " + ajusteCompleto);
			
			return true;	
            
        }
    /*var lida = $secao.hasClass('lida');
    if (!lida) {
        
//        apresentacao($secao);
//        galeria($secao);
//        ajustaImgArticle($secao);
    } else {
        return true;
    }
	*/
    function ajusteResponsividade($secao) {
		
		$secao.css('min-height', $windowH);
		var id = $secao.attr('id');
		console.log('id'+id);
		$('.inner-height',$secao).css('min-height', $windowH);
		if($windowW <='640'){
		$('.inner-middle',$secao).css('min-height', ($windowH/1.9));
		}else{
		$('.inner-middle',$secao).css('min-height', ($windowH/1.9));	
		}
        //$('footer').css('min-height', $windowH / 4);
        return true;
    }
	
	function video(){
		$('.video').css('height', $windowW*0.562222);
	}
}
function menu() {
	var $nav = $('#menu_principal');
	if($nav.hasClass('menuAtivo')){
		$nav.removeClass('menuAtivo');
		$('section').css('margin-left', '0px');
	}else{
		$('#menu_principal').addClass('menuAtivo');	
		$('section').css('margin-left', '320px');
	}
    
	
}
function mascaraDados($tipo,$str){
var resultado;
	if($tipo === 'moeda'){
		resultado = $str.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"});	
	}
	if($tipo === 'double'){
		$str = removeCaracteres($str,"R$",'');	
		if($str.indexOf(',')>0){
			$str= $str.replace(',','.');
		 }		 
		 resultado = parseInt($str);
	}
	return resultado;
}
function atualizaValores($obj){
	
	var inscricao_vlr = $("[data-param='inscricao_vlr'] .dado").text();
	var desconto = $("[data-param='desconto'] .dado").text();
	
	inscricao_vlr = mascaraDados("double",inscricao_vlr);
	// inscricao_vlr = removeCaracteres(inscricao_vlr,"R$",'').trim();
	desconto = removeCaracteres(desconto,"R$",'').trim();
	var vlr = parseInt(inscricao_vlr)-parseInt(desconto);
	
	$('.dado',$obj).html("R$ "+vlr+",00");
	
	if(isset($("[data-param='cod_voucher']"))){
		$("[data-param='cod_voucher'] input").val('');
	}
}
function empty($a){
	if(typeof $a !== "undefined" || $a !== null){
		return $a;
	}
}
function isset($a){
	if($a.length>0){
		return $a;
	}
}
function formPesquisa(event) {
	event.preventDefault();
	var $form = $(this);
	var dados = $( this ).serialize();
	var $obj = $("#sec_cont_retorno_resultado");
	carregar("POST", $form.attr('action'), dados,$obj);
}
function resultado_campo_pesquisa() {
//    clicado na opção seus dados são transferidos para os inputs
    var objPai = $(this).closest('[data-param]');
    var campo = $(this);
	var objPesq = $('.resultado_pesquisa',objPai);
    var string = campo.val().toLowerCase();
	if(string.length>1){
		objPesq.css('display', 'block');
		 var objFilho = $('li',objPesq);
        objFilho.each(function () {
            var texto = $(this).text().toLowerCase();
            var n = texto.indexOf(string);
            if (n > -1) {
                $(this).css('display', 'block');
            } else {
                $(this).css('display', 'none');
				
            }
        });
		objPesq.on('click','li',function(){
			var alvo = $(this);
			var conteudo = alvo.text();
			campo.val(conteudo);
			objPesq.css('display', 'none');
		});
		
	}else{
		objPesq.css('display', 'none');
	}
	
	/*console.log('resultado_campo_pesquisa : '+nome);
	var string = $(this).val().toLowerCase();
	
	resultado_pesquisa
	pesquisa(texto);
	
    var id_prod = obj.attr('data-id_produtos');
    
    objPai.find('input[name*="produtos"]').val(nome);
    
    $('.resultadoPesquisa').css('display', 'none');
	*/
}
function pesqProd() {
//    Captura string digitada e compara com valor de li. Retorno iguais
    var objPai = $(this).closest('fieldset');
    var string = $(this).val().toLowerCase();
    if (string.length > 0) {
        objPai.find('.resultadoPesquisa').css('display', 'block');
        var obj = $('.resultadoPesquisa li');
        obj.each(function () {
            var texto = $(this).text().toLowerCase();
            var n = texto.indexOf(string);
            if (n > -1) {
                $(this).css('display', 'block');
            } else {
                $(this).css('display', 'none');
            }
        });
    } else {
        objPai.find('.resultadoPesquisa').css('display', 'none');
    }
//    var url = 'html/index.php?menu=menu&f=pesquisa&t=clientes&c=nome&palavra='+valor
//    carregar(url,'class','resultadoPesquisa',secao)
//    $(this).val(valor) ;
}
function liberaSubmit(){
	$objpai = $(this).closest('form');
	$obj = $("input[type='submit']",$objpai);
	if($(this).prop('checked')){
	$obj.removeAttr('disabled');	
	}else{
	$obj.attr('disabled','disabled');		
	}
	
}
function alternaInscricao(){
	$("#sec_cont_inscricao [data-param='inscricao_titulo'] .item-param").css('border-bottom','solid 2px #f4f7f6');
	$("#sec_cont_inscricao [data-inscricao_titulo]").css('background-color','#0098db');
	$valor = $(this).attr("data-inscricao_titulo");
	$(this).css('background-color','orange');
	$(this).closest('.item-param').css('border-bottom','solid 1px orange');
	$("#sec_cont_inscricao .container-items .item").css('display','none');
	$("#sec_cont_inscricao .container-items #"+$valor).css('display','block');
	
}
function rolagemDestaques(){
	$objPai = $("#home #sec_cont_destaque");
	$objFilho  = $('.item',$objPai);
	$ordem = 0;
	$objFilho.eq($ordem).addClass('ativo');

	function rolagem(){
		$objFilho.eq($ordem).addClass('rolagem');
			setTimeout(function (){
			$objFilho.eq($ordem).removeClass('ativo').removeClass('rolagem');		
			$ordem++;
			if($ordem<$objFilho.length){
			$objFilho.eq($ordem).addClass('ativo');		
			}else{
			$ordem = 0;
			$objFilho.eq($ordem).addClass('ativo');		
			}
			}, 5000);	
	}
	if($objFilho.length>1){
	setInterval(rolagem, 5000);
	}
}
function carregar(method,url,dados){
   $.ajax({
        type: method,
        url: url,
		data: dados,
		dataType: "html",
        success: function(data) {			
         return data;
        }
    });
}
function validacoes($foco){	
	if($foco.attr('data-param') ==='cod_voucher'){
		var $obj = $('input',$foco);
		var str = $obj.val().substring(0,5);
		$obj.val(str);
		 if(str.length === 5){
			 var inscr_vlr = $("[data-param='inscricao_vlr'] .dado").text();			 
			 inscr_vlr = mascaraDados("double",inscr_vlr);
		$.ajax({
			type: "POST",
			url: url+"inc/coddesc.php",
			data: {'cod_voucher':str,'inscricao_vlr': inscr_vlr},
			dataType: "html",
			success: function(data){
				data = parseFloat(data);
				if(!Number.isNaN(data)){
					console.log("success"+data);
					$("[data-param='desconto'] .dado").html(mascaraDados('moeda',data));
					var vlr_total = inscr_vlr-data; 
					$("[data-param='total_vlr'] .dado").html(mascaraDados('moeda',vlr_total));	
				}
			}
		});
		}else{
			var inscr_vlr = $("[data-param='inscricao_vlr'] .dado").text();		
			$("[data-param='desconto'] .dado").html(mascaraDados('moeda',0));				
			$("[data-param='total_vlr'] .dado").html(mascaraDados('moeda',inscr_vlr));	
		}
	}
	if($foco.attr('data-param') ==='cod_area'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,2));
		if(str.length<2){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');
			}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}
		}
	if($foco.attr('data-param') ==='cvv'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,3));
		if(str.length<3){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');
			}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}
		}
	if($foco.attr('data-param') ==='validade_mes'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,2));
		if(str.length<2){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');
			}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}
	}
	if($foco.attr('data-param') ==='validade_ano'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());		
		$obj.val(str.substring(0,4));		
		if(str.length<4){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');
			}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}
		}
		
	if($foco.attr('data-param') ==='n_cartao'){
		$obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,19));	
		if(str.length<19){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');
			}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}
		}
	if($foco.attr('data-param') ==='telefone'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,9));
		if(str.length<9){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');		
		}
	}	
	if($foco.attr('data-param') ==='cpf'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,11));
		if(str.length<11){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
	}	
	if($foco.attr('data-param')==='endereco'){
		var $obj = $('input',$foco);
		var str = $obj.val();
		$obj.val(str.substring(0,80));
		if(str.length<1){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
		}	
		
	if($foco.attr('data-param')==='numero'){
		var $obj = $('input',$foco);
		var str = $obj.val();
		$obj.val(str.substring(0,20));
		if(str.length<1){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
		}
	if($foco.attr('data-param')==='bairro'){
		var $obj = $('input',$foco);
		var str = $obj.val();
		$obj.val(str.substring(0,60));
		if(str.length<1){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
		}
	if($foco.attr('data-param')==='cidade'){
		var $obj = $('input',$foco);
		var str = $obj.val();
		$obj.val(str.substring(2,60));
		if(str.length<1){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
	}	
	if($foco.attr('data-param')==='estado'){
		var $obj = $('input',$foco);
		var str = somenteTextos($obj.val());
		str = str.toUpperCase();
		$obj.val(str.substring(0,2));
		if(str.length<2){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
		}	
	if($foco.attr('data-param')==='cep'){
		var $obj = $('input',$foco);
		var str = somenteNumeros($obj.val());
		$obj.val(str.substring(0,8));
		if(str.length<8){
			$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
		}else{
			$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
		}
		}
	if($foco.attr('data-param')==='email'){
		var $obj = $('input',$foco);
		var retornoValida = validateEmail($obj.val());
		if(retornoValida){
				$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}else{
				$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
			}
		}
	if($foco.attr('data-param')==='nascimento'){
		var $obj = $('input',$foco);
		var retornoValida = validateDate($obj.val());		
		if(retornoValida){
				$obj.closest('[data-param]').removeClass('pendente').css('border','none');			
			}else{
				$obj.closest('[data-param]').addClass('pendente').css('border','solid 1px red');	
			}
		}
	function validateDate(str) {
		var dates = new Date(str);
			dia = dates.getDate()+1;
			month = dates.getMonth() + 1;
			year = dates.getFullYear();
			
			var array = [];
			if(dia){array.push(dia);}
			if(month){array.push(month);}
			if(year){array.push(year); var str = String(array[2]).length; }
			if(array[2] && str===4){
				if(array[2] > 1920){
				return str;
				}
			}
    }
	function validateEmail($email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		return emailReg.test( $email );
	}
	function somenteNumeros($str){
		return $str.replace(/[^0-9]+/g,"");
	}
	function somenteTextos($str){
		return $str.replace(/[^A-Za-z]+/g,"");
	}
	
	function bandeiraCartao(num){		
		var tgdeveloper = {
    getCardFlag: function(cardnumber) {
        var cardnumber = cardnumber.replace(/[^0-9]+/g, '');

        var cards = {
            visa      : /^4[0-9]{12}(?:[0-9]{3})/,
            mastercard : /^5[1-5][0-9]{14}/,
            diners    : /^3(?:0[0-5]|[68][0-9])[0-9]{11}/,
            amex      : /^3[47][0-9]{13}/,
            discover  : /^6(?:011|5[0-9]{2})[0-9]{12}/,
            hipercard  : /^(606282\d{10}(\d{3})?)|(3841\d{15})/,
            elo        : /^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})/,
            jcb        : /^(?:2131|1800|35\d{3})\d{11}/,        
            aura      : /^(5078\d{2})(\d{2})(\d{11})$/      
        };

        for (var flag in cards) {
			
            if(cards[flag].test(cardnumber)) {
				
                return flag;
            }
        }        

        return false;
    }

	}	
}
}



function getInstallments(){
	PagSeguroDirectPayment.getInstallments({
	amount: $("input[name='total_vlr']").val(),
	maxInstallmentNoInterest: 1,
	brand: $("input[name='total_vlr']").val(),
	success: {},
	error: {},
	complete: {}
});

}
function getHash(){
	console.log('funcao getHash');
	  PagSeguroDirectPayment.onSenderHashReady(function(response){
			if(response.status == 'error') {
				console.log('funcao getHash retorno false para status '+response.message);
				return false;
			}
			var hash = response.senderHash; 
			$("input[name='hash']").val(hash);
			return true
		});	
}


function formPagamento(event){
$objForm = $(this);
var valPgto = $("input[name='pgto_id']:checked").val();

if(valPgto==='cartao'){
	
}else{
	return;
}

	
}

function calculaIdade() {
	var date = new Date($(this).val());
	day = date.getDate()+1;
	month = date.getMonth() + 1;
	year = date.getFullYear();
	
    var d = new Date,
        ano_atual = d.getFullYear(),
        mes_atual = d.getMonth() + 1,
        dia_atual = d.getDate(),

        ano_aniversario = +year,
        mes_aniversario = +month,
        dia_aniversario = +day,
		tamanho = year.length,		
        quantos_anos = ano_atual - ano_aniversario;
    if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
        quantos_anos--;
    }
	if(quantos_anos>=60 && quantos_anos<100){
		console.log('idoso');
		$vlr_inscr = $("input[name='inscricao_vlr']").val();
		desconto = $vlr_inscr-($vlr_inscr*0.5);
		$("[data-param='desconto'] span").html("R$ "+desconto+",00");
		$("input[name='desconto']").val(desconto);
		
	}else{
		desconto = 0;
		$("[data-param='desconto'] span").html("R$ "+desconto+",00");
		$("input[name='desconto']").val(desconto);
	}
	setTimeout(function(){
		 atualizaValores();	
		},500);
	/*desc = function atualizaDesconto(desconto){
		$("[data-param='desconto'] span").html("R$ "+desconto+",00");
		$("input[name='desconto']").val(desconto);
		return TRUE;
	}*/
    // return quantos_anos < 0 ? 0 : quantos_anos; 
	 return quantos_anos ; 
}

 function removeCaracteres(str,lista,acao){
	$arr = lista.split(',');
	var i ;
	for(i=0;i<$arr.length;i++){
		console.log("removeCaracteres => lista = "+$arr[i]);
		str= str.replace($arr[i],acao);
		
	}
	return str;
 }

