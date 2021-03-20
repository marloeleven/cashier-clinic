import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { MatDialog } from '@angular/material';
import { HttpService } from '@app/service/http.service';
import { UserService } from '@app/service/user.service';
import { FormComponent as PatientRecordCreateComponent } from './form/form.component';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { Record } from '@app/interface/form/record';
import { PatientRecord } from '@app/interface/form/patientrecord';
import { SnackbarHelper } from '@app/helper/snackbar.helper';
import { Format } from '@app/helper/format';
import { GENDER_TYPES } from '@app/variables';
import BaseClass from '@app/component/BaseClass';

@Component({
  selector: 'app-record',
  templateUrl: './record.component.html',
  styleUrls: ['./record.component.css'],
})
export class RecordComponent extends BaseClass {
  public displayedColumns: string[] = [
    'id',
    'reference_number',
    'created_at',
    'discount_value',
    'total',
    'action',
  ];

  protected DeleteComponent = DeleteComponent;
  protected FormTitle = 'Patient Record';
  protected BaseUrl = 'patient/record';

  public GENDER_TYPES = GENDER_TYPES;
  public Format = Format;

  public patientId;

  public recordsArray: Record[] = [];

  public patientInfo: PatientRecord = {
    id: 0,
    first_name: '',
    middle_name: '',
    last_name: '',
    contact_number: '',
    gender: 'male',
    birth_date: Format.date(Date.now()),
    idc_type_lookup_table_id: 0,
    idc_type: { name: '' },
    idc_number: '',
    name: '',
    ageDisplay: '',
  };

  constructor(
    private route: ActivatedRoute,
    public userService: UserService,
    protected http: HttpService,
    protected snackbarHelper: SnackbarHelper,
    protected dialog: MatDialog
  ) {
    super(http, snackbarHelper, dialog);
  }

  ngOnInit() {
    this.patientId = this.route.snapshot.params.id;

    this.http.get(`patient/${this.patientId}`).then(data => {
      const age = Format.getAge(data.birth_date);

      const ageDisplay = age >= 60 ? `${age} (Senior)` : age;

      Object.assign(this.patientInfo, data, {
        name: Format.fullName(data),
        ageDisplay,
      });
    });

    this.http.get(`patient/${this.patientId}/record`).then(data => {
      this.data = data;

      this.showDeleted({ checked: false });
    });
  }

  dataFilter(checked) {
    return this.data.filter(item => !!item.cancelled_by_id === checked);
  }

  discountDisplay({ discount }) {
    if (discount) {
      return `${discount.name} (${discount.amount}%)`;
    }
    return '';
  }

  showDialog(data) {
    this.dialog.open(PatientRecordCreateComponent, {
      data: {
        data,
      },
    });
  }
}
