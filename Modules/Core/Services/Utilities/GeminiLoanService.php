<?php
/**
 * Created by PhpStorm.
 * User: webby
 * Date: 03/08/2018
 * Time: 11:26 AM
 */

namespace Modules\Core\Services\Utilities;
use SoapClient;
use SoapFault;
use Carbon\Carbon;

class GeminiLoanService
{
    public $url;

    public function __construct()
    {
        $this->url = config('pennylender.gemini.base_url');
    }

    public function getTitles()
    {
        $requestSoap = '<?xml version="1.0" encoding="UTF-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
        <GetTitles xmlns="http://tempuri.org/" />
  </soap12:Body>
</soap12:Envelope>
        ';
    }

    public function sampleService()
    {
        // TODO: Use this to write an article later
        try {
            $data = [
                'customerID' => 5,
            ];

            $client = new SoapClient($this->url);
            $response = $client->GetCustomerDetail($data);
            $responseArray = json_decode(json_encode((array)$response), true);
            $detailsResponse = $responseArray['GetCustomerDetailResult']['CustApplication'];
            return $detailsResponse;
        } catch(SoapFault $fault) {
            echo '<br>'.$fault;
        }
    }

    public function getStates()
    {
        $client = new SoapClient($this->url);
        $response = $client->GetStates();
        $responseArray = json_decode(json_encode((array)$response), true);
        $stateResponse = $responseArray['GetStatesResult'];
        $loanXmlObject = (array) simplexml_load_string($stateResponse);

        return $loanXmlObject;
    }

    public function addCustomer($data) {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
            <soap12:Body>
                <CustData xmlns="http://tempuri.org/">
                    <Address>'.$data["address"].'</Address>
                    <BVNo>'.$data["bvn_number"].'</BVNo>
                    <BirthDate>'.$data["date_of_birth"].'</BirthDate>
                    <City>'.$data["current_city"].'</City>
                    <CustomerID>'.$data["user_id"].'</CustomerID>
                    <Email>'.$data["email"].'</Email>
                    <ExpiryDate>'. Carbon::now()->addYears(5) .'</ExpiryDate>
                    <FirstName>'.$data["first_name"].'</FirstName>
                    <Gender>'.$data["gender"].'</Gender>
                    <Identification>'.$data["valid_means_of_id"].'</Identification>
                    <IdentificationNo>'.$data["identification_number"].'</IdentificationNo>
                    <IssuedDate></IssuedDate>
                    <LGACode></LGACode>
                    <Landmark></Landmark>
                    <LastName>'.$data["last_name"].'</LastName>
                    <MaidenName></MaidenName>
                    <MaritalStatus>'.$data["marital_status"].'</MaritalStatus>
                    <MiddleName></MiddleName>
                    <OtherIdentification></OtherIdentification>
                    <PhoneNo>'.$data["telephone"].'</PhoneNo>
                    <SectorCode>'.$data["employer_sector"].'</SectorCode>
                    <StateCode>'.$data["state_code"].'</StateCode>
                    <Title>'.$data["title"].'</Title>
                </CustData>
            </soap12:Body>
        </soap12:Envelope>';

        $result = $this->makeSoapCall($request);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $data = (array) simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($data) {
            $responseArray = json_decode(json_encode((array)$data), true);
            $detailsResponse = $responseArray;
            return $detailsResponse;
        } else {
            return "Not found";
        }
    }

