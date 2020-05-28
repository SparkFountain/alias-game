import { Component, Input, Output, EventEmitter } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-players',
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent {
  _activeSession: ActiveSession;
  @Input('activeSession')
  set activeSession(session: ActiveSession) {
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

  // TODO: implement or remove
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

  startSession(): void {
    const body = new URLSearchParams();
    body.set('session', this._activeSession.name);

    this.http.post(`${environment.server}/start-session`, body.toString(), environment.formHeader).toPromise();
  }

  leaveSession(): void {
    this.iAmActivePlayer = false;
    this.activePlayer.emit(false);
    this.leave.emit();
  }
}
