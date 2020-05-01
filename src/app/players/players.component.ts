import { Component, OnInit, Input, AfterViewInit, Output, EventEmitter } from '@angular/core';
import { Team } from '../interfaces/team';
import { ActiveSession } from '../interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { Response } from '../interfaces/response';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-players',
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent implements AfterViewInit {
  @Input() activeSession: ActiveSession;
  @Output() activePlayer: EventEmitter<boolean> = new EventEmitter();

  public teams: Team[];
  public activeTeam: string;

  constructor(private http: HttpClient) {
    this.activeTeam = '';
  }

  ngAfterViewInit(): void {
    setTimeout(() => {
      this.activeTeam = this.activeSession.teams.find((team: Team) => team.active).name;
    }, 10);
  }

  requestActivePlayer(): void {
    const body = new URLSearchParams();
    body.set('session', this.activeSession.name);
    body.set('team', 'TODO');
    body.set('player', 'TODO');

    this.http
      .post(`${environment.server}/request-active-player`, body.toString())
      .toPromise()
      .then((response: Response<void>) => {
        this.activePlayer.emit(true);
      });
  }
}
