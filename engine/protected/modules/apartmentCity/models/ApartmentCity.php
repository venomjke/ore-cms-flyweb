<?php

/**
 * This is the model class for table "{{apartment_city}}".
 *
 * The followings are the available columns in table '{{apartment_city}}':
 * @property integer $id
 * @property string $name
 * @property integer $sorter
 * @property string $date_updated
 */
class ApartmentCity extends ParentModel
{
    private static $_activeCity;
	/**
	 * Returns the static model of the specified AR class.
	 * @return ApartmentCity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{apartment_city}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('sorter', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, sorter, date_updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            //array(self::BELONGS_TO, 'Apartment', 'city_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => tt('Name'),
			'sorter' => 'Sorter',
			'date_updated' => 'Date Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
    public function search(){
        $criteria=new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        $criteria->order = 'sorter ASC';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>param('adminPaginationPageSize', 20),
            ),
        ));
    }

    public function beforeSave(){
        if($this->isNewRecord){
            $maxSorter = Yii::app()->db->createCommand()
                ->select('MAX(sorter) as maxSorter')
                ->from($this->tableName())
                ->queryScalar();
            $this->sorter = $maxSorter+1;
        }

        return parent::beforeSave();
    }

    public static function getActiveCity(){
        if(self::$_activeCity === null){
            $sql = 'SELECT ac.name AS name, ac.id AS id
                    FROM {{apartment}} ap, {{apartment_city}} ac
                    WHERE ac.id = ap.city_id';

            //$cachingTime = param('shortCachingTime', 3600*4);
            //$dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{apartment}}');

            $results = Yii::app()->db->createCommand($sql)->queryAll();

            self::$_activeCity = CHtml::listData($results, 'id', 'name');
        }
        return self::$_activeCity;
    }

    public function beforeDelete(){
        if($this->model()->count() <= 1){
            return false;
        }

        $sql = "UPDATE {{apartment}} SET city_id=0, active=0 WHERE city_id=".$this->id;
        Yii::app()->db->createCommand($sql)->execute();

        return parent::beforeDelete();
    }

}