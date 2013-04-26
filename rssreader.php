<?php

//start_of_rssreader_last5news
function rssreader_last5news($where,$who,$site) {
 $data=html_entity_decode(file_get_contents($site));
 $data=mb_convert_encoding($data,'utf-8','utf-8,iso-8859-1,iso-8859-15');
 $data=utf8_decode($data);
 $valid=true;
 try {
  $dom=$GLOBALS['rssreader']['XmlLoader']($data);
 }
 catch(DOMException $e) {
  $valid=false;
 }
 if($valid===false) {
  say($where,$who,'xml non valide');
  return false;
 }
 $allitems = $dom->getElementsByTagName('item');
 for($i=0;$i<5&&$i<$allitems->length;$i++) {
  say($where,$who,$allitems->item($i)->getElementsByTagName('title')->item(0)->nodeValue."\r\n");
 }
 return true;
}
//end_of_rssreader_last5news

//start_of_rssreader_lastnews
function rssreader_lastnews($where,$who,$site) {
 $data=html_entity_decode(file_get_contents($site));
 $data=mb_convert_encoding($data,'utf-8','utf-8,iso-8859-1,iso-8859-15');
 $data=utf8_decode($data);
 $valid=true;
 try {
  $dom=$GLOBALS['rssreader']['XmlLoader']($data);
 }
 catch(DOMException $e) {
  $valid=false;
 }
 var_dump($valid);
 if($valid===false) {
  say($where,$who,'xml non valide');
  return false;
 }
 $allitems = $dom->getElementsByTagName('item');
 say($where,$who,$allitems->item(0)->getElementsByTagName('title')->item(0)->nodeValue."\r\n");
 return true;
}
//end_of_rssreader_lastnews

//start_of_XmlLoader
function XmlLoader($strXml)
{
 set_error_handler('HandleXmlError');
 $dom = new DOMDocument();
 $dom->loadXml($strXml);
 restore_error_handler();
 return $dom;
}
//end_of_XmlLoader

//start_of_HandleXmlError
function HandleXmlError($errno, $errstr, $errfile, $errline)
{
   if ($errno==E_WARNING && 
(substr_count($errstr,"DOMDocument::loadXML()")>0))
   {
       throw new DOMException($errstr);
   }
   else
       return false;
}
//end_of_HandleXmlError

?>
