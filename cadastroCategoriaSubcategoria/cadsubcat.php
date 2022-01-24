<?php
include "header.php";
if (isset($_POST["botao"]) && $_POST["botao"] == "Cadastrar") { //se clicou no botão Cadastrar
	try {
		$stmt = $PDO->prepare("SET CHARACTER SET utf8");
		$stmt->execute();
		$stmt = $PDO->prepare("insert into subcategoria (descricao, observacao) values (:descricao, :observacao);");
		$stmt->bindParam(':descricao', $_POST['descricao']);
		$stmt->bindParam(':observacao', $_POST['observacao']);
		$result = $stmt->execute();
		if (!$result) echo "<script>alert('Ocorreu um erro: subtategoria não foi cadastrado!')</script>";
		else echo "<script>alert('subtategoria cadastrado!')</script>";
	} catch (PDOException $e) {
		echo "<script>alert(\"Ocorreu um erro no cadastro: " . $e->getMessage() . "\");</script>";
	}
}
if (isset($_POST["botao"]) && $_POST["botao"] == "Excluir") { //Se clicou no botão excluir
	$stmt = $PDO->prepare("SET CHARACTER SET utf8");
	$stmt->execute();
	$stmt = $PDO->prepare("select * from subcategoria where idsubcat = :id;");
	$stmt->bindParam(':id', $_POST['alterar']);
	$stmt->execute();
	if ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$observacao = $linha['observacao'];
		echo "<script> if(confirm('Você deseja excluir definitivamente o " . $linha['descricao'] . "?')) {location.href = 'cadsubcat.php?acao=ConfirmaExcluir&id=" . $_POST['alterar'] . "';
		 }else{
		   alert('Registro não excluido!');
		   location.href = 'cadsubcat.php';
		 } </script>";
	}
}
if (isset($_GET["acao"]) && $_GET["acao"] == "ConfirmaExcluir") {
	//Se clicou no botão que confirma a exclusão
	try {
		$stmt = $PDO->prepare("delete from subcategoria where idsubcat = :id;"); //faz um comando sql
		$stmt->bindParam(':id', $_GET['id']); //preenche com o código a excluir
		$stmt->execute(); //exclui o registro
	} catch (PDOException $e) {
		echo "<script>alert('Não é possível excluir esse registro.');</script>";
	}
}
if (isset($_POST["botao"]) && $_POST["botao"] == "Salvar") //Se clicou no botão salvar
{ //faz um comando sql para atualizar (update) a tabela
	try {
		$stmt = $PDO->prepare("SET CHARACTER SET utf8");
		$stmt->execute();
		$stmt = $PDO->prepare("update subcategoria set descricao = :descricao, observacao = :observacao  where idsubcat = :id");
		$stmt->bindParam(':descricao', $_POST['descricao']);
		$stmt->bindParam(':observacao', $_POST['observacao']);
		$stmt->bindParam(':id', $_POST['alterar']);

		$result = $stmt->execute();
		if (!$result) {
			echo "<script>alert('Não foi possível atualizar esse registro.');</script>";
		} else echo "<script>alert('Registro alterado!');</script>";
	} catch (PDOException $e) {
		echo "<script>alert(\"Ocorreu um erro no cadastro: " . $e->getMessage() . "\");</script>";
	}
}
$descricao = "";
$observacao = "";
$editar = false;
if (isset($_GET["id"]) && !isset($_POST["botao"])) {
	// se há um código a ser alterado, busca os dados para já deixar
	// preenchido os campos com os já existentes antes
	$stmt = $PDO->prepare("SET CHARACTER SET utf8");
	$stmt->execute();
	$stmt = $PDO->prepare("select * from subcategoria where idsubcat = :id;");
	$stmt->bindParam(':id', $_GET['id']);
	$stmt->execute();
	if ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$editar = true;
		$descricao = $linha['descricao'];
		$observacao = $linha['observacao'];
	}
}
?>
<form name="fCadUsuario" id="fCadUsuario" method="post" action="cadsubcat.php">
	<fieldset>
		<legend>Cadastro de Subtategorias</legend>
		<a href="#" onClick=<?php echo "\"window.open('popupconsulta.php?table=subcategoria&pk=idsubcat&field=descricao&cadastro=Categoria&location=cadsubcat.php', 'Consulta', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=400'); return false;\""; ?>><img src="search.png" width="32"></a>
		<br><br>
		<label for="descricao">Descrição:</label><br />
		<input type="text" name="descricao" id="descricao" size="50" maxlength="128" value="<?= $descricao ?>" required autofocus /><br />
		<label for="observacao">Observção:</label><br />
		<input type="text" name="observacao" id="observacao" size="50" maxlength="128" value="<?= $observacao ?>" required />
		<br />
		<br /><br />
		<?php if ($editar) {
			echo "<INPUT TYPE=\"hidden\" NAME=\"alterar\" VALUE=\"{$_GET["id"]}\">";
		?>
			<input type="submit" name="botao" id="botao" value="Salvar" />
			<input type="submit" name="botao" id="botao" value="Excluir" />

		<?php } else { ?>
			<input type="submit" name="botao" id="botao" value="Cadastrar" />
		<?php } ?>
		<input type="submit" name="botao" id="botao" value="Cancelar" />
	</fieldset>
</form>
</div>
<?php
include "footer.php";
?>