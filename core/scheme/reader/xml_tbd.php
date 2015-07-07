<?php
/**
 * The One_Scheme_Reader_Xml class loads One_Scheme definitions and instantiates them
 * as a factory pattern.
 *


  * @TODO review this file and clean up historical code/comments
 * @subpackage Scheme
ONEDISCLAIMER

 **/

//@TODO: wtf are subschemes ??

class One_Scheme_Reader_Xml implements One_Scheme_Reader_Interface
{
	/**
	 * Loads a scheme along with it's attributes, behaviors, relations, tasks, store and other information about the scheme
	 *
	 * @param $schemeName
	 * @return One_Scheme
	 */
	public static function load($schemeName)
	{
		$scheme    = new One_Scheme($schemeName);
		$subscheme = NULL;

		$dom = new DOMDocument('1.0', 'utf-8');

		$fileName = $schemeName;
		if(strpos($schemeName, '%') !== false) // match to see if we are looking for a subscheme
		{
			$schemeMatch = explode('%', $schemeName, 2);
			$fileName  = $schemeMatch[1];
			$subscheme = $schemeMatch[2];
		}

		$schemepath = One_Locator::locate('meta'.DS.'scheme'.DS.$fileName.'.xml');
    if ($schemepath === null) {
				throw new One_Exception('INVALID_SCHEME^^schemename='.$fileName);
    }

		if(!$dom->load($schemepath))
			throw new One_Exception('INVALID_SCHEME^^schemename='.$fileName);

		$xpath = new DOMXPath($dom);

		// If we are dealing with a subscheme, set the root where we'll be looking for as the group,
		// otherwise load the entire scheme
		if(!is_null($subscheme))
			$nodelist = $xpath->query('/scheme/attributes/group[@name="'.$subscheme.'"]');
		else
			$nodelist = $xpath->query('/scheme');

		if( $nodelist->length > 0 )
			$meta = $nodelist->item(0);
		else
			throw new One_Exception('INVALID_SCHEME^^schemename='.$fileName);


		// Get the metastructure from a remote location if set
		if($meta->hasAttribute('remote'))
		{
			$remote = $meta->getAttribute('remote');
			$rMeta  = One_Loader_Scheme_Remote::getScheme($schemeName, $remote);
			if(!is_null($rMeta))
				$meta = $rMeta;
			else
				throw new One_Exception('INVALID_SCHEME^^schemename='.$fileName);
		}

		$schemeType = (trim($meta->getAttribute( 'type' )) == '') ? 'xml' : trim($meta->getAttribute('type'));
		$scheme->setType($schemeType);

//		$scheme->setMeta( $meta ); // @TODO Should this still be here? Definately don't pass the DomElement!!
		self::setAttributes($scheme, $xpath, $meta, $subscheme);

		self::setLinks($scheme, $xpath, $meta);

		self::setBehaviors($scheme, $xpath, $meta);

		self::setRules($scheme, $xpath, $meta);

		self::setConnection($scheme, $xpath, $meta);

		self::setInformation($scheme, $xpath, $meta);

		self::setSubschemes($scheme, $xpath, $meta);

		self::setRoutings($scheme, $xpath, $meta);

		return $scheme;
	}

	/**
	 * Set the attributes of the scheme
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 * @param string $subscheme
	 */
	protected static function setAttributes(One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta, $subscheme = NULL)
	{
		// create the attribute set for this scheme
		$attributes      = array();
		$aliasAttributes = array();

		$rAttributes = $xpath->query($meta->getNodePath().'/attributes/attribute');
		for($i = 0; $i < $rAttributes->length; $i++)
		{
			$att_spec = $rAttributes->item($i);

			// Get the name and type of the attribute and remove them to easilly pass the other attributes
			$att_name = $att_spec->getAttribute('name');
			$att_type = $att_spec->getAttribute('type');
			$att_spec->removeAttribute('name');
			$att_spec->removeAttribute('type');

			$att_options = array();
			foreach($att_spec->attributes as $att)
			{
				$att_options[$att->name] = $att->value;
			}

			if('calculated' == $att_type) {
				$att_type = 'calculated_'.ucfirst($scheme->getName()).'_'.ucfirst($att_name);
			}
			$a = new One_Scheme_Attribute($att_name, $att_type, $att_options);

			$attributes[$a->getName()] = $a;
			// PD17OCT08 (added) : process alias as well
			if ($a->getAlias()) $aliasAttributes[ $a->getAlias() ] = $a;

			// When dealing with a flex-type attribute, automatically add the flex behavior
			if($a->getType() instanceof One_Type_Flex)
			{
				$behavior = One_Repository::getBehavior('flex', $scheme->getName());
				$spec = array('flexfield' => $a->getName());
				$scheme->addBehavior($behavior, $spec);
			}

			// When dealing with a calculated-type attribute, automatically add the flex behavior
			if($a->getType() instanceof One_Type_Calculated)
			{
				$behavior = One_Repository::getBehavior('calculated', $scheme->getName());
				$spec = array('attribute' => $a->getName());
				$scheme->addBehavior($behavior, $spec);
			}
		}

		if(!is_null($subscheme)) // set id-attribute for subschemes hardcoded
		{
			$a = new One_Scheme_Attribute('id', 'int', array('identity' => 'true'));
			$attributes[$a->getName()] = $a;
			if ($a->getAlias()) $aliasAttributes[$a->getAlias()] = $a;
		}

		$scheme->setAttributes($attributes);
		$scheme->setAliasAttributes($aliasAttributes);
	}

