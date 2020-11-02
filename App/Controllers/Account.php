<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\Balance;
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
    public function settingsAction()
    {
        View::renderTemplate('Settings/index.html');
    }
}