    public function uploadLoanRequest($data)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
            <soap12:Body>
                <UpdateLoanRequest xmlns="http://tempuri.org/">
                <importCustomer>
                    <BVNo>'.(int)$data['bvn'].'</BVNo>
                    <CustomerID>'.$data['customerId'].'</CustomerID>
                    <SectorCode>'.getSectorCode($data['sector']).'</SectorCode>
                    <FirstName>'.$data['firstName'].'</FirstName>
                    <LastName>'.$data['lastName'].'</LastName>
                    <MiddleName>'.$data['middleName'].'</MiddleName>
                    <Title>'.$data['title'].'</Title>
                    <Address>'.$data['address'].'</Address>
                    <City>'.$data['city'].'</City>
                    <Landmark>'.$data['landmark'].'</Landmark>
                    <LGACode>'.getLgaCode().'</LGACode>
                    <PhoneNo>'.$data['phoneNumber'].'</PhoneNo>
                    <Email>'.$data['email'].'</Email>
                    <MaidenName>'.$data['maidenName'].'</MaidenName>
                    <MaritalStatus>'.$data['maritalStatus'].'</MaritalStatus>
                    <Identification>'.getMeansOfIdentification($data['meansOfIdentification']).'</Identification>
                    <OtherIdentification>'.$data['otherIdentification'].'</OtherIdentification>
                    <IdentificationNo>'.$data['idNumber'].'</IdentificationNo>
                    <IssuedDate>'.parseDateFormatForSoap($data['issuedDate']).'</IssuedDate>
                    <ExpiryDate>'.parseDateFormatForSoap($data['expiryDate']).'</ExpiryDate>
                    <StateCode>'.getStateCode($data['state']).'</StateCode>
                    <CountryCode>NGA</CountryCode>
                    <BirthDate>'.parseDateFormatForSoap($data['dob']).'</BirthDate>
                    <Gender>'.$data['gender'].'</Gender>
                </importCustomer>
                <importemployer>
                    <EmploymentStatus>'.$data['employmentStatus'].'</EmploymentStatus>
                    <EmployerName>'.$data['employerName'].'</EmployerName>
                    <Address>'.$data['employerAddress'].'</Address>
                    <City>'.$data['employerCity'].'</City>
                    <Landmark>'.$data['employerLandmark'].'</Landmark>
                    <LGACode>'.getLgaCode().'</LGACode>
                    <StateCode>'.getStateCode($data['employerState']).'</StateCode>
                    <MonthlyIncome>'.$data['monthlyIncome'].'</MonthlyIncome>
                    <SectorCode>'.getSectorCode($data['employerSectorCode']).'</SectorCode>
                    <TelephoneNo>'.$data['employerPhoneNumber'].'</TelephoneNo>
                    <Email>'.$data['employerEmail'].'</Email>
                    <StaffID></StaffID>
                    <TaxNo></TaxNo>
                    <PensionNo></PensionNo>
                    <EmployedDate>'.parseDateFormatForSoap($data['employmentDate']).'</EmployedDate>
                    <PrevEmploymentMonth>3</PrevEmploymentMonth>
                    <JobNo>2</JobNo>
                    <EducationLevel>'.$data['educationLevel'].'</EducationLevel>
                    <PayDay></PayDay>
                </importemployer>
                <importNextofKin>
                    <TitleKey>'.getTitleKey($data['title']).'</TitleKey>
                    <KinName>'.$data['nokName'].'</KinName>
                    <Address>'.$data['nokAddress'].'</Address>
                    <Landmark>'.$data['nokLandmark'].'</Landmark>
                    <City>'.$data['nokCity'].'</City>
                    <LGACode>'.getLgaCode().'</LGACode>
                    <StateCode>'.getStateCode($data['nokState']).'</StateCode>
                    <Relationship>'.$data['nokRelationship'].'</Relationship>
                    <Email>'.$data['nokEmail'].'</Email>
                    <PhoneNo>'.$data['nokPhoneNumber'].'</PhoneNo>
                    <EmployerName>'.$data['nokEmployer'].'</EmployerName>
                </importNextofKin>
                <importOtherInfo>
                    <IsBankAccount>true</IsBankAccount>
                    <BankCode>'.getBankCode($data['bankName']).'</BankCode>
                    <AccountNo>'.$data['accountNumber'].'</AccountNo>
                    <BVNo>'.(int)$data['bvn'].'</BVNo>
                    <IsLoanOutstanding>'.(int)$data['anyExistingLoan'].'</IsLoanOutstanding>
                    <LoanRepayment>'.$data['loanRepaymentAmount'].'</LoanRepayment>
                    <BankRelationLength>0</BankRelationLength>
                    <ChildrenCount>'.$data['childrenCount'].'</ChildrenCount>
                    <HouseholdCount>'.$data['houseHoldCount'].'</HouseholdCount>
                    <ResidentialStatus>'.$data['residentialStatus'].'</ResidentialStatus>
                    <AdvertID>'.getAdvertId($data['advert']).'</AdvertID>
                </importOtherInfo>
                <importLoanDetail>
                    <BVNo>'.(int)$data['bvn'].'</BVNo>
                    <ApplDate>'.parseDateFormatForSoap($data['applicationDate']).'</ApplDate>
                    <ProductCode>200</ProductCode>
                    <Amount>'.$data['loanAmount'].'</Amount>
                    <PurposeKey>'.getLoanPurposeKey($data['purpose']).'</PurposeKey>
                    <Installment>'.$data['installment'].'</Installment>
                    <DisburseDate>'.parseDateFormatForSoap($data['disburseDate']).'</DisburseDate>
                    <FirstPayDate>'.parseDateFormatForSoap($data['firstPayDate']).'</FirstPayDate>
                    <CurrencyCode>NGN</CurrencyCode>
                    <RepayKey>'.getRepaymentModeKey($data['repaymentMode']).'</RepayKey>
                    <ContractType>'.($data['contractType']).'</ContractType>
                    <LNAccountNo>'.$data['LnAccountNo'].'</LNAccountNo>
                    <BankCode>'.getBankCode($data['bankName']).'</BankCode>
                    <BankAccountNo>'.$data['accountNumber'].'</BankAccountNo>
                </importLoanDetail>
                </UpdateLoanRequest>
            </soap12:Body>
        </soap12:Envelope>';

