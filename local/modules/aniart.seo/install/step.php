<?
if ($exceptionMessage = $APPLICATION->GetException()) {
    echo CAdminMessage::ShowMessage($exceptionMessage->GetString());
} else {
    echo CAdminMessage::ShowNote("Модуль Aniart SEO установлен");
}
?>

<form action="<? echo($APPLICATION->GetCurPage()); ?>">
    <input type="hidden" name="lang" value="<? echo(LANG); ?>" />
    <input type="submit" value="<?= "Вернуться к списку" ?>">
</form>