	/**
	 * Set links for the scheme when present
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 */
	protected static function setLinks(One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta)
	{
		// 	create the link set for this scheme
		$relations = $xpath->query($meta->getNodePath().'/relations/relation');
		for($i = 0; $i < $relations->length; $i++)
		{
			$relationSpec = $relations->item($i);
			$relation = One_Repository::getRelation($relationSpec->getAttribute('name'));
			$relation->createLinks($scheme);
		}
	}

	/**
	 * Set behaviors for the scheme when present
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 */
	protected static function setBehaviors( One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta )
	{
		$behaviors = $xpath->query($meta->getNodePath().'/behaviors/behavior');
		for($i = 0; $i < $behaviors->length; $i++)
		{
			$behaviorSpec = $behaviors->item($i);
			$behavior     = One_Repository::getBehavior($behaviorSpec->getAttribute('name'), $scheme->getName());
			$spec         = array();
			for($j = 0; $j < $behaviorSpec->attributes->length; $j++)
			{
				$attr = $behaviorSpec->attributes->item($j);
				$spec[$attr->name] = $attr->value;
			}
			$scheme->addBehavior($behavior, $spec);	//TODO: allow parameters per scheme
		}
	}

	/**
	 * Set the rules for the scheme
	 * These rules define when a user is allowed to perform a certain task
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 */
	protected static function setRules( One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta )
	{
		$tasks = $xpath->query($meta->getNodePath().'/tasks/task');
		for($i = 0; $i < $tasks->length; $i++)
		{
			$taskSpec = $tasks->item($i);
			$conditions = $taskSpec->getElementsByTagName('conditions');

			if($conditions->length > 0)
					$conditions = $conditions->item(0);
			else
				continue;

			if($conditions->childNodes->length > 0)
			{
				$condition = $conditions->firstChild;
				do
				{
					switch($condition->nodeName)
					{
						case 'cinclude':
							self::parseInclude($scheme, $xpath, $condition->getAttribute('file'), $taskSpec->getAttribute('name'));
							break;
						case 'condition':
							self::parseCondition($scheme, $xpath, $condition, $taskSpec->getAttribute('name'));
							break;
					}
					$condition = $condition->nextSibling;
				}while(!is_null($condition));
			}
		}
	}

	/**
	 * Set the One_Store used for the scheme
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 */
	protected static function setConnection(One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta)
	{
		// TR20100615 don't set if empty EG: dummy schemes
		$connectionSpec = $xpath->query($meta->getNodePath().'/connection');
		if($connectionSpec->length > 0)
		{
			$connection = $connectionSpec->item(0);
			$connectionName = trim($connection->getAttribute('name'));
			if($connectionName != '')
			{
				$scheme->setConnection(One_Repository::getConnection($connectionName));
				$connectionAtts = array();
				$allAttributes = $connection->attributes;
				for($i = 0; $i < $allAttributes->length; $i++)
				{
					$connectionAtt = $allAttributes->item($i);
					$connectionAtts[$connectionAtt->name] = $connectionAtt->value;

				}
				// Set resources like as an array, because this is not necesarily the same for different stores
				// EG: mysql has table, SugarCRM has module
				$scheme->setResources($connectionAtts);
			}
		}
	}

	/**
	 * Set any extra information to the scheme if present
	 * This can be a better readable title, descriptions, whether it's an internal scheme or not, ...
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 */
	protected static function setInformation(One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta)
	{
		$infoSpec = $xpath->query($meta->getNodePath().'/info');
		if($infoSpec->length == 1)
		{
			$info = $infoSpec->item(0);
			if($info->hasChildNodes())
			{
				$child = $info->firstChild;
				do
				{
					if($child->nodeType == XML_ELEMENT_NODE)
					{
						$nodeName = $child->nodeName;
						$content  = $child->textContent;
						if(in_array($nodeName, array('title', 'description', 'image', 'group', 'grouporder'))) {
							$scheme->$nodeName = $content;
						}
						elseif($nodeName == 'options')
						{
							$options = preg_split('!\s*,\s*!', $content);
							foreach($options as $option) {
								$scheme->information[$option] = $option;
							}
						}
						else
							$scheme->information[$nodeName] = $content;
					}
					$child = $child->nextSibling;
				}while(!is_null($child));
			}
		}
	}

