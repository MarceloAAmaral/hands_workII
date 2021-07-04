<?PHP 
class Pagseguro{
	public $TokenPagseg;
	public $EmailPagseg;
	public $UrlSession;
	public $UrlPagseg;
	public $UrlTransacao;
	public $movimento_id;
	public $idSecao;
	public $hash;
	public $Teste;
	var $Ambiente;
	function __construct(){
		$this->Ambiente = "";
	}
	
	function setAmbientePagueSeguro($ambiente){
		$this->EmailPagseg = "falecom@marceloamaral.com.br";
		if(!empty($ambiente)){
			$this->Ambiente = $ambiente.".";
		}
		$this->UrlPagseg = "https://stc.".$this->Ambiente."pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
		$this->UrlSession = "https://ws.".$this->Ambiente."pagseguro.uol.com.br/v2/sessions?";		
		$this->UrlTransacao = "https://ws.".$this->Ambiente."pagseguro.uol.com.br/v2/transactions";
		if($ambiente === 'sandbox'){
			$this->TokenPagseg = 'FE760A7F37F14821845B1D86636BA7D4';	
		}else{
			$this->TokenPagseg = 'D564EEA054A84A69A2327BD9468DF18D';
		}
	print "<script src='{$this->UrlPagseg}' type='text/javascript'></script>";
	}
	
	function efetuaPagamentoBoleto($dados) {
		$data['paymentMode'] = 'default';
		$data['paymentMethod'] = 'boleto';
		$data['currency'] = 'BRL';
		// $data['extraAmount'] = 0.00;
		$data['itemId1'] = 0001;		
		$data['itemDescription1'] = $dados['evento_titulo'].",".$dados['perc_titulo'];
		$data['itemAmount1'] = number_format($dados['movimento_vlr']-$dados['desconto'], 2, '.', '');
		$data['itemQuantity1'] = 1;
		$data['reference'] = $dados['inscritos_id']; 
		$data['senderName'] = $dados['atleta'];
		$data['senderCPF'] = $dados['cpf'];
		$data['senderAreaCode'] = $dados['cod_area'];
		$data['senderPhone'] = $dados['telefone'];
		$data['senderEmail'] = $dados['email'];
		$data['shippingAddressRequired'] = 'false';
		$data['senderHash'] = $dados['hash'];
		$data['receiverEmail'] = $this->EmailPagseg;		
		$data['email'] = $this->EmailPagseg;
		$data['token'] = $this->TokenPagseg;
		$data = http_build_query($data);		
				
		if($retornoCurl = $this->curl($this->UrlTransacao,$data)){
			$retorno = array();
				if(isset($retornoCurl->error)){
					foreach($retornoCurl->error as $child) {
						foreach($child as $key => $value) {
							if($key === 'code'){
								$retorno['erro'][$key] = $value;
							}
						}
					}
				}else{
					$retorno['statuspagseguro_id'] = $retornoCurl->status;
					$retorno['link_boleto']=$retornoCurl->paymentLink;
					$retorno['codigo']= $retornoCurl->code;
					$retorno['taxapagseguro'] = $retornoCurl->feeAmount;
					$retorno['movimento_status_id']=3;
				}
				return $retorno;
	}
	}
function efetuaPagamentoCartao($dados) {		
		$data['paymentMode'] = 'default';
		$data['paymentMethod'] = 'creditCard';
		$data['currency'] = 'BRL';
		$data['itemId1'] = 0001;
		$data['itemDescription1'] = $dados['evento_titulo'].",".$dados['perc_titulo'];
		$data['itemAmount1'] = number_format($dados['movimento_vlr']-$dados['desconto'], 2, '.', '');
		$data['itemQuantity1'] = 1;		
		$data['notificationURL'] = 'https://www.maissport.com.br/pagamento/notificacao.php';
		$data['reference'] = $dados['inscritos_id'];
		$data['senderName'] = $dados['atleta'];
		$data['senderCPF'] = $dados['cpf'];
		$data['senderAreaCode'] = $dados['cod_area'];
		$data['senderPhone'] = $dados['telefone'];
		$data['senderEmail'] = $dados['email'];
		$data['senderHash'] = $dados['hash'];
		$data['receiverEmail'] = 'falecom@marceloamaral.com.br';		
		$data['installmentQuantity'] = 1;
		$data['installmentValue'] = $data['itemAmount1'];
		//$data['noInterestInstallmentQuantity'] = 1;
		$data['creditCardHolderName'] = $dados['titular_cartao']; 
		$data['creditCardHolderCPF'] = $dados['cpf'];
		$data['creditCardHolderBirthDate'] = str_replace("-","/",mascaraDados('data',$dados['nascimento']));
		$data['creditCardHolderAreaCode'] = $dados['cod_area'];
		$data['creditCardHolderPhone'] = $dados['telefone'];
		$data['billingAddressStreet'] = $dados['endereco'];
		$data['billingAddressNumber'] = $dados['numero'];
		$data['billingAddressComplement'] = $dados['complemento'];
		$data['billingAddressDistrict'] = $dados['bairro'];
		$data['billingAddressPostalCode'] = $dados['cep'];
		$data['billingAddressCity'] = $dados['cidade'];
		$data['billingAddressState'] = $dados['estado']; 
		$data['billingAddressCountry'] = 'BRA';
		$data['shippingAddressRequired'] = 'false';
		$data['creditCardToken'] = $dados['credicardToken'];
		$data['email'] = $this->EmailPagseg;
		$data['token'] = $this->TokenPagseg;
		$data = http_build_query($data);
		if($retornoCurl = $this->curl($this->UrlTransacao,$data)){
			$retorno = array();
				if(isset($retornoCurl->error)){
					foreach($retornoCurl->error as $child) {
						foreach($child as $key => $value) {
							if($key === 'code'){
								$retorno['erro'][$key] = $value;
							}
						}
					}
				}else{
					$retorno['statuspagseguro_id'] = $retornoCurl->status;
					$retorno['link_boleto']='';
					$retorno['codigo']= $retornoCurl->code;
					$retorno['taxapagseguro'] = $retornoCurl->feeAmount;
					$retorno['movimento_status_id']=3;
				}
				return $retorno;
		}
	}
function idSessaoPagSeguro(){
		$data = array("email"=>$this->EmailPagseg,"token"=>$this->TokenPagseg);
		$data = http_build_query($data);
		$retornoCurl = $this->curl($this->UrlSession,$data);
		
		if($retornoCurl->id){
			print "
		<script type='text/javascript'>
			PagSeguroDirectPayment.setSessionId('{$retornoCurl->id}');
		</script>";
			return (string)$retornoCurl->id;
		}else{
			for($a=0;$a<3;$a++){
				if($a==3){
					print "<br> Processo interrompido. Entre em contato com o WebMaster e informe problemas de obtenção no Id de sessão. <br> Desculpe-nos por isso! e desejamos revê-lo brevemente. <br> 
					MaisSport Eventos Esportivos";
				}else{
					print "<br>Problemas ao gravar idSessao. Tentando novamente....";	
						if($retornoCurl = $this->curl($this->UrlSession,$data)){
							if($retornoCurl->id){
							print "
							<script type='text/javascript'>
							PagSeguroDirectPayment.setSessionId('{$retornoCurl->id}');
							</script>";
							$a = 4;
							return (string)$retornoCurl->id;		
							}
						}
				}				
			}					
		}
}
function curl($url,$data){	
		$curl = curl_init();
		$headers = array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1');		
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl,CURLOPT_HTTPHEADER, $headers );
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true );
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		// curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$ret_curl = curl_exec($curl);		
		if($ret_curl){	
		curl_close($curl);
		return simplexml_load_string($ret_curl);
		}else{
			echo "problemas na execução do Curl";			
		}
	}
