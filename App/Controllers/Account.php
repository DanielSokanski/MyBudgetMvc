<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;
use \App\Auth;

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
		    public function addExpenceAction()
    {
		$expence = User::addExpence($POST['kwota'],$POST['data'],$POST['zaplata'],$POST['kategoria'],$POST['komentarz']);
		If ($expence)
		{
			
			Flash::addMessage('Udało się! Dodałeś poprawnie wydatek.');
		}
        
    }
    /**
     * Validate if email is available (AJAX) for a new signup or an existing user.
     * The ID of an existing user can be passed in in the querystring to ignore when
     * checking if an email already exists or not.
     *
     * @return void
     */
    public function validateEmailAction()
    {
        $is_valid = ! User::emailExists($_GET['email'], $_GET['ignore_id'] ?? null);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}
