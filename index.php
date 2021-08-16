<?php
include 'config/select.php';
global $connection;
$json = file_get_contents('https://store-site-backend-static-ipv4.ak.epicgames.com/freeGamesPromotions?locale=ru&country=RU&allowCountries=RU');
$data = json_decode($json);


for ($i = 0; $i <= 20; $i++) {
    $title = $data->data->Catalog->searchStore->elements[$i]->title;
    if ($title !== '' && $title !== NULL && strlen($title) > 1) {
        $date = $data->data->Catalog->searchStore->elements[$i]->promotions->promotionalOffers[0]->promotionalOffers[0]->endDate;
        $slug = 'https://www.epicgames.com/store/ru/p/'.$data->data->Catalog->searchStore->elements[$i]->catalogNs->mappings[0]->pageSlug;
        if ($date !== '' && $date !== NULL && $date > 1) {
            $endDate = date('d.m.Y', strtotime(mb_substr($date, 0, 19)));
            $endTime = date('H:i:s', strtotime(mb_substr($date, 0, 19)));
            $game = new DB('имя бд', 'имя пользоватеоя', 'пароль');
            $result = $game->query($title);
            foreach ($result as $value) {
                if ($value['count'] > 0) {
                } else {
                    $game->insert($title,$endDate);
                    $text = $title.' до '. $endDate. ' скачай бесплатно на '.$slug;
                    message_to_telegram($text);
                }
            }
        }
    }
}





