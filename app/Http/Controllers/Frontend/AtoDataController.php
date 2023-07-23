<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\Payslip;
use App\Models\Frontend\Ato_data;
use App\Http\Controllers\Controller;

class AtoDataController extends Controller
{
    public $xmlStart = '
    <?xml version="1.0" encoding="utf-8"?>
    <soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope">
      <soapenv:Header>
        <eb:Messaging xmlns:eb="http://docs.oasis-open.org/ebxml-msg/ebms/v3.0/ns/core/200704/" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soapenv:mustUnderstand="true" wsu:Id="soapheader-1">
          <ns2:UserMessage xmlns:ns2="http://docs.oasis-open.org/ebxml-msg/ebms/v3.0/ns/core/200704/">
            <ns2:MessageInfo>
              <ns2:Timestamp>2020-03-11T00:13:32.868Z</ns2:Timestamp>
              <ns2:MessageId>A1583885608914.e2a21ef1-8b14-4454-881f-3f35d7b2fde8@1583885608915</ns2:MessageId>
            </ns2:MessageInfo>
            <ns2:PartyInfo>
              <ns2:From>
                <ns2:PartyId type="http://abr.gov.au/PartyIdType/ABN">67094544519</ns2:PartyId>
                <ns2:Role>http://sbr.gov.au/ato/Role/Business</ns2:Role>
              </ns2:From>
              <ns2:To>
                <ns2:PartyId type="http://abr.gov.au/PartyIdType/ABN">51824753556</ns2:PartyId>
                <ns2:Role>http://sbr.gov.au/agency</ns2:Role>
              </ns2:To>
            </ns2:PartyInfo>
            <ns2:CollaborationInfo>
              <ns2:Service>http://sbr.gov.au/ato/payevnt/2020</ns2:Service>
              <ns2:Action>Submit.004.00</ns2:Action>
              <ns2:ConversationId>c726ccf9-98f2-4f80-94dd-f8f448ea0f33</ns2:ConversationId>
            </ns2:CollaborationInfo>
            <ns2:MessageProperties>
              <ns2:Property name="MSHIdentity">ebMS/AS4 Client</ns2:Property>
              <ns2:Property name="VendorVersion">1.0.0.3</ns2:Property>
              <ns2:Property name="Vendor">Sample</ns2:Property>
              <ns2:Property name="BMS Name">Test Product</ns2:Property>
              <ns2:Property name="BMS Version">Version 1</ns2:Property>
              <ns2:Property name="IBMMEIGClientVersion">1.0.0.2-SNAPSHOT</ns2:Property>
              <ns2:Property name="ProductID">000001</ns2:Property>
              <ns2:Property name="IBMMEIGClient">true</ns2:Property>
              <ns2:Property name="BMS Vendor">eCommerce Test</ns2:Property>
              <ns2:Property name="MSHVersion">1.0.0.3</ns2:Property>
            </ns2:MessageProperties>
            <ns2:PayloadInfo>
              <ns2:PartInfo href="cid:Attachment">
                <ns2:Schema></ns2:Schema>
                <ns2:PartProperties>
                  <ns2:Property name="DocumentName">PAYEVNT</ns2:Property>
                  <ns2:Property name="PartID">1</ns2:Property>
                  <ns2:Property name="DocumentType">BASE</ns2:Property>
                  <ns2:Property name="MimeType">text/plain</ns2:Property>
                  <ns2:Property name="filename">Attachment</ns2:Property>
                </ns2:PartProperties>
              </ns2:PartInfo>
            </ns2:PayloadInfo>
          </ns2:UserMessage>
        </eb:Messaging>
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" soapenv:mustUnderstand="true">
          <saml:EncryptedAssertion xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="soapheader-2">
            <xenc:EncryptedData xmlns:xenc="http://www.w3.org/2001/04/xmlenc#" Id="ED-73684f5d-15ee-4b7a-9aa5-af1ca90a31b2" Type="http://www.w3.org/2001/04/xmlenc#Element">
              <xenc:EncryptionMethod Algorithm="http://www.w3.org/2001/04/xmlenc#aes256-cbc"></xenc:EncryptionMethod>
              <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
                <xenc:EncryptedKey Id="EK-48d844cb-f1e6-438e-bb7e-9c171214d03c">
                  <xenc:EncryptionMethod Algorithm="http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p">
                    <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
                  </xenc:EncryptionMethod>
                  <ds:KeyInfo>
                    <wsse:SecurityTokenReference>
                      <ds:X509Data>
                        <ds:X509IssuerSerial>
                          <ds:X509IssuerName>CN=Australian Government Authentication Services OCA - G2,OU=For Development purposes ONLY,OU=Australian Authentication Services,O=Australian Government,C=AU</ds:X509IssuerName>
                          <ds:X509SerialNumber>38604690174694584784568861399985626074</ds:X509SerialNumber>
                        </ds:X509IssuerSerial>
                      </ds:X509Data>
                    </wsse:SecurityTokenReference>
                  </ds:KeyInfo>
                  <xenc:CipherData>
                    <xenc:CipherValue>x7iHeuc+oLtmd3ktKp7nkZSAFmSMR7YNjrG1rrt5wEamPxSbC9pe5I/DArrV0XfQMsHQjOLtb6Q51/Ql3hibGqSXpTiduODWz6xi1sxIHh6Zb0WfArfUFxXgw1HR0UqDNUV2PNXB7gxw/+du6zR6WuyHmHFu0Qu1X/EWLVlhL2p/juo0mmUE9X4FqqhDokLENle9WsbmqKVnUEVlDPlXqK2NWqN20UHp1HTFCE+9jLQzXcinYpzO1jki+L7kcgi1RFe8bpo38TuBFPqNT1Ahxs5rjK97LW2mmGgemWvl6B/1vEfAUTleZ6BRawOaa9B+rt2FmP3kCn1mWLMEdN7UrQ==</xenc:CipherValue>
                  </xenc:CipherData>
                </xenc:EncryptedKey>
              </ds:KeyInfo>
              <xenc:CipherData>
                <xenc:CipherValue>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</xenc:CipherValue>
              </xenc:CipherData>
            </xenc:EncryptedData>
          </saml:EncryptedAssertion>
          <wsse:BinarySecurityToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="signingCert">MIIEGjCCAwKgAwIBAgIDBAoBMA0GCSqGSIb3DQEBCwUAMIGFMQswCQYDVQQGEwJBVTElMCMGA1UEChMcQXVzdHJhbGlhbiBCdXNpbmVzcyBSZWdpc3RlcjEgMB4GA1UECxMXQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxLTArBgNVBAMTJFRlc3QgQXVzdHJhbGlhbiBCdXNpbmVzcyBSZWdpc3RlciBDQTAeFw0xOTAxMjkwMDM5NTNaFw0yMDAzMjkwMDM5NTNaMDcxCzAJBgNVBAYTAkFVMRQwEgYDVQQKEws2NzA5NDU0NDUxOTESMBAGA1UEAxMJWUFMQUNUMTI5MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCrnmr2OiQFAfE2B0eTTX/WLIdu7L3vw74Q/wrSKvRCMW4kP8Y9+OBTUs8Oru7sqBx3V8w7R5PTRT+lOqdiAyuK/6gilYNptmcEmXhW3mxoGc2JmHP274lr2dP7U+wh3YSZYmmMjmuYs/ffG3Rvl/Ig6OqUSFvXebtUktpmOfCuCQIDAQABo4IBYjCCAV4wDAYDVR0TAQH/BAIwADCB5AYDVR0gBIHcMIHZMIHWBgkqJAGXOWUBCAEwgcgwgaYGCCsGAQUFBwICMIGZGoGWVXNlIHRoaXMgY2VydGlmaWNhdGUgb25seSBmb3IgdGhlIHB1cnBvc2UgcGVybWl0dGVkIGluIHRoZSBhcHBsaWNhYmxlIENlcnRpZmljYXRlIFBvbGljeS4gTGltaXRlZCBsaWFiaWxpdHkgYXBwbGllcyAtIHJlZmVyIHRvIHRoZSBDZXJ0aWZpY2F0ZSBQb2xpY3kuMB0GCCsGAQUFBwIBFhF3d3cudGVzdGFicmNhLmNvbTAXBgYqJAGCTQEEDRYLNjcwOTQ1NDQ1MTkwDgYDVR0PAQH/BAQDAgTwMB8GA1UdIwQYMBaAFIl9rmp4ImmE7AdpVMbA9GOX4c9OMB0GA1UdDgQWBBQ6EUaBFC2k+XOlBdeo8O+V1f7FrTANBgkqhkiG9w0BAQsFAAOCAQEADOxDhFSHnp7SAvBuZiN3G2j8xs8x8MYKIfhGlzC4/DfR2v1itLcyC6goyf/ysLS0J8UrEUppMlhgbF/nMlncgPY0cfaTvPF4tmymKLsuWzRvcOp2Dgl95TczT7FwCdOsissJcTzXv0/LZfrFOA86HxMM46er5EgCr3J9nT5V60sO5S1x4VBRTpz4IMjU8NVEx0AlbrIHUj0nbHFM2ygoFzMMnSt6PEF/udo2/uPYyHJgwgz/OlTT4pfCzrEpd2gGRbrQjpVKLw0IW4acYX6Twh9m4Ojtkg4eK9Yve236HsfalgPnFYrbO3gz0F4yNWIHZUA/ONucu45vNEX17FaitA==</wsse:BinarySecurityToken>
          <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
            <ds:SignedInfo>
              <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:CanonicalizationMethod>
              <ds:SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"></ds:SignatureMethod>
              <ds:Reference URI="#soapheader-1">
                <ds:Transforms>
                  <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:Transform>
                </ds:Transforms>
                <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
                <ds:DigestValue>3ijDRIrqAsqSkj36a5bxoEtjinA=</ds:DigestValue>
              </ds:Reference>
              <ds:Reference URI="#soapheader-2">
                <ds:Transforms>
                  <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:Transform>
                </ds:Transforms>
                <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
                <ds:DigestValue>72NyoZmhYL92Qe2RjYLpwwmD9ms=</ds:DigestValue>
              </ds:Reference>
              <ds:Reference URI="#soapbody">
                <ds:Transforms>
                  <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:Transform>
                </ds:Transforms>
                <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
                <ds:DigestValue>lVf6gg3lYIQe95cm3r25FwQ7j6U=</ds:DigestValue>
              </ds:Reference>
              <ds:Reference URI="cid:Attachment">
                <ds:Transforms>
                  <ds:Transform Algorithm="http://docs.oasis-open.org/wss/oasis-wss-SwAProfile-1.1#Attachment-Content-Signature-Transform"></ds:Transform>
                </ds:Transforms>
                <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
                <ds:DigestValue>1Eyo0FbgBUa7drjHzI19b2ZZ4qU=</ds:DigestValue>
              </ds:Reference>
            </ds:SignedInfo>
            <ds:SignatureValue>J8SaS5YzUELiX/gpvwAVRhTzHuqb0XXzllqqWWippVOA1u4xhUW/01eBSopmMDoyLorNeegKwtMJ cMzyjadHjPHUaaST59RWTrUuiHxrPbtWUPSCN2AJWHAH6+cGDXN8riUoXTFL01JlZxbGAFsTzStn wdC0CpYiQ4PvPDNS06c=</ds:SignatureValue>
            <ds:KeyInfo Id="KeyId-64E2EE8A9FF065ABBA15838856132051">
              <wsse:SecurityTokenReference xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="STRId-64E2EE8A9FF065ABBA15838856132062">
                <wsse:Reference URI="#signingCert" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"></wsse:Reference>
              </wsse:SecurityTokenReference>
            </ds:KeyInfo>
          </ds:Signature>
        </wsse:Security>
      </soapenv:Header>
      <soapenv:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="soapbody"></soapenv:Body>
    </soapenv:Envelope><Attachment>';
    public function index()
    {
        return view('frontend.payroll.data_send_ato.select_date');
    }
    public function filter(Request $request)
    {
        $date_from =  makeBackendCompatibleDate($request->date_from);
        $date_to =  makeBackendCompatibleDate($request->date_to);
        // return
        $payslips = Payslip::where('tran_date', '>=', $date_from)->where('tran_date', '<=', $date_to)->orderBy('id', 'desc')->get();
        return view('frontend.payroll.data_send_ato.index', compact('payslips'));
    }
    public function generate(Request $request)
    {
        $payslips = Payslip::whereIn('id', $request->check_id)->get();
        $etotalGross = $etotalPayg= 0;
        foreach ($payslips as $epayslip) {
            $etotalGross += $epayslip->pay_accum->gross;
            $etotalPayg += $epayslip->pay_accum->payg;
        }
        $client =  $payslips->first()->client;
        $data = [
            'client_id'    => $client->id,
            'pay_accum_id' => json_encode($request->check_id),
            'payment_date' => $payslips->first()->tran_date,
            'gross'        => $etotalGross,
            'payg'         => $etotalPayg
        ];
        Ato_data::create($data);
        $myXMLData = $this->xmlStart;
        $myXMLData .='
        <Record_Delimiter DocumentID="'.$client->abn_number.'-'.$payslips->first()->tran_date->format('dmy').'" DocumentType="PARENT" DocumentName="PAYEVNT" RelatedDocumentID=""/>
        <tns:PAYEVNT xsi:schemaLocation="http://www.sbr.gov.au/ato/payevnt ato.payevnt.0004.2020.01.00.xsd" xmlns:tns="http://www.sbr.gov.au/ato/payevnt" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <tns:Rp>
                <tns:SoftwareInformationBusinessManagementSystemId>125b8925-9a97-4178-8dee-78d3fdeb0437</tns:SoftwareInformationBusinessManagementSystemId>
                <tns:AustralianBusinessNumberId>'.$client->abn_number.'</tns:AustralianBusinessNumberId>
                <tns:WithholdingPayerNumberId>123456789<tns:WithholdingPayerNumberId/>
                <tns:OrganisationDetailsOrganisationBranchC>'.$client->branch.'</tns:OrganisationDetailsOrganisationBranchC>
                <tns:OrganisationName>
                    <tns:DetailsOrganisationalNameT>'.($client->company??$client->fullname).'</tns:DetailsOrganisationalNameT>
                    <tns:PersonUnstructuredNameFullNameT>'.$client->director_name.'</tns:PersonUnstructuredNameFullNameT>
                </tns:OrganisationName>
                <tns:ElectronicContact>
                    <tns:ElectronicMailAddressT>'.$client->email.'</tns:ElectronicMailAddressT>
                    <tns:TelephoneMinimalN>'.$client->phone.'</tns:TelephoneMinimalN>
                </tns:ElectronicContact>
                <tns:AddressDetailsPostal>
                    <tns:PostcodeT>'.$client->post.'</tns:PostcodeT>
                    <tns:CountryC>au</tns:CountryC>
                </tns:AddressDetailsPostal>
                <tns:Payroll>
                    <tns:PaymentRecordTransactionD>'.$payslips->first()->tran_date->format('Y-m-d').'</tns:PaymentRecordTransactionD>
                    <tns:InteractionRecordCt>'.$client->id.'</tns:InteractionRecordCt>
                    <tns:MessageTimestampGenerationDt>'.now().'</tns:MessageTimestampGenerationDt>
                    <tns:InteractionTransactionId>'.rand(111,9999999999).'</tns:InteractionTransactionId>
                    <tns:AmendmentI>true</tns:AmendmentI>
                    <tns:IncomeTaxAndRemuneration>
                        <tns:PayAsYouGoWithholdingTaxWithheldA>'.number_format($etotalPayg,2).'</tns:PayAsYouGoWithholdingTaxWithheldA>
                        <tns:TotalGrossPaymentsWithholdingA>'.number_format($etotalGross,2).'</tns:TotalGrossPaymentsWithholdingA>
                        <tns:ChildSupportGarnisheeA>300.43</tns:ChildSupportGarnisheeA>
                        <tns:ChildSupportWithholdingA>200.25</tns:ChildSupportWithholdingA>
                    </tns:IncomeTaxAndRemuneration>
                </tns:Payroll>
                <tns:Declaration>
                    <tns:SignatoryIdentifierT>'.$client->director_name.'</tns:SignatoryIdentifierT>
                    <tns:SignatureD>'.date('Y-m-d').'</tns:SignatureD>
                    <tns:StatementAcceptedI>true</tns:StatementAcceptedI>
                </tns:Declaration>
            </tns:Rp>
            <tns:Int>
                <tns:AustralianBusinessNumberId>'.$client->tax_file_number.'</tns:AustralianBusinessNumberId>
                <tns:TaxAgentNumberId>'.$client->tax_file_number.'</tns:TaxAgentNumberId>
                <tns:PersonUnstructuredNameFullNameT>'.$client->agent_name.'</tns:PersonUnstructuredNameFullNameT>
                <tns:ElectronicContact>
                    <tns:ElectronicMailAddressT>'.$client->email.'</tns:ElectronicMailAddressT>
                    <tns:TelephoneMinimalN>'.$client->phone.'</tns:TelephoneMinimalN>
                </tns:ElectronicContact>
                <tns:Declaration>
                    <tns:SignatoryIdentifierT>'.$client->agent_name.'</tns:SignatoryIdentifierT>
                    <tns:SignatureD>'.date('Y-m-d').'</tns:SignatureD>
                    <tns:StatementAcceptedI>true</tns:StatementAcceptedI>
                </tns:Declaration>
            </tns:Int>
        </tns:PAYEVNT>
        ';
        foreach ($payslips as $payslip) {
            $employee =  $payslip->employee;
            $pay_accum =  $payslip->pay_accum;
            $myXMLData .= '
            <Record_Delimiter DocumentID="'.$client->abn_number.'-'.$payslips->first()->tran_date->format('dmy').str_pad(substr("$employee->id ", -6, -1), 5, 0, STR_PAD_LEFT).'.'.str_pad(substr("$employee->id ", -5, -1), 4, 0, STR_PAD_LEFT).'" DocumentType="CHILD" DocumentName="PAYEVNTEMP" RelatedDocumentID="'.$client->abn_number.'-'.$payslips->first()->tran_date->format('dmy').'"/>
            <tns:PAYEVNTEMP xsi:schemaLocation="http://www.sbr.gov.au/ato/payevntemp ato.payevntemp.0004.2020.01.00.xsd" xmlns:tns="http://www.sbr.gov.au/ato/payevntemp" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <tns:Payee>
                    <tns:Identifiers>
                        <tns:TaxFileNumberId>'.$employee->tax_number.'</tns:TaxFileNumberId>
                        <tns:AustralianBusinessNumberId>93603870438</tns:AustralianBusinessNumberId>
                        <tns:EmploymentPayrollNumberId>'.$employee->id.$employee->client_id.'</tns:EmploymentPayrollNumberId>
                        <tns:PreviousPayrollIDEmploymentPayrollNumberId>50230<tns:PreviousPayrollIDEmploymentPayrollNumberId>
                    </tns:Identifiers>
                    <tns:PersonNameDetails>
                        <tns:FamilyNameT>'.$employee->first_name.'</tns:FamilyNameT>
                        <tns:GivenNameT>'.$employee->last_name.'</tns:GivenNameT>
                        <tns:OtherGivenNameT>Matthew</tns:OtherGivenNameT>
                    </tns:PersonNameDetails>
                    <tns:PersonDemographicDetailsBirth>
                        <tns:Dm>'.$employee->dob->format('d').'</tns:Dm>
                        <tns:M>'.$employee->dob->format('m').'</tns:M>
                        <tns:Y>'.$employee->dob->format('Y').'</tns:Y>
                    </tns:PersonDemographicDetailsBirth>
                    <tns:AddressDetails>
                        <tns:Line1T>'.$employee->address.'</tns:Line1T>
                        <tns:LocalityNameT>'.$employee->city.'</tns:LocalityNameT>
                        <tns:StateOrTerritoryC>'.$employee->state.'</tns:StateOrTerritoryC>
                        <tns:PostcodeT>'.$employee->post.'</tns:PostcodeT>
                        <tns:CountryC>au</tns:CountryC>
                    </tns:AddressDetails>
                    <tns:ElectronicContact>
                        <tns:ElectronicMailAddressT>'.$client->emaill.'</tns:ElectronicMailAddressT>
                        <tns:TelephoneMinimalN>'.$client->phone.'</tns:TelephoneMinimalN>
                    </tns:ElectronicContact>
                    <tns:EmployerConditions>
                        <tns:EmploymentStartD>'.$employee->start_date.'</tns:EmploymentStartD>
                        <tns:EmploymentEndD>'.($employee->term_date??($pay_accum->financial_year+1 .'-06-30')) .'</tns:EmploymentEndD>
                        <tns:PaymentBasisC>F</tns:PaymentBasisC>
                        <tns:CessationTypeC>V</tns:CessationTypeC>
                        <tns:TaxTreatmentC>RDXXXX</tns:TaxTreatmentC>
                        <tns:TaxOffsetClaimTotalA>330</tns:TaxOffsetClaimTotalA>
                    </tns:EmployerConditions>
                    <tns:PayrollPeriod>
                        <tns:StartD>'.$employee->payperiod_start.'</tns:StartD>
                        <tns:EndD>'.$employee->payperiod_end.'</tns:EndD>
                        <tns:RemunerationPayrollEventFinalI>false</tns:RemunerationPayrollEventFinalI>
                        <tns:RemunerationCollection>
                        <tns:Remuneration>
                            <tns:IncomeStreamTypeC>SAW</tns:IncomeStreamTypeC>
                            <tns:AddressDetailsCountryC>au</tns:AddressDetailsCountryC>
                            <tns:IncomeTaxPayAsYouGoWithholdingTaxWithheldA>200.56</tns:IncomeTaxPayAsYouGoWithholdingTaxWithheldA>
                            <tns:IncomeTaxForeignWithholdingA>150.99</tns:IncomeTaxForeignWithholdingA>
                            <tns:IndividualNonBusinessExemptForeignEmploymentIncomeA>240.06</tns:IndividualNonBusinessExemptForeignEmploymentIncomeA>
                            <tns:GrossA>25310.04</tns:GrossA>
                            <tns:PaidLeaveCollection>
                                <tns:PaidLeave>
                                    <tns:TypeC>C</tns:TypeC>
                                    <tns:PaymentA>756.75</tns:PaymentA>
                                </tns:PaidLeave>
                            </tns:PaidLeaveCollection>
                            <tns:AllowanceCollection>
                                <tns:Allowance>
                                    <tns:TypeC>CD</tns:TypeC>
                                    <tns:EmploymentAllowancesA>1.55</tns:EmploymentAllowancesA>
                                </tns:Allowance>
                            </tns:AllowanceCollection>
                            <tns:AllowanceCollection>
                                <tns:Allowance>
                                    <tns:TypeC>OD</tns:TypeC>
                                    <tns:EmploymentAllowancesA>6.95</tns:EmploymentAllowancesA>
                                    <tns:OtherAllowanceTypeDe>Other</tns:OtherAllowanceTypeDe>
                                </tns:Allowance>
                            </tns:AllowanceCollection>
                            <tns:OvertimePaymentA>80.22</tns:OvertimePaymentA>
                            <tns:GrossBonusesAndCommissionsA>200.34</tns:GrossBonusesAndCommissionsA>
                            <tns:GrossDirectorsFeesA>500.59</tns:GrossDirectorsFeesA>
                            <tns:IndividualNonBusinessCommunityDevelopmentEmploymentProjectA>180.02</tns:IndividualNonBusinessCommunityDevelopmentEmploymentProjectA>
                            <tns:SalarySacrificeCollection>
                                <tns:SalarySacrifice>
                                    <tns:TypeC>S</tns:TypeC>
                                    <tns:PaymentA>1.75</tns:PaymentA>
                                </tns:SalarySacrifice>
                            </tns:SalarySacrificeCollection>
                            <tns:LumpSumCollection>
                                <tns:LumpSum>
                                    <tns:TypeC>R</tns:TypeC>
                                    <tns:PaymentsA>100.01</tns:PaymentsA>
                                </tns:LumpSum>
                            </tns:LumpSumCollection>
                            <tns:EmploymentTerminationPaymentCollection>
                                <tns:EmploymentTerminationPayment>
                                    <tns:IncomeTaxPayAsYouGoWithholdingTypeC>B</tns:IncomeTaxPayAsYouGoWithholdingTypeC>
                                    <tns:IncomeD>2020-08-18</tns:IncomeD>
                                    <tns:IncomeTaxFreeA>784</tns:IncomeTaxFreeA>
                                    <tns:IncomeTaxableA>55</tns:IncomeTaxableA>
                                    <tns:IncomePayAsYouGoWithholdingA>9854.55</tns:IncomePayAsYouGoWithholdingA>
                                </tns:EmploymentTerminationPayment>
                            </tns:EmploymentTerminationPaymentCollection>
                        </tns:Remuneration>
                        </tns:RemunerationCollection>
                        <tns:DeductionCollection>
                        <tns:Deduction>
                            <tns:RemunerationTypeC>F</tns:RemunerationTypeC>
                            <tns:RemunerationA>26.30</tns:RemunerationA>
                        </tns:Deduction>
                        </tns:DeductionCollection>
                        <tns:SuperannuationContributionCollection>
                        <tns:SuperannuationContribution>
                            <tns:EntitlementTypeC>L</tns:EntitlementTypeC>
                            <tns:EmployerContributionsYearToDateA>2257.45</tns:EmployerContributionsYearToDateA>
                        </tns:SuperannuationContribution>
                        </tns:SuperannuationContributionCollection>
                        <tns:IncomeFringeBenefitsReportableCollection>
                        <tns:IncomeFringeBenefitsReportable>
                            <tns:FringeBenefitsReportableExemptionC>X</tns:FringeBenefitsReportableExemptionC>
                            <tns:A>123.45</tns:A>
                        </tns:IncomeFringeBenefitsReportable>
                        </tns:IncomeFringeBenefitsReportableCollection>
                    </tns:PayrollPeriod>
                </tns:Payee>
            </tns:PAYEVNTEMP>
            ';
        }
        $timestamppp  =  date('Y_m_d_H_i_s');
        $namee 		= 'ATO_'.$timestamppp.'.xml';
        header("Content-Description: File Transfer");
        header("Content-Type: text/xml");
        header("Content-Disposition: attachment; filename=".$namee);
        echo $myXMLData;
        exit();
    }

