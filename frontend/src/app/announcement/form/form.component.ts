import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatOption } from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { SaveService } from '@app/service/save.service';
import { Announcement } from '@app/interface/form/announcement';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  public title: string;
  public type: string;
  public formData: Announcement = {
    id: 0,
    title: '',
    body: ''
  };

  constructor(
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
  }

  ngOnInit() {}

  submit(event, requestType) {
    event.preventDefault();

    const elements = event.target.elements;

    const id = elements.id.value;
    const title = elements.title.value;
    const body = elements.body.value;

    const data = {
      title,
      body
    };

    this.saveService.save({
      requestType,
      id,
      data,
      dialogRef: this.dialogRef,
      snackbarHelper: this.snackbarHelper,
      target: event.target,
      updateURL: `announcement/${id}`,
      createURL: 'announcement'
    });
  }
}
