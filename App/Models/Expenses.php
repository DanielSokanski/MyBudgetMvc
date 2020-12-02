<?php


namespace App\Models;

use Core\View;
use PDO;
use DateTime;

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

        
        public static function showExpenseList()
        {
        $user_id = $_SESSION['user_id'];
        $db = static::getDB();
        $sql_updateInclist = 'SELECT name as ExpenseName, limit_wydatkow as budzet FROM expences_category_assigned_to_users WHERE user_id=:user_id';

        $sql_updateInclist_result = $db->prepare($sql_updateInclist);
        $sql_updateInclist_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_updateInclist_result->execute();
        return $sql_updateInclist_result->fetchAll();
        }
        public static function showPaymentMethods()
        {
        $user_id = $_SESSION['user_id'];
        $db = static::getDB();
        $sql_updatePaylist = 'SELECT name as PaymentName FROM payment_methods_assigned_to_users WHERE user_id=:user_id';

        $sql_updatePaylist_result = $db->prepare($sql_updatePaylist);
        $sql_updatePaylist_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_updatePaylist_result->execute();
        return $sql_updatePaylist_result->fetchAll();
        }
        
        public static function showCalculation($kategoria, $kwota)
        {
        $user_id = $_SESSION['user_id'];
        $db = static::getDB();  
        $sql_calculation = 'SELECT limit_wydatkow, name, id FROM expences_category_assigned_to_users WHERE user_id=:user_id AND name=:kategoria';
        $sql_calculation_result = $db->prepare($sql_calculation);
        $sql_calculation_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_calculation_result->bindValue(':kategoria', $kategoria, PDO::PARAM_STR);
        $sql_calculation_result->execute();
        $row = $sql_calculation_result->fetch(PDO::FETCH_ASSOC);
        if($row>0)
            {
             $current_date = new DateTime();
             $month = $current_date->format('m');
             $year = $current_date->format('Y');
             $idOfCategory =  $row['id'];
             if ($row['limit_wydatkow']>0)
             {
            $firstDay = $year.'-'.$month.'-'.'01';
            $lastDay = $year.'-'.$month.'-'.'31'; 
            $sql_cat_calculation = 'SELECT sum(amount) as catExpenses, expense_category_assigned_to_users FROM expenses WHERE
            expenses.expense_category_assigned_to_users = :idOfCategory AND user_id=:user_id AND date_of_expense BETWEEN :firstDay AND :lastDay'; 
            $sql_cat_calculation_result = $db->prepare($sql_cat_calculation);
            $sql_cat_calculation_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $sql_cat_calculation_result->bindValue(':firstDay', $firstDay, PDO::PARAM_STR);
            $sql_cat_calculation_result->bindValue(':lastDay', $lastDay, PDO::PARAM_STR);    
            $sql_cat_calculation_result->bindValue(':idOfCategory', $idOfCategory, PDO::PARAM_INT);  
            $sql_cat_calculation_result->execute();    
            $newRow = $sql_cat_calculation_result->fetch(PDO::FETCH_ASSOC);
            $alreadySpend = $newRow['catExpenses'];
            $difference = $row['limit_wydatkow'] - $alreadySpend;
            if ($difference>0)
            {
            echo "
            <div class='col-sm-12 col-md-12 mt-2 text-center text-dark font-weight-bold bg-light' style='width:800px; margin:0 auto;'>
            <b>Informacje o limicie.</b>Moszesz jeszcze wydać <b>$difference zł</b> w kategorii <b>$kategoria</b></div>
             ";
             echo"
             <div style='width:800px; margin:0 auto;'>
             <table class='bg-success'><thead><tr>
                 <th class='border border-dark' style='width: 25%'>Ustalony limit</th>
                 <th class='border border-dark' style='width: 25%'>Dotychczas wydano</th>
                 <th class='border border-dark' style='width: 25%'>Różnica</th>
                 <th class='border border-dark' style='width: 30%'>Wydatki + wpisana kwota</th>
             </tr></thead><tbody><tr>
                 <td class='border border-dark' style='width: 25%'>$row[limit_wydatkow]</td>
                 <td class='border border-dark' style='width: 25%'>$alreadySpend</td>
                 <td class='border border-dark' style='width: 25%'>$difference</td>
                 <td class='border border-dark' style='width: 30%'>$kwota</td>
             </tr></tbody></table></div>"; 
            }
            else
            {
                echo "
            <div class='col-sm-12 col-md-12 mt-2 text-center text-dark font-weight-bold bg-danger' style='width:800px; margin:0 auto;'>
            <b>Informacje o limicie.</b>Już wyłaciłeś więcej o <b>$difference zł</b> w kategorii <b>$kategoria</b> niż zakładał ustalony limit</div>
             ";
             echo"
             <div style='width:800px; margin:0 auto;'>
             <table class='bg-danger'><thead><tr>
                 <th class='border border-light' style='width: 25%'>Ustalony limit</th>
                 <th class='border border-light' style='width: 25%'>Dotychczas wydano</th>
                 <th class='border border-light' style='width: 25%'>Różnica</th>
                 <th class='border border-light' style='width: 30%'>Wydatki + wpisana kwota</th>
             </tr></thead><tbody><tr>
                 <td class='border border-light' style='width: 25%'>$row[limit_wydatkow]</td>
                 <td class='border border-light' style='width: 25%'>$alreadySpend</td>
                 <td class='border border-light' style='width: 25%'>$difference</td>
                 <td class='border border-light' style='width: 30%'>$kwota</td>
             </tr></tbody></table></div>"; 
            }
             }
         }
        }
}
