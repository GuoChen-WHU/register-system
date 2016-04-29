<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" >
  <title>查询结果</title>
</head>
<body>
<?php
  require('dohtml.php');

	$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
	$stuName = trim($_POST["student-name"]);
	$stuId = trim($_POST["student-id"]);

	try {
		// check forms filled in
		if ( !filled_out() ) {
			throw new Exception('You have not filled the form out correctly, please
				go back and try again.');
		}
		// check id
		if ( !valid_id($stuId) ) {
			throw new Exception('Student ID is not valid,  please go back and try again.');
		}
		
		$item = confirm();
		if ( $item['man_single'] == false && $item['man_double'] == false 
			&& $item['woman_single'] == false && $item['woman_double'] == false
			&& $item['mix_double'] == false && $item['referee'] == false) {
				throw new Exception("你还没有报名参与任何项目，<a href=\"$DOCUMENT_ROOT/BadmintonApplication/form_player.html\">现在报名参赛</a>.");
		} else {
			doHeader("查询结果");
			echo "<p>你报名参与了以下项目：</p><ul>";
			if ( $item['man_single'] ) {
				echo "<li>男单</li>";
			}
			if ( $item['man_double'] ) {
				echo "<li>男双</li>";
			}
			if ( $item['woman_single'] ) {
				echo "<li>女单</li>";
			}
			if ( $item['woman_double'] ) {
				echo "<li>女双</li>";
			}
			if ( $item['mix_double'] ) {
				echo "<li>混双</li>";
			}
			if ( $item['referee'] ) {
				echo "<li>裁判员</li>";
			}
			echo "</ul>";
			doFooter();
		}
		
	} catch ( Exception $e ) {
		doHeader("出错啦");
		echo $e->getMessage();
		doFooter();
		exit;
	}
	
	function filled_out() {
		$stuName = trim($_POST["student-name"]);
		$stuId = trim($_POST["student-id"]);
		if ( !$stuId || !$stuName) {
			return false;
		}
		return true;
	}
	
	function valid_id($stuId) {
		if (ereg('^201[0-9][1|2][0|8]2130[0-9]{3}$', $stuId)) {
			return true;
		}
		else {
			return false;
		}
	}

	function confirm() {
		$stuName = trim($_POST["student-name"]);
		$stuId = trim($_POST["student-id"]);
		$db = new mysqli('localhost', 'applyAccount', 'applyPassword', 'BadmintonApplication');
		$db->query('set names utf8');
		try {
			if (mysqli_connect_error()) {
				throw new Exception('Error: Could not connect to database. Please try again later.');
			}
		} catch ( Exception $e ) {
			doHeader("出错啦");
			echo $e->getMessage();
			doFooter();
			exit;
		}
		
		//The array stores the competition item one engaged.
		$competitionItem = array( 'man_single'=>false, 'man_double'=>false, 
			'woman_single'=>false, 'woman_double'=>false, 'mix_double'=>false, 'referee'=>false);
		
		$isPlayer = "select * from player where id = $stuId limit 1";
		$resultIsP = $db->query($isPlayer);
		$isReferee = "select * from referee where id = $stuId limit 1";
		$resultIsR = $db->query($isReferee);
		//player
		if ( mysqli_num_rows($resultIsP) ) {
			
			$playerItem = "select * from player_engaged_item where id = $stuId limit 1";
			$resultItem = $db->query($playerItem);
			if ( mysqli_num_rows($resultItem) ) {
				$engagedItem = $resultItem->fetch_assoc();
				if ( $engagedItem["man_single"] ) {
					$competitionItem['man_single'] = true;
				}
				if ( $engagedItem["man_double_firstId"] ) {
					$competitionItem['man_double'] = true;
				}
				if ( $engagedItem["woman_single"] ) {
					$competitionItem['woman_single'] = true;
				}
				if ( $engagedItem["woman_double_firstId"] ) {
					$competitionItem['woman_double'] = true;
				}
				if ( $engagedItem["mix_double_firstId"] ) {
					$competitionItem['mix_double'] = true;
				}
			}
			$resultItem->close();
		}
		//referee
		if ( mysqli_num_rows($resultIsR) ) {
			$competitionItem['referee'] = true;
		}

		$resultIsP->close();
		$resultIsR->close();
		return $competitionItem;
	}
