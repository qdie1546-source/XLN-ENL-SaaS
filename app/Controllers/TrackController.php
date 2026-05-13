<?php

namespace App\Controllers;

use LinkHub\Models\Link;
use LinkHub\Models\Statistic;

class TrackController extends Controller
{
    public function link($id)
    {
        $linkModel = new Link();
        $link = $linkModel->find($id);

        if (!$link) {
            http_response_code(404);
            echo '<h1>404 Not Found</h1>';
            exit;
        }

        $linkModel->incrementClickCount($id);

        $statModel = new Statistic();
        $statModel->logPageView($link['page_id'], [
            'link_id' => $id,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
        ]);

        $this->redirect($link['url']);
    }
}
