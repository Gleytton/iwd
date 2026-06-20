<?php

$host = '147.65.190.60';
$user = "daniel.teixeira";
$senha = "123mudar";
$db = "nmapdbtest";
$caminho = "mysql:dbname=$db;host=$host"; 

try{
	$pdo = new PDO($caminho, $user, $senha);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
	// Log or display the error message for debugging
	echo "Database connection failed: " . $e->getMessage();
}