	/**
	 * If the given scheme had any subschemes, auto-load them as well
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param string $meta
	 */
	protected static function setSubschemes(One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta)
	{
		// add grouped attributes and create their own scheme
		$rGroups = $xpath->query($meta->getNodePath().'/attributes/group');
		if($rGroups->length > 0)
		{
			for($i = 0; $i < $rGroups->length; $i++)
			{
				$group = $rGroups->item($i);

				// set subrelation
				$subscheme = One_Repository::getScheme($schemeName.'%'.$group->getAttribute('name'));
				$relation  = One_Repository::getRelation('subscheme%'.$schemeName.'%'.$group->getAttribute('name'));
				$relation->createLinks($scheme);
				$scheme->setSubscheme($group->getAttribute('name'), $subscheme);
			}

			unset($a);
			// a subscheme is by definition linked to a text-type attribute
			$a = new One_Scheme_Attribute($group->getAttribute('name'), 'text');
			$scheme->addAttribute($a);
		}
	}

	/**
	 * Set routings for the scheme when present
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $meta
	 */
	protected static function setRoutings(One_Scheme $scheme, DOMXPath $xpath, DOMElement $meta)
	{
		$routings = $xpath->query($meta->getNodePath().'/routings/routing');
		for($i = 0; $i < $routings->length; $i++)
		{
			$routingSpec = $routings->item($i);
			$spec        = array();
			for($j = 0; $j < $routingSpec->attributes->length; $j++)
			{
				$attr = $routingSpec->attributes->item($j);
				$spec[strtolower($attr->name)] = $attr->value;
			}

			if(isset($spec['alias'])) {
				$alias = $spec['alias'];
				unset($spec['alias']);

				$useId = false;
				if(isset($spec['useid']))
				{
					if(in_array(trim($spec['useid']), array('true', '1', 'yes'))) {
						$useId = true;
					}
					unset($spec['useid']);
				}

				One_Routing::addAlias($scheme, $alias, $spec, $useId);
			}
		}
	}

	/**
	 * Parse conditions that are used to see whether you are allowed to perform certain actions on the scheme
	 *
	 * @param One_Scheme $scheme
	 * @param DOMXPath $xpath
	 * @param DOMElement $element
	 * @param string $name name of the rule
	 * @return One_Permission_Rule
	 */
	protected static function parseCondition(One_Scheme $scheme, DOMXPath $xpath, DOMElement $element, $name = NULL)
	{
		foreach(array('rule', 'and', 'or') as $roa)
		{
			$currs = $xpath->query($element->getNodePath().'/'.$roa);
			if($currs->length > 0)
			{
				$curr = $currs->item(0);

				if($roa == 'and' || $roa == 'or')
				{
					$p = new One_Permission_Rule_Joiner(array('type' => $roa, 'not' => (($curr->getAttribute('not') == 'not') ? true : false)));

					$rules = $curr->getElementsByTagName('rule');
					if($rules->length > 0)
					{
						for($i = 0; $i < $rules->length; $i++)
						{
							$rule = $rules->item($i);
							$p->addRule(new One_Permission_Rule(array('type' => $rule->getAttribute('type'), 'not' => (($rule->getAttribute('not') == 'not') ? true : false))));
						}

						unset($rule);
					}

					if($curr->getElementsByTagName('and')->length > 0 || $curr->getElementsByTagName('or')->length > 0) {
						$p->addRule(self::parseCondition($curr, $scheme));
					}

				}
				else if($roa == 'rule') {
					$p = new One_Permission_Rule(array('type' => $curr->getAttribute('type'), 'not' => (($curr->getAttribute('not') == 'not') ? true : false)));
				}
			}
		}

		if(!is_null($name))
		{
			$tasks = explode(';', $name);
			foreach($tasks as $task)
			{
				if(trim( $task ) != '') {
					$scheme->addRule($task, $p);
				}
			}
		}

		return $p;
	}

	/**
	 * Parse an include file that contains conditions
	 *
	 * @param One_Scheme $scheme
	 * @param string $include Name of the include file
	 * @param string $name of the condition
	 * @return One_Permission_Rule
	 */
	protected static function parseInclude(One_Scheme $scheme, $include, $name = NULL)
	{
		$filename = One_Config::getInstance()->getCustomPath().DS.'conditions'.DS.strtolower($include).'.xml';
		if(!file_exists($filename))
		{
			$filename = One_Config::getInstance()->getPath().DS.'conditions'.DS.strtolower($include).'.xml';
			if(!file_exists($filename)) {
				return;
			}
		}

		$dom = new DOMDocument('1.0', 'utf-8');
		if($dom->load($filename))
		{
			$xpath = new DOMXPath($dom);
			$conditions = $xpath->query('/conditions');

			if($conditions->length > 0) {
				$conditions = $conditions->item(0);
			}
			else {
				return;
			}

			if($conditions->childNodes->length > 0)
			{
				$condition = $conditions->firstChild;
				do
				{
					switch($condition->nodeName)
					{
						case 'cinclude':
							self::parseInclude($condition->getAttribute('file'), $scheme);
							break;
						case 'condition':
							self::parseCondition($scheme, $xpath, $condition, $scheme);
							break;
					}
					$condition = $condition->nextSibling;
				}while(!is_null($condition));
			}
		}

		return $p;
	}
}
