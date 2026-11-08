<?php

use Glpi\Exception\Http\AccessDeniedHttpException;

require_once(__DIR__ . '/_check_webserver_config.php');

global $DB;

Session::checkRight(Link::$rightname, READ);

if (isset($_GET["lID"])) {
    $iterator = $DB->request([
        'SELECT' => ['id', 'link', 'data'],
        'FROM'   => 'glpi_links',
        'WHERE'  => [
            'id' => $_GET['lID'],
        ],
    ]);

    if (count($iterator) == 1) {
        $current = $iterator->current();
        $file = $current['data'];
        $link = $current['link'];

        if ($item = getItemForItemtype($_GET["itemtype"])) {
            if (!$item->can($_GET['id'], READ)) {
                throw new AccessDeniedHttpException();
            }
            if ($item->getFromDB($_GET["id"])) {
                $content_filename = Link::generateLinkContents($link, $item, false);
                $content_data     = Link::generateLinkContents($file, $item, false);

                if (isset($_GET['rank']) && isset($content_filename[$_GET['rank']])) {
                    $filename = $content_filename[$_GET['rank']];
                } else {
                    // first one (the same for all IP)
                    $filename = reset($content_filename);
                }

                if (isset($_GET['rank']) && isset($content_data[$_GET['rank']])) {
                    $data = $content_data[$_GET['rank']];
                } else {
                    // first one (probably missing arg)
                    $data = reset($content_data);
                }
                header("Content-disposition: filename=\"" . rawurlencode($filename) . "\"");
                $mime = "application/scriptfile";

                header("Content-type: " . $mime);
                header('Expires: 0');

                // May have several values due to network datas : use only first one
                echo $data;
            }
        }
    }
}
