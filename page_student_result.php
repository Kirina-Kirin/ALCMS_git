<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";
	
	if($_SESSION['status']=="student"){
		$result = mysqli_query($con,"SELECT t.Topic_Name, t.Academic_ID, t.IntermediateThreshold , t.ExpertThreshold, r.Resource_Address,r.Resource_Name,l.Score,l.Max_Score, a.Student_Aptitude_Level, l.KnowledgeLevel 
		FROM learning_records l 
		LEFT JOIN topics t ON l.Topic_ID = t.Topic_ID
		LEFT JOIN resource r ON l.Resource_ID = r.Resource_ID
		LEFT JOIN student s ON s.Student_ID = '".$_SESSION['login']."' 
		LEFT JOIN student_aptitude a ON s.Student_Aptitude_ID = a.Student_Aptitude_ID 
		WHERE l.Student_ID = '".$_SESSION['login']."' ORDER BY t.Academic_ID ASC");
	}else{header("Location: page_login.php");}
	
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title></title>
	<!--CSS-->
	<link rel="stylesheet" type="text/css" href="css/chart-style.css"/>
	<script type="text/javascript" src="script/form.js"></script>
	<style>
		.colorYellow{
			  background-color:yellow;
			  -webkit-filter:brightness(120%);
			  filter:brightness(120%);
		}
		.colorRed{
			  background-color:red;
			  -webkit-filter:brightness(110%);
			  filter:brightness(110%);
		}
		.colorGreen{
			  background-color:green;
			  -webkit-filter:brightness(120%);
			  filter:brightness(120%);
		}
	</style>
</head>
<body>

    <div class="main">
		<!--chart credit: http://www.standards-schmandards.com/exhibits/barchart/-->
<table cellspacing="0" cellpadding="0">
      <caption align="top">&#3612;&#3621;&#3585;&#3634;&#3619;&#3607;&#3604;&#3626;&#3629;&#3610;&#3588;&#3623;&#3634;&#3617;&#3648;&#3594;&#3637;&#3656;&#3618;&#3623;&#3594;&#3634;&#3597;<br /><br /></caption>
	  <tr>
        <th style="width:10%;"> &#3623;&#3636;&#3594;&#3634;</th>
        <th class="center"> &#3588;&#3632;&#3649;&#3609;&#3609;</th>
		<th style="width:10%;"> &#3649;&#3610;&#3610;&#3613;&#3638;&#3585;&#3627;&#3633;&#3604;</th>
      </tr>
<?php
	while ($score = mysqli_fetch_array($result)){
		$style="colorGreen";
		$scorePercent = round(($score['Score']*100.0/$score['Max_Score']),2);
		if($scorePercent<$score['ExpertThreshold'])$style="colorYellow";
		if($scorePercent<$score['IntermediateThreshold'])$style="colorRed";
		
	$padAcId= str_pad($score['Academic_ID'], 2, "0", STR_PAD_LEFT);
	$file = file_get_contents('./ALCMS.owl', true);
	$dom = new DomDocument;
	$dom->loadXml($file);
	$uri = $dom->documentElement->lookupnamespaceURI(NULL);
	$xph = new DOMXPath($dom);
	$xph->registerNamespace('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
	$xph->registerNamespace('owl', "http://www.w3.org/2002/07/owl#");
	$xph->registerNamespace('default', $uri);
	$qxph = '//owl:NamedIndividual[default:cp_topic="Topic_'.$padAcId.'" and default:cp_aptitude="'.$score['Student_Aptitude_Level'].'" and default:cp_level="'.$score['KnowledgeLevel'].'"]';
echo "<script>console.log('".$qxph."');</script>";
	$pkPath = $xph->query($qxph.'/default:cp_address');
	$pkName = $xph->query($qxph.'/default:cp_name');
	$filepath ="";
echo '<script>console.log("count pkPath:'.count($pkPath).'");</script>';
echo '<script>console.log("count pkName :'.count($pkName).'");</script>';
echo '<script>console.log("val pkPath:'.$pkPath->item(0)->nodeValue.'");</script>';
echo '<script>console.log("val pkName :'.$pkName->item(0)->nodeValue.'");</script>';

	$qxph = '//owl:NamedIndividual/cp_topic';
echo "<script>console.log('".$qxph."');</script>";
echo '<script>console.log("val test :'.$xph->query($qxph)->item(0)->nodeValue.'");</script>';


	foreach($pkPath as $pk_path){
echo '<script>console.log("path:'.$pk_path->nodeValue.'");</script>';
		foreach($pkName as $pk_name){
echo '<script>console.log("name:'.$pk_name->nodeValue.'");</script>';
			if($pk_path!=null && $pk_name!=null){
				$filepath = $pk_path->nodeValue.$pk_name->nodeValue;
echo '<script>console.log("filepath:'.$filepath.'");</script>';
			}
		}
	}
     echo '<tr>
			<td class="first">['.$score['Academic_ID'].'] '.$score['Topic_Name'].'</td>
			<td class="value" ><img src="image/bar_bw.png" width="'.(($score['Score']/$score['Max_Score'])*90.0).
				'%" height="16" class="'.$style.'" />'.$scorePercent.'% <table><tr>'.
				'<td> <img src="image/bar_bw.png"/ width="15em" height="15em" class="colorRed"> 
					Beginner < '.$score['IntermediateThreshold'].'%</td>'.
				'<td> <img src="image/bar_bw.png"/ width="15em" height="15em" class="colorYellow"> 
					Intermediate >= '.$score['IntermediateThreshold'].'%</td> '.
				'<td><img src="image/bar_bw.png"/ width="15em" height="15em" class="colorGreen"> 
					Expert >= '.$score['ExpertThreshold'].'% </td>'.
			'</tr> </table></td>
			<td class="last2">';
			$lastModify = -1;
			$fileName='';
			if(file_exists($filepath.'.zip')){ 
				$fileName=$filepath.'.zip';
				$lastModify = filemtime($fileName);
			}
			if(file_exists($filepath.'.rar')){ 
				if($lastModify < filemtime($filepath.'.rar'))
					$fileName = $filepath.'.rar';
			}
			if($fileName!=='')
				echo '<a rel="nofollow" href="'.$filepath.'">Download here</a>'; 
			else echo'No file to download';

	echo '</td></tr>';
	}
	for($i=mysqli_num_rows ( $result );$i<7;$i++){
		echo '<tr><td class="first"></td>
        <td class="value"><img src="image/bar.png" alt="" width="0%" height="16" /></td>
		<td class="last"></td>
		</tr>';
	}
	//mysqli_close($con);
?>
    </table>
</div>
</body>
</html>
