<?php 

/**
 * A class used to describe a card and its data.
 */
class Card{
    public int $id;
    public string $name;
    public string $type;
    public int $hp;
    public int $attack;
    public int $defense;
    public int $price;
    public string $description;
    public string $image;
    public int $owner;

    public function __construct(int $id, string $name, string $type, int $hp, int $defense, int $attack, int $price, string $description, string $image, int $owner){
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->hp = $hp;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->owner = $owner;
    }
}



?>