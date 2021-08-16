<?php

class DB
{
    // Объект класса PDO
    private $db;

    // Соединение с БД
    public function __construct($name_db, $db_user, $db_pass)
    {
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=' . $name_db, $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => true));
            $this->db->exec("SET CHARACTER SET utf8");
        } catch (PDOException $er) {
            print "Ошибка: " . $er->getMessage() . "<br/>";
            die();
        }
    }

    public function query($name)
    {
        $query = 'SELECT COUNT(game.id) as count FROM game WHERE game.name = :name';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($title, $endDate)
    {
        $query = 'INSERT INTO `game`(`id`, `name`, `dataend`, `send`) VALUES (:id,:name,:dataend,:send)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', '');
        $stmt->bindValue(':name', $title);
        $stmt->bindValue(':dataend', $endDate);
        $stmt->bindValue(':send', '1');
        $stmt->execute();
    }


}


define('TELEGRAM_TOKEN', 'токен');
define('TELEGRAM_CHATID', 'id чата');


function message_to_telegram($text)
{
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL => 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => array(
                'chat_id' => TELEGRAM_CHATID,
                'text' => $text,
            ),
        )
    );
    curl_exec($ch);
}