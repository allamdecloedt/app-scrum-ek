<?php
header("Content-Type: application/xml; charset=utf-8");

$urls = [
    ['loc' => 'https://www.wayo.academy/', 'lastmod' => '2024-11-26', 'changefreq' => 'daily', 'priority' => '1.0'],
    ['loc' => 'https://www.wayo.academy/about', 'lastmod' => '2024-11-26', 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => 'https://www.wayo.academy/courses', 'lastmod' => '2024-11-26', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => 'https://www.wayo.academy/contact', 'lastmod' => '2024-11-26', 'changefreq' => 'weekly', 'priority' => '0.8']
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
foreach ($urls as $url) {
    echo '<url>';
    echo '<loc>' . $url['loc'] . '</loc>';
    echo '<lastmod>' . $url['lastmod'] . '</lastmod>';
    echo '<changefreq>' . $url['changefreq'] . '</changefreq>';
    echo '<priority>' . $url['priority'] . '</priority>';
    echo '</url>';
}
echo '</urlset>';
?>
