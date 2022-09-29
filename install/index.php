<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use g2r\test\RestTable;

Loc::loadMessages(__FILE__);

class g2r_test extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'g2r.test';
        $this->MODULE_NAME = Loc::getMessage('G2R_TEST_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('G2R_TEST_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('G2R_TEST_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://bitrix.expert';
    }

    public function doInstall()
    {
         global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $GLOBALS["APPLICATION"]->IncludeAdminFile("Установка модуля", __DIR__ . "/step.php");
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            RestTable::getEntity()->createDbTable();
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(RestTable::getTableName());
        }
    }
}
