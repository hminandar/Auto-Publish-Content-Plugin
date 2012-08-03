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
		$this->_params = new JParameter( $this->_plugin->params );
	}

	function onAfterRender()
	{

		global $mainframe;
		$db =& JFactory::getDBO();

		$APcategories 	= $this->params->get( 'categories', '' );
		$APsections 	= $this->params->get( 'sections', '' );

		if($APcategories == '' && $APsections == '')
		{
			return;
		}
		
	//categories
		if($APcategories!=''){

			$query = 'INSERT INTO jos_content_frontpage (content_id, ordering) 
					  SELECT id, 0 FROM jos_content WHERE catid IN ('.$APcategories.') AND id NOT IN (SELECT content_id from jos_content_frontpage)';
			$db->Execute($query);
		}	
	//sections
		if($APsections!=''){
			$query = 'INSERT INTO jos_content_frontpage (content_id, ordering) 
					  SELECT id, 0 FROM jos_content WHERE sectionid IN ('.$APsections.') AND id NOT IN (SELECT content_id from jos_content_frontpage)';
			$db->Execute($query);
		}

		return true;

	}	
}
?>