import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { Format } from '@app/helper/format';
import { PROCEDURE_TYPES, DEFAULT_URL } from '@app/variables';
import { categorizeProcedures } from '@app/helper/function';

interface Record {
  cashier: object;
  created_at: string;
  original_amount: number;
  discounted_amount: number;
  discount_value: number;
  discount_type: number;
  total: number;
  attending_physician: string;
}

const sortByIndex = (a, b) => (a.index <= b.index ? -1 : 1);

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css'],
})
export class FormComponent implements OnInit {
  public PROCEDURE_TYPES = PROCEDURE_TYPES;
  public Format = Format;
  public title: string;
  public formData: Record = {
    cashier: { full_name: '' },
    created_at: '',
    original_amount: 0,
    discounted_amount: 0,
    discount_value: 0,
    discount_type: 0,
    total: 0,
    attending_physician: '',
  };
  public proceduresArray = [];

  private id = 0;

  constructor(
    private http: HttpService,
    public dialogRef: MatDialogRef<any>,
    @Inject(MAT_DIALOG_DATA) public resourceData
  ) {
    const { data } = this.resourceData;
    this.title = `${this.resourceData.title}`;

    this.formData = data;

    this.id = data.id;

    this.title = `Patient Record #${data.reference_number}`;

    this.http
      .get(`patient/record/${data.id}/procedure`)
      .then(({ procedures }) => {
        const proceduresFlatten = procedures.map(({ amount, procedure }) => {
          procedure.amount = amount;
          return procedure;
        });

        this.proceduresArray = categorizeProcedures(proceduresFlatten);
      });
  }

  ngOnInit() {}

  discountDisplay({ discount }) {
    if (discount) {
      return `${discount.name} (${discount.amount}%)`;
    }
    return '';
  }

  print() {
    window.open(`${DEFAULT_URL}report/pdf/${this.id}`, '_blank');
  }
}
