<?php
/**
 * The One_Store_Connection_Joomla class supplies the connection to a Joomla database
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Store

 **/
class One_Store_Connection_Joomla extends One_Store_Connection_Abstract
{
	/**
	 * Open the connection
	 * @return One_Store_Connection_Mysql
	 */
	public function open()
	{
		$db = JFactory::getDBO();

		// Set the proper encoding if needed
		$encoding = $this->getEncoding();
		if(null != $encoding)
		{
			$db->setQuery('SET NAMES "'.$db->getEscaped($encoding).'"');
			$db->query();
		}

		return $db;
	}
}
