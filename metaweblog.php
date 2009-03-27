<?php
/**
* @copyright	Justo Gonzalez de Rivera 2008
* @license		GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.utilities.date');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_frontpage'.DS.'tables'.DS.'frontpage.php');

class plgXMLRPCmetaWeblog extends JPlugin
{
	function plgXMLRPCmetaWeblog(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function getApiName(){
		return array('MetaWeblog') ;
	}
	

	
	/**
	* @return array An array of associative arrays defining the available methods
	*/
	function onGetWebServices()
	{
		global $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		return array
		(		
			'blogger.getUsersBlogs' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getUserBlogs',
				'docstring' => JText::_('Returns a list of weblogs to which an author has posting privileges.'),
				'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
			),
			'blogger.getUserInfo' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getUserInfo',
				'docstring' => JText::_('Returns information about an author in the system.'),
				'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString))
			),
			'blogger.deletePost' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::deletePost',
				'docstring' => 'Deletes a post.',
				'signature' => array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean))
			),
			'blogger.getTemplate' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::deletePost',
				'docstring' => 'Deletes a post.',
				'signature' => array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean))
			),
			'metaWeblog.getUsersBlogs' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getUserBlogs',
				'docstring' => JText::_('Returns a list of weblogs to which an author has posting privileges.'),
				'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
			),
			'metaWeblog.getUserInfo' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getUserInfo',
				'docstring' => JText::_('Returns information about an author in the system.'),
				'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString))
			),
			'metaWeblog.deletePost' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::deletePost',
				'docstring' => 'Deletes a post.',
				'signature' => array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean))
			),
			'metaWeblog.newPost' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::newPost',
				'docstring' => 'Creates a new post, and optionally publishes it.',
				'signature' => array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct, $xmlrpcBoolean))
				),
			'metaWeblog.editPost' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::editPost',
				'docstring' => 'Updates the information about an existing post.',
				'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct, $xmlrpcBoolean))
				),
			'metaWeblog.getPost' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getPost',
				'docstring' => 'Returns information about a specific post.',
				'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString))
				),
			'metaWeblog.getCategories' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getCategories',
				'docstring' => 'Returns the list of categories',
				'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString))
				),
			'metaWeblog.getRecentPosts' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::getRecentPosts',
				'docstring' => 'Returns a list of the most recent posts in the system.',
				'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcInt))
				),
			'metaWeblog.newMediaObject' => array(
				'function' => 'plgXMLRPCmetaWeblogServices::newMediaObject',
				'docstring' => 'Uploads media to the blog.',
				'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct))
				)
		);
	}
}

