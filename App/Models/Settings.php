<?php

namespace App\Models;

use PDO;
use \Core\View;
use DateTime;

class Settings extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    public static function showIncomeCategories()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $sql_incomes_cat = 'SELECT name as incomesName FROM incomes_category_assigned_to_users WHERE user_id=:user_id';
        $sql_incomes_ca_result = $db->prepare($sql_incomes_cat);
        $sql_incomes_ca_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_incomes_ca_result->execute();
        echo        "<form id='incomes' method='post'>
					<p><input type='submit' class='btn btn-susscess btn-lg btn-block active' name='displaybtn' id='incomebtn' aria-pressed='true' value='Kategorie przychodów'></p>	
					</form>";	
        while ($row=$sql_incomes_ca_result->fetch(PDO::FETCH_ASSOC)){
        echo "
        <div id='$row[incomesName]'>
        <div class='col-sm-10 col-md-9 mx-0 text-dark text-left bg-light d-inline-block'>$row[incomesName]</div>
        <div class='col-sm-1 col-md-1 mx-0 text-danger text-center d-inline-block bg-success'><button class='open-AddBookDialog btn btn-block active text-center text-light' id='IncomeEdit' data-id='$row[incomesName]' data-toggle='modal' data-target='#editIncome'>Edytuj</button></div>
        <div class='col-sm-1 col-md-1 mx-0 text-danger text-center d-inline-block bg-danger'><button class='open-erase btn btn-block active text-center text-light' id='IncomeErase' data-id='$row[incomesName]' data-toggle='modal' data-target='#eraseIncome'>Usuń</button></div>
        </div>";
       
        }
        echo "<div class='col-sm-6 col-md-6 text-light text-center mt-3 font-weight-bold'>
        <button class='btn btn-success btn-block active' data-toggle='modal' data-target='#addIncomeCat'>Dodaj kategorię</button>
        </div>";
    }	

    public static function showExpenseCategories()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $sql_expenses_cat = 'SELECT name as expenseName, limit_wydatkow as LW FROM expences_category_assigned_to_users WHERE user_id=:user_id';
        $sql_expenses_ca_result = $db->prepare($sql_expenses_cat);
        $sql_expenses_ca_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_expenses_ca_result->execute();
        echo        "<form id='expenses' method='post'>
					<p><input type='submit' class='btn btn-susscess btn-lg btn-block active' name='displaybtn' id='expensebtn' aria-pressed='true' value='Kategorie wydatków'></p>	
					</form>";	
        while ($row=$sql_expenses_ca_result->fetch(PDO::FETCH_ASSOC)){
        echo "
        <div id='$row[expenseName]'>
        <div class='col-sm-10 col-md-9 mx-0 text-dark text-left bg-light d-inline-block'>$row[expenseName]" ;

        if ($row['LW']!=0)
            {
            echo  "<p>Limit: $row[LW]</p>";
            }
        echo "</div>
        <div class='col-sm-1 col-md-1 mx-0 text-danger text-center d-inline-block bg-success'><button class='open-AddBookDialog btn btn-block active text-center text-light' id='ExpenseEdit' data-id='$row[expenseName]' data-toggle='modal' data-target='#editExpense'>Edytuj</button></div>
        <div class='col-sm-1 col-md-1 mx-0 text-danger text-center d-inline-block bg-danger'><button class='open-eraseE btn btn-block active text-center text-light' id='ExpenseErase' data-id='$row[expenseName]' data-toggle='modal' data-target='#eraseExpense'>Usuń</button></div>
        ";
        }
        echo "<div class='col-sm-6 col-md-6 text-light text-center mt-3 font-weight-bold'>
        <button class='btn btn-success btn-block active' data-toggle='modal' data-target='#addExpenseCat'>Dodaj kategorię</button>
        </div>";
    }	
    public static function showPaymentMethods()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $sql_payment_cat = 'SELECT name as paymantName FROM payment_methods_assigned_to_users WHERE user_id=:user_id';
        $sql_payment_cat_result = $db->prepare($sql_payment_cat);
        $sql_payment_cat_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_payment_cat_result->execute();
        echo        "<form id='payment' method='post'>
					<p><input type='submit' class='btn btn-susscess btn-lg btn-block active' name='displaybtn' id='paymentbtn' value='Kategorie płatności'></p>	
					</form>";
        while ($row=$sql_payment_cat_result->fetch(PDO::FETCH_ASSOC)){
        echo "
        <div id='$row[paymantName]'>
        <div class='col-sm-10 col-md-9 mx-0 text-dark text-left bg-light d-inline-block'>$row[paymantName]</div> 
        <div class='col-sm-1 col-md-1 mx-0 text-danger text-center d-inline-block bg-success'><button class='open-AddBookDialogPayment btn btn-block active text-center text-light' id='PaymentEdit' data-id='$row[paymantName]' data-toggle='modal' data-target='#editPayment'>Edytuj</button></div>
        <div class='col-sm-1 col-md-1 mx-0 text-danger text-center d-inline-block bg-danger'><button class='open-eraseP btn btn-block active text-center text-light' id='PaymentErase' data-id='$row[paymantName]' data-toggle='modal' data-target='#erasePayment'>Usuń</button></div></div> ";
        }
        echo "<div class='col-sm-6 col-md-6 text-dark text-center mt-3 font-weight-bold'>
        <button class='btn btn-success btn-block active' data-toggle='modal' data-target='#addPaymentCat'>Dodaj kategorię</button>
        </div>";
    }	
    
    public static function showUserData()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $sql_payment_cat = 'SELECT username, email FROM users WHERE id=:user_id';
        $sql_payment_cat_result = $db->prepare($sql_payment_cat);
        $sql_payment_cat_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_payment_cat_result->execute();
        echo        "<form id='userD' method='post'>
					<p><input type='submit' class='btn btn-susscess btn-lg btn-block active' name='displaybtn' id='userbtn' value='Dane użytkownika'></p>	
					</form>";
        while ($row=$sql_payment_cat_result->fetch(PDO::FETCH_ASSOC)){
        echo "
        <div id='$row[username]' class='col-sm-10 col-md-9 mx-0 text-dark text-left bg-light d-inline-block'>$row[username]</div> 
        <div class='col-sm-2 col-md-2 mx-0 text-danger text-center d-inline-block bg-success'><button class='name btn btn-block active text-center text-light' id='DataEdit' data-id='$row[username]' data-toggle='modal' data-target='#editName'>Edytuj</button></div>
     
        <div id='$row[email]' class='col-sm-10 col-md-9 mx-0 text-dark text-left bg-light d-inline-block'>$row[email]</div> 
        <div class='col-sm-2 col-md-2 mx-0 text-danger text-center d-inline-block bg-success'><button class='mail btn btn-block active text-center text-light' id='DataEdit' data-id='$row[email]' data-toggle='modal' data-target='#editMail'>Edytuj</button></div>
      
        ";
        }
    }	

    public function addIncomeCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $incomecategory = $this->newIncCat;
        $sql_newIncome_cat = 'INSERT INTO incomes_category_assigned_to_users VALUES (NULL,:user_id,:incomecategory,NULL,NULL)';
        $sql_NewIncome_result = $db->prepare($sql_newIncome_cat);
        $sql_NewIncome_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_NewIncome_result->bindValue(':incomecategory', $incomecategory, PDO::PARAM_STR);
        return $sql_NewIncome_result->execute();
    }

    public function addExpenseCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $expensecategory = $this->newExpCat;
        $sql_newExpense_cat = 'INSERT INTO expences_category_assigned_to_users VALUES (NULL,:user_id,:expensecategory,NULL,NULL)';
        $sql_NewExpense_result = $db->prepare($sql_newExpense_cat);
        $sql_NewExpense_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_NewExpense_result->bindValue(':expensecategory', $expensecategory, PDO::PARAM_STR);
        return $sql_NewExpense_result->execute();
    }
    
    public function addPaymentCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $paymentcategory = $this->newPayCat;
        $sql_newPayment_cat = 'INSERT INTO payment_methods_assigned_to_users VALUES (NULL,:user_id,:paymentcategory)';
        $sql_newPayment_result = $db->prepare($sql_newPayment_cat);
        $sql_newPayment_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $sql_newPayment_result->bindValue(':paymentcategory', $paymentcategory, PDO::PARAM_STR);
        return $sql_newPayment_result->execute();
    }
    public function changeIncomeCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $incCat = $this->incCat;
        $incCatOld = $this->incCatOld;
         $current_date = new DateTime();
        $month = $current_date->format('m');
        $changeIncomeName_sql = 'UPDATE `incomes_category_assigned_to_users` SET `name` = :incCat  WHERE `user_id` = :user_id AND `name`=:incCatOld';
        $changeIncomeName_result = $db->prepare($changeIncomeName_sql);
        $changeIncomeName_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $changeIncomeName_result->bindValue(':incCat', $incCat, PDO::PARAM_STR);
        $changeIncomeName_result->bindValue(':incCatOld', $incCatOld, PDO::PARAM_STR);
        return $changeIncomeName_result->execute();
    }

    public function changeName()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $newName = $this->newName;
        $oldName = $this->oldName;
        $changeName_sql = 'UPDATE `users` SET `username` = :newName WHERE `user_id` = :user_id AND `name`=:oldName';
        $changeName_result = $db->prepare($changeName_sql);
        $changeName_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $changeName_result->bindValue(':newName', $newName, PDO::PARAM_STR);
        $changeName_result->bindValue(':oldName', $oldName, PDO::PARAM_STR);
        return $changeName_result->execute();
    }
    public function changeEmail()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $newEmail = $this->newEmail;
        $oldEmail = $this->oldEmail;
        $changeEmail_sql = 'UPDATE `users` SET `email` = :newEmail WHERE `user_id` = :user_id AND `name`=:oldEmail';
        $changeEmail_result = $db->prepare($changeEmail_sql);
        $changeEmail_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $changeEmail_result->bindValue(':newEmail', $newEmail, PDO::PARAM_STR);
        $changeEmail_result->bindValue(':oldEmail', $oldEmail, PDO::PARAM_STR);
        return $changeEmail_result->execute();
    }
    public function changeExpenseCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $expenseCat = $this->exCat;
        $expenseLimit = $this->expenseLimit;
        $expenseCatOld = $this->exCatOld;
        if ($expenseLimit=="")
        {
            $changeExpenseName_sql = 'UPDATE `expences_category_assigned_to_users` SET `name` = :exCat WHERE `user_id` = :user_id AND `name`=:exCatOld';
            $changeExpenseName_result = $db->prepare($changeExpenseName_sql);
            $changeExpenseName_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $changeExpenseName_result->bindValue(':exCat', $expenseCat, PDO::PARAM_STR);
            $changeExpenseName_result->bindValue(':exCatOld', $expenseCatOld, PDO::PARAM_STR);
            return $changeExpenseName_result->execute();
        }
        else
        {
            $current_date = new DateTime();
            $month = $current_date->format('m');
            $changeExpenseName_sql = 'UPDATE `expences_category_assigned_to_users` SET `name` = :exCat,`limit_wydatkow`=:expenseLimit,`miesiac`=:month  WHERE `user_id` = :user_id AND `name`=:exCatOld';
            $changeExpenseName_result = $db->prepare($changeExpenseName_sql);
            $changeExpenseName_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $changeExpenseName_result->bindValue(':exCat', $expenseCat, PDO::PARAM_STR);
            $changeExpenseName_result->bindValue(':exCatOld', $expenseCatOld, PDO::PARAM_STR);
            $changeExpenseName_result->bindValue(':expenseLimit', $expenseLimit, PDO::PARAM_INT);
            $changeExpenseName_result->bindValue(':month', $month, PDO::PARAM_INT);
            return $changeExpenseName_result->execute();
        }
    }
    
    public function changePaymentCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $paymentCat = $this->exCatPayment;
        $paymentCatOld = $this->exCatOldPayment;
        $changePaymentName_sql = 'UPDATE `payment_methods_assigned_to_users` SET `name` = :paymentCat WHERE `user_id` = :user_id AND `name`=:paymentCatOld';
        $changePaymentName_result = $db->prepare($changePaymentName_sql);
        $changePaymentName_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $changePaymentName_result->bindValue(':paymentCat', $paymentCat, PDO::PARAM_STR);
        $changePaymentName_result->bindValue(':paymentCatOld', $paymentCatOld, PDO::PARAM_STR);
        return $changePaymentName_result->execute();
    }
    private function checkName($newname)
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $checkIncomeName = 'SELECT name FROM incomes_category_assigned_to_users WHERE user_id=:user_id AND name=":newname"';
        $checkIncomeName_result = $db->prepare($checkIncomeName);
        $checkIncomeName_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $checkIncomeName_result->bindValue(':newname', $newname, PDO::PARAM_STR);
        return $sql_NewIncome_result->execute();
    }
    public function eraseIncomeCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $catToErase = $this->eraseCat;
        if ($catToErase!="Inne")
        { 
            //odczytanie id usuwanej kategorii
        $idCatToErase_sql='SELECT id FROM incomes_category_assigned_to_users WHERE name=:catToErase AND user_id=:user_id';
        $idCatToErase_result = $db->prepare($idCatToErase_sql);
        $idCatToErase_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $idCatToErase_result->bindValue(':catToErase', $catToErase, PDO::PARAM_STR);
        $idCatToErase_result->execute();
        $idCatToErase = $idCatToErase_result->fetch();
        $oldID = $idCatToErase['id'];
            //odczytanie id kategorii Inne
        $idCatOther_sql='SELECT id FROM incomes_category_assigned_to_users WHERE name="Inne" AND user_id=:user_id';
        $idCatOther_result = $db->prepare($idCatOther_sql);
        $idCatOther_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $idCatOther_result->execute();
        $iidCatOther = $idCatOther_result->fetch();
        $newID = $iidCatOther['id'];
     
            //ustawienie poprawnego id w tabeli expeses dla rekordow usuwanej kategorii
        $alterCatNo_sql = 'UPDATE incomes SET income_category_assigned_to_user_id=:newID WHERE user_id=:user_id AND income_category_assigned_to_user_id=:oldID';
        $alterCatNo_result = $db->prepare($alterCatNo_sql);
        $alterCatNo_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $alterCatNo_result->bindValue(':newID', $newID, PDO::PARAM_INT);
        $alterCatNo_result->bindValue(':oldID', $oldID, PDO::PARAM_INT);
        $alterCatNo_result->execute();    

            //usuwanie kategorii dla tego usera
        $eraseCategory_sql='DELETE FROM incomes_category_assigned_to_users WHERE user_id=:user_id AND name=:catToErase';
        $eraseCategory_result = $db->prepare($eraseCategory_sql);
        $eraseCategory_result->bindValue(':catToErase', $catToErase, PDO::PARAM_STR);
        $eraseCategory_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $eraseCategory_result->execute();
        }
        else
        {
        return false;
        }
    }
    
    public function eraseExpenseCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $exCatToErase = $this->eraseCatE;
        if ($exCatToErase!="Inne")
        { 
            //odczytanie id usuwanej kategorii
        $idCatToEraseEx_sql='SELECT id FROM expences_category_assigned_to_users WHERE name=:catToErase AND user_id=:user_id';
        $idCatToEraseEx_result = $db->prepare($idCatToEraseEx_sql);
        $idCatToEraseEx_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $idCatToEraseEx_result->bindValue(':catToErase', $exCatToErase, PDO::PARAM_STR);
        $idCatToEraseEx_result->execute();
        $idCatToErase = $idCatToEraseEx_result->fetch();
        $oldID = $idCatToErase['id'];
            //odczytanie id kategorii Inne
        $idCatOther_sql='SELECT id FROM expences_category_assigned_to_users WHERE name="Inne wydatki" AND user_id=:user_id';
        $idCatOther_result = $db->prepare($idCatOther_sql);
        $idCatOther_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $idCatOther_result->execute();
        $iidCatOther = $idCatOther_result->fetch();
        $newID = $iidCatOther['id'];
     
            //ustawienie poprawnego id w tabeli expeses dla rekordow usuwanej kategorii
        $alterCatNo_sql = 'UPDATE expenses SET expences_category_assigned_to_users=:newID WHERE user_id=:user_id AND expense_category_assigned_to_user_id=:oldID';
        $alterCatNo_result = $db->prepare($alterCatNo_sql);
        $alterCatNo_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $alterCatNo_result->bindValue(':newID', $newID, PDO::PARAM_INT);
        $alterCatNo_result->bindValue(':oldID', $oldID, PDO::PARAM_INT);
        $alterCatNo_result->execute();    

            //usuwanie kategorii dla tego usera
        $eraseCategory_sql='DELETE FROM expences_category_assigned_to_users WHERE user_id=:user_id AND name=:exCatToErase';
        $eraseCategory_result = $db->prepare($eraseCategory_sql);
        $eraseCategory_result->bindValue(':exCatToErase', $exCatToErase, PDO::PARAM_STR);
        $eraseCategory_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $eraseCategory_result->execute();
        }
        else
        {
        return false;
        }
    }
    
    public function erasePaymentCat()
    {
        $db = static::getDB();
        $user_id =  $_SESSION['user_id'];
        $paymentCatToErase = $this->eraseCatP;
        if ($paymentCatToErase!="Inne formy płatności")
        { 
            //odczytanie id usuwanej kategorii
        $idCatToErasePayment_sql='SELECT id FROM payment_methods_assigned_to_users WHERE name=:paymentCatToErase AND user_id=:user_id';
        $idCatToErasePayment_result = $db->prepare($idCatToErasePayment_sql);
        $idCatToErasePayment_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $idCatToErasePayment_result->bindValue(':paymentCatToErase', $paymentCatToErase, PDO::PARAM_STR);
        $idCatToErasePayment_result->execute();
        $idCatToErase = $idCatToErasePayment_result->fetch();
        $oldID = $idCatToErase['id'];
            //odczytanie id kategorii Inne
        $idCatOther_sql='SELECT id FROM payment_methods_assigned_to_users WHERE name="Inne formy płatności" AND user_id=:user_id';
        $idCatOther_result = $db->prepare($idCatOther_sql);
        $idCatOther_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $idCatOther_result->execute();
        $iidCatOther = $idCatOther_result->fetch();
        $newID = $iidCatOther['id'];
     
            //ustawienie poprawnego id w tabeli payments_methods_assigned_to_users dla rekordow usuwanej kategorii
        $alterCatNo_sql = 'UPDATE expenses SET payment_method_assigned_to_users=:newID WHERE user_id=:user_id AND payment_method_assigned_to_users=:oldID';
        $alterCatNo_result = $db->prepare($alterCatNo_sql);
        $alterCatNo_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $alterCatNo_result->bindValue(':newID', $newID, PDO::PARAM_INT);
        $alterCatNo_result->bindValue(':oldID', $oldID, PDO::PARAM_INT);
        $alterCatNo_result->execute();    

            //usuwanie kategorii dla tego usera
        $eraseCategory_sql='DELETE FROM payment_methods_assigned_to_users WHERE user_id=:user_id AND name=:paymentCatToErase';
        $eraseCategory_result = $db->prepare($eraseCategory_sql);
        $eraseCategory_result->bindValue(':paymentCatToErase', $paymentCatToErase, PDO::PARAM_STR);
        $eraseCategory_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $eraseCategory_result->execute();
        }
        else
        {
        return false;
        }
    }
}
?>