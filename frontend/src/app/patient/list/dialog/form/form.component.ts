import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatOption } from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { SaveService } from '@app/service/save.service';
import { GENDER_TYPES, MALE, toDropdownArray } from '@app/variables';
import { Patient } from '@app/interface/form/patient';

import { Format } from '@app/helper/format';
import { HttpService } from '@app/service/http.service';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css'],
})
export class FormComponent implements OnInit {
  public title: string;
  public type: string;
  public formData: Patient = {
    id: 0,
    first_name: '',
    middle_name: '',
    last_name: '',
    birth_date: Format.date(Date.now()),
    gender: '',
    contact_number: '',
    idc_type_lookup_table_id: 0,
    idc_number: '',
  };
  public GENDER_TYPES = toDropdownArray(GENDER_TYPES);
  public idTypeArray = [];

  @ViewChild('idc_type_lookup_table_id') lookupId: MatOption;
  @ViewChild('gender') gender: MatOption;

  constructor(
    private http: HttpService,
    private saveService: SaveService,
    private snackbarHelper: SnackbarHelper,
    public dialogRef: MatDialogRef<any>,
    @Inject(MAT_DIALOG_DATA) public resourceData
  ) {
    const { data } = this.resourceData;
    const type = this.resourceData.type === 'edit' ? 'Edit' : 'New';
    this.title = `${this.resourceData.title} (${type})`;
    this.type = this.resourceData.type;
    this.formData = data;

    if (this.type === 'new') {
      Object.assign(this.formData, {
        birth_date: Format.date(Date.now()),
        gender: MALE,
      });
    }
  }

  ngOnInit() {
    this.http.get('lookup/search/ID_TYPE').then(data => {
      this.idTypeArray = [{ value: 0, name: '' }].concat(
        data.map(obj => {
          obj.value = `${obj.id}`;
          return obj;
        })
      );
    });
  }

  submit(event, requestType) {
    event.preventDefault();

    const elements = event.target.elements;

    const id = elements.id.value;
    const first_name = elements.first_name.value;
    const middle_name = elements.middle_name.value;
    const last_name = elements.last_name.value;
    const gender = this.gender.value;
    const birth_date = elements.birth_date.value;
    const contact_number = elements.contact_number.value;
    const idc_type_lookup_table_id = this.lookupId.value;
    const idc_number = elements.idc_number.value;

    const data = {
      first_name,
      middle_name,
      last_name,
      gender,
      birth_date: Format.date(birth_date),
      contact_number,
      idc_type_lookup_table_id,
      idc_number,
    };

    this.saveService.save({
      requestType,
      id,
      data,
      dialogRef: this.dialogRef,
      snackbarHelper: this.snackbarHelper,
      target: event.target,
      updateURL: `patient/${id}`,
      createURL: 'patient',
    });
  }
}
