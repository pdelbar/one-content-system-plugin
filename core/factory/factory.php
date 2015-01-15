<?php
/**
 * The introduction of a One_Factory as a means to create instances of a particular scheme
 * makes it possible to extend the selection of models beyond select using query and select a single
 * instance. In many cases, it is desirable to encapsulate specific selections in a class and avoid a
 * situation where a controller needs to manipulate a query to select recurring definitions. For instance,
 * for a scheme 'invoice' it may be useful to select open invoices, paid invoices, ... without having
 * to specify the details of how the selection works outside the model factory.
 *
 * The One_Repository currently resolves the select functionality. This should be deferred internally
 * to the appropriate One_Factory (subclassed for a particular scheme). The use of One_Repository
 * for creating selections should be deprecated, leading to a meta-level only use of One_Repository.
 *
 * @author delius
 * @copyright 2010 delius bvba
 * @package one|content
 * @filesource one/lib/factory/factory.php
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 **/
class One_Factory implements One_Factory_Interface
{
	/**
	 * @var One_Scheme One_Scheme concerning the factory
	 */
	protected $scheme = NULL;

	/**
	 * Class constructor
	 *
	 * @param One_Scheme $scheme
	 */
	public function __construct(One_Scheme $scheme)
	{
		$this->scheme = $scheme;
	}

	/**
	 * Select One_Models according to the specified selectors
	 *
	 * @param string $schemeName
	 * @param array $selectors
	 * @return array
	 * @deprecated
	 */
	public function select($selectors = array())
	{
		throw new One_Exception_Deprecated('should no longer be used');
		/*$scheme = $this->scheme;
		$store = $scheme->store();
		$strategy = $store->strategy();

		$query = One_Repository::selectQuery( $this->scheme->getName() );

		return $strategy->select( $query, $selectors );*/
	}

	/**
	 * Select a single instance.
	 *
	 * @param string $schemeName
	 * @param mixed $identityValue
	 * @return One_Model
	 */
	public function selectOne($identityValue)
	{
		$connection = $this->scheme->getConnection();
		$store = $connection->getStore();
		return $store->selectOne($this->scheme, $identityValue);
	}

	/**
	 * Get a One_Query instance
	 *
	 * @param string $schemeName
	 * @param mixed $identityValue
	 * @return One_Query
	 */
	public function selectQuery()
	{
		$q = One_Repository::selectQuery($this->scheme->getName());
		return $q;
	}

	/**
	 * Return the number of One_Models of the specified kind
	 *
	 * @param string $schemeName
	 * @return int
	 */
	public function selectCount()
	{
		$scheme = $this->scheme;
		$query = new One_Query($scheme);

		$behaviors = $scheme->getBehaviors();
		if($behaviors) {
			foreach($behaviors as $behavior) {
				$behavior->onSelect($query);
			}
		}

		return $query->getCount();
	}

	/**
	 * Return an empty instance of this model
	 *
	 * @return One_Model
	 */
	public function getInstance()
	{
		return One_Repository::getInstance($this->scheme->getName());
	}

	/**
	 * Returns the One_Scheme used in this factory
	 *
	 * @return One_Scheme
	 */
	public function getScheme()
	{
		return $this->scheme;
	}
}