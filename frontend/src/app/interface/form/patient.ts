export interface Patient {
  id: number;
  first_name: string;
  middle_name: string;
  last_name: string;
  gender: string;
  birth_date: string;
  contact_number: string;
  idc_type_lookup_table_id: number;
  idc_type?: string;
  idc_number: string;
  name?: string;
}
