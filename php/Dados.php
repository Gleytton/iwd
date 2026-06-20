<?php

class Dados{

	private string $ip;
	private string $osType;
	private string $name;
	private string $dataScan;
	private string $port;
	private string $protocol;
	private string $product;
	private string $state;

	
	public function getIp():string{
		return $this->ip;
	}
	public function setIp(string $ip):void{
		$this->ip = $ip;
	}
	public function getOsType():string{
		return $this->osType;
	}
	public function setOsType(string $osType):void{
		$this->osType = $osType;
	}
	public function getName():string{
		return $this->name;
	}
	public function setName(string $name):void{
		$this->name = $name;
	}
	public function getDataScan():string{
		return $this->dataScan;
	} 
	public function setDataScan(string $dataScan):void{
		$this->dataScan = $dataScan;
	}
	public function getPort():string{
		return $this->port;
	}
	public function setPort(string $port):void{
		$this->port = $port;
	}
	public function getProtocol():string{
		return $this->protocol;
	}
	public function setProtocol(string $protocol):void{
		$this->protocol = $protocol;
	}
	public function getProduct():string{
		return $this->product;
	}
	public function setProduct(string $product):void{
		$this->product = $product;
	}
	public function getState():string{
		return $this->state;
	}
	public function setState(string $state):void{
		$this->state = $state;
	}

	public function __construct(string $ip, string $name ,string $dataScan, string $port, string $protocol, string $product, string $osType, string $state) {

		$this->setIp($ip);
		$this->setName($name);
		$this->setDataScan($dataScan);
		$this->setPort($port);
		$this->setProtocol($protocol);
		$this->setProduct($product);
		$this->setOsType($osType);
		$this->setState($state);
	}
}