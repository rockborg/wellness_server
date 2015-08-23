<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_app", $Language->MenuPhrase("1", "MenuText"), "applist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, "mmi_blog", $Language->MenuPhrase("2", "MenuText"), "bloglist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mmi_category", $Language->MenuPhrase("3", "MenuText"), "categorylist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_event", $Language->MenuPhrase("4", "MenuText"), "eventlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mmi_list_platform", $Language->MenuPhrase("5", "MenuText"), "list_platformlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mmi_user", $Language->MenuPhrase("6", "MenuText"), "userlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_website", $Language->MenuPhrase("7", "MenuText"), "websitelist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
