<?php
/**
 *  The oneToMany link type is used to link this object to many other
 *  objects. To do that, these objects must keep a foreign key value to
 *  this object.
 *
 * 	The foreign key included consists of all identity fields of the target.
 *


  * @TODO review this file and clean up historical code/comments
ONEDISCLAIMER

 **/
class One_Link_Type_Onetosubscheme extends One_Link_Type_Onetomany
{
	/**
	 * Returns the name of this linktype
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'onetosubscheme';
	}

	/**
	 * Return all related items
	 *
	 * @return array
	 */
	public function getRelated(One_Link_Interface $link, One_Model $model, array $options = array())
	{
		$tScheme  = One_Repository::getScheme( $link->getTarget() );
		$tAttr    = $link->getName();


		if( $tScheme->getType() == 'json' )
			$items = json_decode( $model->$tAttr );
		else if( $tScheme->getType() == 'xml' )
		{
			try
			{
				$dom = new DOMDocument( '1.0', 'utf-8' );
				if( !$dom->loadXML( '<root>' . $model->$tAttr . '</root>' ) )
					return false;

				$xpath = new DOMXPath( $dom );
				$nodes = $xpath->query( '/root/item' );

				$items = array();
				for( $i = 0; $i < $nodes->length; $i++ )
				{
					$item = $nodes->item( $i );
					if( $item->hasChildNodes() )
					{
						$itemData = array( 'id' => ( $i + 1 ) );

						$child    = $item->firstChild;
						do
						{
							if( $child->nodeType == 1 )
								$itemData[ $child->nodeName ] = $child->textContent;
							$child = $child->nextSibling;
						}while( !is_null( $child ) );

						$items[] = $itemData;
					}
				}
			}
			catch ( Exception $e )
			{
				throw new One_Exception( $e->getMessage() );
			}
		}

		$data = array();

		if( count( $items ) > 0 )
		{
			foreach( $items as $item )
			{
				$data[] = $this->arrayToInstance( $tScheme, $item );
			}
		}

		return $data;
	}

	/**
	 * Overload toString function
	 * @param mixed
	 * @return string
	 */
	public function toString( $value )
	{
		return $value;
	}

	private function arrayToInstance( $scheme, $row )
	{
		// check the scheme cache
		$idAttribute = $scheme->getIdentityAttribute();
		$id          = $row[ $idAttribute->getName() ];

		$cached = One_Model_IdentityMap::find($scheme->getName(), $id);
		if ($cached) return $cached;

		$model = One::make($scheme->getName());

		$model->fromArray($row);

		// fire afterLoad event for model
		$model->afterLoad();

		One_Model_IdentityMap::add($model);

		return $model;
	}
}
