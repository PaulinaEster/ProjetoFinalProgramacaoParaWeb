<!DOCTYPE html>
<html> 
<?php
if (isset($_GET["cadastro"])) $title = "Consulta de ".$_GET["cadastro"];
else $title = "Consulta";
?>
<head>
    <title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

</head> 
<body> 
<?php 
include "bd.php";
?>
<form name="fBusca" id="fBusca" method="GET" action="popupbusca.php">
	<label for="busca">Busca:</label><br />
    <input type="text" name="busca" id="busca" size="40" maxlength="40" autofocus>
    <input type="submit" name="botao" id="botao" value="Buscar" />
    <br /><br />
<?php	
$busca = "";
    $table = "";
    $pk    = "";
    $fid   = "";
    $desc  = "";
    $fdesc = "";
    $desc  = "";
    $fadic = "";    
    if (isset($_GET["table"])) {
        $table = $_GET["table"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"table\" VALUE=\"{$table}\">";
    }       
    if (isset($_GET["pk"])) {
        $pk    = $_GET["pk"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"pk\" VALUE=\"{$pk}\">";
    }       
    if (isset($_GET["fid"])) {
        $fid = $_GET["fid"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"fid\" VALUE=\"{$fid}\">";
    } 
    if (isset($_GET["desc"])) {
        $fdesc  = $_GET["desc"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"desc\" VALUE=\"{$desc}\">";
    }      
    if (isset($_GET["fdesc"])) {
        $fdesc  = $_GET["fdesc"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"fdesc\" VALUE=\"{$fdesc}\">";
    }
    if (isset($_GET["fadic"])) {
        $fadic  = $_GET["fadic"];
        echo "<INPUT TYPE=\"hidden\" NAME=\"fadic\" VALUE=\"{$fadic}\">";
    }        
	if (isset($_GET["busca"])) {
		$busca = trim($_GET['busca']);
		$sqlconsulta = "SELECT * FROM ".$table." WHERE ".$fdesc." LIKE \"%".$busca."%\" order by ".$fdesc;        
		}
	else {
			$sqlconsulta = "SELECT * from ".$table." order by ".$fdesc;
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
        $nome=$linha[$fdesc];
        if ($fadic != "") $vfadic = $linha[$fadic];
        $nome= str_replace("'", " ", $nome);
		echo "<tr><td><a href=\"javascript:retorna('{$linha[$pk]}', '$nome'";
        if ($fadic != "") echo ", '$vfadic'";
        echo ")\">{$linha[$pk]}</a></td>";
		echo "<td><a href=\"javascript:retorna('{$linha[$pk]}', '$nome'";
        if ($fadic != "") echo ", '$vfadic'";
        echo ")\">{$nome}</a></td></tr>";
		$cont++;
		}
	for($i=$cont;$i<=$registros;$i++) echo "<tr><td>&nbsp</td><td></td></tr>";
	echo '</table>'; 
    $c = 0;
	if ($numPaginas>1) { //exibe a paginação
        echo "<center>";
        if ($pagina>1) {
        	$anterior = $pagina-1;
            echo "<a href='popupbusca.php?busca=$busca&pagina=$anterior&pk=$pk&table=$table&fid=$fid&fdesc=$fdesc&desc=$desc&fadic=$fadic'> << Anterior</a> ";  
        	}
        for($i = 1; $i < $numPaginas + 1; $i++) {
            if ($i==1 || $i == $numPaginas || ($i <= $pagina +2 && $i >= $pagina -2)) {
                if ($i==$pagina) {
                    echo " <b>$i</b> ";
                	}
                else {
                    echo "<a href='popupbusca.php?busca=$busca&pagina=$i&pk=$pk&table=$table&fid=$fid&desc=$desc&fdesc=$fdesc&fadic=$fadic'>".$i."</a> ";  
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
                        echo "<a href='popupbusca.php?busca=$busca&pagina=$proxima&pk=$pk&table=$table&fid=$fid&desc=$desc&fdesc=$fdesc&fadic=$fadic'> Próxima >></a> ";  
                        }
                            
            echo "</center>";
         
    }
?> 
<script type="text/javascript"> //função javascript que retornará o codigo 
function retorna(id, desc, adic = undefined)//passando um parametro 
{ 
    //a janela mãe recebe o id, você precisa passar o nome do formulario e do textfid que receberá o valor passado por parametro. 
    var e = window.opener.document.getElementById("<?=$fid ?>");
    e.value = id;
    var e = window.opener.document.getElementById("<?=$fdesc ?>");
    e.value = desc;
    if (adic != undefined) {
        var e = window.opener.document.getElementById("<?=$fadic ?>");
        e.value = adic;    
    }
    window.close(); //fecha a janla popup 
} 
</script> 

</body> 
</html> 