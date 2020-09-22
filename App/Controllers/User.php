<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{

    /**
     * Show the signup page
     *
     * @return void
     */
    public function addExpenceAction()
    {
        View::renderTemplate('AddExpence/index.html');
    }

    /**
     * Sign up a new user
     *
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
		
			$user->copyIncomeTable();
			$user->expenceTable();
			$user->copyExpenceTable();
			$user->paymentMethod();
			$user->copyPaymentMethod();
			
            $this->redirect('/signup/success');

        } else {

            View::renderTemplate('Login/new.html', [
                'user' => $user
            ]);

        }
    }

    /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }

}