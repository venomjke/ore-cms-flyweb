<?php

/**
 * This is the model class for table "{{news_product}}".
 *
 * The followings are the available columns in table '{{news_product}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property string $pubDate
 * @property string $author
 */
class NewsProduct extends CActiveRecord
{

    const RSS_PRODUCT = 'http://monoray.ru/83-open-real-estate?format=feed&type=rss';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NewsProduct the static model class
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
		return '{{news_product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, link, pubDate, author', 'required'),
			array('title, link', 'length', 'max'=>255),
			array('author', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, description, link, pubDate, author', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'link' => 'Link',
			'pubDate' => 'Pub Date',
			'author' => 'Author',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('pubDate',$this->pubDate,true);
		$criteria->compare('author',$this->author,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getProductNews(){
        //$xml = simplexml_load_file(self::RSS_PRODUCT);
        $xml = self::getFeed(self::RSS_PRODUCT);

        if($xml){
            $db = Yii::app()->db;

            foreach($xml->channel->item as $news){
                $link = (string) $news->link;
                $sql = "SELECT id FROM {{news_product}} WHERE link=:link";
                $id = $db->createCommand($sql)
                    ->bindValue(':link', $link, PDO::PARAM_STR)
                    ->queryScalar();
                if($id){
                    continue;
                }

                $sql = "INSERT INTO {{news_product}}
                        (title, description, link, pubDate, author)
                        VALUES
                        (:title, :description, :link, :pubDate, :author)
                        ";

                $pubDate = self::_toMysqlDate((string) $news->pubDate);

                $db->createCommand($sql)
                    ->bindValue(':title', (string) $news->title, PDO::PARAM_STR)
                    ->bindValue(':description', (string) $news->description, PDO::PARAM_STR)
                    ->bindValue(':link', $link, PDO::PARAM_STR)
                    ->bindValue(':pubDate', $pubDate, PDO::PARAM_STR)
                    ->bindValue(':author', (string) $news->author, PDO::PARAM_STR)
                    ->execute();
            }
        }
    }

    private static function _toMysqlDate($pubDate){
        return date('Y-m-d H:i:s', strtotime($pubDate));
    }

    public function getAllWithPagination($inCriteria = null){
   		if($inCriteria === null){
   			$criteria = new CDbCriteria;
   			$criteria->order = 'pubDate DESC';
   		} else {
   			$criteria = $inCriteria;
   		}

   		$pages = new CPagination($this->count($criteria));
   		$pages->pageSize = param('module_news_itemsPerPage', 10);
   		$pages->applyLimit($criteria);

   		$dependency = new CDbCacheDependency('SELECT MAX(pubDate) FROM {{news_product}}');

   		$items = $this->cache(param('cachingTime', 1209600), $dependency)->findAll($criteria);

   		return array(
   			'items' => $items,
   			'pages' => $pages,
   		);
   	}

    public static function getCountNoShow(){
        $sql = "SELECT COUNT(id) FROM {{news_product}} WHERE is_show=0";
        return (int) Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public static function getFeed($url) {
        $rfd = fopen($url, 'r');
        stream_set_blocking($rfd,true);
        stream_set_timeout($rfd, 5);  // 5-second timeout
        $data = stream_get_contents($rfd);
        $status = stream_get_meta_data($rfd);
        fclose($rfd);

        if ($status['timed_out']) {
            return false;
        } else {
            return simplexml_load_string($data);
        }
    }

}