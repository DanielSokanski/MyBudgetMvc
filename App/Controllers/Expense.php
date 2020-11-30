<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\Expenses;
use \Core\View;
use \App\Auth;
use \App\Flash;

class Expense extends \Core\Controller
{
    public function addRecordExpenseAction()
    {
		$expense = new Expenses($_POST);
		
		if ($expense->addRecordExpense())
		{
            Flash::addMessage('Udało się! Dodałeś poprawnie wydatek');
            $expenses = [];
            $expenses['exCategories']= Expenses::showExpenseList();
            View::renderTemplate('AddExpence/index.html', $expenses);
		}
        else {
			Flash::addMessage('Niepoprawne dane. Sprubuj ponownie.',Flash::WARNING);
            View::renderTemplate('AddExpence/index.html');
        }
    
    }
    public function calculateExpense()
    {
        $kategoria = $_POST['kategoria'];
        $kwota = $_POST['kwota'];
        Expenses::showCalculation($kategoria, $kwota);
    }
}