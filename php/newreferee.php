﻿<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" >
  <title>感谢报名</title>
</head>
<body>
<?php

	$stuName = trim($_POST["student-name"]);
	$stuId = trim($_POST["student-id"]);
	$phoNum = trim($_POST["phone-num"]);

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
		// check phone number
		if ( !valid_tel($phoNum) ) {
			throw new Exception('Phone Number is not valid, please go back and try again.');
		}
		
		register();
		echo "<h1>报名成功</h1>";
		echo "<p>$stuName 同学，学号 $stuId ，手机号 $phoNum ，感谢报名参加裁判工作，请留意后续裁判培训通知。</p><ul>";
	} catch ( Exception $e ) {
		echo "<h1>出错啦</h1>";
		echo "<p>".$e->getMessage()."</p>";
		exit;
	}
	
	function filled_out() {
		$stuName = trim($_POST["student-name"]);
		$stuId = trim($_POST["student-id"]);
		$phoNum = trim($_POST["phone-num"]);
		if ( !$stuId || !$stuName || !$phoNum) {
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
	
	function valid_tel($tel) {
		if (ereg('^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])[0-9]{8}$', $tel)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function register() {
		$stuName = $_POST["student-name"];
		$stuId = $_POST["student-id"];
		$phoNum = $_POST["phone-num"];
		$db = new mysqli('localhost', 'applyAccount', 'applyPassword', 'BadmintonApplication');
		$db->query('set names utf8');
		try {
			if (mysqli_connect_error()) {
				var_dump(mysqli_connect_error());
				throw new Exception('Error: Could not connect to database. Please try again later.');
			}
			
			//Check whether player, true->reject apply; false->continue
			$checkPlayer = "select * from player where id = $stuId limit 1";
			$isPlayer = $db->query($checkPlayer);
			if ( mysqli_num_rows($isPlayer) ) {
				throw new Exception('你已经报名参赛了，不能兼报裁判工作');
			}
			$isPlayer->close();
			
			$checkLimit = "select * from referee";
			$appliedNumber = mysqli_num_rows( $db->query( $checkLimit ) );
			if ( $appliedNumber > 7 ) {
				throw new Exception('来晚了，报名人数已经够了:)');
			}
			
			$check = "select * from referee where id = $stuId limit 1";
			$result = $db->query($check);
			if ( $result == false || mysqli_num_rows($result) == 0 ) {
				$insertReferee = 'insert ignore into referee values("'.$stuId.'", "'.$stuName.'", "'.$phoNum.'")';
				$db->query($insertReferee);
				$db->close();
			} else {
				throw new Exception('你已经报过名了。');
			}
		} catch ( Exception $e ) {
			echo "<h1>出错啦</h1>";
			echo "<p>".$e->getMessage()."</p>";
			exit;
		} 
	}