import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatOption } from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { Category } from '@app/interface/form/category';
import { SaveService } from '@app/service/save.service';
import {
  PROCEDURE_TYPES,
  toDropdownArray,
  GENERIC,
  REPORT_TYPES
} from '@app/variables';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  public title: string;
  public type: string;
  public formData: Category = {
    id: 0,
    procedure_type: 'LABORATORY',
    name: '',
    report_type: GENERIC,
    alias: '',
    index: 0
  };
  public PROCEDURE_TYPES = PROCEDURE_TYPES;
  public reportTypesArray = toDropdownArray(REPORT_TYPES);
  public proceduresArray = toDropdownArray(PROCEDURE_TYPES);

  @ViewChild('procedure_type')
  procedure_type: MatOption;

  @ViewChild('report_type')
  report_type: MatOption;

  constructor(
    private saveService: SaveService,
    private snackbarHelper: SnackbarHelper,
    public dialogRef: MatDialogRef<any>,
    @Inject(MAT_DIALOG_DATA) public resourceData
  ) {}

  ngOnInit() {
    const { data } = this.resourceData;
    const type = this.resourceData.type === 'edit' ? 'Edit' : 'New';
    this.title = `${this.resourceData.title} (${type})`;
    this.type = this.resourceData.type;

    this.formData = data;

    if (this.type === 'new') {
      Object.assign(this.formData, {
        procedure_type: 'LABORATORY'
      });
    }
  }

  submit(event, requestType) {
    event.preventDefault();

    const elements = event.target.elements;

    const id = elements.id.value;
    const procedure_type = this.procedure_type.value;
    const name = elements.name.value;
    const alias = elements.alias.value;
    const report_type = this.report_type.value;
    const index = elements.index.value;

    const data = {
      procedure_type,
      name,
      alias,
      report_type,
      index
    };

    this.saveService.save({
      requestType,
      id,
      data,
      dialogRef: this.dialogRef,
      snackbarHelper: this.snackbarHelper,
      target: event.target,
      updateURL: `procedure/category/${id}`,
      createURL: 'procedure/category'
    });
  }
}
