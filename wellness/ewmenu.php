<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_activities", $Language->MenuPhrase("1", "MenuText"), "activitieslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mi_events", $Language->MenuPhrase("6", "MenuText"), "eventslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mi_services", $Language->MenuPhrase("13", "MenuText"), "serviceslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mi_settings", $Language->MenuPhrase("14", "MenuText"), "settingslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(15, "mi_tips", $Language->MenuPhrase("15", "MenuText"), "tipslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
