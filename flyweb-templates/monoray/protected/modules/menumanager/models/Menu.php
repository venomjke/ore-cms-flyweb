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

class Menu extends CActiveRecord{

	const LINK_NEW_MANUAL = 1;
	const LINK_NEW_AUTO = 2;
	const LINK_DROPDOWN = 3;
	const LINK_DROPDOWN_MANUAL = 4;
	const LINK_DROPDOWN_AUTO = 5;

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{menu}}';
	}

	public static function getMenuItems(){
		$criteria = new CDbCriteria;
		$criteria->select = 'id, type, title, page_title, widget, subitems, href, special';
		$criteria->condition = 'active = 1';
		$criteria->order = 'sorter';

		$menu = Yii::app()->cache->get('menu');

		if($menu === false){
			$menuItems = self::model()->findAll($criteria);

			foreach($menuItems as $item){
				if($item['special']){
					$item['href'] = array($item['href']);
				}

				if($item['type'] == self::LINK_NEW_MANUAL){
					$menu[] = array(
						'label' => $item['title'],
						'url' => $item['href'],
					);
				}
				if($item['type'] == self::LINK_NEW_AUTO){
					$menu[] = array(
						'label' => $item['title'],
						'url' => array('/menumanager/main/view', 'id' => $item['id'], 'title' => $item['title']),
					);
				}

				if($item['type'] == self::LINK_DROPDOWN){
					$subitems = array();
					foreach($menuItems as $tmpItem){
						if($tmpItem['subitems'] == $item['id']){
							if($tmpItem['type'] == self::LINK_DROPDOWN_MANUAL){
								if($tmpItem['special']){
									$tmpItem['href'] = array($tmpItem['href']);
								}
								$subitems[] = array(
									'label' => $tmpItem['title'],
									'url' => $tmpItem['href'],
								);
							}
							if($tmpItem['type'] == self::LINK_DROPDOWN_AUTO){
								$subitems[] = array(
									'label' => $tmpItem['title'],
									'url' => array('/menumanager/main/view', 'id' => $tmpItem['id'], 'title' => $tmpItem['title']),
								);
							}
						}
					}
					if($subitems){
						$menu[] = array(
							'label' => $item['title'],
							'submenuOptions'=>array(
								'class'=>'sub_menu_dropdown'
							),
							'url'=>array('/site/index'),
							'items' => $subitems,
						);
					}
				}
			}

			Yii::app()->cache->set('menu', $menu);
		}
		if(!$menu){
			return array();
		}
		return $menu;
	}

	public function rules(){
		return array(
			array('type', 'required'),
			
			array('title, href, subitems', 'required', 'on' => 'insert, update'),

			array('title, href', 'required', 'on' => 'link_'.self::LINK_NEW_MANUAL),
			array('title', 'required', 'on' => 'link_'.self::LINK_DROPDOWN),
			array('title, href, subitems', 'required', 'on' => 'link_'.self::LINK_DROPDOWN_MANUAL),

			array('title', 'required', 'on' => 'special'),

			array('page_title, page_body, widget', 'safe'),
			
			/*array('name_ru', 'length', 'max'=>255),
			array('id, name_ru, date_updated', 'safe', 'on'=>'search'),
			array('class, coords', 'safe'),*/
		);
	}

	public function relations(){
		return array(
		);
	}

	public function attributeLabels(){
		return array(
			'title' => 'Текст ссылки',
			'active' => 'Включено',
			'type' => 'Тип ссылки',
			'href' => 'Ссылка',
			'subitems' => 'Выпадающий список для размещения в нем',

			'page_title' => 'Заголовок страницы',
			'page_body' => 'Текст на странице',
			'widget' => 'Отобразить снизу страницы',
			
			/*'name_ru' => tt('Station name (Russian)'),
			'date_updated' => 'Date Updated',
			'class' => tt('CSS class(es) (for map)'),
			'coords' => tt('Coordinates of station (for map)'),*/
		);
	}

	public function behaviors(){
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'date_updated',
				'updateAttribute' => 'date_updated',
			),
		);
	}

	public function search(){
		$criteria=new CDbCriteria;

		//$criteria->compare('subitems', 0);
		/*$criteria->compare('id',$this->id);*/
		//$criteria->compare('',$this->name_ru,true);
		/*$criteria->compare('date_updated',$this->date_updated,true);*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
			'sort'=>array(
				'defaultOrder'=>'sorter',
			)
		));
	}

	public function searchSubitems(){
		$criteria = new CDbCriteria;
		$criteria->compare('subitems', $this->id);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>param('adminPaginationPageSize', 20),
			),
			'sort'=>array(
				'defaultOrder'=>'sorter',
			)
		));
	}

	public function getTypes(){
		return array(
			self::LINK_NEW_MANUAL => 'Простая ссылка (задается вручную)',
			self::LINK_NEW_AUTO => 'Страница с текстом',
			self::LINK_DROPDOWN => 'Выпадающий список',
			self::LINK_DROPDOWN_MANUAL => 'Ссылка в выпадающем списке (задается вручную)',
			self::LINK_DROPDOWN_AUTO => 'Страница с текстом в выпадающем списке ',
		);
	}

	public function getForSubitems(){
		$sql = 'SELECT id, title FROM {{menu}} WHERE type="'.self::LINK_DROPDOWN.'" AND active';

		$return = CHtml::listData(Yii::app()->db->createCommand($sql)->queryAll(), 'id', 'title');
		
		if($this->special){
			return CMap::mergeArray(array('0' => 'Не выбрано'), $return);
		} else {
			return $return;
		}

	}

	public function beforeSave(){
		if($this->isNewRecord){
			$this->active = 1;

			$maxSorter = Yii::app()->db->createCommand()
				->select('MAX(sorter) as maxSorter')
				->from('{{menu}}')
				->queryRow();
			$this->sorter = $maxSorter['maxSorter']+1;
		}
		Yii::app()->cache->delete('menu');

		if($this->special){
			if($this->subitems){
				$this->type = self::LINK_DROPDOWN_MANUAL;
			} else {
				$this->type = self::LINK_NEW_MANUAL;
			}
		}

		return parent::beforeSave();
	}

	public function beforeDelete(){
		$sql = 'UPDATE {{menu}} SET subitems=0 WHERE subitems=:subitems';
		Yii::app()->db->createCommand($sql)->execute(array(':subitems' => $this->id));

		return parent::beforeDelete();
	}

	public function getUrl(){
		return array(
			'/menumanager/main/view',
			'id'=>$this->id,
			'title'=>$this->page_title,
		);
	}

	public function getTitle(){
		$return = CHtml::encode($this->title);
		if(Yii::app()->user->isAdmin){
			$href = array();
			switch ($this->id) {
				case 2:
					$href = array('/news/backend/main/admin');
					break;
				case 4:
					$href = array('/articles/backend/main/admin');
					break;
			}
			if($href){
				$return .= ' ['.CHtml::link('Управление разделом', $href).']';
			}
		}

		if($this->type == self::LINK_DROPDOWN){
			if(!self::model()->countByAttributes(array('subitems' => $this->id))){
				$return .= '<br/> - в данном пункте нет вложенных элементов. Пункт меню не будет отображаться.';
			}
		}

		return $return;
	}
	
	public static function getWidgetOptions(){
		$arrWidgets =  array(
			'' => 'Нет',
			'news' => 'Новости',
			'apartments' => 'Список объявлений',
			'viewallonmap' => 'Поиск объявлений на карте',
			'contactform' => 'Форма "Свяжитесь с нами"',
		);

        if (issetModule('metrosearch')) {
            $arrWidgets['metrosearch'] = Yii::t('common', 'Search on map by metro station');
        }
        return $arrWidgets;
	}

}