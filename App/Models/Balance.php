<?php


namespace App\Models;

use Core\View;
use PDO;
use DateTime;

class Balance extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
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