import { Component, OnInit } from '@angular/core';
import { HttpService } from '@app/service/http.service';
import { Announcement } from '@app/interface/form/announcement';
import { Format } from '@app/helper/format';

@Component({
  selector: 'app-dashboard-announcement',
  templateUrl: './dashboard-announcement.component.html',
  styleUrls: ['./dashboard-announcement.component.css']
})
export class DashboardAnnouncementComponent implements OnInit {
  public Format = Format;
  public annoucementsArray: Announcement[] = [];

  constructor(private http: HttpService) {}

  ngOnInit() {
    this.http.get('announcement').then(data => {
      this.annoucementsArray = data.slice(0, 10);
    });
  }

  toggleExpand({ target }) {
    target.closest('.announcement').classList.toggle('expand');
  }
}
