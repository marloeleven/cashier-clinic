import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatOption } from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { Procedure } from '@app/interface/form/procedure';
import { SaveService } from '@app/service/save.service';
import { PROCEDURE_TYPES, toDropdownArray, LABORATORY } from '@app/variables';
import { categoriesArrange } from '@app/helper/function';
import { HttpService } from '@app/service/http.service';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  public title: string;
  public type: string;
  public formData: Procedure = {
    id: 0,
    procedure_type_categories_id: 0,
    name: '',
    details: '',
    amount: 0,
    sort: 0,
  };
  public PROCEDURE_TYPES = PROCEDURE_TYPES;

  public categoriesArray = [];

  @ViewChild('procedure_type_categories_id')
  procedure_type_categories_id: MatOption;

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

    this.http.get('procedure/category').then(data => {
      this.categoriesArray = categoriesArrange(
        data.filter(item => !item.disabled)
      );
    });

    this.formData = data;
  }

  ngOnInit() {}

  submit(event, requestType) {
    event.preventDefault();

    const elements = event.target.elements;

    const id = elements.id.value;
    const procedure_type_categories_id = this.procedure_type_categories_id
      .value;
    const name = elements.name.value;
    const details = elements.details.value;
    const amount = elements.amount.value;
    const sort = elements.sort.value;

    const data = {
      procedure_type_categories_id,
      name,
      details,
      amount,
      sort,
    };

    this.saveService.save({
      requestType,
      id,
      data,
      dialogRef: this.dialogRef,
      snackbarHelper: this.snackbarHelper,
      target: event.target,
      updateURL: `procedure/${id}`,
      createURL: 'procedure'
    });
  }
}
