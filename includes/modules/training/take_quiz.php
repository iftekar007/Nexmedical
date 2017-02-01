<?php

global $AI;


if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['process_quiz']))
{
	$sql = "SELECT * FROM quiz_questions WHERE quiz_id=".(int)$_POST['quiz_id'];
	$quiz_questions = $AI->db->GetAll($sql,'question_id');
	
	$date_end = date("Y-m-d H:i:s");
	$number_of_questions = 0;
	$number_correct = 0;

	//Grab real answers and check against user answers
	foreach ($_POST as $n=>$v)
	{
		if(preg_match('/answer/', $n))
		{
			$question_id = trim($n,'answer_');
			$sql = "SELECT answer_choice_id, answer_text FROM quiz_answer_choices WHERE correct_flag = 1 AND question_id =".$question_id;
			$response = db_query($sql);
			while($response && $row =db_fetch_assoc($response))
			{	
				// input answer
				if($quiz_questions[$question_id]['is_free_form']){
					if(@strpos(strtolower($row['answer_text']), strtolower($v) ) > -1 )
					{
						$number_correct++;
					}
					continue;
				}
				
				// multiple choice
				if($v == $row['answer_choice_id'])
				{
					$number_correct++;
				}
			}
		}
	}

	$submission_id = db_insert_id();
	foreach ($_POST as $n=>$v)
	{
		if(preg_match('/answer/', $n))
		{
			$question_id = trim($n,'answer_');
			$sql = "INSERT INTO quiz_answers (question_id, submission_id, answer) VALUES (".db_in($question_id).",".db_in($submission_id).",".db_in($v).")";
			db_query($sql);
		}
	}
	
	$number_of_questions = (int)db_lookup_scalar("SELECT COUNT(*) FROM quiz_questions WHERE quiz_id=".intval(util_POST('quiz_id',0)));
	$percentage = ($number_correct/$number_of_questions)*100;
	$percent_needed = (int) db_lookup_scalar("SELECT completion_percent FROM quiz WHERE quiz_id = ".intval(util_POST('quiz_id',0)));

	$sql = "INSERT INTO quiz_submissions (quiz_id, userID, date_started, date_ended, number_correct,total_questions,perc_required) VALUES(".db_in($_POST['quiz_id']).",".db_in($AI->user->userID).", '".db_in($_POST['date_start'])."', '".db_in($date_end)."', ".intval($number_correct).",".intval($number_of_questions).",".intval($percent_needed) .")";
	db_query($sql);
	
	$url = util_POST('url','training');
	$next_lesson = util_POST('next_lesson',0);
	$unit = util_POST('unit');

	$html= '';
	if($percentage < $percent_needed  )
	{
		$html.= '<div class="quiz_fail">';
		$html.= '<p>'.number_format($percentage, 0).'% Correct</p>';
		$html.= '<p>You must score a '.number_format($percent_needed).'% to continue</p>';
		//$html.= '<a href="#" onclick="training.close_quiz(\''.util_POST('callback_url').'\');return false;">Close Window</a>';
		$html .= '<a href="javascript:void(0)" class="te_button te_button_new" onclick=\'training.close_quiz("'.$unit.'",0,"'.$url.'")\'>Close</a>';
		$html.= '</div>';		
	}
	else
	{
		$html.= '<div class="quiz_success">';
		$html.= '<p>You passed the quiz</p>';
		$html.= '<p>'.number_format($percentage, 0).'% Correct</p>';
		//$html.= '<a href="#" onclick="training.close_quiz(\''.util_POST('callback_url').'\');return false;">Close Window</a>';
		$html .= '<a href="javascript:void(0)" class="te_button te_button_new" onclick=\'training.close_quiz("'.$unit.'",'.$next_lesson.',"'.$url.'")\'>Close</a>';
		$html.= '</div>';
	}
	
	echo $html;
}
else
{
	$quiz_id = (int)util_GET('quiz_id',0);
	$next_lesson = util_GET('next_lesson',0);
	$url = util_GET('url','training');
	$unit = util_GET('unit');
	$sql = "SELECT * FROM quiz_questions WHERE quiz_id=$quiz_id";
	$response = db_query($sql);
	$row_count = db_num_rows($response);
	$index=0;
	$date_start = date("Y-m-d H:i:s");
	echo '<form id="quiz" action="take_quiz.php" onsubmit="training.process_quiz(this);return false;" method="post">';
	echo '<input type="hidden" name="quiz_id" id="quiz_id" value="'.$quiz_id.'" />';
	echo '<input type="hidden" name="next_lesson" id="next_lesson" value="'.$next_lesson .'" />';
	echo '<input type="hidden" name="url" id="url" value="'.$url.'" />';
	echo '<input type="hidden" name="unit" id="unit" value="'.$unit.'" />';
	echo '<input type="hidden" name="process_quiz" id="process_quiz" value="1" />';
	echo '<input type="hidden" name="date_start" id="date_start" value="'.$date_start.'"/>';
	while($response && $row=db_fetch_assoc($response))
	{
		if($index!=0)
		{
			echo '<div id="question_'.$index.'" style="display:none;">';
		}
		else
		{
			echo '<div id="question_'.$index.'">';
		}
		echo '<div>';
		echo $AI->get_dynamic_area( /*$name_or_id*/ $row['question_content'], /*$type =*/ 'name', /*$lang =*/ $AI->get_lang(), /*$edit =*/ false);
		echo '</div>';
		echo '<div class="answer_choices">';
			if($row['is_free_form']){
				echo '<div class="answer_choice">';
				echo '<input type="text" placeholder="Enter Your Answer" name="answer_'.$row["question_id"].'" />';
				echo '</div>';
			}
			else {
				// multiple choice
				$sql2 = "SELECT * FROM quiz_answer_choices WHERE question_id = ".$row['question_id'];
				$response2 = db_query($sql2);
				while($response2 && $row2=db_fetch_assoc($response2))
				{
					echo '<div class="answer_choice">';
					echo '<input type="radio" id="answer_'.$row["question_id"].'" name="answer_'.$row["question_id"].'" value="'.$row2["answer_choice_id"].'"/>';
					echo '<label>'.$row2['answer_text'].'</label>';
					echo '</div>';
				}
			}
		echo '</div> <!-- answer_choices -->';
		if($index!=0)
		{
			echo '<button onclick="training.show_question(\'question_'.$index.'\',\'question_'.($index-1).'\'); return false;" class="quiz_button_prev">Prev Question</button>';
		}
		if($index != $row_count-1)
		{
			echo '<button class="quiz_button_next" onclick="training.show_question(\'question_'.$index.'\',\'question_'.($index+1).'\'); return false;" >Next Question</button>';
		}
		else
		{
			echo '<input class="quiz_button_next" id="finish_quiz"  name="finish_quiz" type="submit" value="Finish Quiz" />';
		}
		echo '</div>';
		$index++;
	}
	echo '</form>';
	
	echo '<div id="quiz_results" >';
	echo '</div>';
}

