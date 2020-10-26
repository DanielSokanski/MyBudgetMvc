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


	public function addRecordExpense()
    {
        $user_id =  $_SESSION['user_id'];
        
		$dlugosckwoty=strlen($this->kwota);

		for($i=0;$i<$dlugosckwoty;$i++)
		{
			if($this->kwota[$i]==',')
			{
				$this->kwota[$i]='.';
			}
		}
            
            $payment_id = $this->selectPaymentId();
            $expense_category_number = $this->selectExpenseCategory();

            $sql = "INSERT INTO expenses  VALUES (NULL,:id_user, :expense_category, :payment ,:amount, :data, :komentarz)";

            $db = static::getDB();
            $stmt5 = $db->prepare($sql);
            $stmt5->bindValue(':id_user', $user_id, PDO::PARAM_INT);
            $stmt5->bindValue(':expense_category', $expense_category_number, PDO::PARAM_INT);
            $stmt5->bindValue(':payment', $payment_id, PDO::PARAM_INT);
            $stmt5->bindValue(':amount', $this->kwota, PDO::PARAM_STR);
            $stmt5->bindValue(':data', $this->data, PDO::PARAM_STR);
            $stmt5->bindValue(':komentarz', $this->komentarz, PDO::PARAM_STR);
            
       
            return $stmt5->execute();
        }


        public function selectExpenseCategory()
        {
        $user_id = $_SESSION['user_id'];

        $db = static::getDB();

        $sql_select_category = "SELECT id FROM expences_category_assigned_to_users WHERE user_id = :user_id AND name = :expense_category";
        $query_select_category = $db->prepare($sql_select_category);
        $query_select_category->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query_select_category->bindValue(':expense_category', $this->kategoria, PDO::PARAM_STR);
        $query_select_category->execute();

        $category_result = $query_select_category->fetch();

        return $category_result['id'];
        }

        public function selectPaymentId()
        {
        $user_id = $_SESSION['user_id'];

        $db = static::getDB();

        $sql_select_payment = "SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :payment";
        $query_select_payment = $db->prepare($sql_select_payment);
        $query_select_payment->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query_select_payment->bindValue(':payment', $this->zaplata, PDO::PARAM_STR);
        $query_select_payment->execute();

        $payment_result = $query_select_payment->fetch();

        return $payment_result['id'];
        }




        public function addRecordIncome()
    {
        $user_id =  $_SESSION['user_id'];
        
		$dlugosckwoty=strlen($this->kwota);

		for($i=0;$i<$dlugosckwoty;$i++)
		{
			if($this->kwota[$i]==',')
			{
				$this->kwota[$i]='.';
			}
		}

            $income_category_number = $this->selectIncomeCategory();

            $sql = "INSERT INTO incomes  VALUES (NULL,:id_user, :income_category,:amount, :data, :komentarz)";

            $db = static::getDB();
            $stmt5 = $db->prepare($sql);
            $stmt5->bindValue(':id_user', $user_id, PDO::PARAM_INT);
            $stmt5->bindValue(':income_category', $income_category_number, PDO::PARAM_INT);
            $stmt5->bindValue(':amount', $this->kwota, PDO::PARAM_STR);
            $stmt5->bindValue(':data', $this->data, PDO::PARAM_STR);
            $stmt5->bindValue(':komentarz', $this->komentarz, PDO::PARAM_STR);
            
       
            return $stmt5->execute();
        }

        public function selectIncomeCategory()
        {
        $user_id = $_SESSION['user_id'];

        $db = static::getDB();

        $sql_select_category = "SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :income_category";
        $query_select_category = $db->prepare($sql_select_category);
        $query_select_category->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query_select_category->bindValue(':income_category', $this->kategoria, PDO::PARAM_STR);
        $query_select_category->execute();

        $category_result = $query_select_category->fetch();

        return $category_result['id'];
        }

        public function showSpecyficBilans()
        {
            $user_id =  $_SESSION['user_id'];
            if(isset($_POST['okres']))
            {
                $okres = $_POST['okres'];
                $data=date("Y-m");
			    $year  = date('Y'); 
			    $time = strtotime("now");
                $previousmonth = date('Y-m', strtotime(date('Y-m')." -1 month"));
                
                if (($okres=="Bieżący miesiąc")||($okres=="Poprzedni miesiąc")||($okres=="Bieżący rok"))
                {
                    if($okres=="Bieżący miesiąc") 
						{
							$quotationdate = $data;
						}
					else if  ($okres=="Poprzedni miesiąc")	
						{
							$quotationdate = $previousmonth;
						}
					else if  ($okres=="Bieżący rok")	
						{
							$quotationdate = $year;
                        }
                    
                    $db = static::getDB();
                    $sql_bilans_results = "SELECT expences_category_assigned_to_users.name, expenses.amount FROM expenses, expences_category_assigned_to_users WHERE expenses.date_of_expense 
                    LIKE :quotationdate AND expenses.user_id=:user_id AND expenses.expense_category_assigned_to_users=expences_category_assigned_to_users.id GROUP BY expenses.amount ORDER BY expenses.amount DESC";
                    $query_select_expences = $db->prepare($sql_bilans_results);
                    $query_select_expences->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    $query_select_expences->bindValue(':quotationdate', $quotationdate, PDO::PARAM_STR);
                    $query_select_expences->execute();
                    $showCosts = $query_select_expences->fetch();    
                }
            }
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
	
	  public static function findIdByEmail($email)
    {
        $sql = 'SELECT id FROM users WHERE email = :email';

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
	public static function showIncomes($firstDay, $lastDay)
    {
        $user_id =  $_SESSION['user_id'];
        $firstDate = $firstDay;
        $lastDate = $lastDay;

        $db = static::getDB();

        $sql_balance_incomes = "SELECT category_incomes.name as Category, 
                                SUM(incomes.amount) as Amount FROM incomes INNER JOIN 
                                incomes_category_assigned_to_users as category_incomes WHERE 
                                incomes.income_category_assigned_to_user_id = category_incomes.id AND 
                                incomes.user_id= :user_id AND incomes.date_of_income BETWEEN :first_date AND :second_date GROUP BY Category ORDER BY Amount DESC";
        $query_select_incomes_sum = $db->prepare($sql_balance_incomes);
        $query_select_incomes_sum->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query_select_incomes_sum->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $query_select_incomes_sum->bindValue(':second_date', $lastDate, PDO::PARAM_STR);
        $query_select_incomes_sum->execute();

        return $query_select_incomes_sum->fetchAll();

    }

    public static function showExpenses($firstDay, $lastDay)
    {
        $user_id =  $_SESSION['user_id'];
        $firstDate = $firstDay;
        $lastDate = $lastDay;

        $db = static::getDB();

        $sql_balance_expenses = "SELECT category_expenses.name as Category, 
                                 SUM(expenses.amount) as Amount FROM expenses INNER JOIN 
                                 expences_category_assigned_to_users as category_expenses WHERE 
                                 expenses.expense_category_assigned_to_users = category_expenses.id AND
                                 expenses.user_id= :id_user AND expenses.date_of_expense BETWEEN :first_date AND :second_date GROUP BY Category ORDER BY Amount DESC";
        $query_select_expenses_sum = $db->prepare($sql_balance_expenses);
        $query_select_expenses_sum->bindValue(':id_user', $user_id, PDO::PARAM_INT);
        $query_select_expenses_sum->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $query_select_expenses_sum->bindValue(':second_date', $lastDate, PDO::PARAM_STR);
        $query_select_expenses_sum->execute();

        return $query_select_expenses_sum->fetchAll();
    }
}
