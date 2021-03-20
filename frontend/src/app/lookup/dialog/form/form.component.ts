import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import {
  MatDialogRef,
  MAT_DIALOG_DATA,
  MatOption
} from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { SaveService } from '@app/service/save.service';
import { Lookup } from '@app/interface/form/lookup';
import { LOOKUP_TYPES, toDropdownArray } from '@app/variables';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

  public LOOKUP_TYPES = toDropdownArray(LOOKUP_TYPES);
  public title: string;
  public type: string;
  public formData: Lookup = {
    id: 0,
    type: '',
    name: '',
    details: '',
    amount: 0,
    index: 0,
  };

  @ViewChild('lookupType') lookupType: MatOption;

  constructor(
    private saveService: SaveService,
    private snackbarHelper: SnackbarHelper,
    public dialogRef: MatDialogRef<any>,
    @Inject(MAT_DIALOG_DATA) public resourceData
  ) {
    const { data } = this.resourceData;
    const type = this.resourceData.type === 'edit' ? 'Edit' : 'New';
    this.title = `${this.resourceData.title} (${type})` ;
    this.type = this.resourceData.type;
    this.formData = data;
  }

  ngOnInit() {
  }

  submit(event, requestType) {
    event.preventDefault();

    const elements = event.target.elements;

    
    const id = elements.id.value;
    const type = this.lookupType.value;
    const name = elements.name.value;
    const details = elements.details.value;
    const amount = elements.amount.value;
    const index = elements.index.value;
    
    const data = {
      type,
      name,
      details,
      amount,
      index,
    };
    
    this.saveService.save({
      requestType,
      id,
      data,
      dialogRef: this.dialogRef,
      snackbarHelper: this.snackbarHelper,
      target: event.target,
      updateURL: `lookup/${id}`,
      createURL: 'lookup'
    });
  }

}
