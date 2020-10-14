<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{
	    public function addExpenceAction()
    {
        View::renderTemplate('AddExpence/index.html');
    }
	    public function addIncomeAction()
    {
        View::renderTemplate('AddIncome/index.html');
    }
	    public function MainMenuAction()
    {
        View::renderTemplate('MainMenu/index.html');
    }
    public function bilansAction()
    {
        View::renderTemplate('Bilans/index.html');
    }
		    public function addRecordExpenseAction()
    {
		$expense = new User($_POST);
		
		if ($expense->addRecordExpense())
		{
			View::renderTemplate('AddExpence/index.html');
            Flash::addMessage('Udało się! Dodałeś poprawnie wydatek');
            
		}
        else {
			Flash::addMessage('Niepoprawne dane. Sprubuj ponownie.');
            View::renderTemplate('AddExpence/index.html');
        }
    
    }
    public function addRecordIncomeAction()
    {
		$income = new User($_POST);
		
		if ($income->addRecordIncome())
		{
			View::renderTemplate('AddIncome/index.html');
            Flash::addMessage('Udało się! Dodałeś poprawnie przychod');
            
		}
        else {
			Flash::addMessage('Niepoprawne dane. Sprubuj ponownie.');
            View::renderTemplate('AddIncome/index.html');
        }
    
    }
}