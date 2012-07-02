<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Андрей Пасынков
 * Date: 15.03.12
 * Time: 12:30
 */
class ParentModel extends CActiveRecord
{
    public $WorkItemsSelected;

    public function afterDelete(){
        if(isset($this->sorter)){
            $sql = "SELECT id FROM ".$this->tableName()." ORDER BY sorter ASC";
            $ids = Yii::app()->db->createCommand($sql)->queryColumn();
            $i = 1;
            foreach($ids as $id){
                $sql = "UPDATE ".$this->tableName()." SET sorter=$i WHERE id=$id";
                Yii::app()->db->createCommand($sql)->execute();
                $i++;
            }
        }
        return parent::afterDelete();
    }
}
