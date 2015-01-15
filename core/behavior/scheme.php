<?php
/**
 * The One_Scheme _Behaviorabstract class implements post-load and pre-save
 * events, used in mixins for the scheme
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/behavior/scheme.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @abstract
 **/
abstract class One_Behavior_Scheme extends One_Behavior
{
	/**
	 * Function performed after loading the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function afterLoadModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed before updating the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function beforeUpdateModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed before inserting the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function beforeInsertModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed after inserting the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function afterInsertModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed before deleting the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function beforeDeleteModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed after deleting the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function afterDeleteModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed on creation of the model
	 *
	 * @param string $schemeName
	 */
	public function onCreateModel( $schemeName )
	{
		return null;
	}

	/**
	 * Function performed on loading the scheme
	 *
	 * @param One_Scheme $scheme
	 */
	public function onLoadScheme(One_Scheme $scheme){}

	/**
	 * Function performed after updating the model
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function afterUpdateModel(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed on authorisation
	 *
	 * @param One_Scheme $scheme
	 * @param One_Model $model
	 */
	public function onAuthorize(One_Scheme $scheme, One_Model $model){}

	/**
	 * Function performed on selection
	 *
	 * @param One_Query $query
	 */
	public function onSelect(One_Query $query ){}


  public function icon()
  {
    return 'icon-cog';
  }
}
