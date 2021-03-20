import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { HttpModule } from '@angular/http';

import { CookieService } from 'ngx-cookie-service';
import { HttpService } from '@app/service/http.service';
import { DynamicElement } from '@app/helper/dynamic.component.helper';

import {
  MatButtonModule,
  MatCheckboxModule,
  MatInputModule,
  MatAutocompleteModule,
  MatCardModule,
  MatIconModule,
  MatTableModule,
  MatSortModule,
  MatPaginatorModule,
  MatTooltipModule,
  MatDialogModule,
  MatSnackBarModule,
  MatTabsModule,
  MatTreeModule,
  MatSelectModule,
  MatOptionModule,
  MatDatepickerModule,
  MatNativeDateModule,
  MatFormFieldModule,
  MatStepperModule,
  MatExpansionModule
} from '@angular/material';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';

import { DashboardComponent } from '@app/dashboard/dashboard.component';
import { LoginComponent } from '@app/login/login.component';
import { LogoutComponent } from '@app/logout/logout.component';
import { SidebarComponent } from '@app/component/sidebar/sidebar.component';
import { LookupComponent } from '@app/lookup/lookup.component';
import { DeleteComponent } from '@app/component/dialog/delete/delete.component';
import { EditComponent } from '@app/component/dialog/edit/edit.component';
import { InputComponent } from '@app/component/elements/input/input.component';
import { FormComponent as LookupFormComponent } from '@app/lookup/dialog/form/form.component';
import { ProceduresComponent } from '@app/procedures/procedures.component';
import { FormComponent as ProcedureFormComponent } from '@app/procedures/dialog/form/form.component';
import { UserComponent } from '@app/user/user.component';
import { FormComponent as UserFormComponent } from '@app/user/dialog/form/form.component';
import { PatientComponent } from '@app/patient/patient.component';
import { ListComponent as PatientListComponent } from '@app/patient/list/list.component';
import { FormComponent as PatientInfoFormComponent } from '@app/patient/list/dialog/form/form.component';
import { RecordComponent as PatientRecordComponent } from '@app/patient/record/record.component';
import { FormComponent as PatientRecordFormComponent } from '@app/patient/record/form/form.component';
import { CreateComponent as PatientRecordCreateComponent } from '@app/patient/record/create/create.component';
import { AnnouncementComponent } from '@app/announcement/announcement.component';
import { FormComponent as AnnouncementFormComponent } from '@app/announcement/form/form.component';
import { DashboardAnnouncementComponent } from '@app/dashboard-announcement/dashboard-announcement.component';
import { CategoriesComponent } from '@app/categories/categories.component';
import { FormComponent as CategoriesFormComponent } from '@app/categories/form/form.component';
import { SettingsComponent } from '@app/settings/settings.component';
import { PdfComponent } from '@app/report/pdf/pdf.component';
import { ReportComponent } from '@app/report/report.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    HttpModule,
    MatInputModule,
    MatAutocompleteModule,
    MatButtonModule,
    MatCheckboxModule,
    MatCardModule,
    MatIconModule,
    MatTableModule,
    MatSortModule,
    MatPaginatorModule,
    MatTooltipModule,
    MatDialogModule,
    MatSnackBarModule,
    MatSelectModule,
    MatOptionModule,
    MatTabsModule,
    MatTreeModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatFormFieldModule,
    MatStepperModule,
    MatExpansionModule,
    NoopAnimationsModule
  ],
  declarations: [
    DashboardComponent,
    LoginComponent,
    LogoutComponent,
    SidebarComponent,
    LookupComponent,
    DeleteComponent,
    EditComponent,
    InputComponent,
    LookupFormComponent,
    ProceduresComponent,
    ProcedureFormComponent,
    UserComponent,
    UserFormComponent,
    PatientComponent,
    PatientListComponent,
    PatientInfoFormComponent,
    PatientRecordComponent,
    PatientRecordFormComponent,
    PatientRecordCreateComponent,
    AnnouncementComponent,
    AnnouncementFormComponent,
    DashboardAnnouncementComponent,
    CategoriesComponent,
    CategoriesFormComponent,
    SettingsComponent,
    PdfComponent,
    ReportComponent
  ],
  entryComponents: [
    DeleteComponent,
    EditComponent,
    LookupFormComponent,
    ProcedureFormComponent,
    UserFormComponent,
    PatientInfoFormComponent,
    PatientRecordFormComponent,
    AnnouncementFormComponent,
    CategoriesFormComponent
  ],
  providers: [CookieService, HttpService, DynamicElement]
})
export class CoreModule {}
