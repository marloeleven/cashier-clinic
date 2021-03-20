export interface Record {
  id: number;
  reference_number: number;
  cashier: string;
  supervisor: string;
  discount_type: string;
  discount_value: number;
  discount: number;
  total: number;
  disabled: number;
  cancelled: number;
}