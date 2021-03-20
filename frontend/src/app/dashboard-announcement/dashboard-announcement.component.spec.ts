import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DashboardAnnouncementComponent } from './dashboard-announcement.component';

describe('DashboardAnnouncementComponent', () => {
  let component: DashboardAnnouncementComponent;
  let fixture: ComponentFixture<DashboardAnnouncementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DashboardAnnouncementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DashboardAnnouncementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
