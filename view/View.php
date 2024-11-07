<?php
class View {
	private $assign = array();

	function screenView($resource_name){
		include $resource_name.".php";
		return $this->assign;
	}

	function setAssign($item,$value){
		$this->assign = $this->assign + [$item=>$value];
	}

	function getAssign(){
		return $this->assign;
	}
}
