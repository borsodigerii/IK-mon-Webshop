<?php

require_once("utils/Card.php");
require_once("utils/User.php");

class API{

    private static array $users = array();
    private static ?string $usersHash = null;
    private static array $cards = array();
    private static ?string $cardsHash = null;
    
    /**
     * Returns a serialized message through the APIs
     * @param int $code The status code
     * @param string $msg The status message
     * @param mixed $data The payload to return
     * 
     * @return string|false The serialized string on success, false on failure
     */
    public static function getRes(int $code, string $msg, $data = null){
        $res = array();
        $res["code"] = $code;
        $res["message"] = $msg;
        $res["data"] = $data;
        return json_encode($res);
    }

    /**
     * Returns the balance of a user
     * @param int $userId The id of the user
     * @return int greater than 0 on success (the balance of the user), -1 on failure
     */
    public static function getBalanceByUserId(int $userId){
        $users = API::getUsers();
        if(isset($users[strval($userId)])) return $users[strval($userId)]->balance;
        return -1;
    }

    /**
     * Returns the card that has the specified id
     * @param int $cardId The id of the card searched
     * @return ?Card Returns the card if found, null otherwise
     */
    public static function getCardById(int $cardId): ?Card{
        $cards = API::getCards();
        //if(isset($cards[strval($cardId)])) return $cards[strval($cardId)];
        foreach($cards as $card){
            if($card->id == $cardId) return $card;
        }
        return null;
    }

    /**
     * Returns the user that has the specified id
     * @param int $userId The id of the user searched
     * @return ?User Returns the user if found, null otherwise
     */
    public static function getUserById(int $userId): ?User{
        $users = API::getUsers();
        if(isset($users[strval($userId)])) return $users[strval($userId)];
        return null;
    }

    /**
     * Returns the card that has the specified name
     * @param string $cardName The name of the card searched
     * @return ?Card Returns the card if found, null otherwise
     */
    public static function getCardByName(string $cardName): ?Card{
        $cards = API::getCards();
        foreach($cards as $card){
            if($card->name == $cardName) return $card;
        }
        return null;
    }

    /**
     * Get all the users found in the system. The method checks if the cached user's array is outdated compared to the actual one; if it is, it gets updated and then returned, otherwise just simply returned.
     * @return array The Users array
     */
    private static function getUsers(): array {
        if(API::$usersHash == null || API::$usersHash != hash_file("md5", __DIR__ . "/../data/users.json")){
            API::$users = array();
            $rawUsers = json_decode(file_get_contents(__DIR__ . "/../data/users.json"), true);
            foreach ($rawUsers as $user) {
                $userTemp = new User($user["id"], $user["name"], $user["email"], $user["pass"], $user["balance"], $user["isAdmin"]);
                array_push(API::$users, $userTemp);
            }
            API::$usersHash = hash_file("md5", __DIR__ . "/../data/users.json");
            
            return API::$users;
        }else{
            return API::$users;
        }
    }

    /**
     * Get all the cards found in the system. The method checks if the cached card's array is outdated compared to the actual one; if it is, it gets updated and then returned, otherwise just simply returned.
     * @return array The Cards array
     */
    public static function getCards(): array {
        if(API::$cardsHash == null || API::$cardsHash != hash_file("md5",  __DIR__ . "/../data/cards.json")){
            API::$cards = array();
            $rawCards = json_decode(file_get_contents( __DIR__ . "/../data/cards.json"), true);
            foreach ($rawCards as $card) {
                $cardTemp = new Card($card["id"], $card["name"], $card["type"], $card["hp"], $card["defense"], $card["attack"], $card["price"], $card["description"], $card["image"], $card["owner"]);
                array_push(API::$cards, $cardTemp);
            }
            API::$cardsHash = hash_file("md5",  __DIR__ . "/../data/users.json");
            
            return API::$cards;
        }else{
            return API::$cards;
        }
    }

    /**
     * Updates the balance for the specified user with the given amount
     * @param int $userId The user
     * @param int $newBalance The new balance of the user
     */
    public static function setBalanceForUser(int $userId, float $newBalance){
        $users = API::getUsers();
        $users[strval($userId)]->balance = $newBalance;
        API::saveUsers($users);
    }

    /**
     * Saves the user's datafile with the given data
     * @param array $users The new users data
     */
    private static function saveUsers(array $users){
        API::$users = $users;
        file_put_contents($_SERVER["DOCUMENT_ROOT"]."/data/users.json", json_encode($users));
        API::$usersHash = hash_file("md5", $_SERVER["DOCUMENT_ROOT"]."/data/users.json");
    }
    /**
     * Saves the card's datafile with the given data
     * @param array $cards The new cards data
     */
    private static function saveCards(array $cards){
        API::$cards = $cards;
        file_put_contents($_SERVER["DOCUMENT_ROOT"]."/data/cards.json", json_encode($cards));
        API::$cardsHash = hash_file("md5", $_SERVER["DOCUMENT_ROOT"]."/data/cards.json");
    }