        $result = $this->makeSoapCall($request);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $data = (array) simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if($data){
            $responseArray = json_decode(json_encode((array)$data), true);
            $loanResponse = $responseArray['soapBody']['UpdateLoanRequestResponse']['UpdateLoanRequestResult'];
            $loanXmlObject = (array) simplexml_load_string($loanResponse);
            $loanResultObject = [
                "contractNo" => $loanXmlObject->ContractNo[0],
                "customerId" => $loanXmlObject->CustomerID,
                "responseCode" => $loanXmlObject->RespCode,
                "responseMessage" => $loanXmlObject->RespMessage
            ];
            return $loanResultObject;
        }else{
            return 'Not Found';
        }

    }

    public function searchForUserUsingBvn($bvn)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
            <soap12:Body>
                <GetBVNumber xmlns="http://tempuri.org/">
                    <bvNumber>'.(int)$bvn.'</bvNumber>
                </GetBVNumber>
            </soap12:Body>
        </soap12:Envelope>';

        $result = $this->makeSoapCall($request);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $data = (array) simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($data) {
            $responseArray = json_decode(json_encode((array)$data), true);
            $detailsResponse = $responseArray['soapBody']['GetBVNumberResponse']['GetBVNumberResult'];
            return $detailsResponse;
        } else {
            return "Not found";
        }
    }


    public function getCustomerDetails($customerId)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
        <soap12:Body>
            <GetCustomerDetail xmlns="http://tempuri.org/">
            <customerID>'.(int)$customerId.'</customerID>
            </GetCustomerDetail>
        </soap12:Body>
        </soap12:Envelope>';

        $result = $this->makeSoapCall($request);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $data = (array) simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($data) {
            $responseArray = json_decode(json_encode((array)$data), true);
            $detailsResponse = $responseArray['soapBody']['GetCustomerDetailResponse']['GetCustomerDetailResult']['CustApplication'];
            return $detailsResponse;
        } else {
            return "Not found";
        }

    }

    public function getCustomerLoanAccount($customerId)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
            <soap12:Body>
                <GetCustomerLoanAccounts xmlns="http://tempuri.org/">
                <customerID>'.(int)$customerId.'</customerID>
                </GetCustomerLoanAccounts>
            </soap12:Body>
        </soap12:Envelope>';

        $result = $this->makeSoapCall($request);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $data = (array) simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($data) {
            $responseArray = json_decode(json_encode((array)$data), true);
            $loanAccountResponse = $responseArray['soapBody']['GetCustomerLoanAccountsResponse']['GetCustomerLoanAccountsResult'];
            $loanXmlObject = (array) simplexml_load_string($loanAccountResponse);
            $loanResultObject = [
              "accountNo" => $loanXmlObject->LoanAccount->AccountNo,
              "disburseDate" => $loanXmlObject->LoanAccount->DisburseDate,
              "facilityStatus" => $loanXmlObject->LoanAccount->FacilityStatus,
              "installment" => $loanXmlObject->LoanAccount->Installment,
              "loanAmount" =>  $loanXmlObject->LoanAccount->LoanAmount,
              "loanBalance" => $loanXmlObject->LoanAccount->LoanBalance
            ];

          return $loanResultObject;
        } else {
            return "Not found";
        }
    }

    public function getLoanPaymentSchedule($accountNumber) {
        $request = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <GetLoanPaymentSchedule xmlns="http://tempuri.org/">
                <accountNo>'.$accountNumber.'</accountNo>
                </GetLoanPaymentSchedule>
            </soap:Body>
        </soap:Envelope>';

        $result = $this->makeSoapCall($request);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $data = (array) simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($data) {
            $responseArray = json_decode(json_encode((array)$data), true);
            $detailsResponse = $responseArray['soapBody']['GetLoanPaymentScheduleResponse']['GetLoanPaymentScheduleResult']['_LoanInstallment'];
            return $detailsResponse;
        } else {
            return "Not found";
        }
    }

    public function makeSoapCall($request)
    {
        $header = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: ".strlen($request),
        );

        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $this->url );
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST,           true );
        curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $request);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);

        $result = curl_exec($soap_do);
        return $result;
    }
}
