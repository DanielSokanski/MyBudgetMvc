<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\Incomes;
use \Core\View;
use \App\Auth;
use \App\Flash;

class Income extends \Core\Controller
{
    public function addRecordIncomeAction()
    {
		$income = new Incomes($_POST);
		
		if ($income->addRecordIncome())
		{
            Flash::addMessage('Udało się! Dodałeś poprawnie przychod');
			View::renderTemplate('AddIncome/index.html'); 
		}
        else {
			Flash::addMessage('Niepoprawne dane. Sprubuj ponownie.',Flash::WARNING);
            View::renderTemplate('AddIncome/index.html');
        }
    
    }
}
