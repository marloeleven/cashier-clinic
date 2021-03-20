import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { LogoutComponent } from './logout/logout.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { UserComponent } from './user/user.component';
import { PatientComponent } from './patient/patient.component';
import { ListComponent as PatientInfoComponent } from './patient/list/list.component';
import { RecordComponent as PatientRecordComponent } from './patient/record/record.component';
import { LookupComponent } from './lookup/lookup.component';
import { ProceduresComponent } from './procedures/procedures.component';
import { CreateComponent as PatientRecordFormComponent } from '@app/patient/record/create/create.component';
import { AnnouncementComponent } from '@app/announcement/announcement.component';
import { DashboardAnnouncementComponent } from '@app/dashboard-announcement/dashboard-announcement.component';
import { SettingsComponent } from '@app/settings/settings.component';
import { CategoriesComponent } from '@app/categories/categories.component';
import { PdfComponent } from '@app/report/pdf/pdf.component';
import { ReportComponent } from '@app/report/report.component';

// GUARDS
import { AuthGuard } from './guard/auth.guard';
import { UserGuard } from './guard/user.guard';
import { AdminGuard } from './guard/admin.guard';

const routes: Routes = [
  {
    path: 'dashboard',
    redirectTo: '',
    pathMatch: 'full',
    canActivate: [AuthGuard]
  },
  {
    path: '',
    pathMatch: 'full',
    component: DashboardComponent,
    canActivate: [AuthGuard, UserGuard],
    children: [
      {
        path: '',
        component: DashboardAnnouncementComponent
      }
    ]
  },
  {
    path: 'admin',
    component: DashboardComponent,
    canActivate: [AuthGuard, AdminGuard],
    children: [
      {
        path: '',
        component: DashboardAnnouncementComponent
      },
      {
        path: 'users',
        component: UserComponent
      },
      {
        path: 'lookup',
        component: LookupComponent
      },
      {
        path: 'procedures',
        component: ProceduresComponent
      },
      {
        path: 'categories',
        component: CategoriesComponent
      },
      {
        path: 'report',
        component: ReportComponent
      },
      {
        path: 'announcement',
        component: AnnouncementComponent
      }
    ]
  },
  {
    path: 'cashier',
    component: DashboardComponent,
    canActivate: [AuthGuard],
    children: [
      {
        path: 'report',
        component: ReportComponent
      }
    ]
  },
  {
    path: 'patients',
    component: PatientComponent,
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        component: PatientInfoComponent
      },
      {
        path: ':id',
        component: PatientRecordComponent
      },
      {
        path: ':id/create',
        component: PatientRecordFormComponent
      }
    ]
  },
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: 'report/pdf/:id',
    component: PdfComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'settings',
    component: DashboardComponent,
    canActivate: [AuthGuard],
    children: [
      {
        path: '',
        component: SettingsComponent
      }
    ]
  },
  {
    path: 'logout',
    component: LogoutComponent
  }
  // { path: '**', component: PageNotFoundComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { enableTracing: true })],
  exports: [RouterModule],
  providers: [AuthGuard, UserGuard, AdminGuard]
})
export class AppRoutingModule {}
