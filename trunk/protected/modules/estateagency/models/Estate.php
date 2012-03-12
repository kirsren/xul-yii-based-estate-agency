<?php

/**
 * This is the model class for table "estates".
 *
 * The followings are the available columns in table 'estates':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $price
 * @property integer $rooms
 * @property string $heating
 * @property string $type
 * @property string $city
 * @property string $address
 * @property integer $agent
 * @property integer $client
 */
class Estate extends CActiveRecord
{
     public $image; // used by the form to send the file.
     
	/**
	 * Returns the static model of the specified AR class.
	 * @return Estate the static model class
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
		return 'estates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, price, rooms, heating, type, city, address, agent, client', 'required'),
			array('price, rooms, agent, client', 'numerical', 'integerOnly'=>true),
			array('name, city, address', 'length', 'max'=>255),
			array('heating', 'length', 'max'=>20),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, price, rooms, heating, type, city, address, agent, client', 'safe', 'on'=>'search'),
            array('image', 'file', 'types' => 'png, jpg', 'allowEmpty' => true), // behavior
		);
	}
    
   
	
	public function behaviors() {
		return array(
			'imageBehavior' => array(
				'class' => 'ImageARBehavior',
				'attribute' => 'image', // this must exist
				'extension' => 'png, jpg', // possible extensions, comma separated
				'prefix' => 'estate_',
				'relativeWebRootFolder' => 'files/estates', // this folder must exist
				
				# 'forceExt' => png, // this is the default, every saved image will be a png one.
				# Set to null if you want to keep the original format
				
				//'useImageMagick' => '/usr/bin', # I want to use imagemagick instead of GD, and
				# it is located in /usr/bin on my computer.
				
				// this will define formats for the image.
				// The format 'normal' always exist. This is the default format, by default no
				// suffix or no processing is enabled.
				'formats' => array(
					// create a thumbnail grayscale format
					'thumb' => array(
						'suffix' => '_thumb',
						'process' => array('resize' => array(60, 60)),
					),
					// create a large one (in fact, no resize is applied)
					'large' => array(
						'suffix' => '_large',
					),
					// and override the default :
					'normal' => array(
						'process' => array('resize' => array(200, 200)),
					),
				),
				
				'defaultName' => 'default', // when no file is associated, this one is used by getFileUrl
				// defaultName need to exist in the relativeWebRootFolder path, and prefixed by prefix,
				// and with one of the possible extensions. if multiple formats are used, a default file must exist
				// for each format. Name is constructed like this :
				//     {prefix}{name of the default file}{suffix}{one of the extension}
			)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		Yii::app()->getModule('user');
		return array(
            'agent'=> array(self::BELONGS_TO, 'YumUser', 'agent'),
            'client'=>array(self::BELONGS_TO, 'Client', 'client'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'price' => 'Price',
			'rooms' => 'Rooms',
			'heating' => 'Heating',
			'type' => 'Type',
			'city' => 'City',
			'address' => 'Address',
			'agent' => 'Agent',
			'client' => 'Client',
            'image'  => 'Image',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('rooms',$this->rooms);
		$criteria->compare('heating',$this->heating,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('agent',$this->agent);
		$criteria->compare('client',$this->client);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    
}