<?php
try {
	$PDO = new PDO("mysql:host=localhost; dbname=estoque; charset=utf8", 
"estoque", "3st0qu3");
	$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
	die("Erro de conexão com BD: " . $e->getMessage());
} 
?>