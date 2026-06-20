<?php

class Info{

	private PDO $pdo;
	

	public function getPdo():PDO{

		return $this->pdo;
	}
	public function setPdo($pdo):void{

		$this->pdo = $pdo;
	}

	public function __construct($pdo){

		$this->setPdo($pdo);
	}


	
	public function formarObjeto($dado){

		return new Dados($dado['ip'],
						 $dado['name'],
						 $dado['data_scan'],
						 $dado['port'],
						 $dado['protocol'],
						 $dado['product'],
						 $dado['ostype'],
						 $dado['state']);
	}




	public function buscar(?string $ip = null): array
	{
	    $sql = "SELECT h.ip, h.name, h.data_scan, s.port, s.protocol, s.name AS product, h.ostype, s.state
	            FROM host AS h
	            JOIN host_service AS hs ON h.id = hs.host_id
	            JOIN service AS s ON s.id = hs.service_id
	            WHERE 1";
	    if ($ip) {
	        $sql .= " AND h.ip = :ip";
	    }
	    $sql .= " ORDER BY h.data_scan DESC, h.ip ASC";

	    $statment = $this->pdo->prepare($sql);
	    if ($ip) {
	        $statment->bindValue(':ip', $ip, PDO::PARAM_STR);
	    }
	    $statment->execute();

	    $dados = $statment->fetchAll(PDO::FETCH_ASSOC);

		return array_map(fn($dado) => $this->formarObjeto($dado), $dados);
	}
	
	public function buscarPorIp(string $ip): array
	{
	    $sql = "SELECT h.ip, h.name, h.data_scan, s.port, s.protocol, s.name AS product, h.ostype, s.state
	            FROM host AS h
	            JOIN host_service AS hs ON h.id = hs.host_id
	            JOIN service AS s ON s.id = hs.service_id
	            WHERE h.ip = :ip";

	    $statment = $this->pdo->prepare($sql);
	    $statment->bindValue(':ip', $ip, PDO::PARAM_STR);
	    $statment->execute();

	    $dados = $statment->fetchAll(PDO::FETCH_ASSOC);

	    return array_map(function($dado) {
	        return $this->formarObjeto($dado);
	    }, $dados);
	}	
}