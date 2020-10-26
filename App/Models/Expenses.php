<?php


namespace App\Models;

use Core\View;
use PDO;


class Expenses extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
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

}
