<div class="pre-content"></div>
<div class="quiz">
<h1><?php echo stripslashes($_SESSION['wpsqt'][$quizName]['sections'][$sectionKey]['name']); ?></h1>

<?php if ( isset($_SESSION['wpsqt']['current_message']) ) { ?>
	<p><?php echo $_SESSION['wpsqt']['current_message']; ?></p>
<?php } ?>

<?php 
if (isset($GLOBALS['q_config']) && isset($GLOBALS['q_config']['url_info']['url'])) {
	$url = $GLOBALS['q_config']['url_info']['url'];
} else {
	$url = $_SERVER['REQUEST_URI'];
}
?>
<form method="post" action="<?php echo esc_url($url); ?>">
	<input type="hidden" name="wpsqt_nonce" value="<?php echo WPSQT_NONCE_CURRENT; ?>" />
	<input type="hidden" name="step" value="<?php echo ( $_SESSION['wpsqt']['current_step']+1); ?>">
<?php
		$answers = ( isset($_SESSION["wpsqt"][$quizName]["sections"][$sectionKey]["answers"]) ) ? $_SESSION["wpsqt"][$quizName]["sections"][$sectionKey]["answers"] : array();
foreach ($_SESSION['wpsqt'][$quizName]['sections'][$sectionKey]['questions'] as $questionKey => $question) { ?>

	<div class="wpst_question">
		<?php 
		
			$questionId = $question['id'];		
			$givenAnswer = isset($answers[$questionId]['given']) ? $answers[$questionId]['given'] : array();
			
			if ( isset($question["required"]) &&  $question["required"] == "yes" ){ 
				?>
				<font color="#FF0000"><strong>*
				
			<?php			
				// See if the question has been missed and this a replay if not end the red text here.
				if ( empty($_SESSION['wpsqt']['current_message']) || in_array($questionId,$_SESSION['wpsqt']['required']['given']) ){
					?></strong></font><?php 
				}
			}			
						
			echo stripslashes($question['name']);
			
			// See if the question has been missed and this is a replay
			if ( !empty($_SESSION['wpsqt']['current_message']) && !in_array($questionId,$_SESSION['wpsqt']['required']['given']) ){
				?></strong></font><?php 
			}	
		
			if ( !empty($question['add_text']) ){
			?>
			<p><?php echo nl2br(stripslashes($question['add_text'])); ?></p>
			<?php } ?>
			
			<?php if ( isset($question['image'])) { ?>
			<p><?php echo stripslashes($question['image']); ?></p>
			<?php } ?>
			
			<?php do_action('wpsqt_quiz_question_section',$question); ?>
			
			<?php require Wpsqt_Question::getDisplayView($question); ?>

			<?php if (isset($question['explanation']) && !empty($question['explanation'])) {
				// Parse the explanation text with the token replacement method
				// 	- Set up the token object
				require_once WPSQT_DIR.'/lib/Wpsqt/Tokens.php';
				$objTokens = Wpsqt_Tokens::getTokenObject();
				$objTokens->setDefaultValues();
				//	- replace the tokens
				$explanation = $objTokens->doReplacement( $question['explanation'] );
				if (!isset($question['explanation_onlyatfinish']) || $question['explanation_onlyatfinish'] !== "yes" ) { 
					echo '<a href="#" class="wpsqt-show-answer" style="display: none;">Show answer</a>';
					echo '<div class="wpsqt-answer-explanation" style="display: none;">'.nl2br(stripslashes($explanation)).'</div>';
				}
			} ?>
			
	</div>
<?php } ?>
<?php
if ($sectionKey == (count($_SESSION['wpsqt'][$quizName]['sections']) - 1)) {
	?><p><input type='submit' value='Submit' class='button-secondary' /></p><?php
} else {
	?><p><input type='submit' value='Next &raquo;' class='button-secondary' /></p><?php
}
?>
	
</form>
</div>
<div class="post-content"></div>
