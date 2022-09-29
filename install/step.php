<?
use \Bitrix\Main\Localization\Loc;
use g2r\test\RestTable;

class rest_parsing{
	private $info = array();
	private $dblock;
	private $query;

	function __construct($query) {
		$this->query = $query;
		$this->loadData();
		echo CAdminMessage::ShowNote("тестовый Модуль был установлен");
	}

	private function FieldTypeBlock(){
		//Описываем свойства нового типа инфоблока
		return array(
		    'ID' => 'Products',
		    'SECTIONS' => 'N',
		    'IN_RSS' => 'N',
		    'SORT' => 900,
		    'LANG' => array(
		        'ru' => array(
		            'NAME' => 'Товары',
		            'SECTION_NAME' => 'Товары',
		            'ELEMENT_NAME' => 'Товары',
		        ),
		        'en' => array(
		            'NAME' => 'Products',
		            'SECTION_NAME' => 'Banners',
		            'ELEMENT_NAME' => 'Banners'
		        ),
		    )
		);
	}

	private function FieldBlock(){
		//Описываем свойства нового инфоблока
 
		return array(
    		'VERSION' => 2,
    		'ACTIVE' => 'Y',
    		'NAME' => 'Товары',
    		'IBLOCK_TYPE_ID' => 'Products',
    		'CODE' => 'pr',
    		'SITE_ID' => array('s1'),
    		'SORT' => 500,
    		'GROUP_ID' => array(2 => 'R'),
    		'LIST_MODE' => 'C',
    		'WORKFLOW' => 'N',
    		'INDEX_ELEMENT' => 'N',
    		'INDEX_SECTION' => 'N',
    		'RSS_ACTIVE' => 'N',
    		'XML_ID' => 'products',
    		'LIST_PAGE_URL' => '/#IBLOCK_CODE#/',
    		'SECTION_PAGE_URL' => '/#IBLOCK_CODE#/#SECTION_CODE_PATH#/',
    		'DETAIL_PAGE_URL' => '/#IBLOCK_CODE#/#SECTION_CODE_PATH#/#ELEMENT_CODE#.html',
    		'FIELDS' => array(
        		'DETAIL_PICTURE' => array(
            		'IS_REQUIRED' => 'Y',
        		),
        		'CODE' => array(
            		'IS_REQUIRED' => 'N',
            		'DEFAULT_VALUE' => array(
                		'UNIQUE' => 'Y',
            		),
        		),
        		'IBLOCK_SECTION' => array(
            		'IS_REQUIRED' => 'N',
        		),
        		'SECTION_CODE' => array(
            		'IS_REQUIRED' => 'N',
            		'DEFAULT_VALUE' => array(
                		'TRANSLITERATION' => 'Y',
                		'UNIQUE' => 'Y',
                		'TRANS_CASE' => 'L',
                		'TRANS_SPACE' => '-',
                		'TRANS_OTHER' => '-',
            		),
        		),
    		),
    		'IS_CATALOG' => 'N',
    		'VAT_ID' => '',
		);
	}

	private function properties(){
		 //Описываем поля свойств нового нужных инфоблоков
		 return array(
		    array(
		    	'NAME' => 'Цена',
		        'ACTIVE' => 'Y',
		        'SORT' => 500,
		        'CODE' => 'PRICE',
		        "PROPERTY_TYPE" => "N",
		        'IBLOCK_ID' => $this->dblock
		    )
		);
	}

