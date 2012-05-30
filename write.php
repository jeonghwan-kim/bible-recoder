<?php
	// =========================================================================
	//  write.php
	//  
	//  input : 성경제목, 장수
	//  output : db에서 해당 성경 장을 조회하여 화면에 편집형태로 출력
	//  
	// =========================================================================
	require_once('htmlAuth.php');
	require_once('functions.php');

	function getTextFromDB($title, $chapter) {
		$text = ""; // 성경의 한장을 저장(form db)
		$query = "SELECT * FROM bible_contents " .
		"WHERE title = '$title' AND chapter = '$chapter' ORDER BY verse";
		$dbc = connectDB();
		$result = mysqli_query($dbc, $query) or die("Error querying");
		mysqli_close($dbc);
		
		while($row = mysqli_fetch_array($result)) {
			$verse = $row['verse'];
			$contents = $row['contents'];
			$text .= $verse . '. ' . $contents . "\n";
		}	

		return $text;
	}

	function showTextArea($title, $chapter, $text) {
		echo '<form method="post" action="parse_save.php">';
		echo '<textarea name="text">' . $text . '</textarea><br />';
		echo '<input type="submit" name="submit" value="저장" />&nbsp&nbsp' .
		'<input type="submit" name="submit" value="취소" />';
		echo '<input type="hidden" name="title" value="' . $title . '" />';
		echo '<input type="hidden" name="chapter" value="' . $chapter . '" />';
		echo '</form>';
	}

	setHeadHtml('Bible', 'style.css');	

	/* 파라메터 가져오기  */
	$title = $_GET['title']; // 제목
	$chapter = $_GET['chapter']; // 장
	if (!isset($title) || !isset($chapter)) {
		echo "parameter error<br />";
		exit();
	}

	/* 상단 - 타이틀 출력 */
	showTitle();
	
	/* 좌측 - 리스트 출력 */
	showList($title);
	
	/* 제목출력 (마태복음 1장) */
	echo '<div id="right">';
	echo '<div><h2>' . $title . ' ' . $chapter .' 장</h2></div><br />';
	
	echo '입력 방법은 간단한 규칙만 지키면 됩니다. <br />' .
	'<절>. <내용>을 입력한 뒤 엔터를 입력하시변 됩니다. <br /><br />' . 
	'예를 들어 아래와 같이 입력하면 됩니다. <br /> <br />' . 
	'3. 심령이 가난한 자는 복이 있나니 천국이 저희 것임이요 <br />' .
	'4. 애통하는 자는 복이 있나니 저희가 위로를 받을 것임이요 <br />' .
	'5. 온유한 자는 복이 있나니 저희가 땅을 기업으로 받을 것임이요 <br /> <br />' .
	'참 쉽죠? 그럼 아래 입력해 보세요~<br /><br />';

	/* 해당 성경 장의 본문 조회 */
	$text = getTextFromDB($title, $chapter);

	/* textarea에 본문 출력 */
	showTextArea($title, $chapter, $text);

	/* 하단 - 공백 */
	showFooter();
?>

