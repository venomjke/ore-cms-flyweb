<?php
/*
* Flyweb Dev team
*/
class MainController extends ModuleUserController {

	public $modelName = 'Slider';
	
	public function actionIndex(){
		$this->redirect('/');
	}
}