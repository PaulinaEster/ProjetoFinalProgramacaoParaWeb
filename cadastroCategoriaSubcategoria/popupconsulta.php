<!DOCTYPE html>
<html> 
<?php
if (isset($_GET["cadastro"])) $title = "Consulta de ".$_GET["cadastro"];
else $title = "Consulta";
?>
<head>
    <title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php 
    $location = "";
    if (isset($_GET["location"])) $location = $_GET["location"];
?>

<script type="text/javascript"> //função javascript que retornará o codigo 
function retorna(id, descricao)//passando um parametro 
    { 
    //a janela mãe recebe o id, você precisa passar o descricao do formulario e do textfield que receberá o valor passado por parametro. 
    var location = <?php echo "'".$location."'\n"; ?>
    window.opener.document.location.href = location+'?id='+id;
    window.close();	//fecha a janla popup 
} 
</script> 
</head> 
<body> 
<form name="fConsulta" id="fConsulta" method="GET" action="popupconsulta.php">
	<label for="busca">Busca:</label><br />
    <input type="text" name="busca" id="busca" size="40" maxlength="40" autofocus>
    <input type="submit" name="botao" id="botao" value="Buscar" />
    <br /><br />
<?php
    include "bd.php";
    $stmt = $PDO->prepare("SET CHARACTER SET utf8");
    $stmt->execute();
	$busca = "";
    $table = "";
    $pk    = "";
    $field = "";
    if (isset($_GET["location"])){ 
        echo "<INPUT TYPE=\"hidden\" NAME=\"location\" VALUE=\"{$location}\">";
    }
    if (isset($_GET["table"])){
        $table = $_GET["table"];  
        echo "<INPUT TYPE=\"hidden\" NAME=\"table\" VALUE=\"{$table}\">";
    } 
    if (isset($_GET["pk"])) {
        $pk    = $_GET["pk"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"pk\" VALUE=\"{$pk}\">";
    }   
    if (isset($_GET["field"])) {
        $field = $_GET["field"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"field\" VALUE=\"{$field}\">";
    }
	if (isset($_GET["busca"])) {
		$busca = trim($_GET['busca']);        
		$sqlconsulta = "SELECT * FROM ".$table." WHERE ".$field." LIKE \"%".$busca."%\" order by ".$field;
		}
	else {
			$sqlconsulta = "SELECT * from ".$table." order by ".$field;
		}
    $consulta = $PDO->query($sqlconsulta);
    $total = $consulta->rowCount(); //conta o total de itens              
    $registros = 8;//seta a quantidade para 8 itens por página
    if($total < 1) {
        $numPaginas = 0;
        } 
    else {
    	$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1; //verifica a página atual caso seja informada na URL, senão atribui como 1ª página		
    	$numPaginas = ceil($total/$registros);
    	if($pagina<1) $pagina = 1;
    	if($pagina>$numPaginas) $pagina = $numPaginas;
    	$inicio = ($registros*$pagina)-$registros;

    	$sqlconsulta = $sqlconsulta." limit $inicio, $registros";

    	$consulta = $PDO->query($sqlconsulta);
    	}	   
	$cont=0;
	echo '<table>';
	while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        $descricao= $linha[$field];
        $descricao= str_replace("'", " ", $descricao);
		echo "<tr><td><a href=\"javascript:retorna('{$linha[$pk]}', '')\">{$linha[$pk]}</a></td>";
		echo "<td><a href=\"javascript:retorna('{$linha[$pk]}', '')\">{$descricao}</a></td></tr>";
		$cont++;
		}
	for($i=$cont;$i<=$registros;$i++) echo "<tr><td>&nbsp</td><td></td></tr>";
	echo '</table>'; 
    $c = 0;
	if ($numPaginas>1) { //exibe a paginação
        echo "<center>";
        if ($pagina>1) {
        	$anterior = $pagina-1;
            echo "<a href='popupconsulta.php?busca=$busca&pagina=$anterior&location=$location&pk=$pk&table=$table&field=$field'> << Anterior</a> ";  
        	}
        for($i = 1; $i < $numPaginas + 1; $i++) {
            if ($i==1 || $i == $numPaginas || ($i <= $pagina +2 && $i >= $pagina -2)) {
                if ($i==$pagina) {
                    echo " <b>$i</b> ";
                	}
                else {
                    echo "<a href='popupconsulta.php?busca=$busca&pagina=$i&location=$location&pk=$pk&table=$table&field=$field'>".$i."</a> ";  
                    }      
                $c = 0;                            
                }
            else   {
                if ($c==0) {
                    echo " ... ";
                    $c++;
                }
            }    
        }
            if ($pagina<$numPaginas) {
                        $proxima = $pagina + 1;
                        echo "<a href='popupconsulta.php?busca=$busca&pagina=$proxima&location=$location&pk=$pk&table=$table&field=$field'> Próxima >></a> ";  
                        }
                            
            echo "</center>";
         
    }
?> 
</form>
</body> 
</html> 