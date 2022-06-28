<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\InvoiceLineItem;
use App\Enums\InvoicePayedStatus;
use App\Helpers\StringHelper;
use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use App\Texts\ResponseMessages;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends BaseTokenClass
{
    #[Route('/invoice/add', name: 'add_invoice', methods: ['POST'])]
    public function add(ManagerRegistry $doctrine, CompanyRepository $companyRepository, InvoiceRepository $invoiceRepository): JsonResponse
    {

//        $items = array();
//
//        $item = new \stdClass();
//        $item->description = "Apple Macbook Pro";
//        $item->quantity = 1;
//        $item->unit_price = 2899;
//        $item->vat = 20;
//        $items[] = $item;
//
//        echo json_encode($items);
//        die();


        // // get parameters
        $request = Request::createFromGlobals();
        $request->getPathInfo();
        $companyId = $request->request->get('company_id');
        $title = $request->request->get('title');
        $summery = $request->request->get('summery');
        $vatNumber = $request->request->get('vat_number');
        $terms = $request->request->get('terms');
        $currency = $request->request->get('currency');
        $lineItems = $request->request->get('line_items');
        try {
            $invoice = $invoiceRepository->createInvoice($this->loggedInUser->getId(), $companyId, $title, $summery, $vatNumber, $terms, $currency, $lineItems);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ]);
        }

        return $this->json([
            'message' => ResponseMessages::$INVOICE_CREATED_SUCCESSFULLY,
            'invoice_id' => $invoice->getId(),
            'invoice_number' => $invoice->getNumber(),
        ]);

    }


    #[Route('/invoice/set_payed', name: 'set_payed', methods: ['POST'])]
    public function set_payed(ManagerRegistry $doctrine, InvoiceRepository $invoiceRepository): JsonResponse
    {
        $request = Request::createFromGlobals();
        $entityManager = $doctrine->getManager();
        $request->getPathInfo();

        // // get parameters
        $invoiceId = $request->request->get('invoice_id');

        try {
            $invoiceRepository->setPaid($this->loggedInUser->getId(), $invoiceId);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ]);
        }

        return $this->json([
            'message' => ResponseMessages::$INVOICE_PAYED_SUCCESSFULLY,
        ]);

    }
}
