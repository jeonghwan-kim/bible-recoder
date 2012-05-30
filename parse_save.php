<?php	
	// =========================================================================
	// parse_save.php
	// 
	// 입력한 성경을 db 형식에 맞게 잘라 db로 저장함
	// (취소 버튼 클릭시 index.php로 이동)
	// =========================================================================
	require_once('functions.php');
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	// 한글 파라메터 설정시 필요한 부분

	/* 잘못된 접근 */
	if (!isset($_POST['submit'])) {
		echo 'error ouccerd';
		exit();
	}
	
	/* 취소 버튼클릭시 index.php로 이동 */
	if ($_POST['submit'] == '취소') {	
		moveurl('index.php');
	}
	
	/* 테이블에 맞게 분할하여 db에 저장 */
	else if ($_POST['submit'] == '저장') {	
		$text = $_POST['text'];
		$title = $_POST['title']; 
		$chapter = $_POST['chapter'];
		$today = date("Y-m-d");
		
		$dbc = connectDB();
		if (empty($text)) { /* 삭제 */
			$query = "DELETE FROM bible_contents WHERE title='$title' AND chapter='$chapter'";
			mysqli_query($dbc, $query) or die('error to delete');
			
			/* 체크리스트에 미입력으로 기록 */
			$query = "UPDATE bible_check_list SET written='0' WHERE title='$title' AND chapter='$chapter'";
			mysqli_query($dbc, $query) or die("error to update2");
		}
		
		else { /* 입력 */
			/* 문자열 분리 */
			$array = explode("\n", $text); // 개행문자 기준으로 분리
			$to_db_data = array();
			foreach($array as $arr) {
				$first_dot_pos = strpos($arr, "."); // . 위치 찾았다.
				if ($first_dot_pos) { // "." 이 있을 경우만 작업함.
					$verse		= substr($arr, 0, $first_dot_pos); // verse 잘랐다.
					$contents	= substr($arr, $first_dot_pos+1); // contents 잘랐다.
					$contents= trim($contents); // contents 앞뒤 공백 제거.

					/* db에 저장 (기존내용 삭제 후 저장, 편집이 있을수 있기 때문) */
					$query = "DELETE FROM bible_contents ".
					"WHERE title='$title' AND chapter='$chapter' AND verse='$verse'";
					mysqli_query($dbc, $query) or die("errro to query "); // 삭제
					$query = "INSERT INTO bible_contents (title, chapter, verse,contents,date) ".
					"VALUES ('$title', '$chapter', '$verse', '$contents', '$today')";
					mysqli_query($dbc, $query) or die("errro to query "); // 저장
				}
			}
			
			/* db에 입력된 성경임을 표시 list->is_blank */
			$query = "UPDATE bible_check_list SET written='1' WHERE title='$title' AND chapter='$chapter'";
			mysqli_query($dbc, $query) or die("error to update2");
			
			/* db에 최근 입력 성경(title)과 장(chapter)를 업데이트 */
			$query = "UPDATE bible_recent SET title='$title', chapter='$chapter'".
			"WHERE id=1";			
		}
		
		mysqli_query($dbc, $query) or die("error to update");
		
		/* 홈으로 이동 */
	 	moveurl("index.php"); // 나중에 index.php로 이동
	}
	
	/* 잘못된 접근 */
	else {
		echo 'error ouccerd';
		exit();
	}
?>

	
