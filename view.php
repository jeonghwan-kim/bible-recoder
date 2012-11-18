<?php	
// =============================================================================
// view.php
// 
// input : 성경제목, 장수 (get)
// output : 성경제목-장수에 해당하는 한절 내용을 출력
// ============================================================================= 
	require_once('htmlAuth.php');
	require_once('functions.php');

	/* 해당 성경 총 장수 출력 */
	function showTotalChapters($title) {
		$total_verse;
		$dbc = connectDB();		
		$query = "SELECT MAX(chapter) FROM bible_check_list WHERE title='$title'";
		$result = mysqli_query($dbc, $query) or die("error in showTotalChapers");
		if ($row = mysqli_fetch_array($result)) {
			$total_verse = $row['MAX(chapter)']; // 성경 총장수 저장
			echo '<div id=chapters><table><tr>';
			for ($i = 1; $i <= $total_verse; $i++) {
				if ( ($i % 10) == 1) echo '<tr>';

				$query = "SELECT * FROM bible_check_list WHERE title='$title' AND chapter='$i'";
				$result = mysqli_query($dbc, $query) or die('error in showTotalChapers2');
				$row = mysqli_fetch_array($result);

				if ($row['written'] == 1) {
					echo "<td id=written><a href=view.php?title=$title&chapter=$i>" . $i . 
						 "장</a></td>";	
				}
				else {
					echo "<td><a href=view.php?title=$title&chapter=$i>" . $i . 
						 "장</a></td>";
				}

				if ( ($i % 10) == 0 ) echo '</tr>';
			}
			echo '</table></div>';
			mysqli_close($dbc);
		}		
	}

	/* 성경 해당 장 본문 출력 */
	function showText($title, $chapter) {
		$query = "SELECT * FROM bible_contents " .
		"WHERE title = '$title' AND chapter = '$chapter' ORDER BY verse";
		$dbc = connectDB();
		$result = mysqli_query($dbc, $query) or die("Error querying4");
		mysqli_close($dbc);
		if (mysqli_num_rows($result) == 0 ) { // 아무것도 입력되지 않았을 경우
			echo "어머니!<br />" .
			"$title $chapter 장은 아직 입력하지 않으셨습니다.<br /><br />" .
			"입력방법은 매우 간단합니다.<br />" .
			"1. <좌측>에서 입력할 성경을 선택하여 <br />" .
			"2. <우측>에 있는 <입력 버튼>을 클릭하여 입력하시면 됩니다. <br />";
		}
		else { // 입력된 경우 db에 저장된 내용을 출력
			while($row = mysqli_fetch_array($result)) {
				$verse = $row['verse'];
				$contents = $row['contents'];
				echo '<div id="verse">' . $verse . '.' . '</div>' .
				'<div id="contents">'. $contents . '</div>';
			}

			echo '</div>'; // end of right div

		}
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
// 	showList($tit÷ßle);

	/* 우측 - 제목출력 (마태복음 1장) */
	showTitleChapter($title, $chapter);

	/* 우측 - 성경의 총 장 출력 (1장, 2장, ... , 28장) */
	showTotalChapters($title); 

	/* 우측 - 본문 출력 */
	showText($title, $chapter);

	/* 하단 - 공백 */
	showFooter();
?>	