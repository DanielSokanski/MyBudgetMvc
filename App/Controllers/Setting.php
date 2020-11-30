<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Settings;
use \App\Flash;
class Setting extends \Core\Controller
{
    public function incomesCategoriesAction()
    {
        Settings::showIncomeCategories();
    }
    
    public function expenseCategoriesAction()
    {
        Settings::showExpenseCategories();
    }
    
    public function paymentMethodAction()
    {
        Settings::showPaymentMethods();
    }
   
    public function userDataAction()
    {
        Settings::showUserData();
    }
    public function addIncomeCategory()
    {
        $income = new Settings($_POST);
        if ($income->addIncomeCat())
		{
            Flash::addMessage('Udało się! Dodałeś poprawnie nową kategorię');
            View::renderTemplate('Settings/index.html');
		}
        else {
			Flash::addMessage('Niepoprawne dane. Spróbuj ponownie.',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    
    public function eraseIncomeCategory()
    {
        $erase = new Settings($_POST);
        if ($erase->eraseIncomeCat())
		{
            Flash::addMessage('Udało się! Usunąłeś poprawnie kategorię');
            View::renderTemplate('Settings/index.html');
		}
        else {
			Flash::addMessage('Niepoprawne dane lub próba usunięcia kategorii Inne. Spróbuj ponownie.',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    public function eraseExpenseCategory()
    {
        $eraseE = new Settings($_POST);
        if ($eraseE->eraseExpenseCat())
		{
            Flash::addMessage('Udało się! Usunąłeś poprawnie kategorię');
            View::renderTemplate('Settings/index.html');
		}
        else {
			Flash::addMessage('Niepoprawne dane lub próba usunięcia kategorii Inne. Spróbuj ponownie.',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }

    public function changeIncome()
    {
        $changeIncome = new Settings($_POST);
        if ($changeIncome->changeIncomeCat())
        {
            Flash::addMessage('Zmieniono kategorię');
            View::renderTemplate('Settings/index.html');
        }
        else {
			Flash::addMessage('Niepoprawne dane lub kategoria istnieje',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    public function changeExpense()
    {
        $changeExpense = new Settings($_POST);
        if ($changeExpense->changeExpenseCat())
        {
            Flash::addMessage('Zmieniono kategorię');
            View::renderTemplate('Settings/index.html');
        }
        else {
			Flash::addMessage('Niepoprawne dane lub kategoria istnieje',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    
    public function changePayment()
    {
        $changePayment = new Settings($_POST);
        if ($changePayment->changePaymentCat())
        {
            Flash::addMessage('Zmieniono kategorię płatności');
            View::renderTemplate('Settings/index.html');
        }
        else {
			Flash::addMessage('Niepoprawne dane lub kategoria istnieje',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    
    public function changeName()
    {
        $changeName = new Settings($_POST);
        if ($changeName->changeName())
        {
            Flash::addMessage('Zmieniono nazwę użytkownika');
            View::renderTemplate('Settings/index.html');
        }
        else {
			Flash::addMessage('Niepoprawne dane ',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    public function addExpenseCategory()
    {
        $expense = new Settings($_POST);
        if ($expense->addExpenseCat())
		{
            Flash::addMessage('Udało się! Dodałeś poprawnie nową kategorię');
            View::renderTemplate('Settings/index.html');
		}
        else {
			Flash::addMessage('Niepoprawne dane. Spróbuj ponownie.',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
    
    
    public function addPaymentCategory()
    {
        $payment = new Settings($_POST);
        if ($payment->addPaymentCat())
		{
            Flash::addMessage('Udało się! Dodałeś poprawnie nową kategorię');
            View::renderTemplate('Settings/index.html');
		}
        else {
			Flash::addMessage('Niepoprawne dane. Spróbuj ponownie.',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }

    public function erasePaymentCategory()
    {
        $eraseP = new Settings($_POST);
        if ($eraseP->erasePaymentCat())
		{
            Flash::addMessage('Udało się! Usunąłeś poprawnie kategorię');
            View::renderTemplate('Settings/index.html');
		}
        else {
			Flash::addMessage('Niepoprawne dane lub próba usunięcia kategorii Inne. Spróbuj ponownie.',Flash::WARNING);
            View::renderTemplate('Settings/index.html');
        }
    }
}
?>