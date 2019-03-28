<?php 

class HLCreate{
/*HLBlock parameters end*/


/*проверяем на существование HL с таким именем, если нет то создаем*/

 private function oGetMessage($key, $fields)
	{
	    $messages = array(
	        'ERROR_INCLUDE_HIGHLOADBLOCK_MODULE' => 'Ошибка подключения модуля «#MODULE#»',
	        'HIGHLOADBLOCK_NAME_IS_INVALID' => 'Неверное имя для хайлоад блока',
	        'HIGHLOADBLOCK_EXISTS' => 'Highload Block #NAME# уже существует',
	        'HIGHLOADBLOCK_ADDED_INFO' => 'Highload Block #NAME# [#ID#] добавлен',
	        'HIGHLOADBLOCK_ADDED_INFO_ERROR' => 'Ошибка добавления Highload блока #NAME#: #ERROR#',
	        'HIGHLOADBLOCK_ADDED_INDEX_INFO' => 'Индекс #INDEX_NAME# к таблице #TABLE_NAME# успешно добавлен',
	        'HIGHLOADBLOCK_ALTER_SUCCESS_INFO' => 'ALTER TABLE успешно выполнен: #ROW#',
	        'HIGHLOADBLOCK_ADDED_INDEX_ERROR' => 'Ошибка добавление индекса #INDEX_NAME# к таблице #TABLE_NAME#: #ERROR#',
	 
	        'USER_TYPE_UPDATE' => 'Пользовательское свойство #FIELD_NAME# [#ENTITY_ID#] успешно обновлено',
	        'USER_TYPE_UPDATE_ERROR' => 'Ошибка обновления пользовательского свойства #FIELD_NAME# [#ENTITY_ID#]: #ERROR#',
	        'USER_TYPE_ADDED' => 'Пользовательское свойство #FIELD_NAME# [#ENTITY_ID#] успешно добавлено',
	        'USER_TYPE_ADDED_ERROR' => 'Ошибка добавления пользовательского свойства #FIELD_NAME# [#ENTITY_ID#]: #ERROR#',
	        'USER_TYPE_ENUMS_SET_ERROR' => 'Ошибка установки значений пользовательского свойства #FIELD_NAME# [#ENTITY_ID#]: #ERROR#',
	    );
	 
	    return isset($messages[$key])
	        ? str_replace(array_keys($fields), array_values($fields), $messages[$key])
	        : '';
	}
	 

	 
 private function createHighLoadBlock($tableName, $highBlockName, array $hlData)
	{
	    global $info, $APPLICATION;

	    $info = array();
	 
	    foreach (array('highloadblock') as $moduleId) {
	        if (!\Bitrix\Main\Loader::includeModule($moduleId)) {
	            throw new \Bitrix\Main\SystemException($this->oGetMessage('ERROR_INCLUDE_HIGHLOADBLOCK_MODULE', array(
	                '#MODULE#' => $moduleId
	            )));
	        }
	    }
	 
	    $connection = \Bitrix\Main\Application::getConnection();
	 
	    $sqlHelper = $connection->getSqlHelper();
	 
	    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
	            'filter' => array(
	                'TABLE_NAME' => $tableName,
	            ))
	    )->fetch();
	 
	    if (!$hlblock) {
	 
	        $highBlockName = preg_replace('/([^A-Za-z0-9]+)/', '', trim($highBlockName));
	 
	        if ($highBlockName == '') {
	            throw new \Bitrix\Main\SystemException($this->oGetMessage('HIGHLOADBLOCK_NAME_IS_INVALID'));
	        }
	 
	        $highBlockName = strtoupper(substr($highBlockName, 0, 1)) . substr($highBlockName, 1);
	 
	        $data = array(
	            'NAME' => $highBlockName,
	            'TABLE_NAME' => $tableName,
	        );
	 
	        $result = Bitrix\Highloadblock\HighloadBlockTable::add($data);
	 
	        if ($result->isSuccess()) {
	            $highBlockID = $result->getId();
	 
	            $info[] = $this->oGetMessage('HIGHLOADBLOCK_ADDED_INFO', array(
	                '#NAME#' => $highBlockName,
	                '#ID#' => $highBlockID,
	            ));
	 
	        } else {
	            throw new \Bitrix\Main\SystemException($this->oGetMessage('HIGHLOADBLOCK_ADDED_INFO_ERROR', array(
	                '#NAME#' => $highBlockName,
	                '#ERROR#' => $result->getErrorMessages(),
	            )));
	        }
	 
	    } else {
	        $highBlockID = $hlblock['ID'];
	    }
	 
	    $oUserTypeEntity = new CUserTypeEntity();
	 
	    $sort = 500;
	 
	    foreach ($hlData['FIELDS'] as $fieldName => $fieldValue) {
	        $aUserField = array(
	            'ENTITY_ID' => 'HLBLOCK_' . $highBlockID,
	            'FIELD_NAME' => $fieldName,
	            'USER_TYPE_ID' => $fieldValue[1],
	            'SORT' => $sort,
	            'MULTIPLE' => 'N',
	            'MANDATORY' => $fieldValue[0],
	            'SHOW_FILTER' => 'N',
	            'SHOW_IN_LIST' => 'Y',
	            'EDIT_IN_LIST' => 'Y',
	            'IS_SEARCHABLE' => 'N',
	            'SETTINGS' => array(),
	        );
	 
	        if (isset($fieldValue[2]) && is_array($fieldValue[2])) {
	            $aUserField = array_merge($aUserField, $fieldValue[2]);
	        }
	 
	        $resProperty = CUserTypeEntity::GetList(
	            array(),
	            array('ENTITY_ID' => $aUserField['ENTITY_ID'], 'FIELD_NAME' => $aUserField['FIELD_NAME'])
	        );
	 
	        if ($aUserHasField = $resProperty->Fetch()) {
	            $idUserTypeProp = $aUserHasField['ID'];
	            if ($oUserTypeEntity->Update($idUserTypeProp, $aUserField)) {
	                $info[] = $this->oGetMessage('USER_TYPE_UPDATE', array(
	                    '#FIELD_NAME#' => $aUserHasField['FIELD_NAME'],
	                    '#ENTITY_ID#' => $aUserHasField['ENTITY_ID'],
	                ));
	            } else {
	                if (($ex = $APPLICATION->GetException())) {
	                    throw new \Bitrix\Main\SystemException($this->oGetMessage('USER_TYPE_UPDATE_ERROR', array(
	                        '#FIELD_NAME#' => $aUserHasField['FIELD_NAME'],
	                        '#ENTITY_ID#' => $aUserHasField['ENTITY_ID'],
	                        '#ERROR#' => $ex->GetString(),
	                    )));
	                }
	            }
	        } else {
	            if ($idUserTypeProp = $oUserTypeEntity->Add($aUserField)) {
	                $info[] = $this->oGetMessage('USER_TYPE_ADDED', array(
	                    '#FIELD_NAME#' => $aUserField['FIELD_NAME'],
	                    '#ENTITY_ID#' => $aUserField['ENTITY_ID'],
	                ));
	            } else {
	                if (($ex = $APPLICATION->GetException())) {
	                    throw new \Bitrix\Main\SystemException($this->oGetMessage('USER_TYPE_ADDED_ERROR', array(
	                        '#FIELD_NAME#' => $aUserField['FIELD_NAME'],
	                        '#ENTITY_ID#' => $aUserField['ENTITY_ID'],
	                        '#ERROR#' => $ex->GetString(),
	                    )));
	                }
	            }
	        }
	 
	        $sort += 100;
	    }
	 
	    $hlEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
	        \Bitrix\Highloadblock\HighloadBlockTable::getRowById($highBlockID)
	    );
	 
	    if (isset($hlData['ALTER']) && is_array($hlData['ALTER'])) {
	 
	        foreach ($hlData['ALTER'] as $alterData) {
	 
	            if ($connection->query(
	                str_replace(
	                    '#TABLE_NAME#',
	                    $sqlHelper->quote($hlEntity->getDBTableName()),
	                    $alterData
	                )
	            )
	            ) {
	                $info[] = $this->oGetMessage('HIGHLOADBLOCK_ALTER_SUCCESS_INFO', array(
	                    '#ROW#' => str_replace(
	                        '#TABLE_NAME#',
	                        $sqlHelper->quote($hlEntity->getDBTableName()),
	                        $alterData
	                    )
	                ));
	            }
	 
	        }
	 
	    }
	 
	        if (isset($hlData['INDEXES']) && is_array($hlData['INDEXES'])) {
	 
	        foreach ($hlData['INDEXES'] as $indexData) {
	 
	            $iResult = $connection->createIndex(
	                str_replace('#TABLE_NAME#', $hlEntity->getDBTableName(), $indexData[0]),
	                str_replace('#TABLE_NAME#', $hlEntity->getDBTableName(), $indexData[1]),
	                $indexData[2]
	            );
	 
	            if ($iResult) {
	                $info[] = $this->oGetMessage('HIGHLOADBLOCK_ADDED_INDEX_INFO', array(
	                    '#INDEX_NAME#' => str_replace('#TABLE_NAME#', $hlEntity->getDBTableName(), $indexData[1]),
	                    '#TABLE_NAME#' => $hlEntity->getDBTableName(),
	                ));
	            } else {
	                throw new \Bitrix\Main\SystemException($this->oGetMessage('HIGHLOADBLOCK_ADDED_INDEX_ERROR', array(
	                    '#INDEX_NAME#' => str_replace('#TABLE_NAME#', $hlEntity->getDBTableName(), $indexData[1]),
	                    '#TABLE_NAME#' => $hlEntity->getDBTableName(),
	                    '#ERROR#' => '',
	                )));
	            }
	 
	        }
	 
	   		}
	 
	    return $highBlockID;
	 
	}


		public function createHB(){

			$highBlock = array(
	    'seo_aniart',
	    'SEO',
	    array(
	        'FIELDS' => array(            
	            'UF_PAGE' => array('Y', 'string', array(
	                'EDIT_FORM_LABEL' => array(
	                    'ru' => 'UF_PAGE',
	                ),
	                'LIST_COLUMN_LABEL' => array(
	                    'ru' => 'UF_PAGE',
	                ),
	            )),
	            'UF_PAGE_TITLE' => array('Y', 'string', array(
	                'EDIT_FORM_LABEL' => array(
	                    'ru' => 'UF_PAGE_TITLE',
	                ),
	                'LIST_COLUMN_LABEL' => array(
	                    'ru' => 'UF_PAGE_TITLE',
	                ),
	            )),
	            'UF_SORT' => array('Y', 'string', array(
	                'EDIT_FORM_LABEL' => array(
	                    'ru' => 'UF_SORT',
	                ),
	                'LIST_COLUMN_LABEL' => array(
	                    'ru' => 'UF_SORT',
	                ),
	            )),
	            'UF_META_TITLE' => array('Y', 'string', array(
	                'EDIT_FORM_LABEL' => array(
	                    'ru' => 'UF_META_TITLE',
	                ),
	                'LIST_COLUMN_LABEL' => array(
	                    'ru' => 'UF_META_TITLE',
	                ),
	            )),
	            'UF_KEYWORDS' => array('Y', 'string', array(
	                'EDIT_FORM_LABEL' => array(
	                    'ru' => 'UF_KEYWORDS',
	                ),
	                'LIST_COLUMN_LABEL' => array(
	                    'ru' => 'UF_KEYWORDS',
	                ),
	            )),
	            'UF_DESCRIPTION' => array('Y', 'string', array(
	                'EDIT_FORM_LABEL' => array(
	                    'ru' => 'UF_DESCRIPTION',
	                ),
	                'LIST_COLUMN_LABEL' => array(
	                    'ru' => 'UF_DESCRIPTION',
	                ),
	            )),
	            
	        ),        
	    )
	);

	        global $DB;	        
	 
	        $DB->StartTransaction();

	        $idNewHighLoadBlock = null;
	         
	        try {            
	            $idNewHighLoadBlock = $this->createHighLoadBlock($highBlock[0], $highBlock[1], $highBlock[2]);
	         
	            $DB->Commit();
	         
	            echo implode("<br>\n", $info);
	         
	        } catch (\Bitrix\Main\SystemException $e) {
	         
	            $DB->Rollback();
	         
	            echo sprintf("%s<br>\n%s",
	                $e->getMessage(),
	                implode("<br>\n", $info)
	            );
	         
	        }

	        return $idNewHighLoadBlock;
	    }
	
}