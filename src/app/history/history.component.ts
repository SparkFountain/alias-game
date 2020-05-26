import { Component, OnInit, Input } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { HistoryEvent } from '../interfaces/history-event';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

import { Response } from '../interfaces/response';

@Component({
  selector: 'app-history',
  templateUrl: './history.component.html',
  styleUrls: ['./history.component.scss']
})
export class HistoryComponent implements OnInit {
  @Input() activeSession: ActiveSession;

  public historyEvents: HistoryEvent[];

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.historyEvents = [];

    setInterval(() => {
      this.http
        .get(`${environment.server}/fetch-history`, { params: { session: this.activeSession.name } })
        .toPromise()
        .then((response: Response<HistoryEvent[]>) => {
          this.historyEvents = [];

          // console.info('History Events:', response.data);

          response.data.forEach((event: HistoryEvent) => {
            this.historyEvents.push(event);
          });
        });
    }, 1000);
  }
}
