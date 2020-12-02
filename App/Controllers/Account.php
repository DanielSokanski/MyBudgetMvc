<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\Balance;
use \App\Models\Incomes;
use \App\Models\Expenses;
use \Core\View;
use \App\Auth;
use \App\Flash;
use DateTime;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{
	    public function addExpenceAction()
    {
        $new_expense_list = [];
        $new_expense_list['updateExpense'] = Expenses::showExpenseList();
        $new_expense_list['updatePaymentMethods'] = Expenses::showPaymentMethods();
        View::renderTemplate('AddExpence/index.html', $new_expense_list);
    }
	    public function addIncomeAction()
    {
        $new_income_list = [];
        $new_income_list['updateIncome'] = Incomes::showIncomeList();
        View::renderTemplate('AddIncome/index.html', $new_income_list);
    
    }
	    public function MainMenuAction()
    {
        View::renderTemplate('MainMenu/index.html');
    }
    public function bilansAction()
    {
        View::renderTemplate('Bilans/index.html');
    }
    public function settingsAction()
    {
        View::renderTemplate('Settings/index.html');
    }
}