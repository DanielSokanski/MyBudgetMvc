<?php


namespace App\Models;

use Core\View;
use PDO;


class Incomes extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
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
}