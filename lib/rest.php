<?php

namespace g2r\test;
//namespace Bitrix\Main\CModule;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\CModule;
use Bitrix\Main\Iblock\CIBlockType;
Loc::loadMessages(__FILE__);

class RestTable extends DataManager
{
    public static function getTableName()
    {
        return 'test_example';
    }
    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true,
                'title' => Loc::getMessage('G2R_TEST_ID'),
            )),
            new StringField('NAME', array(
                'required' => true,
                'title' => Loc::getMessage('G2R_TEST_NAME'),
                'default_value' => function () {
                    return Loc::getMessage('G2R_TEST_NAME_DEFAULT_VALUE');
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                },
            )),
            new StringField('IMAGE_SET', array(
                'required' => false,
                'title' => Loc::getMessage('G2R_TEST_IMAGE_SET'),
                'fetch_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (strlen($value)) {
                                return explode(',', $value);
                            }
                        },
                    );
                },
                'save_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (is_array($value)) {
                                $value = array_filter($value, 'intval');

                                return implode(',', $value);
                            }
                        },
                    );
                },
            )),
        );
    }
}
