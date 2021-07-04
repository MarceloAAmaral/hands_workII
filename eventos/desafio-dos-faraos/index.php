<!DOCTYPE html>
<html>
    <?PHP include "../../html/head.php"; ?>
    <body>
        <div id="content-main">
			<?PHP include "../../html/menu.php"; ?>
			<section id="desafio-dos-faraos">
				<h1>Desafio dos Faraós</h1>
				<article> Ao inscrever-se aguardar o contato de nossa equipe para as orientações de pagamento.</article>
				<form method="POST" action="">
					<div id="dados-atleta" class='col-1 left'>
					<h2>Dados do atleta</h2>
						<div class='col-2 item'>
							<label>Nome</label>
							<input name='nome' type='text' value=""/>
						</div>
						<div class='col-2 item'>
							<label>Nascimento</label>
							<input name='nascimento' type='date' value=""/>
						</div>
						<div class='col-2 item'>
							<label>Endereço</label>
							<input name='endereco' type='text' value=""/>
						</div>
						<div class='col-2 item'>
							<label>Email</label>
							<input name='email' type='email' value=""/>
						</div>
						<div class='col-4 item'>
							<label>Bairro</label>
							<input name='endereco' type='text' value=""/>
						</div>
						<div class='col-4 item'>
							<label>N°</label>
							<input name='endereco' type='text' value=""/>
						</div>
						<div class='col-4 item'>
							<label>DDD</label>
							<input name='ddd' type='number' value=""/>
						</div>
						<div class='col-4 item'>
							<label>Telefone</label>
							<input name='telefone' type='number' value=""/>
						</div>
					</div>
					<div id="dados-inscricao" class='col-1 left'>
					<h2>Dados da inscrição</h2>
						
						<div class='col-4 item'>
							<label>Percurso</label>
							<select name='percurso'>
								<option value="">selecione</option>
								<option value="5k">5 Km</option>
								<option value="10k">10 Km</option>
							</select>
						</div>
						<div class='col-4 item'>
							<label>Kit</label>
							<select name='percurso'>
								<option value="sem-kit">Sem Kit</option>
								<option value="com-kit">Com kit</option>
							</select>
						</div>
						<div class='col-4 item'>
							<label>Valor</label>
							<h3>R$ 130,00</h3>
						</div>
					</div>
					<div class='col-2  row-center'><input type="submit" name='enviar' value='Inscrever'/></div>
				</form>
			</section>
		</div>
		<?PHP include "../../html/footer.php"; ?>        
    </body>
		<?PHP include "../../inc/script.php"; ?>
</html>
