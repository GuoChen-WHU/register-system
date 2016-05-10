<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" >
  <title>感谢报名</title>
</head>
<body>
<?php
  require('dohtml.php');

	$stuName = trim($_POST["student-name"]);
	$stuId = trim($_POST["student-id"]);
	$phoNum = trim($_POST["phone-num"]);
	@ $issm = $_POST["is-single-man"];
	@ $issw = $_POST["is-single-woman"];
	@ $isdm = $_POST["is-double-man"];
	@ $isdw = $_POST["is-double-woman"];
	@ $ismix = $_POST["is-mix"];
	$parName = trim($_POST["partner-name"]);
	$parId = trim($_POST["partner-id"]);
	$mixParName = trim($_POST["mix-partner-name"]);
	$mixParId = trim($_POST["mix-partner-id"]);
	
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
		if ( isset($isdm) || isset($isdw) ) {
			if ( !valid_id($parId) ) {
				throw new Exception('Student ID is not valid,  please go back and try again.');
			}
		}
		if ( isset($ismix) ) {
			if ( !valid_id($mixParId) ) {
				throw new Exception('Student ID is not valid,  please go back and try again.');				
			}
		}
		// check phone number
		if ( !valid_tel($phoNum) ) {
			throw new Exception('Phone Number is not valid, please go back and try again.');
		}
		
		$registerInfo = register();
		doHeader("报名成功");
		echo "<p>$stuName 同学, 学号 $stuId, 手机号 $phoNum, 报名参加了以下项目：</p><ul>";
		if ( isset( $registerInfo["sm"] ) ) {
      $drawNum = $registerInfo["sm"];
			echo "<li>男单 (抽签号：$drawNum)</li>";
		}
		if ( isset( $registerInfo["sw"] ) ) {
      $drawNum = $registerInfo["sw"];
			echo "<li>女单 (抽签号：$drawNum)</li>";
		}
		if ( isset( $registerInfo["dm"] ) ) {
      $drawNum = $registerInfo["dm"];
			echo "<li>男双  搭档：$parName (抽签号：$drawNum)</li>";
		}
		if ( isset( $registerInfo["dw"] ) ) {
      $drawNum = $registerInfo["dw"];
			echo "<li>女双  搭档：$parName (抽签号：$drawNum)</li>";
		}
		if ( isset( $registerInfo["mix"] ) ) {
      $drawNum = $registerInfo["mix"];
			echo "<li>混双  搭档：$mixParName (抽签号：$drawNum)</li>";
		}
		echo "</ul>";
    echo "<p>关于抽签的<a href=\"../explain.html\">说明</a></p>";
		doFooter();
		
	} catch ( Exception $e ) {
		doHeader("出错啦");
		echo "<p>".$e->getMessage()."</p>";
		doFooter();
		exit;
	}
	
	function filled_out() {
		$stuName = trim($_POST["student-name"]);
		$stuId = trim($_POST["student-id"]);
		$phoNum = trim($_POST["phone-num"]);
		@ $issm = $_POST["is-single-man"];
		@ $issw = $_POST["is-single-woman"];
		@ $isdm = $_POST["is-double-man"];
		@ $isdw = $_POST["is-double-woman"];
		@ $ismix = $_POST["is-mix"];
		$parName = trim($_POST["partner-name"]);
		$parId = trim($_POST["partner-id"]);
		$mixParName = trim($_POST["mix-partner-name"]);
		$mixParId = trim($_POST["mix-partner-id"]);
		//these three is necessary
		if ( !$stuName || !$stuId || !$phoNum ) {
			return false;
		}
		//one of the five types is necessary
		if ( !isset($issm) && !isset($issw) && !isset($isdm) && 
				 !isset($isdw) && !isset($ismix) ) {
			return false;
		}
		//once applied double, partner info are necessary
		if ( isset($isdm) || isset($isdw) ) {
			if ( !$parName || !$parId ) {
				return false;
			}
		}
		//once applied mix, mix partner info are necessary
		if ( isset($ismix) ) {
			if ( !$mixParName || !$mixParId ) {
				return false;
			}
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
		$stuName = trim($_POST["student-name"]);
		$stuId = trim($_POST["student-id"]);
		$phoNum = trim($_POST["phone-num"]);
		@ $issm = $_POST["is-single-man"];
		@ $issw = $_POST["is-single-woman"];
		@ $isdm = $_POST["is-double-man"];
		@ $isdw = $_POST["is-double-woman"];
		@ $ismix = $_POST["is-mix"];
		$parName = trim($_POST["partner-name"]);
		$parId = trim($_POST["partner-id"]);
		$mixParName = trim($_POST["mix-partner-name"]);
		$mixParId = trim($_POST["mix-partner-id"]);
		
    $registerInfo = array();
		$db = new mysqli('localhost', 'applyAccount', 'applyPassword', 'BadmintonApplication');
		$db->query('set names utf8');
		try {
			if (mysqli_connect_error()) {
				var_dump(mysqli_connect_error());
				throw new Exception('Error: Could not connect to database. Please try again later.');
			}
			
			//Check whether referee, true->reject apply; false->continue
			//The player
			$checkReferee = "select * from referee where id = $stuId limit 1";
			$isReferee = $db->query($checkReferee);
			if ( mysqli_num_rows($isReferee) ) {
				throw new Exception('你已经报名裁判了，不能同时报名参赛');
			}
			$isReferee->close();
			//The partner
			if ( $parId ) {
				$checkReferee = "select * from referee where id = $parId limit 1";
				$isReferee = $db->query($checkReferee);
				if ( mysqli_num_rows($isReferee) ) {
					throw new Exception('你的双打搭档已经报名裁判了，不能同时报名参赛');
				}
				$isReferee->close();
			}
			if ( $mixParId ) {
				$checkReferee = "select * from referee where id = $mixParId limit 1";
				$isReferee = $db->query($checkReferee);
				if ( mysqli_num_rows($isReferee) ) {
					throw new Exception('你的混双搭档已经报名裁判了，不能同时报名参赛');
				}
				$isReferee->close();
			}
			
			//apply for man_single
			if ( isset($issm) ) {
				$check = "select * from man_single where id = $stuId limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertSM = "insert into man_single values(\"$stuId\", $drawNum)";
					$db->query($insertSM);
          $registerInfo["sm"] = $drawNum;
				} else {
					throw new Exception('你已经报名参加过男单项目了！');
				}
			}

			if ( isset($issw) ) {
				$check = "select * from woman_single where id = $stuId limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
				  $insertSW = "insert into woman_single values(\"$stuId\", $drawNum)";
				  $db->query($insertSW);
          $registerInfo["sw"] = $drawNum;
				} else {
					throw new Exception('你已经报名参加过女单项目了！');
				}
			}
			
			if ( isset($isdm) ) {
				$check = "select * from man_double where firstId = $stuId or secondId = $stuId or firstId = $parId or secondId = $parId limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertDM = "insert into man_double values(\"$stuId\", \"$parId\", $drawNum)";
					$db->query($insertDM);
					$insertPartner = 'insert ignore into player values("'.$parId.'", "'.$parName.'", "")';
					$db->query($insertPartner);
          $registerInfo["dm"] = $drawNum;
				} else {
					throw new Exception('你或你的搭档已经报名参加过男双项目了！');
				}
			}
	
			if ( isset($isdw) ) {
				$check = "select * from woman_double where firstId = $stuId or secondId = $stuId or firstId = $parId or secondId = $parId limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertDW = "insert into woman_double values(\"$stuId\", \"$parId\", $drawNum)";
					$db->query($insertDW);
					$insertPartner = 'insert ignore into player values("'.$parId.'", "'.$parName.'", "")';
					$db->query($insertPartner);
          $registerInfo["dw"] = $drawNum;
				} else {
					throw new Exception('你或你的搭档已经报名参加过女双项目了！');
				}					
			}
			
			if ( isset($ismix) ) {
				$check = "select * from mix_double where firstId = $stuId or secondId = $stuId or firstId = $mixParId or secondId = $mixParId limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertMix = "insert into mix_double values(\"$stuId\", \"$mixParId\", $drawNum)";
					$db->query($insertMix);
					$insertMixPartner = 'insert ignore into player values("'.$mixParId.'", "'.$mixParName.'", "")';
					$db->query($insertMixPartner);
          $registerInfo["mix"] = $drawNum;
				} else {
					throw new Exception('你或你的搭档已经报名参加过混双项目了！');
				}
			}
			
			$insertPlayer = 'replace into player values("'.$stuId.'", "'.$stuName.'", "'.$phoNum.'")';
			$db->query($insertPlayer);
			$db->close();
      return $registerInfo;
		} catch ( Exception $e ) {
			doHeader("出错啦");
			echo "<p>".$e->getMessage()."</p>";
			doFooter();
			exit;
		} 
	}
?>
</body>
</html>