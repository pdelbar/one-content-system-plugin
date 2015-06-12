<?php
/**
 * @deprecated
 */
class One_Bootstrap
{
	public static function initiate( $siteRoot, $path, $customPath )
	{
		throw new One_Exception_Deprecated('do your own bootstrapping, dude');
		// Register the autoloader
		One_Loader::register();

		One_Config::getInstance()
			->setUrl($siteRoot)
			->setCustomPath($customPath )
			->setUserStore('mysql')
			->setTemplater(new One_Template_Adapter_NanoPretend);
//		define( 'ONETEMPLATER', 'nano');

//		$tmp = $path . DS . 'nano' . DS;
//		define( 'ONE_SCRIPT_PATH', $tmp );
//		define( 'ONE_SCRIPT_CUSTOM_PATH', $customPath . DS . 'nano' );
//
//		require_once( $tmp . 'tools' . DS . 'autoload.php' );
		require_once( One_Config::getInstance()->getPath() . '/tools.php' );


	}
}
