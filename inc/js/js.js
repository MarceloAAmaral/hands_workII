$(document).ready(function () {
    var $window = $(window);
    $header = $('#header');
    $windowH = $window.height();
    $windowW = $window.width();
    headerH = $header.height();   
	protocolo = $(location).attr('protocol'); 
	dominio = $(location).attr('hostname'); 
    
	url = protocolo+"//"+dominio+"/";
	/*A VARIÁVEL URL DEVE SER ATUALIZADA AO FIM COM O DIRETÓRIO EM EXECUÇÃO, CASO NÃO SEJA EXECUTADO NA RAIZ*/
	$("#content-main").on('click', '.bt-menu', menu);
    
});
function rolagemSuave(link){
	var x =  $(link).offset().top;	
	$('html, body').animate({ 
		scrollTop: x
	}, 800);
}
function menu() {
	var $header = $('header');
	if($header.hasClass('menuAtivo')){
		$header.removeClass('menuAtivo');		
	}else{
		$header.addClass('menuAtivo');	
	}
}


