<?php

/**
 * @since 0.84
 */

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (isset($_POST['type'], $_POST['begin'])) {
    $default_end = Reservation::getDefaultPeriodicityEnd((string) $_POST['begin'], (string) $_POST['type']);

    echo "<table style='width: 90%'>";
    switch ($_POST['type']) {
        case 'day':
            echo "<tr><td>" . __s('End date') . '</td><td>';
            Html::showDateField('periodicity[end]', ['value' => $default_end]);
            echo "</td></tr>";
            break;

        case 'week':
            echo "<tr><td>" . __s('End date') . '</td><td>';
            Html::showDateField('periodicity[end]', ['value' => $default_end]);
            echo "</td></tr></table>";
            echo "<table class='tab_glpi'>";
            echo "<tr class='center'><td>&nbsp;</td>";
            $days = [
                'Monday'    => __('Monday'),
                'Tuesday'   => __('Tuesday'),
                'Wednesday' => __('Wednesday'),
                'Thursday'  => __('Thursday'),
                'Friday'    => __('Friday'),
                'Saturday'  => __('Saturday'),
                'Sunday'    => __('Sunday'),
            ];
            foreach ($days as $day) {
                echo "<th>" . htmlescape($day) . "</th>";
            }
            echo "</tr><tr class='center'><td>" . __s('By day') . '</td>';

            foreach (array_keys($days) as $day_key) {
                echo "<td><input type='checkbox' name='periodicity[days][" . htmlescape($day_key) . "]'></td>";
            }
            echo "</tr>";
            break;

        case 'month':
            echo "<tr><td colspan='2'>";
            $values = [
                'date' => __('Each month, same date'),
                'day'  => __('Each month, same day of week'),
            ];
            Dropdown::showFromArray('periodicity[subtype]', $values);
            echo "</td></tr>";
            echo "<tr><td>" . __s('End date') . '</td><td>';
            Html::showDateField('periodicity[end]', ['value' => $default_end]);
            echo "</td></tr>";
    }
    echo '</table>';
}
