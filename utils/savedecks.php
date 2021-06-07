<?php
require "../vendor/autoload.php";
session_start();
header('Content-type: application/json');
$json = file_get_contents('php://input');

$json_decode = json_decode($json, true); 
$json_encode = json_encode($json_decode);
echo $json_encode;

$userid = $_SESSION['user_id'];
$deckTitle = $json_decode[0]->deck;

if (isset($_SESSION['user_id'])) {
    $db = new Database();
    try {
        $db->insert("
        INSERT INTO `decks`(`title`) VALUES (:title)",
            [
                'title' => $deckTitle,
            ]
        );
        $deckid = $db->select("SELECT LAST_INSERT_ID()");
        $db->insert("
        INSERT INTO `userdecks`(`userid`,`deckid`) VALUES (:userid,:deckid)",
            [
            	'userid' => $userid,
                'deckid' => array_values($id[0])[0],
            ]
        );
		for ($i = 0; $i <= sizeof($json_decode); $i++)
		{
			$db->insert("
        	INSERT INTO `cards`(`term`,`definition`,`deck`) VALUES (:term,:definition,:deck)",
            [
            	'term' => $json_decode[$i]->term,
                'definition' => $json_decode[$i]->definition,
                'deck' => $deckTitle,
            ]
        );
		}   
    } catch (Exception $e) {
    	print_r($e->getMessage());
    }
}