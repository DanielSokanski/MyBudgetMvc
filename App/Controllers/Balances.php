<?php
namespace App\Controllers;
use App\Flash;
use DateTime;
use Core\View;
use \App\Models\Balance;
class Balances extends \Core\Controller
{
public function balanceTemplate($firstDay, $lastDay)
    {
        $arguments = [];
        $arguments['incomes'] = Balance::showIncomes($firstDay, $lastDay);
        $arguments['expenses'] = Balance::showExpenses($firstDay, $lastDay);
        $arguments['totalIncomesAmount'] = $this->calcSum($arguments['incomes']);
        $arguments['totalExpensesAmount'] = $this->calcSum($arguments['expenses']);
        $arguments['balance'] = number_format ( ($arguments['totalIncomesAmount'] - $arguments['totalExpensesAmount']) , 2, '.', ' ');
        

        View::renderTemplate('/Bilans/index.html', $arguments);
    }
    public function currentAction()
    {
        $current_date = new DateTime();
        $month = $current_date->format('m');
        $year = $current_date->format('Y');
        $firstDay = $year.'-'.$month.'-'.'01';
        $lastDay = $this->lastDayOfMonth($month, $year);
        $_SESSION['lead'] = 'Bieżący miesiąc';
        $this->balanceTemplate($firstDay, $lastDay);
    }

    public function previousAction()
    {
        $current_date = new DateTime();
        $month = $current_date->format('m') - 1;
        $year = $current_date->format('Y');
        $firstDay = $year.'-'.$month.'-'.'01';
        $lastDay = $this->lastDayOfMonth($month, $year);
        $_SESSION['lead'] = 'Poprzedni miesiąc';
        $this->balanceTemplate($firstDay, $lastDay);
    }

    public function yearAction()
    {
        $current_date = new DateTime();
        $year = $current_date->format('Y');
        $firstDay = $year.'-'.'01'.'-'.'01';
        $lastDay = $current_date->format('Y-m-d');
        $_SESSION['lead'] = 'Bieżący rok';
        $this->balanceTemplate($firstDay, $lastDay);
    }


    public function modalAction()
    {
        if($_POST['first_date'] == '' || $_POST['second_date'] == '')
        {
            Flash::addMessage('Należy wybrać obie daty!', Flash::WARNING);
            $current_date = new DateTime();
            $month = $current_date->format('m');
            $year = $current_date->format('Y');
            $firstDay = $year.'-'.$month.'-'.'01';
            $lastDay = $this->lastDayOfMonth($month, $year);
            $this->balanceTemplate($firstDay, $lastDay);
        }
        else
        {
            if($_POST['first_date'] > $_POST['second_date'])
            {
                Flash::addMessage('Pierwsza data nie może być większa niż druga!', Flash::WARNING);
                $current_date = new DateTime();
                $month = $current_date->format('m');
                $year = $current_date->format('Y');
                $firstDay = $year.'-'.$month.'-'.'01';
                $lastDay = $this->lastDayOfMonth($month, $year);
                $this->balanceTemplate($firstDay, $lastDay);
            }
            else
            {
                $firstDay = $_POST['first_date'];
                $lastDay = $_POST['second_date'];
                $_SESSION['lead'] = 'Bilans z okresu od '.$firstDay.' do '.$lastDay;
                $this->balanceTemplate($firstDay, $lastDay);
            }
        }
    }


    private function calcSum($sqlArray)
    {
        $sum = 0.0;
        foreach ($sqlArray as $values) {
            $sum += floatval($values['Amount']);
        }
        return $sum;
    }

    private function lastDayOfMonth($month, $year)
    {
        if($month === 2)
        {
            if(($year%4 === 0 && $year%100 !== 0) || $year%400 === 0)
            {
                return $year.'-'.$month.'-'.'29';
            }
            else
            {
                return $year.'-'.$month.'-'.'28';
            }
        }
        else if($month === 1 || $month === 3 || $month === 5 || $month === 7 || $month === 8 || $month === 10 || $month === 12)
        {
            return $year.'-'.$month.'-'.'31';
        }
        else
        {
           return $year.'-'.$month.'-'.'30';
        }

    }
}