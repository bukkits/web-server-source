<?php

namespace vardump;

class IntVariable extends Variable{
	private $int;
	public function __construct(VarDumpParser $parser){
		$this->int = intval($parser->readUntil(")"));
		$parser->skip(1);
	}
	public function presentInHtml(){
		echo Variable::TYPE_INT;
		echo ":";
		echo "<ul>";
		echo "<li>Decimal (base 10): <code>$this->int</code></li>";
		echo "<li>Binary (base 2): <code>";
		printf("%04b", $this->int);
		echo "<sub>2</sub></code></li>";
		echo "<li>Hexadecimal (base 16): <code>";
		printf("%02X", $this->int);
		echo "<sub>16</sub></code></li>";
		echo "</ul>";
	}
}