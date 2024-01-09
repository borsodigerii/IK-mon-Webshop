<?php 

/**
 * A class used to describe a user and their data.
 */
class User{
    public int $id;
    public string $name;
    public string $email;
    public string $pass;
    public float $balance;
    public bool $isAdmin;

    public function __construct(int $id, string $name, string $email, string $pass, float $balance, bool $isAdmin){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->pass = $pass;
        $this->balance = $balance;
        $this->isAdmin = $isAdmin;
    }
}



?>