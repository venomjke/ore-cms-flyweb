		<table>
<?php
	if($isGuest){
		$model->username = '';
		$model->phone    = '';
		$model->useremail = '';
		?>

			<tr>
				<td> <?php echo $form->labelEx($model,'username'); ?> </td>
				<td> <?php echo $form->textField($model,'username'); ?> </td>
			</tr>
			<tr>
				<td> <?php echo $form->labelEx($model,'phone'); ?> </td>
				<td> <?php echo $form->textField($model,'phone'); ?> </td>
			</tr>
			<tr>
				<td> <?php echo $form->labelEx($model,'useremail'); ?> </td>
				<td> <?php echo $form->textField($model,'useremail'); ?> </td>
			</tr>
		<?php
	}
?>
		<tr>
			<td> 	<?php echo $form->labelEx($model,'comment'); ?> </td>
			<td> 	<?php echo $form->textArea($model,'comment',array('class'=>'width500', 'rows' => '3')); ?> </td>
		</tr>
		</table>