	function oGetMessage($key, $fields){
		//языковые константы
	    $messages = array(
	        'A_AM_NEW_IBLOCK_TYPE_MESSAGE_ADDED' => 'Тип информационного блока «#IBLOCK_TYPE#» успешно добавлен',
	        'A_AM_NEW_IBLOCK_TYPE_MESSAGE_UPDATE' => 'Тип информационного блока «#IBLOCK_TYPE#» успешно обновлён',
	 
	        'A_AM_NEW_IBLOCK_MESSAGE_ADDED' => 'Информационный блок «#IBLOCK#» [#ID#] успешно добавлен',
	        'A_AM_NEW_IBLOCK_MESSAGE_UPDATE' => 'Информационный блок «#IBLOCK#» [#ID#] успешно обновлён',
	        'A_AM_NEW_IBLOCK_MESSAGE_ERROR' => 'Ошибка добавления информационного блока «#IBLOCK#»: #ERROR#',
	        'A_AM_NEW_IBLOCK_MESSAGE_ERROR_UPDATE' => 'Ошибка обновления информационного блока «#IBLOCK#»: #ERROR#',
	        'A_AM_NEW_IBLOCK_MESSAGE_EXISTS' => 'Инфоблок «#IBLOCK#» уже существует',
	 
	        'A_AM_NEW_IBLOCK_PROP_MESSAGE_UPDATE' => 'Свойство «#NAME#» [#ID#] успешно обновлено',
	        'A_AM_NEW_IBLOCK_PROP_MESSAGE_UPDATE_ERROR' => 'Ошибка обновления свойства «#NAME#» [#ID#]: #ERROR#',
	 
	        'A_AM_NEW_IBLOCK_PROP_MESSAGE_ADDED' => 'Свойство «#NAME#» [#ID#] успешно добавлено',
	        'A_AM_NEW_IBLOCK_PROP_MESSAGE_ADDED_ERROR' => 'Ошибка добавления свойства «#NAME#»: #ERROR#',
	    );
	    return isset($messages[$key])
	        ? str_replace(array_keys($fields), array_values($fields), $messages[$key])
	        : '';
	}
	private function addTypeBlock(){
		//функция добавления типа блока
		$boolIblockExists = false;
		 
		$iblockTypeIterator = \Bitrix\Iblock\TypeTable::getById('products');
		if (($iblockType = $iblockTypeIterator->fetch())) {
		    $boolIblockExists = true;
		}
		 
		$obBlockType = new CIBlockType;
		 
		if ($boolIblockExists) {
		    if ($obBlockType->Update($this->FieldTypeBlock()['ID'], $this->FieldTypeBlock())) {
		        $this->$info[] = $this->oGetMessage('A_AM_NEW_IBLOCK_TYPE_MESSAGE_UPDATE', array(
		            '#IBLOCK_TYPE#' => $this->FieldTypeBlock()['LANG']['ru']['NAME'],
		        ));
		    } else {
		        throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_TYPE_MESSAGE_ERROR_UPDATE', array(
		            '#ERROR#' => $obBlockType->LAST_ERROR,
		        )));
		    }
		} else {
		    $res = $obBlockType->Add($this->FieldTypeBlock());
		    if ($res) {
		        $this->$info[] = $this->oGetMessage('A_AM_NEW_IBLOCK_TYPE_MESSAGE_ADDED', array(
		            '#IBLOCK_TYPE#' => $this->FieldTypeBlock()['LANG']['ru']['NAME'],
		        ));
		    } else {
		        throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_TYPE_MESSAGE_ERROR', array(
		            '#ERROR#' => $obBlockType->LAST_ERROR,
		        )));
		    }
		}
	}
	private function addBlock(){
		//функция добавления блока
		$newIBlockId = 0;
		$iblockIterator = \Bitrix\Iblock\IblockTable::getList(array(
		    'filter' => array(
		        '=IBLOCK_TYPE_ID' => 'products',
		    )
		));
		if (($iblock = $iblockIterator->fetch())) {
		    $newIBlockId = $iblock['ID'];
		}
		 
		$obIBlock = new CIBlock;
		 
		if (intval($newIBlockId) > 0) {
		 
		    if ($obIBlock->Update($newIBlockId, $this->FieldBlock())) {
		        $this->$info[] = $this->oGetMessage('A_AM_NEW_IBLOCK_MESSAGE_UPDATE', array(
		            '#IBLOCK#' => $this->FieldBlock()['NAME'],
		            '#ID#' => intval($newIBlockId),
		        ));
		    } else {
		        throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_MESSAGE_ERROR_UPDATE', array(
		            '#ERROR#' => $obIBlock->LAST_ERROR,
		        )));
		    }
		 
		} else {
		    $res = $obIBlock->Add($this->FieldBlock());
		 
		    if ($res) {
		        $newIBlockId = intval($res);
		        $this->$info[] = $this->oGetMessage('A_AM_NEW_IBLOCK_MESSAGE_ADDED', array(
		            '#IBLOCK#' => $this->FieldBlock()['NAME'],
		            '#ID#' => $newIBlockId,
		        ));
		    } else {
		        throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_MESSAGE_ERROR', array(
		            '#ERROR#' => $obIBlock->LAST_ERROR,
		        )));
		    }
		}
		$this->dblock = CIBlock::GetList(array(), array(), false, false, array("IBLOCK_ID"))->GetNext()["ID"];
	}

	private function addProperties(){
		\Bitrix\Main\Loader::includeModule('iblock');
		global $APPLICATION;
		//функция добавления свойств к блоку
		foreach ($this->properties() as $arProperty) {
		 
		    $arProperty['IBLOCK_ID'] = str_replace('{NEW_IBLOCK_ID}', $newIBlockId, $arProperty['IBLOCK_ID']);
		 
		    $ibp = new CIBlockProperty();
		 
		    $resProperty = CIBlockProperty::GetList(
		        array(),
		        array(
		            'CODE' => $arProperty['CODE'],
		            'IBLOCK_ID' => $arProperty['IBLOCK_ID']
		        )
		    );
		    if ($arHasProperty = $resProperty->Fetch()) {
		        if ($ibp->Update($arHasProperty['ID'], $arProperty)) {
		            $this->$info[] = $this->oGetMessage('A_AM_NEW_IBLOCK_PROP_MESSAGE_UPDATE', array(
		                '#NAME#' => $arProperty['NAME'],
		                '#ID#' => $arHasProperty['ID'],
		            ));
		        } else {
		            if (($ex = $APPLICATION->GetException())) {
		                throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_PROP_MESSAGE_UPDATE_ERROR', array(
		                    '#ERROR#' => $ex->GetString(),
		                    '#NAME#' => $arProperty['NAME'],
		                    '#ID#' => $arHasProperty['ID'],
		                )));
		            } elseif (isset($ibp->LAST_ERROR)) {
		                throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_PROP_MESSAGE_UPDATE_ERROR', array(
		                    '#ERROR#' => $ibp->LAST_ERROR,
		                    '#NAME#' => $arProperty['NAME'],
		                    '#ID#' => $arHasProperty['ID'],
		                )));
		            }
		        }
		 
		    } else {

		        if (($propID = $ibp->Add($arProperty))) {
		            $this->$info[] = $this->oGetMessage('A_AM_NEW_IBLOCK_PROP_MESSAGE_ADDED', array(
		                '#NAME#' => $arProperty['NAME'],
		                '#ID#' => $propID,
		            ));
		        } else {
		            if (($ex = $APPLICATION->GetException())) {
		                throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_PROP_MESSAGE_ADDED_ERROR', array(
		                    '#NAME#' => $arProperty['NAME'],
		                    '#ERROR#' => $ex->GetString(),
		                )));
		            } elseif (isset($ibp->LAST_ERROR)) {
		                throw new \Bitrix\Main\SystemException($this->oGetMessage('A_AM_NEW_IBLOCK_PROP_MESSAGE_ADDED_ERROR', array(
		                    '#NAME#' => $arProperty['NAME'],
		                    '#ERROR#' => $ibp->LAST_ERROR,
		                )));
		            }
		        }
		    }
		}
	}
	private function addBlockElement(){

		global $USER;

		$response = file_get_contents($this->query);
		$arr = json_decode($response);
		$el = new CIBlockElement;

		foreach ($arr->result->toReturn as $item) {
			$PROP = array();
			$PROP["PRICE"] = $item->price;  
			$arLoadProductArray = Array(
				"MODIFIED_BY"    => $USER->GetID(), 
				"IBLOCK_SECTION_ID" => false,          
				"IBLOCK_ID"      => $this->dblock,
				"PROPERTY_VALUES"=> $PROP,
				"NAME"           => $item->subject,
				"ACTIVE"         => "Y",            
				"PREVIEW_TEXT"   => "текст для списка элементов",
				"DETAIL_TEXT"    => "текст для детального просмотра",
				"DETAIL_PICTURE" => CFile::MakeFileArray($item->img)
			);

			if(!$el->Add($arLoadProductArray))
			  echo "Error: ".$el->LAST_ERROR;
		}
	}

	public function loadData(){		
		
		global $DB; 

		$DB->StartTransaction();
		
		try {

		    $this->addTypeBlock();
		    $this->addBlock();
		    $this->addProperties();

		    foreach($this->$info as $mess){
		    	echo CAdminMessage::ShowNote($mess);
		    }

			$this->addBlockElement();

			$DB->Commit();
		} catch (\Bitrix\Main\SystemException $e) {
			$DB->Rollback();
			echo sprintf("%s<br>\n%s",$e->getMessage(),implode("<br>\n", $info));
		}
	}
}

if(!check_bitrix_sessid())
	return;

if($ex = $APPLICATION->GetException())
	echo CAdminMessage::ShowNote(array(
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
else{
	new rest_parsing("https://cdn.asiaoptom.com/api/v1/?key=test5_d8a3ebe%D0%B573866f4ca6f798d8c1b64f21&type=section&filter[query]=男士衣服");
	//loadData("https://cdn.asiaoptom.com/api/v1/?key=test5_d8a3ebe%D0%B573866f4ca6f798d8c1b64f21&type=section&filter[query]=男士衣服");
	//RestTable::loadData("fdsfdf"); //функция для загрузки данных
}
?>
<form action="<?echo $APPLICATION->GetCurPage(); ?>">
	<input type="hidden" name="lang" value="<?echo LANGUAGE_ID?>">
	<input type="submit" name="" value="<?echo Lcc::getMessage("MOD_BACK");?>">
</form>
