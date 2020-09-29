<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (username,email,password_hash)
                    VALUES (:name, :email, :password_hash)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
           $stmt->execute();
		   
		   $sql1='INSERT INTO incomes_category_assigned_to_users (name) SELECT name FROM incomes_category_default';
            $db = static::getDB();
            $stmt1= $db->prepare($sql1);
			 $stmt1->execute();
			 
			 $sql2 = 'INSERT INTO expences_category_assigned_to_users (name) SELECT  name FROM expenses_category_default';
            $db = static::getDB();
            $stmt2 = $db->prepare($sql2);
			 $stmt2->execute();
			 
			 $sql3 = 'INSERT INTO payment_methods_assigned_to_users (name) SELECT name FROM payment_methods_default';
            $db = static::getDB();
            $stmt3 = $db->prepare($sql3);
			 $stmt3->execute();

			 $sql11='UPDATE  incomes_category_assigned_to_users  SET incomes_category_assigned_to_users.user_id=(SELECT users.id FROM users ORDER BY users.id DESC LIMIT 1) WHERE incomes_category_assigned_to_users.user_id=0';
			 $db = static::getDB();
			 $stmt11= $db->prepare($sql11);
			 $stmt11->execute();
			 
			  $sql22='UPDATE  expences_category_assigned_to_users  SET expences_category_assigned_to_users.user_id=(SELECT users.id FROM users ORDER BY users.id DESC LIMIT 1) WHERE expences_category_assigned_to_users.user_id=0';
			 $db = static::getDB();
            $stmt22= $db->prepare($sql22);
			 $stmt22->execute();
			 
			 $sql33='UPDATE  payment_methods_assigned_to_users  SET payment_methods_assigned_to_users.user_id=(SELECT users.id FROM users ORDER BY users.id DESC LIMIT 1) WHERE payment_methods_assigned_to_users.user_id=0';
			 $db = static::getDB();
            $stmt33= $db->prepare($sql33);
			 $stmt33->execute();

			 return true;
        }
        return false;
    }


	public function addExpence()
    {
		$id = $POST['id'];
		$dlugosckwoty=strlen($POST['kwota']);

		for($i=0;$i<$dlugosckwoty;$i++)
		{
			if($POST['kwota'][$i]==',')
			{
				$POST['kwota'][$i]='.';
			}
		}
		
            $sql = "SELECT * FROM expenses_category_assigned_to_users".$id." WHERE name=".$this->kategoria."";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            
       
            return $stmt->execute();
        }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }


    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     * @param string $ignore_id Return false anyway if the record found has this ID
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }


    public static function authenticate($email1, $haslo2)
    {
        $user = static::findByEmail($email1);

        //if ($user) {
        if ($user) {
            if (password_verify($haslo2, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }





    /**
     * Update the user's profile
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateProfile($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];

        // Only validate and update the password if a value provided
        if ($data['password'] != '') {
            $this->password = $data['password'];
        }

        $this->validate();

        if (empty($this->errors)) {

            $sql = 'UPDATE users
                    SET name = :name,
                        email = :email';

            // Add password if it's set
            if (isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= "\nWHERE id = :id";


            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            // Add password if it's set
            if (isset($this->password)) {

                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            }

            return $stmt->execute();
        }

        return false;
    }
	
}
