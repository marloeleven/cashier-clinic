import { Component } from "@angular/core";
import { MatDialog } from "@angular/material";
import { HttpService } from "@app/service/http.service";
import { UserService } from "@app/service/user.service";
import { SnackbarHelper } from "@app/helper/snackbar.helper";
import { DeleteComponent } from "@app/component/dialog/delete/delete.component";
import { FormComponent } from "./dialog/form/form.component";
import { GENDER_TYPES } from "@app/variables";
import BaseClassV2 from "@app/component/BaseClassV2";

const mapSearchable = obj => {
  obj.FirstMiddleLastname = `${obj.first_name} ${obj.middle_name} ${obj.last_name}`;
  obj.firstLastMiddleName = `${obj.first_name} ${obj.last_name} ${obj.middle_name}`;
  obj.LastFirstMiddlenameReverse = `${obj.last_name} ${obj.first_name} ${obj.middle_name}`;
  return obj;
};

@Component({
  selector: "app-list",
  templateUrl: "./list.component.html",
  styleUrls: ["./list.component.css"]
})
export class ListComponent extends BaseClassV2 {
  displayedColumns: string[] = [
    "id",
    "first_name",
    "middle_name",
    "last_name",
    "birth_date",
    "gender",
    "action"
  ];

  protected FormComponent = FormComponent;
  protected DeleteComponent = DeleteComponent;
  protected FormTitle = "Patient";
  protected BaseUrl = "patient";

  public GENDER_TYPES = GENDER_TYPES;
  public idTypeArray = [];

  constructor(
    public userService: UserService,
    protected http: HttpService,
    protected snackbarHelper: SnackbarHelper,
    protected dialog: MatDialog
  ) {
    super(http, snackbarHelper, dialog);
  }
}
