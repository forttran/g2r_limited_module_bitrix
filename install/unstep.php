<?
use \Bitrix\Main\Localization\Loc;
use g2r\test\RestTable;

global $DB; 

if(!check_bitrix_sessid())
	return;

if($ex = $APPLICATION->GetException())
	echo CAdminMessage::ShowNote(array(
		"TYPE" => "ERROR",
		"MESSAGE" => "Ошибка удаления",
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
else{
	$DB->StartTransaction();

    if(!CIBlockType::Delete('Products')){
        $DB->Rollback();
        echo CAdminMessage::ShowNote(array(
			"TYPE" => "ERROR",
			"MESSAGE" => "Ошибка удаления",
			"DETAILS" => $ex->GetString(),
			"HTML" => true,
		));
    }
    echo CAdminMessage::ShowNote("Тестовый Модуль был удален");
    $DB->Commit();
}
?>