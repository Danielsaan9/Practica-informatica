<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$network = new IPNetwork();

if ($_POST['ipnetworks_id'] && $network->can($_POST['ipnetworks_id'], READ)) {
    echo "<br>";
    echo "<a href='" . htmlescape($network->getLinkURL()) . "'>" . htmlescape($network->fields['completename']) . "</a><br>";

    $address = $network->getAddress()->getTextual();
    $netmask = $network->getNetmask()->getTextual();
    $gateway = $network->getGateway()->getTextual();

    $start   = new IPAddress();
    $end     = new IPAddress();

    $network->computeNetworkRange($start, $end);

    //TRANS: %1$s is address, %2$s is netmask
    echo htmlescape(sprintf(__('IP network: %1$s/%2$s'), $address, $netmask)) . "<br>";
    echo htmlescape(sprintf(__('First/last addresses: %1$s/%2$s'), $start->getTextual(), $end->getTextual()));
    if (!empty($gateway)) {
        echo "<br>";
        echo htmlescape(sprintf(__('Gateway: %s'), $gateway));
    }
}
