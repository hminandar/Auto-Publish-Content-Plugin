<?php
/**
* @version		1.0
* @copyright	Theo van der Sluijs / IAMBOREDSOIBLOG.eu
* @license		1 euro per site.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemAutopublishcontent extends JPlugin
{

	function plgSystemAutopublishcontent(& $subject, $config)
	{
		parent::__construct($subject, $config);
		
		$this->_plugin = JPluginHelper::getPlugin( 'system', 'autopublishcontent' );
		$this->_params = $this->_plugin->params;
	}

	function onAfterRender()
	{

	global $mainframe;
		$db =& JFactory::getDBO();

		$APcategories 	= $this->params->get( 'categories', '' );
		$APpubfron 		= $this->params->get( 'pubfron', '' );

		if(isset($APcategories) || $APcategories != NULL){
			$APcategories = implode(",", $APcategories);
		}
		
		//auto frontpage all cats
		if(count($APcategories) < 1)
		{
		
			//DO NOTHING !
			
		}elseif(count($APcategories) > 0 ){

	
		//set to frontpage!
			if($APpubfron == 1 || $APpubfron == 2){		
/* THIS ONE DOES ALL !
			$query = 'INSERT INTO #__content_frontpage (content_id, ordering) 
						  SELECT id, 0 FROM #__content WHERE id NOT IN (SELECT content_id from #__content_frontpage)';
*/			
			
				$query = "INSERT INTO #__content_frontpage (content_id, ordering) 
						SELECT id, 0 FROM #__content WHERE catid IN ($APcategories) AND id NOT IN (SELECT content_id from #__content_frontpage)";
				$db->setQuery($query);		
				$result = $db->query();
			
				unset($result);
			
			//set featured
				$query = "UPDATE `jos_content` SET `featured` = 1 WHERE catid IN ($APcategories) AND `featured` = 0;";
				$db->setQuery($query);		
				$result = $db->query();
			
				unset($result);
			}
			
			$date =& JFactory::getDate();
			$formatedDate = $date->toFormat();

			//publish it
			if($APpubfron == 0 || $APpubfron == 2){
			
				$query = "UPDATE `jos_content` SET `publish_up` = '$formatedDate', `state` = 1 WHERE catid IN ($APcategories) AND (`publish_up` = '' OR `publish_up` is NULL OR `publish_up` = '0000-00-00 00:00');";
				
				$db->setQuery($query);		
				$result = $db->query();
			
				unset($result);
			}
		}
		
		return true;	

	}	
}
?>