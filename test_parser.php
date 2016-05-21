<!DOCTYPE html>
<html>
<body>

<?php

$file = file_get_contents('./ALCMS.owl', true);

	echo '<div>'.htmlentities ($file).'</div><br/>';
$dom = new DomDocument;
$dom->loadXml($file);
$uri = $dom->documentElement->lookupnamespaceURI(NULL);
$xph = new DOMXPath($dom);

$xph->registerNamespace('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
$xph->registerNamespace('owl', "http://www.w3.org/2002/07/owl#");
$xph->registerNamespace('default', $uri);

// edit topic
$topicId = '99';
$qstr = '(//owl:NamedIndividual[default:tp_ID="Topic_'.$topicId.'"])';
// $tpID = $xph->query($qstr.'/default:tp_ID');
//$tpID[0]->nodeValue = "upload_material/";
$tpName = $xph->query($qstr.'/default:tp_name');
foreach($tpName as $tname)
$tname->nodeValue = 'new name';
$filename = './ALCMS.owl';
file_put_contents($filename, $dom->saveXML());
// end:

// Find and edit upload file info
$qstr = '//NamedIndividual[cp_topic="Topic_11" and cp_aptitude="VKA" and cp_level="L"]';
$pkPath = $xph->query($qstr.'/cp_address');
$pkName = $xph->query($qstr.'/cp_name');
foreach($pkPath as $ppath){
	$ppath->nodeValue = "upload_material/";
	foreach($pkName as $pname){
	$pname->nodeValue = $pname->nodeValue.'.zip';
	echo "<br/>Here:".$ppath->nodeValue.$pname->nodeValue."<br/>";
	}
}
// end:

// Add new topic and its package
/* $topicId = '99';
 $aptitudes = ['AKV','AVK','KAV','KVA','VAK','VKA'];
 $levels =['H','L','M'];
 $learnStyles=[
	 'AKVH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	
	 'AKVL' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Questioning"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Role_Playing"/>',
		
	 'AKVM' => ' <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Questioning"/>
        /<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Role_Playing"/>',
		
	 'AVKH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	 'AVKL' => ' <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Deductive"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Investigation"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Skill_Development_Reasoning"/>',
		
	 'AVKM' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Deductive"/>
        <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Investigation"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Skill_Development_Reasoning"/>',
		
	 'KAVH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	
	 'KAVL' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Cippa"/>
        <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Discovery"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Laboratory"/>',
		
	'KAVM' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Discovery"/>
        <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Laboratory"/>',
		
	'KVAH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	
	 'KVAL' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Problem"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Project"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Scientific"/>',
		
	 'KVAM' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Project"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Scientific"/>',
		
	 'VAKH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	
	 'VAKL' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Inquiry"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Self_Study"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Socretis"/>',
		
	 'VAKM' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Self_Study"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Socretis"/>',
		
	 'VKAH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	
	 'VKAL' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Committee_Work"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Demonstration"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Discussion"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Experiment"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Skill_Development_Problem_Solving"/>',
	
	 'VKAM' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Demonstration"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Discussion"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Experiment"/>
         <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Skill_Development_Problem_Solving"/>'
];
 printf('<div style="display:none">');
 foreach ($aptitudes as $ap) {
 foreach ($levels as $lv) {	
	 $aplv = $ap.$lv;
	 $newTopicPackage =' 
	 <!-- http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Package_'.$aplv.'_'.$topicId.' -->
	
     <owl:NamedIndividual rdf:about="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Package_'.$aplv.'_'.$topicId.'">
         <rdf:type rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Package_'.$aplv.'"/>
         <cp_aptitude rdf:datatype="&xsd;string">'.$ap.'</cp_aptitude>
         <cp_level rdf:datatype="&xsd;string">'.$lv.'</cp_level>
        <cp_name rdf:datatype="&xsd;string">Package_'.$aplv.'_'.$topicId.'</cp_name>
         <cp_topic rdf:datatype="&xsd;string">Topic_'.$topicId.'</cp_topic>
        <cp_address rdf:datatype="&xsd;string">upload_material/</cp_address>'.$learnStyles[$aplv].'      
     </owl:NamedIndividual>';
	
	 $qstr = '(//owl:NamedIndividual[default:cp_aptitude="'.$ap.'" and default:cp_level="'.$lv.'"])[last()]';

	 $fragment = $dom->createDocumentFragment();
	 $fragment->appendXML($newTopicPackage);

	 $topicPath = $xph->query($qstr);
	 try {
		 $topicPath[0]->parentNode->insertBefore($fragment,$topicPath[0]->nextSibling);
	 } catch(\Exception $e){
		 $topicPath[0]->parentNode->appendChild($fragment);
	 }
 }
 }
 $newTopic ='<!-- http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Topic_'.$topicId.' -->
     <owl:NamedIndividual rdf:about="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Topic_'.$topicId.'">
         <rdf:type rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Contents"/>
         <tp_ID rdf:datatype="&xsd;string">Topic_'.$topicId.'</tp_ID>
         <tp_name rdf:datatype="&xsd;string"></tp_name>';
 foreach ($aptitudes as $ap) {
 foreach ($levels as $lv) {	
	 $newTopic =  $newTopic.'<has_package rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Package_'.$ap.$lv.'_'.$topicId.'"/>';
 }}
    $newTopic =  $newTopic.'</owl:NamedIndividual>';
   
	 $fragment = $dom->createDocumentFragment();
	 $fragment->appendXML($newTopic);
	 $qstr = '(//owl:NamedIndividual[rdf:type/@rdf:resource = "http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Contents"])[last()]';
	 $topicPath = $xph->query($qstr);
	 try {
		 $topicPath[0]->parentNode->insertBefore($fragment,$topicPath[0]->nextSibling);
	 } catch(Exception $e){
		 $topicPath[0]->parentNode->appendChild($fragment);
	 }
 printf('</div>');*/
// end:


// Saving
// echo "Saving all the document:\n";
// echo htmlentities($dom->saveXML());
// $filename = './ALCMS.owl';
// file_put_contents($filename, $dom->saveXML());
// end:
		
//$xml=simplexml_load_string($myXMLData) or die("Error: Cannot create object");
//print_r($xml);
?>

</body>
</html>
