<?php
$this->pageTitle .= ' - '.$model->getStrByLang('title');
?>

<div class="<?php echo issetModule('viewpdf') ? 'div-pdf-fix' : ''; ?>">
	<?php
		if(issetModule('viewpdf')) {
			echo '<div class="floatleft pdficon">
				<a href="'.Yii::app()->baseUrl.'/viewpdf/main/view?id='.$model->id.'"
					target="_blank"><img src="'.Yii::app()->baseUrl.'/images/design/file_pdf.png"
					alt="'.Yii::t('common', 'Pdf version').'" title="'.Yii::t('common', 'Pdf version').'"  />
				</a></div>';
		}
		echo '<div class="floatleft"><h1 class="h1-ap-title">'.$model->getStrByLang('title').'</h1></div>';
		if($model->rating){ // если у объявления есть рейтинг - показываем
			?>
			<div class="ratingview">
				<?php
					$this->widget('CStarRating',
						array(
							'name'=>'ratingview'.$model->id,
							'id'=>'ratingview'.$model->id,
							'value'=>intval($model->rating),
							'readOnly'=>true,
						));
				?>
			</div>
			<?php
		}
		?>
</div>
<div class="clear"></div>
	<?php
	// показвываем непосредственно объявление
	$this->renderPartial('_view', array(
		'data'=>$model,
		'usertype' => 'visitor',
	));

?>

<div id="comments">
	<?php
		echo '<h2>'.Yii::t('module_comments','Comments').'</h2>';
		if(Yii::app()->user->hasFlash('newComment') || $comment->getErrors()){

			// Новый комментарий добавлен (или ошибка), скролим страницу до области с комментариями (и сообщением)
			Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/scrollto.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerScript('comments','scrollto("comments");',CClientScript::POS_READY);
		}
		
		echo '<a href="#" onclick="$(\'#comments_form\').toggle(); return false;">'.Yii::t('module_comments','Leave a Comment').'</a>';

		// рисуем скрытую форму добавления комментария. Если есть ошибки валидации - то форма не скрытая
		echo '<div id="comments_form" class="'.($comment->getErrors()?'':'hidden').'">';
		$this->renderPartial('application.modules.comments.views.backend._form',array(
			'model'=>$comment,
		));
		echo '</div>';

		if(Yii::app()->user->hasFlash('newComment')){
			echo "<div class='flash-success'>".Yii::app()->user->getFlash('newComment')."</div>";
		}

		// Если есть комментарии - показываем количество и сами комментарии
		echo '<div id="comments-list">';
		if($model->commentCount){
			$this->renderPartial('_comments',array(
				'apartment'=>$model,
				'comments'=>$model->comments,
			));
		} else {
			echo Yii::t('module_comments', 'There are no comments');
		}
		echo '</div>';

		
	?>
</div>
