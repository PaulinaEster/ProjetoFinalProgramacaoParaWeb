<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../pages/header.php";
if (isset($_POST["botao"]) && $_POST["botao"]=="Cadastrar")	{ 	
//se clicou no botão Cadastrar
	try {
		$stmt = $PDO->prepare("SET CHARACTER SET utf8");
		$stmt->execute();
		$stmt = $PDO->prepare("INSERT INTO produto (descricao, idcateg, idsubcat, unidade, controleexercito, controleambiental, estoqueminimo) VALUES (:descricao, :idcateg, :idsubcat, :unidade, :controleexercito, :controleambiental, :estoqueminimo) ");
		$stmt->bindParam(':descricao', $_POST['descricao']);
		$stmt->bindParam(':idcateg', $_POST['idcateg']);
		if ($_POST['idsubcat']=='null') $idsubcat = null;
		else $idsubcat = $_POST['idsubcat'];
		$stmt->bindParam(':idsubcat', $idsubcat);
		$stmt->bindParam(':unidade', $_POST['unidade']);
		if (isset($_POST['controleexercito'])) $ctrlex = 1;
		else $ctrlex = 0;
		if (isset($_POST['controleambiental'])) $ctrlam = 1;
		else $ctrlam = 0;
		$stmt->bindParam(':controleexercito', $ctrlex);
		$stmt->bindParam(':controleambiental', $ctrlam); 		
		$stmt->bindParam(':estoqueminimo', $_POST['estoqueminimo']);
		$result = $stmt->execute(); 
		if (!$result) echo "<script>alert('Ocorreu um erro: Produto não foi cadastrada!')</script>";
		else echo "<script>alert('Produto cadastrado!')</script>";
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		echo "<script>alert(\"Ocorreu um erro no cadastro: ".$e->getMessage()."\");</script>";
	}
}
if (isset($_POST["botao"]) && $_POST["botao"]=="Excluir") {//Se clicou no botão excluir
	$stmt = $PDO->prepare("SET CHARACTER SET utf8");
	$stmt->execute();
	$stmt = $PDO->prepare("select * from produto where idproduto = :id;");
	$stmt->bindParam(':id', $_POST['alterar']);
	$stmt->execute();
	if ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
		 $nome = $linha['nome'];
		 echo "<script> if(confirm('Você deseja excluir definitivamente o produto ".$linha['descricao']."?')) {location.href = 'cadproduto.php?acao=ConfirmaExcluir&id=".$_POST['alterar']."';
		 }else{
		   alert('Registro não excluido!');
		   location.href = 'cadproduto.php';
		 } </script>";
	}
}
if (isset($_GET["acao"]) && $_GET["acao"]=="ConfirmaExcluir" ) { 
	//Se clicou no botão que confirma a exclusão
	try {
		$stmt = $PDO->prepare("delete from produto where idproduto = :id;"); //faz um comando sql
		$stmt->bindParam(':id', $_GET['id']); //preenche com o código a excluir
		$stmt->execute(); //exclui o registro
		}
	catch(PDOException $e)
		{
		 echo "<script>alert('Não é possível excluir esse registro.');</script>";
		}
	}
if (isset($_POST["botao"]) && $_POST["botao"]=="Salvar") //Se clicou no botão salvar
	{ //faz um comando sql para atualizar (update) a tabela
	try {
		$stmt = $PDO->prepare("SET CHARACTER SET utf8");
        $stmt->execute();	    
	    $stmt = $PDO->prepare("update produto set descricao = :descricao, idcateg = :idcateg, idsubcat = :idsubcat, unidade = :unidade, controleexercito = :controleexercito, controleambiental = :controleambiental, estoqueminimo = :estoqueminimo where idproduto = :id ");
		$stmt->bindParam(':descricao', $_POST['descricao']);
		$stmt->bindParam(':idcateg', $_POST['idcateg']);
		if ($_POST['idsubcat']=='null') $idsubcat = null;
		else $idsubcat = $_POST['idsubcat'];
		$stmt->bindParam(':idsubcat', $idsubcat);
		$stmt->bindParam(':unidade', $_POST['unidade']);
		if (isset($_POST['controleexercito'])) $ctrlex = 1;
		else $ctrlex = 0;
		if (isset($_POST['controleambiental'])) $ctrlam = 1;
		else $ctrlam = 0;
		$stmt->bindParam(':controleexercito', $ctrlex);
		$stmt->bindParam(':controleambiental', $ctrlam); 		
		$stmt->bindParam(':estoqueminimo', $_POST['estoqueminimo']);
		$stmt->bindParam(':id', $_POST['alterar']);
		$result = $stmt->execute(); 
		if (!$result){
			echo "<script>alert('Não foi possível atualizar esse registro.');</script>";
		}		
		else echo "<script>alert('Registro alterado!');</script>";
	}
	catch (PDOException $e) {
		 echo "<script>alert(\"Ocorreu um erro no cadastro: ".$e->getMessage ()."\");</script>";
		 }
	}
