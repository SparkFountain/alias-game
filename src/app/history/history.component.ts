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
        term: 'Urlaub 4',
        teamColor: '#ff0000',
        hits: {
          teamA: 2,
          teamB: 0,
          neutral: 1,
          black: 0
        }
      },
      {
        term: 'Winter 2',
        teamColor: '#0000ff',
        hits: {
          teamA: 0,
          teamB: 2,
          neutral: 0,
          black: 0
        }
      }
    ];
  }
}
