<?php
interface DataProvider
{
	public function next();
	
	public function total();
	
	public function count();
	
	public function row();
	
	public function pagesize();
	
	public function page();
}
?>