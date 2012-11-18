<?php
	
	/******************* DB 연결 함수 ****************************************/
	function connectDB() {
		$db_host = 'localhost';
		$user =  'root';
		$password = 'root';
		$database = 'jeonghwankim';
		
		$dbc = mysqli_connect($db_host, $user, $password, $database)
		  	   or die('Error connecting to MySQL server.');
		  	 
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		mysqli_query($dbc, "set names utf8"); // DB 한글 설정

		return $dbc;
	}

	/******************* 헤더 출력 함수함수 ***************************************/
	function setHeadHtml($title, $css_path) {
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" '.
		'"http://www.w3.org/TR/html4/strict.dtd">';
		
		echo '<html><head>'.
		'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.
		'<title>' . $title . '</title>'.
		'<link rel="stylesheet" type="text/css" href="' . $css_path . '">'.
		'<html><body>';
	}
	
	/******************* 화면 이동 함수 ****************************************/
	function moveURL($url) {
		echo "<meta http-equiv='refresh' content='0; url=$url'>";
	}
	
	/******************* 화면 출력 함수 ****************************************/

	/* 상단 - 타이틀 출력 */
	function showTitle() {
		echo '<div id="title">' .
		'<h1><a href="index.php">성경기록노트</a></h1>' .
		'</div>'; 
		
	}
	
	/* 좌측 - 성경 리스트 출력 */
	function showList($title) {
		$query = "SELECT * FROM bible_list ORDER BY id";
		$dbc = connectDB();
		$result = mysqli_query($dbc, $query) or die ('can not call from list.');
		
		echo '<div id="list"><ul>';
		while ($row = mysqli_fetch_array($result)) {
			$bible_name = $row['title'];
			/* 선택한 성경 */
			if ($bible_name == $title) {
				echo '<li><a href=view.php?title=' . $bible_name . '&chapter=1>'.
				'<span id="selected_bible">' . $bible_name . '</span></a></li>';
			}
			/* 미선택 성경 */
			else {
				echo "<li><a href=view.php?title=$bible_name&chapter=1>" . 
				$bible_name . "</a></li>";
			}
		}
		mysqli_close($dbc);
		echo '</ul></div>';
	}
	
	/* 우측 - 성경제목 장수 출력 */
	function showTitleChapter($title, $chapter) {
		echo "<div id=right>" .
		"<div><h2>$title $chapter 장&nbsp&nbsp" . 
		"<input type=button value=입력 " .
		"onclick=window.location.href='write.php?title=$title&chapter=$chapter' />".
		"</h2></div>";

	}		
	
	/* 하단 - 공백 출력 */
	function showFooter() {	
		echo '<div id="footer">' 
		. '</div>' 
		
		. '</body>' 
		. '</html>';
	}		
	
?>


