<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/form", name="app_form_index")
     */
    public function index(): Response
    {
        $results = [];
        $dateInput = $timezoneInput = null;
        $request = Request::createFromGlobals();
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $errors = [];

            $timezoneInput = $data['timezone'];
            $dateInput = $data['date'];

            if ($dateInput === '') {
                $errors[] = 'Date is empty';
            }

            if ($timezoneInput === '') {
                $errors[] = 'Timezone is empty';
            }

            $listOfTimezones = DateTimeZone::listIdentifiers();

            if (DateTime::createFromFormat('Y-m-d', $dateInput) === false) {
                $errors[] = sprintf('Date: %s is invalid format', $dateInput);
            }

            if (!in_array($timezoneInput, $listOfTimezones)) {
                $errors[] = sprintf('Timezone %s is not valid', $timezoneInput);
            }

            if (count($errors)) {
                return $this->render('form/form.html.twig', ['errors' => $errors, 'date' => $dateInput, 'timezone' => $timezoneInput]);
            }

            $dateTime = new DateTimeImmutable();
            $timezone = new DateTimeZone($timezoneInput);
            $createdDate = $dateTime->createFromFormat('Y-m-d', $dateInput, $timezone);
            $results['timezone'] = $createdDate->getTimezone()->getName();
            $results['offset'] = floor($createdDate->getOffset() / 60);
            $results['days_in_month'] = $this->days_in_month(2, $createdDate->format('y'));
            $results['month'] = $createdDate->format('F');
            $results['days_long'] = $this->days_in_month($createdDate->format('m'), $createdDate->format('y'));
        }

        return $this->render('form/form.html.twig', ['results' => $results, 'date' => $dateInput, 'timezone' => $timezoneInput]);
    }

    private function days_in_month(int $month, int $year): int
    {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }
}
