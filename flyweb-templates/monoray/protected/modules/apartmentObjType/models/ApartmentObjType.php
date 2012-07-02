<?php

/**
 * This is the model class for table "{{apartment_type}}".
 *
 * The followings are the available columns in table '{{apartment_type}}':
 * @property integer $id
 * @property string $name
 * @property integer $sorter
 * @property string $date_updated
 */
class ApartmentObjType extends ParentModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ApartmentType the static model class
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
		return '{{apartment_obj_type}}';
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
			array('name', 'length', 'max'=>150),
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
            //array(self::BELONGS_TO, 'Apartment', 'obj_type_id')
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

    public function beforeDelete(){
        if($this->model()->count() <= 1){
			echo 0;
            return false;
        }
		
        $db = Yii::app()->db;

        $sql = "SELECT id FROM ".$this->tableName()." WHERE id != ".$this->id." ORDER BY sorter ASC";
        $type_id = (int) $db->createCommand($sql)->queryScalar();

        $sql = "UPDATE {{apartment}} SET obj_type_id={$type_id}, active=0 WHERE obj_type_id=".$this->id;
        $db->createCommand($sql)->execute();

        return parent::beforeDelete();
    }

}