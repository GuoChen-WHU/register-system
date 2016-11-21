<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" >
  <title>感谢报名</title>
</head>
<body>
<?php

	$stuName = trim($_POST["student-name"]);
	$stuId = trim($_POST["student-id"]);
	$phoNum = trim($_POST["phone-num"]);
	$item1 = trim($_POST["item1"]);
	$item2 = trim($_POST["item2"]);
	$parName1 = trim($_POST["partner1-name"]);
	$parId1 = trim($_POST["partner1-id"]);
	$parName2 = trim($_POST["partner2-name"]);
	$parId2 = trim($_POST["partner2-id"]);

	try {
		// check forms filled in
		if ( !filled_out() ) {
			throw new Exception('You have not filled the form out correctly, please
				go back and try again.');
		}
		// check id
		if ( !valid_id($stuId) ) {
			throw new Exception('Your student ID is not valid,  please go back and try again.');
		}
		if ( $parId1 != "" ) {
			if ( !valid_id($parId1) ) {
				throw new Exception('The student ID of $item1 partner is not valid,  please go back and try again.');
			}
		}
		if ( $parId2 != "" ) {
			if ( !valid_id($parId2) ) {
				throw new Exception('The student ID of $item2 partner is not valid,  please go back and try again.');				
			}
		}
		// check phone number
		if ( !valid_tel($phoNum) ) {
			throw new Exception('Phone Number is not valid, please go back and try again.');
		}
		
		$registerInfo = register();
		echo "<h1>报名成功</h1>";
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
      $par = $registerInfo["dm-par"];
			echo "<li>男双  搭档：$par (抽签号：$drawNum)</li>";
		}
		if ( isset( $registerInfo["dw"] ) ) {
      $drawNum = $registerInfo["dw"];
      $par = $registerInfo["dw-par"];
			echo "<li>女双  搭档：$par (抽签号：$drawNum)</li>";
		}
		if ( isset( $registerInfo["mix"] ) ) {
      $drawNum = $registerInfo["mix"];
      $par = $registerInfo["mix-par"];
			echo "<li>混双  搭档：$par (抽签号：$drawNum)</li>";
		}
		echo "</ul>";
    echo "<p>关于抽签的<a href=\"../explain.html\">说明</a></p>";
		
	} catch ( Exception $e ) {
		echo "<h1>出错啦</h1>";
		echo "<p>".$e->getMessage()."</p>";
		exit;
	}
	
	function filled_out() {
		$stuName = trim($_POST["student-name"]);
		$stuId = trim($_POST["student-id"]);
		$phoNum = trim($_POST["phone-num"]);
	  $item1 = trim($_POST["item1"]);
	  $item2 = trim($_POST["item2"]);
	  $parName1 = trim($_POST["partner1-name"]);
	  $parId1 = trim($_POST["partner1-id"]);
	  $parName2 = trim($_POST["partner2-name"]);
	  $parId2 = trim($_POST["partner2-id"]);
		//these three is necessary
		if ( !$stuName || !$stuId || !$phoNum ) {
			return false;
		}
		//one of the items is necessary
		if ( $item1 !== "sng-man" && $item1 !== "sng-wm" 
				&& $item1 !== "dbl-man" && $item1 !== "dbl-wm"
				&& $item1 !== "dbl-mix" && $item2 !== "sng-man"
				&& $item2 !== "sng-wm" && $item2 !== "dbl-man"
				&& $item2 !== "dbl-wm" && $item2 !== "dbl-mix" ) {
			return false;
		}
		//once applied double, partner info are necessary
		if ( $item1 == "dbl-man" || $item1 == "dbl-wm" || $item1 == "dbl-mix" ) {
			if ( !$parName1 || !$parId1 ) {
				return false;
			}
		}
		if ( $item2 == "dbl-man" || $item2 == "dbl-wm" || $item2 == "dbl-mix" ) {
			if ( !$parName2 || !$parId2 ) {
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
	  $item1 = trim($_POST["item1"]);
	  $item2 = trim($_POST["item2"]);
	  $parName1 = trim($_POST["partner1-name"]);
	  $parId1 = trim($_POST["partner1-id"]);
	  $parName2 = trim($_POST["partner2-name"]);
	  $parId2 = trim($_POST["partner2-id"]);
		
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
			if ( $parId1 ) {
				$checkReferee = "select * from referee where id = $parId1 limit 1";
				$isReferee = $db->query($checkReferee);
				if ( mysqli_num_rows($isReferee) ) {
					throw new Exception('你的双打搭档已经报名裁判了，不能同时报名参赛');
				}
				$isReferee->close();
			}
			if ( $parId2 ) {
				$checkReferee = "select * from referee where id = $parId2 limit 1";
				$isReferee = $db->query($checkReferee);
				if ( mysqli_num_rows($isReferee) ) {
					throw new Exception('你的双打搭档已经报名裁判了，不能同时报名参赛');
				}
				$isReferee->close();
			}
			
			//apply for man_single
			if ( $item1 == "sng-man" || $item2 == "sng-man" ) {
				$check = "select * from man_single where id = $stuId limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertSM = "insert into man_single values(\"$stuId\", $drawNum)";
					$response = $db->query($insertSM);
          $registerInfo["sm"] = $drawNum;
				} else {
					throw new Exception('你已经报名参加过男单项目了！');
				}
			}

			if ( $item1 == "sng-wm" || $item2 == "sng-wm" ) {
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
			
			if ( $item1 == "dbl-man" ) {
				$check = "select * from man_double where firstId = $stuId or secondId = $stuId or firstId = $parId1 or secondId = $parId1 limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertDM = "insert into man_double values(\"$stuId\", \"$parId1\", $drawNum)";
					$db->query($insertDM);
					$insertPartner = 'insert ignore into player values("'.$parId1.'", "'.$parName1.'", "")';
					$db->query($insertPartner);
          $registerInfo["dm"] = $drawNum;
          $registerInfo["dm-par"] = $parName1;
				} else {
					throw new Exception('你或你的搭档已经报名参加过男双项目了！');
				}
			}
	
			if ( $item1 == "dbl-wm" ) {
				$check = "select * from woman_double where firstId = $stuId or secondId = $stuId or firstId = $parId1 or secondId = $parId1 limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertDW = "insert into woman_double values(\"$stuId\", \"$parId1\", $drawNum)";
					$db->query($insertDW);
					$insertPartner = 'insert ignore into player values("'.$parId1.'", "'.$parName1.'", "")';
					$db->query($insertPartner);
          $registerInfo["dw"] = $drawNum;
          $registerInfo["dw-par"] = $parName1;
				} else {
					throw new Exception('你或你的搭档已经报名参加过女双项目了！');
				}					
			}
			
			if ( $item1 == "dbl-mix" ) {
				$check = "select * from mix_double where firstId = $stuId or secondId = $stuId or firstId = $parId1 or secondId = $parId1 limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertMix = "insert into mix_double values(\"$stuId\", \"$parId1\", $drawNum)";
					$db->query($insertMix);
					$insertMixPartner = 'insert ignore into player values("'.$parId1.'", "'.$parName1.'", "")';
					$db->query($insertMixPartner);
          $registerInfo["mix"] = $drawNum;
          $registerInfo["mix-par"] = $parName1;
				} else {
					throw new Exception('你或你的搭档已经报名参加过混双项目了！');
				}
			}

			if ( $item2 == "dbl-man" ) {
				$check = "select * from man_double where firstId = $stuId or secondId = $stuId or firstId = $parId2 or secondId = $parId2 limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertDM = "insert into man_double values(\"$stuId\", \"$parId2\", $drawNum)";
					$db->query($insertDM);
					$insertPartner = 'insert ignore into player values("'.$parId2.'", "'.$parName2.'", "")';
					$db->query($insertPartner);
          $registerInfo["dm"] = $drawNum;
          $registerInfo["dm-par"] = $parName2;
				} else {
					throw new Exception('你或你的搭档已经报名参加过男双项目了！');
				}
			}
	
			if ( $item2 == "dbl-wm" ) {
				$check = "select * from woman_double where firstId = $stuId or secondId = $stuId or firstId = $parId2 or secondId = $parId2 limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertDW = "insert into woman_double values(\"$stuId\", \"$parId2\", $drawNum)";
					$db->query($insertDW);
					$insertPartner = 'insert ignore into player values("'.$parId2.'", "'.$parName2.'", "")';
					$db->query($insertPartner);
          $registerInfo["dw"] = $drawNum;
          $registerInfo["dw-par"] = $parName2;
				} else {
					throw new Exception('你或你的搭档已经报名参加过女双项目了！');
				}					
			}
			
			if ( $item2 == "dbl-mix" ) {
				$check = "select * from mix_double where firstId = $stuId or secondId = $stuId or firstId = $parId2 or secondId = $parId2 limit 1";
				$result = $db->query($check);
				if ( $result == false || mysqli_num_rows($result) == 0 ) {
          $drawNum = mt_rand(100, 999);
					$insertMix = "insert into mix_double values(\"$stuId\", \"$parId2\", $drawNum)";
					$db->query($insertMix);
					$insertMixPartner = 'insert ignore into player values("'.$parId2.'", "'.$parName2.'", "")';
					$db->query($insertMixPartner);
          $registerInfo["mix"] = $drawNum;
          $registerInfo["mix-par"] = $parName2;
				} else {
					throw new Exception('你或你的搭档已经报名参加过混双项目了！');
				}
			}
						
			$insertPlayer = 'replace into player values("'.$stuId.'", "'.$stuName.'", "'.$phoNum.'")';
			$db->query($insertPlayer);
			$db->close();
      return $registerInfo;
		} catch ( Exception $e ) {
			echo "<h1>出错啦</h1>";
			echo "<p>".$e->getMessage()."</p>";
			exit;
		} 
	}
?>
</body>
</html>