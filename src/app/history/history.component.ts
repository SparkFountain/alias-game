import { Component, OnInit, Input } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { HistoryEvent } from '../interfaces/history-event';

@Component({
  selector: 'app-history',
  templateUrl: './history.component.html',
  styleUrls: ['./history.component.scss']
})
export class HistoryComponent implements OnInit {
  @Input() activeSession: ActiveSession;

  public historyEvents: HistoryEvent[];

  constructor() {}

  ngOnInit(): void {
    this.historyEvents = [
      {
        teamA: 2,
        teamB: 0,
        neutral: 0,
        black: 0
      }
    ];
  }
}
