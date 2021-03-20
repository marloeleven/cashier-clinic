import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatOption } from '@angular/material';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { SaveService } from '@app/service/save.service';
import { USER_TYPES, GENDER_TYPES, toDropdownArray } from '@app/variables';
import { User } from '@app/interface/form/user';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  public title: string;
  public type: string;
  public formData: User = {
    id: 0,
    username: '',
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    type: '',
    gender: ''
  };
  public USER_TYPES = toDropdownArray(USER_TYPES);
  public GENDER_TYPES = toDropdownArray(GENDER_TYPES);

  @ViewChild('gender') gender: MatOption;
  @ViewChild('userType') userType: MatOption;

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
    const username = elements.username.value;
    const first_name = elements.first_name.value;
    const middle_name = elements.middle_name.value;
    const last_name = elements.last_name.value;
    const email = elements.email.value;
    const gender = this.gender.value;
    const type = this.userType.value;

    const data = {
      username,
      first_name,
      middle_name,
      last_name,
      email,
      type,
      gender
    };

    this.saveService.save({
      requestType,
      id,
      data,
      dialogRef: this.dialogRef,
      snackbarHelper: this.snackbarHelper,
      target: event.target,
      updateURL: `user/admin/${id}`,
      createURL: 'user'
    });
  }
}
