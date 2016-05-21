<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
 	error_reporting(E_ERROR | E_PARSE); 
/*	error_reporting(E_ALL); ini_set('display_errors', '1');*/
	include "chgable_db_connect.php";
	
	if(isset($_POST['create_topic_button'])) {
		$sql = "INSERT INTO topics (Academic_ID,Topic_Name, Topic_Desc, Topic_Objective, IntermediateThreshold, ExpertThreshold) 
		VALUES ('".$_POST['topicAcademic']."','".$_POST['topicname']."','".$_POST['topicdesc']."','"
		.$_POST['topicobj']."','".$_POST['intermediate']."','".$_POST['expert']."')";
		$row = mysqli_query($con,$sql);
		if( mysqli_errno($con) == 1062) {
    // Duplicate key
			$data = array("msg"=>"This Topic ID is already used.","name"=>"error");
			header('Content-type: application/json');
			echo json_encode( $data );
		}else if(!$row){
			$data = array("msg"=>"Cannot insert data","name"=>"error");
			header('Content-type: application/json');
			echo json_encode( $data );
		}else{
			//OWL export
		$file = file_get_contents('./ALCMS.owl', true);

		$dom = new DomDocument;
		$dom->loadXml($file);
		$uri = $dom->documentElement->lookupnamespaceURI(NULL);
		$xph = new DOMXPath($dom);

		$xph->registerNamespace('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
		$xph->registerNamespace('rdfs', "http://www.w3.org/2000/01/rdf-schema#");
		$xph->registerNamespace('owl', "http://www.w3.org/2002/07/owl#");
		$xph->registerNamespace('default', $uri);
		
$topicId = $_POST['topicAcademic'];
$aptitudes = array('AKV','AVK','KAV','KVA','VAK','VKA');
$levels =array('H','L','M');
$learnStyles=array(
	'AKVH' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_normal"/>',
	
	'AKVL' => '<has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Questioning"/>
        <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Role_Playing"/>',
		
	'AKVM' => ' <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Questioning"/>
        <has_teaching_style rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#TS_Role_Playing"/>',
		
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
);

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
	foreach ($topicPath as $path) {
		if($path->nextSibling!=null){
			$path->parentNode->insertBefore($fragment,$path->nextSibling);
		}else{ 
			$path->parentNode->appendChild($fragment);
		}
	}
}
}
$newTopic ='<!-- http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Topic_'.$topicId.' -->
    <owl:NamedIndividual rdf:about="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Topic_'.$topicId.'">
        <rdf:type rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Contents"/>
        <tp_ID rdf:datatype="&xsd;string">Topic_'.$topicId.'</tp_ID>
        <tp_name rdf:datatype="&xsd;string">'.$_POST['topicname'].'</tp_name>';
foreach ($aptitudes as $ap) {
foreach ($levels as $lv) {	
	$newTopic =  $newTopic.'<has_package rdf:resource="http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Package_'.$ap.$lv.'_'.$topicId.'"/>';
}}
   $newTopic =  $newTopic.'</owl:NamedIndividual>';
   
	$fragment = $dom->createDocumentFragment();
	$fragment->appendXML($newTopic);
	$qstr = '(//owl:NamedIndividual[rdf:type/@rdf:resource = "http://www.semanticweb.org/ake_dell/ontologies/2015/11/untitled-ontology-70#Contents"])[last()]';
	$topicPath = $xph->query($qstr);
	foreach ($topicPath as $path) {
		if($path->nextSibling!=null){
			$path->parentNode->insertBefore($fragment,$path->nextSibling);
		}else{ 
			$path->parentNode->appendChild($fragment);
		}
	}

	$filename = './ALCMS.owl';
	file_put_contents($filename, $dom->saveXML());
		// end OWL
			
			$data = array("url"=>"page_topiclist.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data );
		}
		//header("Location:page_topiclist.php");
	}
		
	//mysqli_close($con);
?>		