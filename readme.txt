манипуляции связанные с БД
Задача 4.
а. Установка модуля aniart.seo
    добавление Хайлоада
    Bitrix\Highloadblock\HighloadBlockTable::add

    добавление пользовалтельских полей к Хайлоаду
    CUserTypeEntity::Add
б. Удаление модуля aniart.seo
    удаление Хайлоада
    HighloadBlockTable::delete (данный метод удаляет как ХАйлоад так и его пользовательские поля)
