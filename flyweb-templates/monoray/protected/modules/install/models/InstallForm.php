<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	1.2.0
*	copyright			:	(c) 2012 Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class InstallForm extends CFormModel {

    public $agreeLicense;

    public $dbHost = 'localhost';
    public $dbUser = 'root';
    public $dbPass;
	public $dbName;
	public $dbPrefix = 'ore_';

	public $adminName;
	public $adminPass;
	public $adminEmail;

	public function rules()	{
		return array(
			array('dbUser, dbHost, dbName, adminName, adminPass, adminEmail', 'required'),
			array('agreeLicense', 'required', 'requiredValue' => true, 'message'=>'Вы должны согласиться с "лицензионным соглашением"'),
			array('adminEmail', 'email'),
			array('dbUser, dbPass, dbName', 'length', 'max' => 30),
			array('dbHost', 'length', 'max' => 50),
			array('adminPass', 'length', 'max' => 20, 'min' => 6),
			array('dbPrefix', 'safe')
		);
	}

	public function attributeLabels() {
		return array(
            'agreeLicense' => 'Я согласен с ' . CHtml::link('лицензионным соглашением', '#',
                                                            array('onclick'=>'$("#licensewidget").dialog("open"); return false;')),
            'dbHost' => 'Сервер базы данных',
            'dbUser' => 'Имя пользователя БД',
            'dbPass' => 'Пароль пользователя БД',
            'dbName' => 'Имя базы данных',
            'dbPrefix' => 'Префикс для таблиц',
            'adminName' => 'Логин администратора',
            'adminPass' => 'Пароль администратора',
            'adminEmail' => 'Email администратора'
		);
	}
}