<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(User::$rightname, User::UPDATEAUTHENT);

if ($_POST["authtype"] > 0) {
    $name = $_POST['name'] ?? 'massiveaction';

    switch ($_POST["authtype"]) {
        case Auth::DB_GLPI:
            echo "<input type='hidden' name='auths_id' value='0'>";
            break;

        case Auth::LDAP:
        case Auth::EXTERNAL:
            AuthLDAP::dropdown([
                'name'      => "auths_id",
                'condition' => ['is_active' => 1],
            ]);
            break;

        case Auth::MAIL:
            AuthMail::dropdown([
                'name'      => "auths_id",
                'condition' => ['is_active' => 1],
            ]);
            break;
    }

    echo "&nbsp;<input type='submit' name='" . htmlescape($name) . "' class='btn btn-primary' value=\"" . _sx('button', 'Post') . "\">";
}