    public function list()
    {
        $atos = Ato_data::all();
        return view('frontend.payroll.update_stp_data_to_ato.update_ato', compact('atos'));
    }

    public function show(Ato_data $ato)
    {
        $payslips = Payslip::whereIn('id', json_decode($ato->pay_accum_id))->get();
        return view('frontend.payroll.update_stp_data_to_ato.index',compact('payslips','ato'));
    }

    public function edit(Ato_data $ato)
    {
        //
    }

    public function update(Request $request, Ato_data $ato)
    {
        $payslips = Payslip::whereIn('id', $request->check_id)->get();
        $etotalGross = $etotalPayg= 0;
        foreach ($payslips as $epayslip) {
            $etotalGross += $epayslip->pay_accum->gross;
            $etotalPayg += $epayslip->pay_accum->payg;
        }
        $client =  $payslips->first()->client;
        $ato->update(['payrun'=>1]);
        if($request->payrun == 1){
            $payrun = 'true';
        }else{
            $payrun = 'false';
        }
        $myXMLData =
                '<Record_Delimiter DocumentID="'.$client->abn_number.'-'.$payslips->first()->tran_date->format('dmy').'" DocumentType="PARENT" DocumentName="PAYEVNT" RelatedDocumentID=""/>
				  <tns:PAYEVNT xsi:schemaLocation="http://www.sbr.gov.au/ato/payevnt ato.payevnt.0003.2018.01.00.xsd" xmlns:tns="http://www.sbr.gov.au/ato/payevnt" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
					 <tns:Rp>
						<tns:SoftwareInformationBusinessManagementSystemId>9319b030-3093-4212-93fa-999f3e0ed744</tns:SoftwareInformationBusinessManagementSystemId>
						<tns:AustralianBusinessNumberId>'.$client->abn_number.'</tns:AustralianBusinessNumberId>
						<tns:OrganisationDetailsOrganisationBranchC>'.$client->branch.'</tns:OrganisationDetailsOrganisationBranchC>
						<tns:OrganisationName>
						   <tns:DetailsOrganisationalNameT>'.($client->company??$client->fullname).'</tns:DetailsOrganisationalNameT>
						   <tns:PersonUnstructuredNameFullNameT>'.$client->director_name.'</tns:PersonUnstructuredNameFullNameT>
						</tns:OrganisationName>
						<tns:ElectronicContact>
						   <tns:ElectronicMailAddressT>'.$client->email.'</tns:ElectronicMailAddressT>
						   <tns:TelephoneMinimalN>'.$client->phone.'</tns:TelephoneMinimalN>
						</tns:ElectronicContact>
						<tns:AddressDetailsPostal>
						   <tns:Line1T>'.$client->street_address.'</tns:Line1T>
						   <tns:LocalityNameT>'.$client->suburb.'</tns:LocalityNameT>
						   <tns:StateOrTerritoryC>'.$client->state.'</tns:StateOrTerritoryC>
						   <tns:PostcodeT>'.$client->post_code.'</tns:PostcodeT>
						   <tns:CountryC>'.$client->country.'</tns:CountryC>
						</tns:AddressDetailsPostal>
						<tns:Payroll>
						   <tns:PaymentRecordTransactionD>'.$payslips->first()->tran_date->format('Y-m-d').'</tns:PaymentRecordTransactionD>
						   <tns:InteractionRecordCt>'.$client->id.'</tns:InteractionRecordCt>
						   <tns:MessageTimestampGenerationDt>'.now().'</tns:MessageTimestampGenerationDt>
						   <tns:InteractionTransactionId>702</tns:InteractionTransactionId>
						   <tns:AmendmentI>'.$payrun.'</tns:AmendmentI>
						   <tns:IncomeTaxAndRemuneration>
							  <tns:PayAsYouGoWithholdingTaxWithheldA>'.$etotalPayg.'</tns:PayAsYouGoWithholdingTaxWithheldA>
							  <tns:TotalGrossPaymentsWithholdingA>'.$etotalGross.'</tns:TotalGrossPaymentsWithholdingA>
						   </tns:IncomeTaxAndRemuneration>
						</tns:Payroll>
						<tns:Declaration>
						   <tns:SignatoryIdentifierT>'.$client->director_name.'</tns:SignatoryIdentifierT>
						   <tns:SignatureD>'.date('Y-m-d').'</tns:SignatureD>
						   <tns:StatementAcceptedI>true</tns:StatementAcceptedI>
						</tns:Declaration>
					 </tns:Rp>
					<tns:Int>
					<tns:AustralianBusinessNumberId>'.$client->tax_file_number.'</tns:AustralianBusinessNumberId>
						<tns:TaxAgentNumberId>'.$client->tax_file_number.'</tns:TaxAgentNumberId>
						<tns:PersonUnstructuredNameFullNameT>'.$client->agent_name.'</tns:PersonUnstructuredNameFullNameT>
						<tns:ElectronicContact>
						   <tns:ElectronicMailAddressT>'.$client->email.'</tns:ElectronicMailAddressT>
						   <tns:TelephoneMinimalN>'.$client->phone.'</tns:TelephoneMinimalN>
						</tns:ElectronicContact>
						<tns:Declaration>
						   <tns:SignatoryIdentifierT>'.$client->agent_name.'</tns:SignatoryIdentifierT>
						   <tns:SignatureD>'.date('Y-m-d').'</tns:SignatureD>
						   <tns:StatementAcceptedI>true</tns:StatementAcceptedI>
						</tns:Declaration>
					 </tns:Int>
					</tns:PAYEVNT>';
        foreach ($payslips as $payslip) {
            $employee =  $payslip->employee;
            $pay_accum =  $payslip->pay_accum;
            $myXMLData .= '
            		<Record_Delimiter DocumentID="'.$client->abn_number.'-'.$payslips->first()->tran_date->format('dmy').'-00'.$employee->id.'.0'.$employee->id.'" DocumentType="CHILD" DocumentName="PAYEVNTEMP" RelatedDocumentID="'.$client->abn_number.'-'.$payslips->first()->tran_date->format('dmy').'"/><tns:PAYEVNTEMP xsi:schemaLocation="http://www.sbr.gov.au/ato/payevntemp ato.payevntemp.0003.2019.01.00.xsd" xmlns:tns="http://www.sbr.gov.au/ato/payevntemp" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            		   <tns:Payee>
            			  <tns:Identifiers>
            				 <tns:TaxFileNumberId>'.$employee->tax_number.'</tns:TaxFileNumberId>
            				 <tns:EmploymentPayrollNumberId>'.$employee->id.$employee->client_id.'</tns:EmploymentPayrollNumberId>
            			  </tns:Identifiers>
            			  <tns:PersonNameDetails>
            				 <tns:FamilyNameT>'.$employee->first_name.'</tns:FamilyNameT>
            				 <tns:GivenNameT>'.$employee->last_name.'</tns:GivenNameT>
            			  </tns:PersonNameDetails>
            			  <tns:PersonDemographicDetails>
            				 <tns:BirthDm>'.$employee->dob->format('d').'</tns:BirthDm>
            				 <tns:BirthM>'.$employee->dob->format('m').'</tns:BirthM>
            				 <tns:BirthY>'.$employee->dob->format('Y').'</tns:BirthY>
            			  </tns:PersonDemographicDetails>
            			  <tns:AddressDetails>
            				 <tns:Line1T>'.$employee->address.'</tns:Line1T>
            				 <tns:LocalityNameT>'.$employee->city.'</tns:LocalityNameT>
            				 <tns:StateOrTerritoryC>'.$employee->state.'</tns:StateOrTerritoryC>
            				 <tns:PostcodeT>'.$employee->post_code.'</tns:PostcodeT>
            				 <tns:CountryC>au</tns:CountryC>
            			  </tns:AddressDetails>
            			  <tns:ElectronicContact>
            				 <tns:ElectronicMailAddressT>'.$client->emaill.'</tns:ElectronicMailAddressT>
            			  </tns:ElectronicContact>
            			  <tns:EmployerConditions>
            				 <tns:EmploymentStartD>'.$employee->start_date.'</tns:EmploymentStartD>
            				 <tns:EmploymentEndD>'.($employee->term_date??($pay_accum->financial_year+1 .'-06-30')) .'</tns:EmploymentEndD>
            			  </tns:EmployerConditions>
            			  <tns:RemunerationIncomeTaxPayAsYouGoWithholding>
            				 <tns:PayrollPeriod>
            					<tns:StartD>'.$employee->payperiod_start.'</tns:StartD>
            					<tns:EndD>'.$employee->payperiod_end.'</tns:EndD>
            					<tns:PayrollEventFinalI>'.$payrun.'</tns:PayrollEventFinalI>
            				 </tns:PayrollPeriod>
            				 <tns:IndividualNonBusiness>
            					<tns:GrossA>'.round($pay_accum->accum_gross).'</tns:GrossA>
            					<tns:TaxWithheldA>'.round($pay_accum->accum_payg).'</tns:TaxWithheldA>
            				 </tns:IndividualNonBusiness>
            				 <tns:AllowanceCollection>
            					<tns:Allowance>
            					   <tns:TypeC>Meals</tns:TypeC>
            					   <tns:IndividualNonBusinessEmploymentAllowancesA>0</tns:IndividualNonBusinessEmploymentAllowancesA>
            					</tns:Allowance>
            				 </tns:AllowanceCollection>
            				 <tns:DeductionCollection>
            					<tns:Deduction>
            					   <tns:TypeC>Fees</tns:TypeC>
            					   <tns:A>0</tns:A>
            					</tns:Deduction>
            				 </tns:DeductionCollection>
            				 <tns:SuperannuationContribution>
            					<tns:EmployerContributionsSuperannuationGuaranteeA>'.round($pay_accum->super).'</tns:EmployerContributionsSuperannuationGuaranteeA>
            				 </tns:SuperannuationContribution>
                          </tns:RemunerationIncomeTaxPayAsYouGoWithholding>
                            <tns:Onboarding>
                                <tns:TFND>
                                    <tns:PaymentArrangementTerminationC>T</tns:PaymentArrangementTerminationC>
                                    <tns:ResidencyTaxPurposesPersonStatusC>Resident</tns:ResidencyTaxPurposesPersonStatusC>
                                    <tns:PaymentArrangementPaymentBasisC>F</tns:PaymentArrangementPaymentBasisC>
                                    <tns:TaxOffsetClaimTaxFreeThresholdI>true</tns:TaxOffsetClaimTaxFreeThresholdI>
                                    <tns:IncomeTaxPayAsYouGoWithholdingStudyAndTrainingLoanRepaymentI>true</tns:IncomeTaxPayAsYouGoWithholdingStudyAndTrainingLoanRepaymentI>
                                    <tns:StudentLoanStudentFinancialSupplementSchemeI>true</tns:StudentLoanStudentFinancialSupplementSchemeI>
                                </tns:TFND>
                                <tns:Declaration>
                                    <tns:StatementAcceptedI>true</tns:StatementAcceptedI>
                                    <tns:SignatureD></tns:SignatureD>
                                </tns:Declaration>
                            </tns:Onboarding>
            		   </tns:Payee>
            		</tns:PAYEVNTEMP>';
        }
        $timestamppp  =  date('Y_m_d_H_i_s');
        $namee 		= 'ATO_'.$timestamppp.'.xml';
        header("Content-Description: File Transfer");
        header("Content-Type: text/xml");
        header("Content-Disposition: attachment; filename=".$namee);
        echo $myXMLData;
        exit();
    }

    public function delete(Ato_data $ato)
    {
        $ato->delete();
        toast('ATO STP Report Deleted','success');
        return back();
    }
}
