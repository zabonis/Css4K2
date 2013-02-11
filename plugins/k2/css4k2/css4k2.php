<?php
/**
* @version 			Css4K2 1.8.x
* @package			Css4K2
* @url				http://www.jiliko.net
* @editor			Jiliko - www.jiliko.net
* @copyright		Copyright ( C ) 2012 JILIKO. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::register( 'K2Plugin',JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'k2plugin.php' );

class plgK2Css4K2 extends K2Plugin {

	// Some params
	var $pluginName = 'css4k2';
	var $pluginNameHumanReadable = 'Css4K2';

	public function __construct( & $subject, $config ) {
		parent::__construct( $subject, $config );
		$this->loadLanguage();
	}
	
	function onK2PrepareContent( & $item, & $params, $limitstart ) {
		$app = JFactory::getApplication();
	}

	function onK2AfterDisplay( & $item, & $params, $limitstart ) {
		$app = JFactory::getApplication();
		return '';
	}

	function onK2BeforeDisplay( & $item, & $params, $limitstart ) {
		$app = JFactory::getApplication();
		return '';
	}

	function onK2AfterDisplayTitle( & $item, & $params, $limitstart ) {
		$app = JFactory::getApplication();
		return '';
	}

	function onK2BeforeDisplayContent( & $item, & $params, $limitstart ) {
		$app = JFactory::getApplication();
		return '';
	}

	function onK2AfterDisplayContent( & $item, & $params, $limitstart ) {
		
		if ( !( $params->get( 'parsedInModule', 0 ) ) ) {
			$app = JFactory::getApplication();
			
			// Call loadCss function if:
			// We're in the k2 item view and the $item is the main item of the page
			// or we're not in the k2 item view and multiCss is on, this means we load all specific css files from displayed items
			$view = JRequest::getCmd( 'view' );
			$id = JRequest::getInt( 'id' );

			if ( $this->params->get( 'multiCss',0 && $view != 'item') || ( $view == 'item' && $id == $item->id ) ) {
				$this->loadCss( $item->params );
			} elseif( $view != 'item')  {
				$this->loadCss( $params );
			}
		}
		
		return '';
	}

	function onK2CategoryDisplay( & $category, & $params, $limitstart ) {
		
		if ( !( $params->get( 'parsedInModule', 0 ) ) ) {
			$app = JFactory::getApplication();
			
			// Call loadCss function if we're in the k2 itemlist view
			$view = JRequest::getCmd( 'view' );
			
			if ( $view == 'itemlist' )
				$this->loadCss( $params );
		}
		
		return '';
	}

	function onK2UserDisplay( & $user, & $params, $limitstart ) {
		
		if ( !( $params->get( 'parsedInModule', 0 ) ) ) {
			$app = JFactory::getApplication();
			
			// Call loadCss function if we're in the k2 itemlist view
			$view = JRequest::getCmd( 'view' );
			
			if ( $view == 'itemlist' )
				$this->loadCss( $params );
		}
			
		return '';
	}

	function onK2SherpaSearchDisplay( & $search, & $params, $limitstart ) {
		
		if ( !( $params->get( 'parsedInModule', 0 ) ) ) {
			$app = JFactory::getApplication();
			
			$this->loadCss( $params );
		}
		
		return '';
	}
	
	function loadCss( $params = null ) {
		$app = JFactory::getApplication();
	
		jimport( 'joomla.filesystem.file' );
		
		$theme = '';
		
		if ( isset( $params ) && is_object( $params ) )
			$theme = $params->get( 'theme','' );
		
		if( $theme == '' )
			$theme = 'default';

		$doc = & JFactory::getDocument();

		//We add the css file to the head of the document.
		//Testing where to get the custom K2 template css
		if ( JFile::exists( JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'com_k2'.DS.$theme.DS.$theme.'_style.css' ) )
			$doc->addStyleSheet( JURI::base().'templates/'.$app->getTemplate().'/html/com_k2/'.$theme.'/'.$theme.'_style.css' );
		elseif ( JFile::exists( JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'com_k2'.DS.'templates'.DS.$theme.DS.$theme.'_style.css' ) )
			$doc->addStyleSheet( JURI::base().'templates/'.$app->getTemplate().'/html/com_k2/templates/'.$theme.'/'.$theme.'_style.css' );
		elseif ( JFile::exists( JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'templates'.DS.$theme.DS.$theme.'_style.css' ) )
			$doc->addStyleSheet( JURI::base().'components/com_k2/templates/'.$theme.'/'.$theme.'_style.css' );	  
	  
		//If we DON'T want to keep the k2 css loaded
		if( !$this->params->get( 'keepK2Css',1 ) ) {
			//We load the head data of the document in an array
			$tabHead = $doc->getHeadData();

			//For each stylesheets loaded, we check the key ( the path & name of the css file )
			foreach( $tabHead['styleSheets'] as $key => $styleSheet ){
				if( strpos( $key, '/k2.css' ) ) {
				//The entry of the css file is deleted
				unset( $tabHead['styleSheets'][$key] );
				break;
				}
	    	}
	    
			//The new head data is loaded in the document
			$doc->setHeadData( $tabHead );
	  	}
	}
	
} // END CLASS
