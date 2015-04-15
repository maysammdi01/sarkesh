<?php
namespace addon\plugin\page;
use \core\cls\core as core;
use \core\cls\db as db;
class setup{
	use addons;
	
	public function install(){
		$orm = db\orm::singleton();
		$strQuery = "CREATE TABLE IF NOT EXISTS `page_catalogue` (
					`id` int(11) NOT NULL,
					  `name` varchar(50) NOT NULL,
					  `canComment` tinyint(4) NOT NULL DEFAULT '1',
					  `localize` varchar(20) NOT NULL,
					  `adr` varchar(100) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


					CREATE TABLE IF NOT EXISTS `page_comments` (
					`id` int(11) NOT NULL,
					  `username` varchar(10) NOT NULL,
					  `post` varchar(20) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `body` text NOT NULL,
					  `date` varchar(20) NOT NULL,
					  `approve` tinyint(4) NOT NULL DEFAULT '1'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

					CREATE TABLE IF NOT EXISTS `page_posts` (
					`id` int(11) NOT NULL,
					  `username` int(11) DEFAULT NULL,
					  `photo` varchar(30) NOT NULL,
					  `title` varchar(500) NOT NULL,
					  `intro` text NOT NULL,
					  `body` text NOT NULL,
					  `catalogue` varchar(20) NOT NULL,
					  `date` varchar(30) NOT NULL,
					  `adr` text NOT NULL,
					  `canComment` tinyint(4) NOT NULL DEFAULT '1',
					  `publish` tinyint(4) NOT NULL DEFAULT '1',
					  `tags` text NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

					ALTER TABLE `page_catalogue`
					 ADD PRIMARY KEY (`id`);

					ALTER TABLE `page_comments`
					 ADD PRIMARY KEY (`id`);

					ALTER TABLE `page_posts`
					 ADD PRIMARY KEY (`id`);

					ALTER TABLE `page_catalogue`
					MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

					ALTER TABLE `page_comments`
					MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

					ALTER TABLE `page_posts`
					MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

		$orm->exec($strQuery,[],NON_SELECT);
		
		//add permissions
		$this->newPermission('pagePublish',false);
		$this->newPermission('pageInsert',true);
		$this->newPermission('pageEditeOwnPage',true);
		$this->newPermission('pageEditeAllPage',false);
		
		//install widgets
		$this->installWidget('page','widgetCatalogues','Catalogues widget');
		
		//save registry keys
		$registry =  core\registry::singleton();
		$registry->newKey('page','postDateFormat','Y:m:d');
		$registry->newKey('page','PostPerPage','5');
		$registry->newKey('page','showAuthor','1');
		$registry->newKey('page','showDate','1');
	}
	
	
}
