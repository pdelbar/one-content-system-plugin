<?php
Interface One_Type_Calculated_Interface
{
	public function calculate(One_Model $model);
	public static function orderList(array $list = array(), $direction = 'asc');
	public function getNativeComposition();
}