    /**
     * Updates the owner of the specified card to the given user
     * @param int $userId The user to set the owner to
     * @param int $cardId The card to set the owner of
     */
    public static function setOwnerForCard(int $userId, int $cardId){
        $cards = API::getCards();
        $cards[strval($cardId)]->owner = $userId;
        API::saveCards($cards);
    }

    /**
     * Removes a card from the system
     * @param int $cardId The id of the card
     */
    public static function removeCard(int $cardId){
        $cards = API::getCards();
        unset($cards[strval($cardId)]);
        API::saveCards($cards);
    }

    /**
     * Returns the total number of cards in the system.
     * @return int The number of cards
     */
    public static function getCardCount(){
        return count(API::getCards());
    }

    /**
     * Returns the total number of users in the system.
     * @return int The number of users
     */
    public static function getUserCount(){
        return count(API::getUsers());
    }

    /**
     * Adds the specified card to the system
     * @param Card The card to add
     */
    public static function addCard(Card $card){
        $cards = API::getCards();
        array_push($cards, $card);
        API::saveCards($cards);
    }

    /**
     * Adds the specified User to the system
     * @param User The user to add
     */
    public static function addUser(User $user){
        $users = API::getUsers();
        array_push($users, $user);
        API::saveUsers($users);
    }

    /**
     * Edits a card based on Id's: it updates the card properties with the given card object's properties
     * @param int $cardId The card to edit
     * @param Card $newCard The card to update the selected with
     */
    public static function editCard(int $cardId, Card $newCard){
        $cards = API::getCards();

        foreach($cards as $card){
            if($card->id == $cardId){
                // megvan a kartya
                $card->name = $newCard->name;
                $card->type = $newCard->type;
                $card->hp = $newCard->hp;
                $card->attack = $newCard->attack;
                $card->defense = $newCard->defense;
                $card->price = $newCard->price;
                $card->description = $newCard->description;
                $card->image = $newCard->image;
                $card->owner = $newCard->owner;
            }
        }
        API::saveCards($cards);
    }

    /**
     * Checks if a user does exists with the given credentials, and returns it if yes
     * @param string $name The name of the user
     * @param string $password The password of the user
     * @return ?User The user itself if found, null otherwise
     */
    public static function checkUser(string $name, string $password = null): ?User{
        if(((empty($name) || empty($password)) && $password != null) || ($password == null && empty($name))) return null;

        $users = API::getUsers();
        foreach($users as $user){
            if(($user->name == $name && $password == null) || ($user->name == $name && $password != null && $user->pass == $password)) return $user;
        }
        return null;
    }


    /**
     * It retrieves how many cards does the user posess.
     * @param int $userId The id of the user
     * @return int The number of posessed cards
     */
    public static function getCardCountForUser(int $userId): int{
        $cards = API::getCards();
        $count = 0;
        foreach($cards as $card){
            if($card->owner == $userId) $count++;
        }
        return $count;
    }

    /**
     * Returns the URL for the card's image (since it can be either URL [deprecated] or just an uploaded filename)
     * @return string The file's relative path to the root
     */
    public static function getCardImageUrl(int $cardId): string{
        $card = API::getCardById($cardId);
        if($card){
            if(filter_var($card->image, FILTER_VALIDATE_URL)){
                // url a kep megadva
                return $card->image;
            } else{
                return "/images/uploads/" . $card->image;
            }
        }
        return "";
    }

    /**
     * Returns the next card id (which is the current count of cards)
     * @return int The next id for the nextly created card
     */
    public static function getNextCardId(): int{
        return API::getCardCount();
    }

    /**
     * Returns the next user id (which is the current count of users)
     * @return int The next id for the nextly created user
     */
    public static function getNextUserId(): int{
        return API::getUserCount();
    }

    /**
     * Generates a nonce for communications
     * @return string The nonce
     */
    public static function generateNonce(): string{
        $length = 20;
        //set up random characters
        $chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        //get the length of the random characters
        $char_len = strlen($chars)-1;
        //store output
        $output = '';
        //iterate over $chars
        while (strlen($output) < $length) {
            /* get random characters and append to output till the length of the output 
             is greater than the length provided */
            $output .= $chars[ rand(0, $char_len) ];
        }
        //return the result
        return $output;
    }
}


?>