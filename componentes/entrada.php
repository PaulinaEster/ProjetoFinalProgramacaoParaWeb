<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrada</title>
</head>

<body>
  <?php
  include "../pages/header.php";

  if (isset($_POST["botao"]) && $_POST["botao"] == "Cadastrar") {
    //se clicou no botão Cadastrar
    try {
      $stmt = $PDO->prepare("INSERT INTO entrada (idproduto, quantidade, estoque,
    capacidade, mesvcto, anovcto, procedencia, custo, data) VALUES (:idproduto, :quantidade,
    :estoque, :capacidade, :mesvcto, :anovcto, :procedencia, :custo, :data) ");
      $stmt->bindParam(':idproduto', $_POST['idproduto']);
      $stmt->bindParam(':quantidade', $_POST['quantidade']);
      $stmt->bindParam(':estoque', $_POST['quantidade']);
      $stmt->bindParam(':capacidade', $_POST['capacidade']);
      $stmt->bindParam(':mesvcto', $_POST['mesvcto']);
      $stmt->bindParam(':anovcto', $_POST['anovcto']);
      $stmt->bindParam(':procedencia', $_POST['procedencia']);
      $stmt->bindParam(':custo', $_POST['custo']);
      $stmt->bindParam(':data', $_POST['data']);
      $result = $stmt->execute();
      echo $result;
      if (!$result) echo "<script>alert('Ocorreu um erro: Entrada não foi
    cadastrada!')</script>";
      else echo "<script>alert('Entrada cadastrada!')</script>";
    } catch (PDOException $e) {
      echo $e->getMessage();
      echo "<script>alert(\"Ocorreu um erro no cadastro: " . $e->getMessage() . "\");</script>";
    }
  }
  if (isset($_POST["botao"]) && $_POST["botao"] == "Excluir") {
    //Se clicou no botão excluir
    $stmt = $PDO->prepare("select * from entrada where identrada = :id;");
    $stmt->bindParam(':id', $_POST['alterar']);
    $stmt->execute();
    if ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<script> if(confirm('Você deseja excluir definitivamente a entrada
    " . $linha['identrada'] . "?')) {location.href =
    'entrada.php?acao=ConfirmaExcluir&id=" . $_POST['alterar'] . "';
    }else{
     alert('Registro não excluido!');
    Define o estoque do item com a
    quantidade da entrada
     location.href = 'entrada.php';
    } </script>";
    }
  }
  if (isset($_GET["acao"]) && $_GET["acao"] == "ConfirmaExcluir") {
    //Se clicou no botão que confirma a exclusão
    try {
      $stmt = $PDO->prepare("delete from entrada where identrada = :id;"); //faz um comando sql
      $stmt->bindParam(':id', $_GET['id']); //preenche com o código a excluir
      $stmt->execute(); //exclui o registro
    } catch (PDOException $e) {
      echo "<script>alert('Não é possível excluir esse registro.');</script>";
    }
  }
  if (isset($_POST["botao"]) && $_POST["botao"] == "Salvar") //Se clicou no botão salvar
  { //faz um comando sql para atualizar (update) a tabela
    try {
      $stmt = $PDO->prepare("update entrada set idproduto = :idproduto, quantidade =
    :quantidade, estoque = :estoque, capacidade = :capacidade, mesvcto = :mesvcto,
    anovcto=:anovcto, procedencia=:procedencia, custo=:custo, data=:data where identrada = :id ");
      $stmt->bindParam(':idproduto', $_POST['idproduto']);
      $stmt->bindParam(':quantidade', $_POST['quantidade']);
      $stmt->bindParam(':estoque', $_POST['quantidade']);
      $stmt->bindParam(':capacidade', $_POST['capacidade']);
      $stmt->bindParam(':mesvcto', $_POST['mesvcto']);
      $stmt->bindParam(':anovcto', $_POST['anovcto']);
      $stmt->bindParam(':procedencia', $_POST['procedencia']);
      $stmt->bindParam(':custo', $_POST['custo']);
      $stmt->bindParam(':data', $_POST['data']);
      $stmt->bindParam(':id', $_POST['alterar']);
      $result = $stmt->execute();
      if (!$result) {
        echo "<script>alert('Não foi possível atualizar esse
    registro.');</script>";
      } else echo "<script>alert('Registro alterado!');</script>";
    } catch (PDOException $e) {
      echo "<script>alert(\"Ocorreu um erro no cadastro: " . $e->getMessage() . "\");</script>";
    }
  }
  $idproduto = "";
  $quantidade = "";
  $estoque = "";
  $capacidade = "";
  $mesvcto = "";
  $anovcto = "";
  $procedencia = "";
  $custo = "";
  $data = "";
  $descvalue = "";
  $unidade = "";
  $editar = false;
  if (isset($_GET["id"]) && !isset($_POST["botao"])) {
    // se há um código a ser alterado, busca os dados para já deixar
    // preenchido os campos com os já existentes antes
    $stmt = $PDO->prepare("SET CHARACTER SET utf8");
    $stmt->execute();
    $stmt = $PDO->prepare("select * from entrada, produto where produto.idproduto =
    entrada.idproduto and identrada = :id;");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    if ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $editar = true;
      $idproduto = $linha['idproduto'];
      $quantidade = $linha['quantidade'];
      $estoque = $linha['estoque'];
      $capacidade = $linha['capacidade'];
      $mesvcto = $linha['mesvcto'];
      $anovcto = $linha['anovcto'];
      $procedencia = $linha['procedencia'];
      $custo = $linha['custo'];
      $data = date('Y-m-d', strtotime($linha['data']));
      $descvalue = $linha['descricao'];
      $unidade = $linha['unidade'];
    }
  }

  ?>
  <form name="fEntrada" id="fEntrada" method="post" action="entrada.php" autocomplete="off" onsubmit="return valida()">
    <fieldset>
      <legend>Entrada de Produtos</legend>
      <table>
        <tr>
          <td></td>
          <td>
            <a href="#" onClick="consulta()"><img src="search.png" width="32"></a>
          </td>
        </tr>
        <tr>
          <td><label for="idproduto">Produto:</label></td>
          <td>
            <a href="#" onClick="busca()">
              <img src="search.png" width="25"></a>
            <input type="number" name="idproduto" id="idproduto" style="width:50px" required readonly value="<?= $idproduto ?>">
            <input type="text" id="descricao" name="descricao" readonly tabindex="-1" value="<?= $descvalue; ?>">
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><label for="quantidade">Quantidade:</label></td>
          <td><input type="number" name="quantidade" id="quantidade" value="<?= $quantidade ?>" min="1" required style="width:80px"> </td>
        </tr>
        <tr>
          <td>Embalagem:</td>
          <td><input type="number" name="capacidade" id="capacidade" value="<?= $capacidade ?>" required style="width:80px">
            <input type="text" name="unidade" id="unidade" size="2" value="<?= $unidade ?>" tabindex="-1" readonly>
          </td>
        <tr>
          <td>
            <label for="procedencia">Procedência:</label>
          </td>
          <td><input type="text" id="procedencia" name="procedencia" value="<?= $procedencia; ?>" size="50"> </td>
        </tr>
        <tr>
          <td>
            <label for="mesvcto">Validade:</label>
          </td>
          <td>
            <input type="number" name="mesvcto" id="mesvcto" value="<?= $mesvcto ?>" min="1" max="12" required style="width:40px"> / <input type="number" name="anovcto" id="anovcto" value="<?= $anovcto ?>" min="2020" max="2100" required style="width:60px">
          </td>
        </tr>
        <tr>
          <td><label for="custo">Custo:</label></td>

          <td><input type="number" name="custo" id="custo" value="<?= $custo ?>" min="0" step="0.01" style="width:120px"> </td>
        </tr>
        <tr>
          <td><label for="data">Data:</label></td>
          <td><input type="date" name="data" id="data" value="<?= $data ?>" required> </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <?php if ($editar) {
              echo "<INPUT TYPE=\"hidden\" NAME=\"alterar\" VALUE=\"{$_GET["id"]}\">";
            ?>
              <input type="submit" name="botao" id="botao" value="Salvar" />
              <input type="submit" name="botao" id="botao" value="Excluir" />
              <input type="submit" name="botao" id="botao" value="Cancelar" />
            <?php } else { ?>
              <input type="submit" name="botao" id="botao" value="Cadastrar" />
              <input type="reset" name="botao" id="botao" value="Cancelar" />
            <?php } ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </form>

  <?php include "../pages/footer.php"; ?>
  <script type="text/javascript">
    function valida() {
      if (document.getElementById('idproduto').value == "") {
        alert('É necessário preencher o campo produto!');
        busca();
        return false;
      }
      return true;
    }

    function busca() {
      window.open(`popupbusca.php?table=produto&pk=idproduto&field=descricao&fid=idproduto&fdesc=descricao&fadic=unidade&cadastro=Produtos', 'Consulta', 'toolbar=no, location=no,directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=400`);
      document.getElementById('quantidade').focus();
      return false;
    }

    function consulta() {
      window.open(`popupconsulta.php?table=entrada&pk=identrada&field=data&cadastro=Entradas&location=entrada.php&rev=1', 'Consulta', 'toolbar=no, location=no, directories=no, status=no,menubar=no, scrollbars=yes, resizable=yes, width=500, height=400`);
      return false;
    }
  </script>
</body>

</html>