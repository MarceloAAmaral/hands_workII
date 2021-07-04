<!DOCTYPE html>
<html>
  <?PHP include "html/head.php"; ?>
	<body>
		<div id="content-main">
			<?PHP include "html/menu.php"; ?>
			<section id="home">
				<div>
					<img class='bg' src="img/destaque_bsrun.jpg"/>				
				</div>
			</section>
			<section id="eventos" class="bg2">
				<h1>Eventos</h1>
				<div class="container col-1 flex">
					<div class="col-4 item">
						
						<div class='img'>
							<img src='img/banner_desafio_dos_faraos_chamada_1080_1080.jpg'/>
						</div>
						<h2> Desafio dos Faraós</h2>
						
						<div class='link'>
							<a href="eventos/desafio-dos-faraos">Inscrição</a>
							<div class='vlr right'>
								<label>R$ 130,00</label>
							</div>
						</div>
					</div>	
					<div class="col-4 item">
						
						<div class='img'>
							<img src='img/meia_maratona_ac.jpg'/>
						</div>
						<h2> Meia Maratona de Antônio Carlos</h2>
						
						<div class='link'>
							<a href="eventos/meia-maratona-antonio-carlos">Inscrição</a>
							<div class='vlr right'>
								<label>R$ 95,00</label>
							</div>
						</div>						
					</div>
					<div class="col-4 item">					
						<div class='img'>
							<img src='img/banner_feras_trail_run_1080x1080.png'/>
						</div>
						<h2> Feras Trail Run</h2>
						
						<div class='link'>
							<a href="eventos/feras-trail-run">Inscrição</a>
							<div class='vlr right'>
								<label>R$ 65,00</label>
							</div>
						</div>
					</div>
					<div class="col-4 item">						
						<div class='img'>
							<img src='img/banner_night_run_ingleses_1080_1080.jpg'/>
						</div>
						<h2> Night Run Ingleses</h2>
							
						<div class='link'>
							<a href="eventos/night-run-ingleses">Inscrição</a>
							<div class='vlr right'>
								<label>R$ 65,00</label>
							</div>
						</div>
					</div>
				</div>				
			</section>
		    <section id="quem-somos">
				<h1>Quem somos</h1>
				<article>Estamos correndo desde 2017. Nunca paramos, nunca desistimos. Assim seguimos em frente na busca de nossos objetivos.
				Superando nossas metas criamos, organizamos e realizamos eventos esportivos seja 
				corrida de rua, ciclismo ou mesmo desafios esportivos. Somos felizes no que fazemos
				pois proporcionamos SU-PE-RA-ÇÃO. <BR>Desafie-se conosco, e descubra que você também pode, e pode ir além.				
				</article>
			</section> 
			<section id="fale-conosco" class="bg2">
				<h1>Vamos bater um papo!</h1>
				<article>Entre em contato para uma conversa, falar de seu próximo desafio, de uma parceira, de algo que não lhe agradamos ou mesmo para elogiar! Fique à vontade.				</article>
				<form action="" method="POST">
					<div class='col-2 item'>
						<label>Nome</label>
						<input name='nome' type='text' value=""/>
					</div>
					<div class='col-2 item'>
						<label>E-mail</label>
						<input name='email' type='text' value=""/>
					</div>
					<div class='col-1 item'>
						<label>Pode falar!</label>
						<textarea name='assunto' ></textarea>
					</div>
						<div class='col-2  row-center'><input type="submit" name='enviar' value='Enviar'/></div>
				</form>
			</section> 			
		<?PHP include "html/footer.php"; ?>        
		</div>
    </body>
		<?PHP include "inc/script.php"; ?>
	<script>
	$("#header a").on('click',function(e){
		e.preventDefault();
		menu();
		var href = $(this).attr('href');
		alert(url);
		href = href.replace(url, '');
		if(href.length>0){
			rolagemSuave("#"+href);				
		}else{
			rolagemSuave("#home");		
		}
		
	});
	</script>
</html>
