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
  @Output() leave: EventEmitter<void> = new EventEmitter();

  public activeTeam: string;
  public iAmActivePlayer: boolean;
  public exchangeCards: boolean;

  constructor(private http: HttpClient) {
    this.activeTeam = '';
    this.iAmActivePlayer = false;
    this.exchangeCards = false;
  }

  ngAfterViewInit(): void {
    setTimeout(() => {
      this.activeTeam = this.activeSession.teams.find((team: Team) => team.active).name;
    }, 10);

    setInterval(() => {
      this.http
        .get(`${environment.server}/fetch-teams`, { params: { session: this.activeSession.name } })
        .toPromise()
        .then((response: Response<Team[]>) => {
          this.activeSession.teams = response.data;
        });
    }, 1000);
  }

  requestActivePlayer(): void {
    this.iAmActivePlayer = true;
    this.activePlayer.emit(true);

    // const body = new URLSearchParams();
    // body.set('session', this.activeSession.name);
    // body.set('team', 'TODO');
    // body.set('player', 'TODO');

    // this.http
    //   .post(`${environment.server}/request-active-player`, body.toString())
    //   .toPromise()
    //   .then((response: Response<void>) => {});
  }

  leaveSession(): void {
    this.iAmActivePlayer = false;
    this.activePlayer.emit(false);
    this.leave.emit();
  }
}
