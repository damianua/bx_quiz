<?php
/**
 * Регистрация обработчиков системный событий
 */
use Bitrix\Main\EventManager;
$eventManager = EventManager::getInstance();

<<<<<<< HEAD
$eventManager->addEventHandler('main', 'OnEpilog', ['\Aniart\Main\Observers\BitrixObserver', 'onEpilog']);
=======
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
$eventManager->addEventHandler('main', 'OnProlog', ['\Aniart\Main\Observers\BitrixObserver', 'onProlog']);