<?php

namespace App\Models\user;

use App\Services\encryption\EncryptionService;
use App\Services\validator\InputValidator;
use Exception;

class UserDTO
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $firstName;
    private string $lastName;
    private string $role;

    /**
     * @throws Exception
     */
    public static function create($id, $username, $email, $password, $firstName, $lastName, $role): UserDTO
    {
        return (new UserDTO())
            ->setId($id)
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($password)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setRole($role);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }



    public function setId(int $id): UserDTO
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setUsername($username): UserDTO
    {

        if (!isset($username)){
            throw new Exception('Username can not be empty!');
        }

        $username = InputValidator::validateStringInput($username);

        if (strlen($username) < 3 || strlen($username) > 45){
            throw new Exception('Username must be between 3 - 45 characters!');
        }

        if (!preg_match("/^[\w]+$/",$username)){
            throw new Exception('Invalid chars in username! Allowed (a-zA-Z0-9_)');
        }

        $this->username = $username;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setEmail(string $email): UserDTO
    {

        if (!isset($email)){
            throw new Exception('Email can not be empty!');
        }

        $email = InputValidator::validateStringInput($email);

        if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
            throw new Exception('Invalid Email!');
        }

        if (strlen($email) < 5 || strlen($email) > 245){
            throw new Exception('Invalid Email!');
        }

        $this->email = $email;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setPassword(string $password): UserDTO
    {

        if (!isset($password)){
            throw new Exception('Password can not be empty!');
        }

        $password = InputValidator::validateStringInput($password);

        if (strlen($password) < 8 || strlen($password) > 245){
            throw new Exception('Password must be between 8 and 45 characters!');
        }

        $password = EncryptionService::hash($password);

        $this->password = $password;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setFirstName(string $firstName): UserDTO
    {

        if (!isset($firstName)){
            $firstName = 'Firstname';
        }

        $firstName = InputValidator::validateStringInput($firstName);

        if (strlen($firstName) < 2 || strlen($firstName) > 75){
            throw new Exception('Name must be between 2 and 75 characters!');
        }

        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setLastName(string $lastName): UserDTO
    {
        if (!isset($lastName)){
            $lastName = 'Lastname';
        }

        $lastName = InputValidator::validateStringInput($lastName);

        if (strlen($lastName) < 2 || strlen($lastName) > 75){
            throw new Exception('Name must be between 2 and 75 characters!');
        }

        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setRole(int $role): UserDTO
    {
        if (!isset($role)){
            $role = 3;
        }

        if (!is_numeric($role)){
            throw new Exception('Invalid User Role!');
        }

        $this->role = $role;
        return $this;
    }

}