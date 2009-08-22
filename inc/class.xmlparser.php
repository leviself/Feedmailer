<?php

/**
 * @category: Feedmailer 
 * @package: config/class.xmlparser.php
 * @author: Dr Small <drsmall@mycroftserver.homelinux.org>
 * @datetime: Day 034 2009; 17:49 [EST]
 * @license: http://www.php.net/license/3_0.txt
*/

class xmlparser{

	/**
	 * Public Variables
	 **/
	public $file, $item, $checkxml;

	/**
	 * Private Variables
	 **/
	private $debug;
	
	/**
	 * The contsructor function executes
	 * at runtime. Load the debugger for this class.
	 **/
	public function __construct(){
		require_once dirname(__FILE__)."/class.debug.php";
		$this->debug = new debugger();

		#include dirname(__FILE__)."/class.xmlcheck.php";
		#$this->checkxml = new XML_check();
	}

	function parse($url){
		if($this->checkURL($url)){

			// Load a XML check class from SourceForge
			// to check if the file is an XML document
			if ($this->check_string($this->file)){
			
				// Determine the type
				$type = $this->checkType($this->file);

				// Parse the XML file
				$xml = new SimpleXMLElement($this->file);

				if($type == 'rss2') {
					foreach ($xml->channel->item as $item){
						$feed[title] = (string)$xml->channel[0]->title;
						$itemurl     = (string)$item->link;
						$title       = (string)$item->title;
						$pubDate     = (string)$item->pubDate;
						$description = ltrim($item->description);
						$comments    = (string)$item->comments;
	
						$items[] = array(
								"feedtitle" => $feed[title],
								"url" => $itemurl,
								"title" => $title,
								"pubdate" => $pubDate,
								"description" => $description,
								"comments" => $comments
								);
						$this->item = $items;		
					}
				}


				if($type == "atom"){
				 	foreach ($xml->entry as $item) {
		
				        	$feed[title] = (string)$xml->title;
						$itemurl     = (string)$item->id;
						$title	     = (string)$item->title;
						$pubDate     = (string)$item->published;
						$description = ltrim($item->summary);
						$comments    = (string)$item->link[1]['href'];
	
						$items[] = array(
								"feedtitle" => $feed[title],
								"url" => $itemurl,
								"title" => $title,
								"pubdate" => $pubDate,
								"description" => $description,
								"comments" => $comments
								);
					
						$this->item = $items;
					}
				}
				$this->debug->write(FF, "xmlparser::parse({$url}) completed");
				return true;
			} else {
				$this->debug->write(WW, "xmlparser::parse({$url}) failed. Document was not XML");
				return false;
			}
		} else {
			return false;
		}
	}


	public function checkURL($url){
		// Set the HTTP User-agent
		ini_set("user_agent", "Feedmailer/2.0 (xmlbot; +http://launchpad.net/feedmailer)");

		$file = @file_get_contents($url);
		if($file){
			$this->debug->write(II, "xmlparser::checkURL({$url}) returned true");
			$this->file = $file;
			return true;
		} else {
			$this->debug->write(WW, "xmlparser::checkURL({$url}) returned false");
			return false;
		}
	}

	/**
	 * Name:	XML Type
	 * Description:	Determine the type of XML document.
	 * Usage:	$this->checkType($file);
	 **/
	private function checkType($file){		
		$xml = new SimpleXMLElement($file);

		if ($xml->channel->item){
			$type = "rss2";
		}

		if ($xml->entry){
			$type = "atom";
		}

		$this->debug->write(II, "xmlparser::checkType() returned {$type}");
		return $type;
	}

	/**
	 * Name:	Check String
	 * Description:	Preform an XML Check on the file contents
	 		passed to it from a variable. Function used from
			XML_Check Class by Luis Argerich (lrargerich@yahoo.com)

			Edited and implemented to be used with Feedmailer/2.0
	 * Usage:	$xml->check_string($contents);
	 * Returns:	boolean
	 **/
	function check_string($xml) {

		$this->parser = xml_parser_create_ns("",'^');
		xml_set_object($this->parser,&$this);
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);

		if (!xml_parse($this->parser, $xml, true)) {
			$this->debug->write(EE, "xmlparser::check_string() returned false");
			return false;
		}

		xml_parser_free($this->parser); 
		$this->debug->write(FF, "xmlparser::check_string() returned true");
		return true;
	} 

}
?>
