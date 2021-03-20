import { Component, OnInit } from '@angular/core';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import { ActivatedRoute } from '@angular/router';
import { MatDialog } from '@angular/material';
import { FormBuilder, FormGroup, ValidationErrors } from '@angular/forms';
import { HttpService } from '@app/service/http.service';
import { Lookup } from '@app/interface/form/lookup';
import { Patient } from '@app/interface/form/patient';
import { PatientRecordCreate } from '@app/interface/form/patientrecordcreate';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { PROCEDURE_TYPES } from '@app/variables';
import { Format } from '@app/helper/format';
import { getObjectFromArray, isSenior } from '@app/helper/function';
import { MALE, DEFAULT_URL } from '@app/variables';
import Message from '@app/message';

const getKeys = Obj => Object.keys(Obj);

const sortByIndex = (a, b) => (a.index < b.index ? -1 : 1);

const filterDrName = (name: string) =>
  name.toLowerCase().replace(/[\s\.]*/gi, '');

@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.css']
})
export class CreateComponent implements OnInit {
  public infoFormGroup: FormGroup;
  public proceduresFormGroup: FormGroup;

  private patientId;
  public Format = Format;
  public PROCEDURE_TYPES = PROCEDURE_TYPES;
  public PROCEDURE_TYPES_KEYS = Object.keys(PROCEDURE_TYPES);

  public discountArray = [];
  public proceduresArray = [];
  public selectedProcedures = [];
  public categorizedProcedures = {};

  public getKeys = getKeys;

  public selectedDiscount: Lookup = {
    id: 0,
    type: 'DISCOUNT',
    name: '',
    details: '',
    amount: 0,
    index: 0
  };

  public discountedAmount = 0;
  public originalAmount = 0;
  public totalAmount = 0;

  public isSenior = false;

  public patientInfo: Patient = {
    id: 0,
    first_name: '',
    middle_name: '',
    last_name: '',
    gender: MALE,
    birth_date: Format.date(Date.now()),
    contact_number: '',
    idc_type_lookup_table_id: 0,
    idc_type: '',
    idc_number: '',
    name: ''
  };

  public patientRecord: PatientRecordCreate = {
    id: 0,
    reference_number: '',
    patient_id: 0,
    discount_type_lookup_table_id: 0,
    discount_value: 0,
    original_amount: 0,
    discounted_amount: 0,
    total: 0,
    attending_physician: '',
    comment: '',
    senior_citizen_discount: false
  };

