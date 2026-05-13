<?php

namespace App\Controllers;

use LinkHub\Models\Page;
use LinkHub\Models\Link;
use LinkHub\Models\Statistic;
use App\Libraries\Config;

class PublicController extends Controller
{
    public function page($slug)
    {
        $pageModel = new Page();
        $page = $pageModel->findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            echo '<h1>404 Not Found</h1>';
            exit;
        }

        if (!$page['is_published']) {
            http_response_code(404);
            echo '<h1>404 Not Found</h1>';
            exit;
        }

        $links = $pageModel->links($page['id']);

        $pageModel->incrementViewCount($page['id']);

        $statModel = new Statistic();
        $statModel->logPageView($page['id'], [
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
        ]);

        $themes = Config::get('themes');
        $themeSlug = 'minimal';
        
        if (isset($page['theme_id'])) {
            $themeId = (int)$page['theme_id'];
            $themeMappings = [
                1 => 'minimal',
                2 => 'gradient',
                3 => 'card',
                4 => 'dark',
                5 => 'vintage',
            ];
            if (isset($themeMappings[$themeId]) && isset($themes[$themeMappings[$themeId]])) {
                $themeSlug = $themeMappings[$themeId];
            }
        }

        $currentTheme = $themes[$themeSlug] ?? $themes['minimal'];

        $this->view('public/page', [
            'page' => $page,
            'links' => $links,
            'theme' => $currentTheme
        ]);
    }

    public function qr($slug)
    {
        $pageModel = new Page();
        $page = $pageModel->findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            echo '<h1>404 Not Found</h1>';
            exit;
        }

        header('Content-Type: image/png');
        echo file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode('https://' . $_SERVER['HTTP_HOST'] . '/' . $slug));
        exit;
    }
}
