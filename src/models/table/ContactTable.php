<?php

namespace modava\contact\models\table;

use backend\components\MyModel;
use modava\contact\models\query\ContactQuery;

class ContactTable extends MyModel
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'contact';
    }

    public static function find()
    {
        return new ContactQuery(get_called_class());
    }
}
