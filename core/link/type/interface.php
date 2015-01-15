<?php
/**
 * One_Link_Type is the way how the relationship is built.
 * One-to-many, many-to-one or many-to-many
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
interface One_Link_Type_Interface
{
	/**
	 * Returns the name of the linktype
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Instantiate the related objects
	 *
	 * @param One_Link_Interface $link
	 * @param One_Model_Interface $model
	 * @param array $options @see One_Query::setOptions()
	 * @return One_Model|array Can be One_Model or array of One_Models
	 */
	public function getRelated(One_Link_Interface $link, One_Model_Interface $model, array $options = array());

	public function countRelated(One_Link_Interface $link, One_Model_Interface $model, array $options = array());
}