  public attending_physicians = [];
  filteredOptions: Observable<string[]>;
  myControl = new FormControl();

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private http: HttpService,
    private snackbarHelper: SnackbarHelper,
    public dialog: MatDialog
  ) {}

  ngOnInit() {
    this.patientId = this.route.snapshot.params.id;

    this.http.get(`patient/${this.patientId}`).then(data => {
      Object.assign(this.patientInfo, data, {
        name: Format.fullName(data)
      });

      this.isSenior = isSenior(new Date(this.patientInfo.birth_date));

      if (this.isSenior) {
        this.patientRecord.senior_citizen_discount = true;
      }
    });

    this.http.get('lookup/search/DISCOUNT').then(data => {
      this.discountArray = [{ name: '' }]
        .concat(data.filter(({ disabled }) => !disabled))
        .sort(sortByIndex);
    });

    this.http.get('procedure/category/forlisting').then(data => {
      this.proceduresArray = data;
    });

    this.http.get('doctors').then(data => {
      this.attending_physicians = data;
    });

    this.filteredOptions = this.myControl.valueChanges.pipe(
      startWith(''),
      map(value => this._filter(value))
    );

    /* VALIDATION */

    // INFORMATION

    this.infoFormGroup = this.formBuilder.group({
      senior_citizen_discount: false,
      attending_physician: [''],
      reference_number: [''],
      discount_type_lookup_table_id: [0],
      comment: ['']
    });

    // PROCEDURES
  }

  private _filter(value: string): string[] {
    const filterValue = filterDrName(value);

    return this.attending_physicians.filter(
      ({ attending_physician: option }) =>
        filterDrName(option).indexOf(filterValue) !== -1
    );
  }

  getDiscountValue() {
    const discountObject = this.discountArray.find(
      discount =>
        discount.id == this.infoFormGroup.value.discount_type_lookup_table_id
    );

    return discountObject && discountObject.amount
      ? `(${discountObject.amount}%)`
      : '';
  }

  getFormValidationErrors() {
    Object.keys(this.infoFormGroup.controls).forEach(key => {
      const controlErrors: ValidationErrors = this.infoFormGroup.get(key)
        .errors;
      if (controlErrors != null) {
        Object.keys(controlErrors).forEach(keyError => {
          console.log(
            'Key control: ' + key + ', keyError: ' + keyError + ', err value: ',
            controlErrors[keyError]
          );
        });
      }
    });
  }

  private updateCategorizeSelected() {
    const selectedProceduresId = this.selectedProcedures.map(({ id }) => id);
    this.categorizedProcedures = this.PROCEDURE_TYPES_KEYS.reduce(
      (obj, key) => {
        const category = this.proceduresArray[key];

        if (category) {
          category.filter(sub => {
            const subSelected = sub.procedures.filter(({ id }) =>
              selectedProceduresId.includes(id)
            );

            if (subSelected.length) {
              if (obj[key] === undefined) {
                obj[key] = [];
              }

              var array = obj[key];

              array.push(Object.assign({}, sub, { procedures: subSelected }));

              return true;
            }

            return false;
          });
        }

        return obj;
      },
      {}
    );
  }

  private addSelect(data) {
    this.selectedProcedures.push(data);

    this.updateCategorizeSelected();
  }

  private removeSelected(data) {
    const index = this.selectedProcedures.findIndex(
      procedure => procedure.id === data.id
    );

    if (index !== -1) {
      this.selectedProcedures.splice(index, 1);
    }

    this.updateCategorizeSelected();
  }

  public compute() {
    // COMPANY DOESN'T HAVE discount

    const discounted = this.selectedProcedures.filter(
      ({ procedure_type }) => procedure_type !== 'SEND_IN'
    );
    const noDiscount = this.selectedProcedures.filter(
      ({ procedure_type }) => procedure_type === 'SEND_IN'
    );

    const discountPercent = this.getDiscountAmount();

    const discountedTotal = discounted.reduce(
      (total, data) => (total += data.amount),
      0
    );

    const noDiscountTotal = noDiscount.reduce(
      (total, data) => (total += data.amount),
      0
    );

    this.originalAmount = discountedTotal + noDiscountTotal;

    // SENIOR Discount application

    let totalDiscountedAmount = discountedTotal * discountPercent;
    if (this.isSenior && this.patientRecord.senior_citizen_discount) {
      const seniorsDiscountedTotal = discountedTotal / 1.12;

      const less = seniorsDiscountedTotal * discountPercent;

      totalDiscountedAmount = discountedTotal - (seniorsDiscountedTotal - less);
    }

    this.discountedAmount = totalDiscountedAmount;
    this.totalAmount = this.originalAmount - this.discountedAmount;
  }

  change(event, data) {
    if (event.target.checked) {
      this.addSelect(data);
      this.compute();
      return;
    }

    this.removeSelected(data);
    this.compute();
  }

  checkAll(event, data) {
    const checked = event.target.checked;
    data.forEach(input => {
      if (checked) {
        !this.isSelected(input.id) && this.addSelect(input);
        return;
      }

      this.removeSelected(input);
    });

    Array.from(
      event.target.closest('mat-expansion-panel').querySelectorAll('input')
    ).forEach((input: HTMLInputElement) => (input.checked = checked));

    this.compute();
  }

  isSelected(id) {
    return this.selectedProcedures.find(procedure => procedure.id === id);
  }

  discountChange({ value }) {
    this.patientRecord.discount_type_lookup_table_id = value;

    if (!value) {
      this.infoFormGroup.controls.comment.setErrors(null);
    }

    this.compute();
  }

  private getDiscountAmount() {
    const discount = getObjectFromArray(
      this.discountArray,
      this.patientRecord.discount_type_lookup_table_id
    );

    this.selectedDiscount = discount;

    if (discount && discount.amount) {
      return discount.amount / 100;
    }
    return 0;
  }

  save() {
    const proceduresArray = this.selectedProcedures;

    const data = Object.assign(
      {},
      {
        patient_id: this.patientId,
        reference_number: this.patientRecord.reference_number,
        discount_type_lookup_table_id: this.patientRecord
          .discount_type_lookup_table_id,
        attending_physician: this.patientRecord.attending_physician,
        comment: this.patientRecord.comment,
        procedures: proceduresArray.reduce((arr, obj) => {
          arr.push(obj.id);
          return arr;
        }, [])
      },
      this.infoFormGroup.value,
      {
        senior_citizen_discount: +this.patientRecord.senior_citizen_discount
      }
    );

    const dialogRef = this.dialog.open(DeleteComponent, {
      data: {
        title: 'Create Patient Record'
      }
    });

    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.http
          .post(`patient/${data.patient_id}/record`, data)
          .then(({ id }) => {
            this.patientRecord.id = id;
            this.snackbarHelper.show(Message.created, 'Close', {
              duration: 5000
            });
          })
          .catch(({ _body }) => {
            const { errors } = JSON.parse(_body);

            this.snackbarHelper.show(errors[0], 'Close', {
              duration: 5000
            });
          });
      }
    });
  }

  print() {
    window.open(`${DEFAULT_URL}report/pdf/${this.patientRecord.id}`, '_blank');
  }
}