class plgXMLRPCmetaWeblogServices
{
	function getUserBlogs($appkey, $username, $password){
	
		global $mainframe, $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password)) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_("Login Failed"));
		}

		$user =& JUser::getInstance($username);
		
		$structArray = array();
				$structArray[] = new xmlrpcval(array(
					'url'		=> new xmlrpcval(JURI::root(), $xmlrpcString),
					'blogid'	=> new xmlrpcval($user->id, $xmlrpcString),
					'blogName'	=> new xmlrpcval($user->name . '\'s articles', $xmlrpcString)
					), 'struct');			
		
		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}
	
	function getUserInfo($appkey, $username, $password)
	{
		global $xmlrpcerruser, $xmlrpcStruct;

		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password)) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_("Login Failed"));
		}

		$user =& JUser::getInstance($username);

		$struct = new xmlrpcval(
		array(
			'nickname'	=> new xmlrpcval($user->get('username')),
			'userid'	=> new xmlrpcval($user->get('id')),
			'url'		=> new xmlrpcval(''),
			'email'		=> new xmlrpcval($user->get('email')),
			'lastname'	=> new xmlrpcval($user->get('name')),
			'firstname'	=> new xmlrpcval($user->get('name'))
		), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}
	
	function newPost($blogid, $username, $password, $content, $publish)
	{
		global $mainframe, $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		// load plugin params info
	 	$plugin =& JPluginHelper::getPlugin('xmlrpc','metaweblog');
	 	$params = new JParameter( $plugin->params );
		
		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password)) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}

		
		$user =& JUser::getInstance($username);

		if ($user->get('gid') < 19) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('You don\'t have enough rights to submit articles'));
		}

		// Create a user access object for the user
		$access					= new stdClass();
		$access->canEdit		= $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn		= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish		= $user->authorize('com_content', 'publish', 'content', 'all');

		/*if (!$access->canEditOwn) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('Not enough rights to edit articles'));
		}*/

		
		
		$catFrontPage = false;
		if( ($catFrontPage_position = array_search('Frontpage',$content['categories'])) !== FALSE){
			$catFrontPage = true;
		if($catFrontPage_position == 0){
			$content['categories'][0] = $content['categories'][1];
		}
		}		
		
		$db =& JFactory::getDBO();
		$db->setQuery("SET NAMES 'utf8'");
		
		$category = substr($content['categories'][0], 0, strpos($content['categories'][0],' ('));
		$query = 'SELECT id,section FROM #__categories WHERE title='.$db->Quote($category);
		
		if(!$category){
			$category = $params->get('catid');
			$query = 'SELECT id,section FROM #__categories WHERE id='.$db->Quote($category);
		}
		
		$db->setQuery($query);
		$cat = $db->loadObjectList();
		
		// create a new content item
		$item =& JTable::getInstance('content');
		
		//using <hr> as a read more separator
		$startReadMoreLine		= strpos($content['description'],'<hr' );
		$finishReadMoreLine		= strpos($content['description'],'>',$startReadMoreLine);
		
		if($startReadMoreLine !== false && $finishReadMoreLine !== false && $params->get('hrReadMore')){
			$introtext = substr($content['description'], 0, $startReadMoreLine);
			$fulltext  = substr($content['description'], $finishReadMoreLine+1);
		}
		elseif($content['more_text']){
			$introtext = $content['description'];
			$fulltext  = $content['more_text'];
		}
		elseif($content['mt_text_more']){
			$introtext = $content['description'];
			$fulltext  = $content['mt_text_more'];
		}
		elseif(strpos($content['description'],'<!--more-->') !== false){
			$startReadMoreLine	= strpos($content['description'],'<!--more-->' );
			$finishReadMoreLine = $startReadMoreLine+11; //after <!--more-->
			$introtext = substr($content['description'], 0, $startReadMoreLine);
			$fulltext  = substr($content['description'], $finishReadMoreLine);
		}
		else{
			$introtext = $content['description'];
			$fulltext  = '';
		}
		
		
		jimport('joomla.filter.filteroutput');
		$db->setQuery("SET NAMES 'utf8'");
		$item->title	 	= html_entity_decode($content['title'], ENT_QUOTES, 'UTF-8');
		$item->introtext	= $introtext;
		$item->fulltext		= $fulltext;
		
		$item->alias       = JFilterOutput::stringURLSafe($item->title);

		$item->catid	 	= $cat[0]->id;
		$item->sectionid 	= $cat[0]->section;
		
		$item->created		= date('Y-m-d H:i:s');
		$item->created_by	= $user->get('id');

		$item->publish_up	= $publish ? date('Y-m-d') : $db->getNullDate();
		$item->publish_down	= $db->getNullDate();

		$item->state		= $publish && $access->canPublish;
		$item->version++;

		if (!$item->store()) {
			return new dom_xmlrpc_fault( '500', 'Post store failed' );
		}
		
		if( ($params->get('frontpage') == 1) OR ($params->get('frontpage') == 2 && $catFrontPage) ){
			//this code is from administrator/components/com_content/controller.php
			$fp = new TableFrontPage($db);
				// Is the item already viewable on the frontpage?
				if (!$fp->load($row->id))
				{
					// Insert the new entry
					$query = 'INSERT INTO #__content_frontpage' .
							' VALUES ( '. (int) $item->id .', 1 )';
					$db->setQuery($query);
					
					if (!$db->query())
						return new dom_xmlrpc_fault( '500', 'Post to the frontpage failed' );
						
					$fp->ordering = 1;
				}
				
			$fp->reorder();

			$cache =& JFactory::getCache('com_content');
			$cache->clean();
		}
		
		return new xmlrpcresp(new xmlrpcval($item->id, $xmlrpcString));
	}
	
	function editPost($postid, $username, $password, $content, $publish)
	{
		global $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;
		$plugin =& JPluginHelper::getPlugin('xmlrpc','metaweblog');
	 	$params = new JParameter( $plugin->params );
		
		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password))
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");

		$user =& JUser::getInstance($username);

		// Create a user access object for the user
		$access					= new stdClass();
		$access->canEdit		= $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn		= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish		= $user->authorize('com_content', 'publish', 'content', 'all');

		// load the row from the db table
		$item =& JTable::getInstance('content');
		
		if(!$item->load( $postid )) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('Sorry, no such post') );
		}
		
		/*if (!$access->canEditOwn && ($item->created_by == $user->id) ) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('You can\'t edit articles'));
		}*/
		if(!$access->canEdit && ($item->created_by != $user->id)) { //the current user doesn't has rights to edit another user's articles
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('You can\'t edit other user\'s articles'));
		}
		
		
		if($item->isCheckedOut($user->get('id'))) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('Sorry, post is already being edited') );
		}

		$startReadMoreLine		= strpos($content['description'],'<hr' );
		$finishReadMoreLine		= strpos($content['description'],'>',$startReadMoreLine);
		
		if($startReadMoreLine !== false && $finishReadMoreLine !== false && $params->get('hrReadMore')){
			$introtext = substr($content['description'], 0, $startReadMoreLine);
			$fulltext  = substr($content['description'], $finishReadMoreLine+1);
		}
		elseif(isset($content['more_text'])){
			$introtext = $content['description'];
			$fulltext  = $content['more_text'];
		}
		elseif(isset($content['mt_text_more'])){
			$introtext = $content['description'];
			$fulltext  = $content['mt_text_more'];
		}
		elseif(strpos($content['description'],'<!--more-->') !== false){
			$startReadMoreLine	= strpos($content['description'],'<!--more-->' );
			$finishReadMoreLine = $startReadMoreLine+11; //after <!--more-->
			$introtext = substr($content['description'], 0, $startReadMoreLine);
			$fulltext  = substr($content['description'], $finishReadMoreLine);
		}
		else{
			$introtext = $content['description'];
			$fulltext  = '';
		}
		
		$item->title	 	= html_entity_decode($content['title']);
		$item->introtext	= $introtext;
		$item->fulltext		= $fulltext;

		$item->version++;
		$now = new JDate();
		$item->modified 	= $now->toMySQL();
		$item->modified_by 	= $user->id;
		$item->state		= $publish && $access->canPublish;		
		$item->checkin();
		
		if (!$item->store())
			return new xmlrpcresp(0, $xmlrpcerruser+1, JText::_('Post store failed') );


		return new xmlrpcresp(new xmlrpcval(true, $xmlrpcBoolean));
	}
	
	function getPost($postid, $username, $password)
	{
		global $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password)) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}
		
		$user =& JUser::getInstance($username);

		// load the row from the db table
		$item =& JTable::getInstance('content' );
		$category = &JTable::getInstance('category' );
		$section = &JTable::getInstance('section' );
		
		$item->load( $postid );
		$category->load( $item->catid );
		$section->load( $item->sectionid );
		
		$aid = plgXMLRPCmetaWeblogHelper::getUserAid( $user->id );
		
		if ( !($category->published && $section->published && ($category->access <= $aid) && ($section->access <= $aid) && ($item->access <= $aid)) )
		{
			return new xmlrpcresp(0, $xmlrpcerruser+2, JText::_("Access Denied"));
		}
		
		$dateCreated =& new JDate($item->created);
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');		
		$articleLink=  JURI::root() . (ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->sectionid));
		
		$struct = new xmlrpcval(
		array(
			'link'			=> new xmlrpcval($articleLink),
			'permaLink'		=> new xmlrpcval($articleLink),
			'userid'		=> new xmlrpcval($user->id),
			'title'			=> new xmlrpcval($item->title),
			'description'	=> new xmlrpcval($item->introtext),
			'more_text'		=> new xmlrpcval($item->fulltext), 
			'mt_text_more'	=> new xmlrpcval($item->fulltext),
			'dateCreated'	=> new xmlrpcval($dateCreated->toISO8601(), 'dateTime.iso8601'),
			'postid'		=> new xmlrpcval($item->id)
		), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}
	
	function deletePost($appkey, $postid, $username, $password, $publish)
	{
		global $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password))
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		

		$user =& JUser::getInstance($username);
		//TODO::implement generic access check

		// load the row from the db table
		$item =& JTable::getInstance('content');
		
		if(!$item->load( $postid ))
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'Sorry, no such post ('.$postid.')' );
		
		if($item->isCheckedOut($user->get('id')))
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'Sorry, post is being edited' );
		
		if( $user->gid < 23 )
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'You don\'t have permission to delete posts' );
		
		
		$item->state = -2; /* send to trash */
		
		if ( !$item->store() )
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'Post delete failed' );
		
		return new xmlrpcresp(new xmlrpcval(true, $xmlrpcBoolean));
	}
	
	function getRecentPosts($blogid, $username, $password, $numposts)
	{
		global $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password)) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}

		$user =& JUser::getInstance($username);
		$aid  = plgXMLRPCmetaWeblogHelper::getUserAid($user->id);
		
		// load plugin params info
	 	$plugin =& JPluginHelper::getPlugin('xmlrpc','metaweblog');
	 	$params = new JParameter( $plugin->params );

		$db =& JFactory::getDBO();

		$query = 'SELECT c.id, c.title, c.alias, c.created_by, c.introtext, c.created, c.state'
				.' FROM #__content AS c'
				.' INNER JOIN #__sections AS s ON c.sectionid = s.id'
				.' INNER JOIN #__categories AS cc ON c.catid = cc.id'
				.' WHERE s.published = 1 AND cc.published = 1'
				.' AND s.access <= '.$aid .' AND cc.access <= '.$aid.' AND c.access <= '.$aid .' AND c.state >= 0'
				.' ORDER BY c.created DESC';
		$db->setQuery($query, 0, $numposts);
		$items = $db->loadObjectList();

		if (!$items) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'No posts available, or an error has occured.' );
		}

		
		require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
		
		$structArray = array();
		
		foreach ($items as $item)
		{
		
			$dateCreated	=& new JDate($item->created);
			$articleLink	= JURI::root() . (ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->sectionid));
			
				$structArray[] = new xmlrpcval(array(
					'dateCreated'	=> new xmlrpcval($dateCreated->toISO8601(), 'dateTime.iso8601'),
					'title'			=> new xmlrpcval($item->title),
					'description'	=> new xmlrpcval($item->introtext),
					'more_text'	=> new xmlrpcval($item->introtext),
					'mt_text_more'	=> new xmlrpcval($item->introtext),
					'userid'		=> new xmlrpcval($item->created_by),
					'postid'		=> new xmlrpcval($item->id),
					'link'			=> new xmlrpcval($articleLink),
					'permaLink'		=> new xmlrpcval($articleLink)
				), $xmlrpcStruct);
		}
		
		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}
	
	function getCategories($blogid, $username, $password)
	{
		global $xmlrpcerruser, $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;
	
		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password)) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}

		$user =& JUser::getInstance($username);
		//TODO::implement generic access check

		// load plugin params info
	 	$plugin =& JPluginHelper::getPlugin('xmlrpc','metaweblog');
	 	$params = new JParameter( $plugin->params );

		$db =& JFactory::getDBO();
		$aid  = plgXMLRPCmetaWeblogHelper::getUserAid($user->id);
		
		$query = 'SELECT cc.title, cc.description, cc.section,'
			. ' s.title as sectionTitle'
			. ' FROM #__categories as cc'
			. ' INNER JOIN #__sections as s ON cc.section = s.id'
			. ' WHERE s.published = 1 AND cc.published = 1'
			. ' AND s.access <= '.$user->gid .' AND cc.access <= '.$user->gid 
			. ' ORDER BY cc.section';
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		if (!$categories) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'No categories available, or an error has occured.' );
		}

		$structArray = array();
		
			$structArray[] = new xmlrpcval(array(
				'title'			=> new xmlrpcval('Uncategorized'),
				'description'	=> new xmlrpcval('Uncategorized')
			), 'struct');
		
		if($params->get('frontpage') == 2){
			$structArray[] = new xmlrpcval(array(
				'title'			=> new xmlrpcval('Frontpage'),
				'description'	=> new xmlrpcval('Frontpage')
				), 'struct');
			}
		foreach ($categories as $category)
		{
			$structArray[] = new xmlrpcval(array(
				'title'			=> new xmlrpcval($category->title .' ('. $category->sectionTitle .')'),
				'description'	=> new xmlrpcval($category->title .' ('. $category->sectionTitle .')')
			), 'struct');
		}

		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));	
	}
	
	function newMediaObject($blogid, $username, $password, $file)
	{
	
		global $xmlrpcStruct, $xmlrpcArray;
		
		if(!plgXMLRPCmetaWeblogHelper::authenticateUser($username, $password))
				return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		
		
		$user =& JUser::getInstance($username);
		$access	= new stdClass();
		$access->canEditOwn	= $user->authorize('com_content', 'edit', 'content', 'own');
		
		if(strpos($file['name'], '/') !== FALSE)
			$file['name']= substr($file['name'], strrpos($file['name'],'/')+1 );
		elseif(strpos($file['name'], '\\' !== FALSE))
			$file['name']= substr($file['name'], strrpos($file['name'],'\\')+1 );
		
		$dir  	 = JPATH_ROOT . DS . 'media' . DS . $user->name . DS;
		$tmp_dir = JPATH_ROOT . DS . 'tmp' . DS;
		
		if(!is_dir($dir))
			mkdir($dir);		
		
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');
		
		$dirPrevPermission = JPath::getPermissions($dir);
		$tmp_dirPrevPermission = JPath::getPermissions($tmp_dir);

		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);

		$file['name']= JFile::makesafe($file['name']);
		$file['name']= substr($file['name'], 0, -4) . rand() . '.' . JFile::getExt($file['name']);
		
		$file['tmp_name']= $tmp_dir . $file['name'];		
		JFile::write( $file['tmp_name'], $file['bits']);
				
		jimport( 'joomla.application.component.helper' );
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php');
		
		if(!MediaHelper::canUpload($file, $error)){
			JFile::delete( $file['tmp_name'] );
			return new xmlrpcresp(0, $xmlrpcerruser+1, 'The file is not valid' );
		}
		
		JFile::write( $dir . $file['name'], $file['bits']);
		JFile::delete( $file['tmp_name'] );
		
		return new xmlrpcresp(new xmlrpcval(array(
					'url'			=> new xmlrpcval(JURI::root() . 'media/'.$user->name.'/'.$file['name'])
				), 'struct'));



	}
	
	
}

class plgXMLRPCmetaWeblogHelper
{
	function authenticateUser($username, $password)
	{
		// Get the global JAuthentication object
		jimport( 'joomla.user.authentication');
		$auth = & JAuthentication::getInstance();
		$credentials = array( 'username' => $username, 'password' => $password );
		$options = array();
		$response = $auth->authenticate($credentials, $options);
		return $response->status === JAUTHENTICATE_STATUS_SUCCESS;
	}
	
	function getUserAid( $userid ) {
		
		$user =& JUser::getInstance($userid);
		$acl = &JFactory::getACL();

		//Get the user group from the ACL
		$grp = $acl->getAroGroup($user->get('id'));

		// Mark the user as logged in
		$user->set('guest', 0);
		$user->set('aid', 1);

		// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
		if ($acl->is_group_child_of($grp->name, 'Registered')      ||
			$acl->is_group_child_of($grp->name, 'Public Backend')) {
 			$user->set('aid', 2);
 		}
		
		return($user->get('aid'));
	}
}
?>