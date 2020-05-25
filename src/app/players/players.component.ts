import { Component, OnInit, Input, AfterViewInit, Output, EventEmitter } from '@angular/core';
import { Team } from '../interfaces/team';
import { ActiveSession } from '../interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { Response } from '../interfaces/response';
import { environment } from 'src/environments/environment';
import { Card } from '../interfaces/card';

@Component({
  selector: 'app-players',
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent implements AfterViewInit {
  _activeSession: ActiveSession;
  @Input('activeSession')
  set activeSession(session: ActiveSession) {
    console.info('Set Active Session', session);
    this._activeSession = session;
  }
  @Output() activePlayer: EventEmitter<boolean> = new EventEmitter();
  @Output() leave: EventEmitter<void> = new EventEmitter();

  public activeTeam: string;
  public iAmActivePlayer: boolean;
  public exchangeCards: boolean;

  public remainingCards: any;

  constructor(private http: HttpClient) {
    this.activeTeam = '';
    this.iAmActivePlayer = false;
    this.exchangeCards = false;

    this.remainingCards = {};
  }

  ngAfterViewInit(): void {
    setInterval(() => {
      this.http
        .get(`${environment.server}/fetch-teams`, { params: { session: this._activeSession.name } })
        .toPromise()
        .then((response: Response<Team[]>) => {
          this._activeSession.teams = response.data;

          this._activeSession.teams.forEach((team: Team) => {
            // find active team
            if(team.active) {
              this.activeTeam = team.name;
            }

            // calculate remaining cards
            let total = 0;
            let uncovered = 0;

            this._activeSession.cards.forEach((card: Card) => {
              if (card.color === team.color) {
                total++;

                if (card.uncovered) {
                  uncovered++;
                }
              }
            });

            this.remainingCards[team.name] = total - uncovered;
            console.info('New remaining cards calculated', this.remainingCards);
          });
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
