<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$menu = array(
    array(
        'parent_menu' => 'global_menu_content',
        'sort' => 400,
        'text' => Loc::getMessage('G2R_TEST_MENU_TITLE'),
        'title' => Loc::getMessage('G2R_TEST_MENU_TITLE'),
        'url' => 'test_index.php',
        'items_id' => 'menu_references',
        'items' => array(
            array(
                'text' => Loc::getMessage('G2R_TEST_SUBMENU_TITLE'),
                'url' => 'test_index.php?param1=paramval&lang=' . LANGUAGE_ID,
                'more_url' => array('test_index.php?param1=paramval&lang=' . LANGUAGE_ID),
                'title' => Loc::getMessage('G2R_TEST_SUBMENU_TITLE'),
            ),
        ),
    ),
);

return $menu;
