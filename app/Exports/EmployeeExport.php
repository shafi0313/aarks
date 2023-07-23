<?php

namespace App\Exports;

use App\Models\Frontend\Payslip;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeExport implements FromCollection
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $payslips = Payslip::whereIn('id', $this->id)->get();
        foreach ($payslips as $payslip) {
            // return $payslip;
            $client =  $payslip->client;
            $employee =  $payslip->employee;
            $pay_accum =  $payslip->pay_accum;
            $myXMLData =
                '<Record_Delimiter DocumentID="16000023656-000012" DocumentType="PARENT" DocumentName="PAYEVNT" RelatedDocumentID=""/>
				  <tns:PAYEVNT xsi:schemaLocation="http://www.sbr.gov.au/ato/payevnt ato.payevnt.0003.2018.01.00.xsd" xmlns:tns="http://www.sbr.gov.au/ato/payevnt" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
					 <tns:Rp>
						<tns:SoftwareInformationBusinessManagementSystemId>9319b030-3093-4212-93fa-999f3e0ed744</tns:SoftwareInformationBusinessManagementSystemId>
						<tns:AustralianBusinessNumberId>'.$client->abn_number.'</tns:AustralianBusinessNumberId>
						<tns:OrganisationDetailsOrganisationBranchC>'.$client->branch.'</tns:OrganisationDetailsOrganisationBranchC>
						<tns:OrganisationName>
						   <tns:DetailsOrganisationalNameT>'.$client->company.'</tns:DetailsOrganisationalNameT>
						   <tns:PersonUnstructuredNameFullNameT>'.$client->contact_person.'</tns:PersonUnstructuredNameFullNameT>
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
						   <tns:PaymentRecordTransactionD>'.date('Y-m-d').'</tns:PaymentRecordTransactionD>
						   <tns:InteractionRecordCt>'.$payslip->id.'</tns:InteractionRecordCt>
						   <tns:MessageTimestampGenerationDt>'.now().'</tns:MessageTimestampGenerationDt>
						   <tns:InteractionTransactionId>453</tns:InteractionTransactionId>
						   <tns:AmendmentI>false</tns:AmendmentI>
						   <tns:IncomeTaxAndRemuneration>
							  <tns:PayAsYouGoWithholdingTaxWithheldA>'.$pay_accum->payg.'</tns:PayAsYouGoWithholdingTaxWithheldA>
							  <tns:TotalGrossPaymentsWithholdingA>'.$pay_accum->gross.'</tns:TotalGrossPaymentsWithholdingA>
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

            // foreach ($send_data as $v) {
            //     $myXMLData .= '
            // 		<Record_Delimiter DocumentID="16000023656-000012-00'.$v->send_data_id.'.0'.$v->send_data_id.'" DocumentType="CHILD" DocumentName="PAYEVNTEMP" RelatedDocumentID="16000023656-000012"/><tns:PAYEVNTEMP xsi:schemaLocation="http://www.sbr.gov.au/ato/payevntemp ato.payevntemp.0003.2019.01.00.xsd" xmlns:tns="http://www.sbr.gov.au/ato/payevntemp" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            // 		   <tns:Payee>
            // 			  <tns:Identifiers>
            // 				 <tns:TaxFileNumberId>'.$v->Tax_no.'</tns:TaxFileNumberId>
            // 				 <tns:EmploymentPayrollNumberId>'.$v->EmpPayrollId.'</tns:EmploymentPayrollNumberId>
            // 			  </tns:Identifiers>
            // 			  <tns:PersonNameDetails>
            // 				 <tns:FamilyNameT>'.$v->f_name.'</tns:FamilyNameT>
            // 				 <tns:GivenNameT>'.$v->l_name.'</tns:GivenNameT>
            // 			  </tns:PersonNameDetails>
            // 			  <tns:PersonDemographicDetails>
            // 				 <tns:BirthDm>'.$v->birth_d.'</tns:BirthDm>
            // 				 <tns:BirthM>'.$v->birth_m.'</tns:BirthM>
            // 				 <tns:BirthY>'.$v->birth_y.'</tns:BirthY>
            // 			  </tns:PersonDemographicDetails>
            // 			  <tns:AddressDetails>
            // 				 <tns:Line1T>'.$v->addrs.'</tns:Line1T>
            // 				 <tns:LocalityNameT>'.$v->subburb.'</tns:LocalityNameT>
            // 				 <tns:StateOrTerritoryC>'.$v->emp_state.'</tns:StateOrTerritoryC>
            // 				 <tns:PostcodeT>'.$v->post_code.'</tns:PostcodeT>
            // 				 <tns:CountryC>au</tns:CountryC>
            // 			  </tns:AddressDetails>
            // 			  <tns:ElectronicContact>
            // 				 <tns:ElectronicMailAddressT>'.$v->maill.'</tns:ElectronicMailAddressT>
            // 			  </tns:ElectronicContact>
            // 			  <tns:EmployerConditions>
            // 				 <tns:EmploymentStartD>'.$v->start_d.'</tns:EmploymentStartD>
            // 				 <tns:EmploymentEndD>'.$v->end_d.'</tns:EmploymentEndD>
            // 			  </tns:EmployerConditions>
            // 			  <tns:RemunerationIncomeTaxPayAsYouGoWithholding>
            // 				 <tns:PayrollPeriod>
            // 					<tns:StartD>'.$v->per_start_s.'</tns:StartD>
            // 					<tns:EndD>'.$v->per_start_e.'</tns:EndD>
            // 					<tns:PayrollEventFinalI>false</tns:PayrollEventFinalI>
            // 				 </tns:PayrollPeriod>
            // 				 <tns:IndividualNonBusiness>
            // 					<tns:GrossA>'.round($v->gross).'</tns:GrossA>
            // 					<tns:TaxWithheldA>'.round($v->TaxWithheldA).'</tns:TaxWithheldA>
            // 				 </tns:IndividualNonBusiness>
            // 				 <tns:AllowanceCollection>
            // 					<tns:Allowance>
            // 					   <tns:TypeC>Meals</tns:TypeC>
            // 					   <tns:IndividualNonBusinessEmploymentAllowancesA>0</tns:IndividualNonBusinessEmploymentAllowancesA>
            // 					</tns:Allowance>
            // 				 </tns:AllowanceCollection>
            // 				 <tns:DeductionCollection>
            // 					<tns:Deduction>
            // 					   <tns:TypeC>Fees</tns:TypeC>
            // 					   <tns:A>0</tns:A>
            // 					</tns:Deduction>
            // 				 </tns:DeductionCollection>
            // 				 <tns:SuperannuationContribution>
            // 					<tns:EmployerContributionsSuperannuationGuaranteeA>'.round($v->supperanuation).'</tns:EmployerContributionsSuperannuationGuaranteeA>
            // 				 </tns:SuperannuationContribution>
            // 			  </tns:RemunerationIncomeTaxPayAsYouGoWithholding>
            // 		   </tns:Payee>
            // 		</tns:PAYEVNTEMP>';
            // }
        }
    }
}
