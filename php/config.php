<?php

$host = 'localhost';
$user = "gleytton.figueiredo";
$senha = "$123Mudar";
$db = "iwdbd";
$caminho = "mysql:dbname=$db;host=$host"; 

try{
	$pdo = new PDO($caminho, $user, $senha);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
	// Log or display the error message for debugging
	echo "Database connection failed: " . $e->getMessage();
}