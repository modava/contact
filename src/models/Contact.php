<?php

namespace modava\contact\models;

use common\models\User;
use modava\contact\ContactModule;
use modava\contact\models\metadata_interface\MetadataInterface;
use modava\contact\models\table\ContactTable;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $fullname
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $title
 * @property string $content
 * @property string $ip_address
 * @property int $status
 * @property int $category_id
 * @property int $created_at
 */
class Contact extends ContactTable
{
    public $toastr_key = 'contact';

    public $contactMetadata = null;
    public $contactMetadataIndex = [];
    public $contactMetadataView = null;
    public $contactMetadataForm = null;

    public function init()
    {
        $params_module_contact = isset(Yii::$app->params['module-contact']) ? Yii::$app->params['module-contact'] : null;
        if ($params_module_contact != null) {
            $metadataClass = isset($params_module_contact['metadataClass']) ? $params_module_contact['metadataClass'] : null;
            if ($metadataClass != null && class_exists($metadataClass)) {
                try {
                    $this->contactMetadata = new $metadataClass();
                } catch (\Exception $ex) {
                }
            }
            $metadataIndex = isset($params_module_contact['metadataIndex']) ? $params_module_contact['metadataIndex'] : null;
            if ($metadataIndex != null) {
                try {
                    if (!is_dir(Yii::getAlias($metadataIndex)) && file_exists(Yii::getAlias($metadataIndex))) {
                        $this->contactMetadataIndex = require Yii::getAlias($metadataIndex);
                    }
                } catch (\Exception $ex) {
                }
            }
            $metadataView = isset($params_module_contact['metadataView']) ? $params_module_contact['metadataView'] : null;
            if ($metadataView != null) {
                try {
                    if (!is_dir(Yii::getAlias($metadataView)) && file_exists(Yii::getAlias($metadataView))) {
                        $this->contactMetadataView = require Yii::getAlias($metadataView);
                    }
                } catch (\Exception $ex) {
                }
            }
            $metadataForm = isset($params_module_contact['metadataForm']) ? $params_module_contact['metadataForm'] : null;
            if ($metadataForm != null) {
                try {
                    if (!is_dir(Yii::getAlias($metadataForm)) && file_exists(Yii::getAlias($metadataForm))) {
                        if (substr($metadataForm, -4) == '.php') $metadataForm = substr($metadataForm, 0, -4);
                        $this->contactMetadataForm = $metadataForm;
                    }
                } catch (\Exception $ex) {
                }
            }
        }
        if ($this->contactMetadata == null ||
            !($this->contactMetadata instanceof MetadataInterface)) {
            $this->contactMetadata = null;
        }
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function afterFind()
    {
        if ($this->contactMetadata != null) $this->contactMetadata->setAttributes($this->metadata, false);
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public function beforeSave($insert)
    {
        $this->metadata = $this->contactMetadata != null ? $this->contactMetadata->getMetadata() : [];
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'created_at',
                    ],
                    'value' => time()
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_VALIDATE => ['contactMetadata']
                    ],
                    'value' => function () {
                        if ($this->contactMetadata != null) {
                            $this->contactMetadata->setAttributes($this->metadata, false);
                            return $this->contactMetadata;
                        }
                        return null;
                    }
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'phone'], 'required'],
            [['content'], 'string'],
            [['status', 'type', 'created_at'], 'integer'],
            [['fullname', 'email', 'address', 'title', 'ip_address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 25],
            [['phone'], 'unique'],
//            [['email'], 'unique'],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => ContactCategory::class, 'targetAttribute' => ['type' => 'id']],
            [['metadata'], 'validateMetadata']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'fullname' => Yii::t('backend', 'Fullname'),
            'phone' => Yii::t('backend', 'Phone'),
            'email' => Yii::t('backend', 'Email'),
            'address' => Yii::t('backend', 'Address'),
            'title' => Yii::t('backend', 'Title'),
            'content' => Yii::t('backend', 'Content'),
            'ip_address' => Yii::t('backend', 'Ip Address'),
            'status' => Yii::t('backend', 'Status'),
            'type' => Yii::t('backend', 'Type'),
            'category_id' => Yii::t('backend', 'Category'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }

    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function validateMetadata()
    {
        if (!$this->hasErrors() && $this->contactMetadata != null) {
            $this->contactMetadata->setAttributes($this->metadata, false);
            if (!$this->contactMetadata->validate()) {
                foreach ($this->contactMetadata->getErrors() as $k => $error) {
                    $err = is_array($error[0]) ? implode('<br/>', $error[0]) : $error[0];
                    $this->addError('metadata[' . $k . ']', $err);
                }
            }
        }
    }

    /**
     * Gets query for [[type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContactCategory()
    {
        return $this->hasOne(ContactCategory::class, ['id' => 'type']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreated()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdated()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

}
