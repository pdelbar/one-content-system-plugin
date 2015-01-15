<?php
/**
 * The One_Store_Connection_Oci class supplies the connection to a Oracle/OCI database
 *
 * @author delius
 * @copyright 2012 delius bvba
 * @package one|content
 * @subpackage Store
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Store_Connection_Oci extends One_Store_Connection_Abstract
{
	/**
	 * Open the connection
	 * @return One_Store_Connection_Oci
	 */
	public function open()
	{
		$meta = $this->getMeta();
//die($meta['db']['tns'] );
		$link = oci_connect( $meta['db']['user'], $meta['db']['password'], $meta['db']['tns'] );
//print_r($link);
		if(!$link) {
//print_r(ocierror());
			throw new One_Exception('Not connected : ' . ocierror());
		}

		// Set the proper encoding if needed
/*
		$encoding = $this->getEncoding();
		if(null != $encoding)
		{
			mysql_query('SET NAMES "'.mysql_real_escape_string('.$encoding.').'"', $link);
		}
*/
		return $link;
	}

	/**
	 * Close the connection
	 * @return One_Store_Connection_Oci
	 */
	public function close($ch = NULL)
	{
		oci_close($ch);
	}
}
