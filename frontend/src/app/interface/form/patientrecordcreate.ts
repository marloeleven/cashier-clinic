export interface PatientRecordCreate {
  id: number;
  reference_number: string;
  patient_id: number;
  discount_type_lookup_table_id: number;
  discount_value: number;
  original_amount: number;
  discounted_amount: number;
  total: number;
  attending_physician: string;
  comment: string;
  senior_citizen_discount: boolean;
}
