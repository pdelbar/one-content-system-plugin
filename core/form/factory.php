<?php
/**
 * One_Form creates a form and loads all conditions and constraints
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 **/
class One_Form_Factory
{
	/**
	 * @var array Available containers and widgets
	 */
	public static $availableCW = NULL;

	/**
	 * @var array
	 */
	public static $_conditions;

	/**
	 * Create a new OneFormContainerForm
	 *
	 * @param One_Scheme $scheme
	 * @param string $formname
	 * @param string $action
	 * @param string $formFile location of the file, admin/form.xml by default
	 * @return OneFormContainerForm
	 */
	public static function createForm(One_Scheme $scheme, $formFile = NULL, $language = NULL, $formname = 'oneForm', $action = '', $method = 'post')
	{
		if(is_null($language)) {
			$language = One::getInstance()->getLanguage();
		}

		$form = One_Form_Reader_Xml::load($scheme, $formFile, $language, $formname, $action, $method);

		return $form;
	}

	/**
	 * Get all available containers and widgets
	 *
	 * @return array
	 */
	public static function getAvailable()
	{
		if(is_null(self::$availableCW))
		{
      $places = One_Loader::locateAllUsing('*.php','%ROOT%form/container/');
      $ignore = array('abstract', 'index', 'factory');
			foreach ($places as $container)
			{
				if(preg_match('|([^/]*).php$|', $container, $match))
				{
					if(in_array($match[1], $ignore) || strpos($match[1], '.') !== false) {
						unset($containers[$cKey]);
					}
					else {
						$containers[$cKey] = strtolower($match[1]);
					}
				}
				else {
					unset($containers[$cKey]);
				}
			}
			sort($containers);

      $places = One_Loader::locateAllUsing('*.php','%ROOT%/form/widget/{joomla,multi,scalar,select,search}/');
			foreach($places as $widget)
			{
					if(preg_match('|([^/]*)/([^/]*).php$|', $widget, $match))
					{
						if(!in_array($match[2], $ignore)) {
							$widgets[] = $match[1]. '-' . strtolower($match[2]);
						}
				}
			}
			$widgets[] = 'button';
			$widgets[] = 'file';
			$widgets[] = 'submit';
			$widgets[] = 'label';
			$widgets[] = 'button';
			$widgets[] = 'nscript';
			$widgets[] = 'inline';					//TODO: adding a widget here should not be necessary !!

			sort($widgets);

			self::$availableCW = array(
											'containers' => $containers,
											'widgets'    => $widgets
										);
		}

		return self::$availableCW;
	}

	/**
     * Check whether the user is authorized by this condition
     *
     * @param string $condition
     * @param array $args
     * @return boolean
     */
    public static function authorize($condition, $args )
    {
        if(array_key_exists((string) $condition, (self::$_conditions))) {
            $cond = self::$_conditions[(string) $condition];
            $status = $cond->authorize( $args );
            return $status;
        }
        else
            throw new One_Exception('The rule "' . (string) $condition . '" does not exist');
    }

}