function serial($dados){
		return http_build_query($dados);	
	}
function paramsPagSeguro($dados){
if($idSecao = $this->idSessaoPagSeguro()){			
echo "<script type='text/javascript'>
	sessaopagseguro_id = '{$idSecao}';
	hash = '';
	token_cartao ='';
	brand = '';
	movimento_origem = '{$dados['movimento_origem']}';
	inscritos_id= '{$dados['inscritos_id']}';
	if(movimento_origem === 'cartao'){
		credicardToken();
	}
	if(movimento_origem === 'boleto'){
		pagsegurohash();
	}

function credicardToken(){
		var n_cartao = '{$dados['n_cartao']}';
		var n_cvv = '{$dados['cvv']}';
		var validade_mes = '{$dados['validade_mes']}';
		var validade_ano = '{$dados['validade_ano']}';
		
		PagSeguroDirectPayment.createCardToken({
			cardNumber: n_cartao,
			cvv: n_cvv,
			expirationMonth: validade_mes,
			expirationYear: validade_ano,
			brand: valor_brand(),
			success: function(response){ 
			token_cartao =  response['card']['token'];
			pagsegurohash();
			},
			error: function(response){
			window.location.replace('http://localhost/ms/eventos/erro/{$dados['inscritos_id']}')
			},
			});
		}
		
function valor_brand(){
			var card = '{$dados['n_cartao']}';
			var bin = card.substr(0,6);
			PagSeguroDirectPayment.getBrand({
			cardBin: bin,
			success: function(response) {
				brand = response['brand']['name'];
				return brand;
				},
			error: function(response) {
				console.log('brand-> error'+response['brand']);
				}
			});
		}
function pagsegurohash(){
	PagSeguroDirectPayment.onSenderHashReady(function(response){
			if(response.status === 'error') {
				return false;
			}else{
				hash = response.senderHash;
				loadDado('inscritos_id='+inscritos_id+'&movimento_origem_id='+{$dados['movimento_origem_id']}+'&cpf='+{$dados['cpf']}+'&sessaopagseguro_id='+sessaopagseguro_id+'&credicardToken='+token_cartao+'&brand='+brand+'&hash='+hash);
				}
		});
	}
function loadDado(campos){	
			var xhttp;
				if (window.XMLHttpRequest) {
				// code for modern browsers
					xhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xhttp = new ActiveXObject('Microsoft.XMLHTTP');
				}
		xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			var retorno = xhttp.responseText.trim();
			if(retorno){
			 window.location.replace('http://localhost/ms/eventos/confirmacao/{$dados['inscritos_id']}')
			}else{
			 console.log('problemas com loadDados');			 
			}
		}
	}
	xhttp.open('POST','load_dados.php?'+campos, true);	xhttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');	
	xhttp.send();
}
	</script>";
	}
}
}
