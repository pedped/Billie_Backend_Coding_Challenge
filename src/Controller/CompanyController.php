<?php

namespace App\Controller;

use App\Entity\Company;
use App\Enums\CompanyStatus;
use App\Repository\CompanyRepository;
use App\Texts\ResponseMessages;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends BaseTokenClass
{

    #[Route('/company/add', name: 'add_company', methods: ['POST'])]
    public function add(ManagerRegistry $doctrine, CompanyRepository $companyRepository): JsonResponse
    {

        $request = Request::createFromGlobals();
        $request->getPathInfo();

        // get parameters
        $name = $request->request->get('name');
        $address = $request->request->get('address');
        $phoneNumber = $request->request->get('phone_number');
        $vatNumber = $request->request->get('vat_number');


        // create admin user
        $company = new Company();
        $company->setUserId($this->loggedInUser->getId());
        $company->setName($name);
        $company->setAddress($address);
        $company->setPhoneNumber($phoneNumber);
        $company->setVatNumber($vatNumber);
        $company->setStatus(CompanyStatus::$ACTIVE);

        // set default debtor limit
        $defaultDebtorLimit = $this->getParameter('app.default_debtor_limit');
        $company->setDebtorLimit($defaultDebtorLimit);

        // add item
        $companyRepository->add($company, true);


        return $this->json([
            'message' => ResponseMessages::$COMPANY_CREATED_SUCCESSFULLY,
            'company_id' => $company->getId(),
        ]);

    }
}