$descricao = "";
$idcateg = "";
$idsubcat = "";
$unidade = "";
$controleexercito = "";
$controleambiental = "";
$estoqueminimo = "";
$editar = false;
if (isset($_GET["id"]) && !isset($_POST["botao"])) {
	// se há um código a ser alterado, busca os dados para já deixar
	// preenchido os campos com os já existentes antes
	$stmt = $PDO->prepare("SET CHARACTER SET utf8");
    $stmt->execute();
	$stmt = $PDO->prepare("select * from produto where idproduto = :id;");
	$stmt->bindParam(':id', $_GET['id']);
	$stmt->execute();
	 if ($linha = $stmt->fetch(PDO::FETCH_ASSOC)){
	 	$editar = true;
	 	$descricao = $linha['descricao']; 
		$idcateg = $linha['idcateg'];
		$idsubcat = $linha['idsubcat'];
		$unidade = $linha['unidade'];
		$controleexercito = $linha['controleexercito'];
		$controleambiental = $linha['controleambiental'];
		$estoqueminimo = $linha['estoqueminimo'];
		}
	}
?>
<form name="fCadProduto" id="fCadProduto" method="post" action="cadproduto.php" autocomplete="off">
<fieldset >
 <legend>Cadastro de Produtos</legend>
 <table>
 <tr><td></td><td>
 <a href="#" onClick="window.open('popupconsulta.php?table=produto&pk=idproduto&field=descricao&cadastro=Produtos&location=cadproduto.php', 'Consulta', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=400'); return false;"><img src="search.png" width="32"></a>
 </td></tr>
 <tr>
 	<td><label for="descricao">Descrição:</label></td>
 	<td><input type="text" name="descricao" id="descricao" size="50" maxlength="100" value="<?= $descricao?>" required autofocus></td></tr>
 	<tr><td><label for="idcateg">Categoria:</label></td>
 	<td><select name="idcateg" id="idcateg">;
 		<?php
  		 	$stmt = $PDO->prepare("SET CHARACTER SET utf8");
		 	$stmt->execute();
		    $consulta = $PDO->query("SELECT * from categoria order by descricao;");
		    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		    	echo "<option value=\"". $linha['idcateg']."\" ";  
				if ($linha['idcateg']==$idcateg) echo " selected ";
				echo ">".$linha['descricao']."</option>\n";
			}
		 ?>
 	</select></td></tr>
 	<tr><td><label for="idsubcat">Subcategoria:</label></td>
 	<td><select name="idsubcat" id="idsubcat">
		 <?php
		 	$stmt = $PDO->prepare("SET CHARACTER SET utf8");
		 	$stmt->execute();
		    $consulta = $PDO->query("SELECT * from subcategoria order by descricao;");
			echo "<option value=\"null\" ></option>\n";
		    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		    	echo "<option value=\"". $linha['idsubcat']."\" ";  
				if ($linha['idsubcat']==$idsubcat) echo " selected ";
				echo ">".$linha['descricao']."</option>\n";
			}
		 ?>
		 </select></td></tr>
	<tr><td><label for="unidade">Unidade:</label></td>
 	<td><select name="unidade" id="unidade">
	 	<option value="g"  <?php if ($unidade=="g") echo "selected";?> >g</option>
	 	<option value="Kg" <?php if ($unidade=="Kg") echo "selected";?> >Kg</option>
	 	<option value="ml" <?php if ($unidade=="ml") echo "selected";?> >ml</option>
	 	<option value="L"  <?php if ($unidade=="L") echo "selected";?> >L</option>
	 </select></td></tr>

 <tr><td><label for="estoqueminimo">Estoque mínimo:</label></td>
 <td><input type="number" name="estoqueminimo" id="estoqueminimo" value="<?= $estoqueminimo?>" />
 </td></tr>
 <tr><td valign="top"><label for="controle">Controle:</label></td>
 <td><input type="checkbox" id="controleexercito" name="controleexercito" value="1" <?php if ($controleexercito==1) echo "checked";?> > <label for="controleexercito">Exército</label><br>
   <input type="checkbox" id="controleambiental" name="controleambiental" value="1" <?php if ($controleambiental==1) echo "checked";?> > <label for="controleambiental">Ambiental</label>
 </td></tr>
 <tr><td>&nbsp;</td></tr>
 <tr><td></td><td>
<?php if ($editar) { 
	echo "<INPUT TYPE=\"hidden\" NAME=\"alterar\" VALUE=\"{$_GET["id"]}\">";
	?>	
	<input type="submit" name="botao" id="botao" value="Salvar" />
 	<input type="submit" name="botao" id="botao" value="Excluir" />

<?php } else { ?>
	<input type="submit" name="botao" id="botao" value="Cadastrar" />
<?php } ?>
 <input type="submit" name="botao" id="botao" value="Cancelar" />
 </td></tr>
</table>
 </fieldset>
 </form>
</div>
<script type="text/javascript">
 var params = window.location.search;
 var url = window.location.pathname;
 if (window.history.replaceState) {
                    window.history.replaceState('Object', this.title,  url);
                } else {
                    //para o IE vc tem que redirecionar
                     if (url.indexOf(params) != -1) {
                        window.location.href = url;
                    }
                }
</script>
<?php
include "../pages/footer.php";
?>
