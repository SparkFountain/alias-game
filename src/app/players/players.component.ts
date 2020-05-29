import { Component, Input, Output, EventEmitter } from '@angular/core';
import { ActiveSession } from '../interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Team } from '../interfaces/team';
import { UserService } from 'src/services/user.service';
import { User } from '../interfaces/user';
import { Player } from '../interfaces/player';

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
    this.activeTeam = this._activeSession.teams.find((team: Team) => team.active).name;
    this._activeSession.teams.forEach((team: Team) => {
      team.players.forEach((player: Player) => {
        if (player.name === this.user.player) {
          this.iAmActivePlayer = player.active;
        }
      });
    });
  }
  @Output() activePlayer: EventEmitter<boolean> = new EventEmitter();
  @Output() leave: EventEmitter<void> = new EventEmitter();

  public user: User;

  public activeTeam: string;
  public iAmActivePlayer: boolean;
  public exchangeCards: boolean;

  public remainingCards: any;

  constructor(private http: HttpClient, private userService: UserService) {
    this.activeTeam = '';
    this.iAmActivePlayer = false;
    this.exchangeCards = false;

    this.remainingCards = {};

    this.user = this.userService.getUser();
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
    // check if two active players exist
    let activePlayers = 0;
    this._activeSession.teams.forEach((team: Team) => {
      team.players.forEach((player: Player) => {
        if (player.active) {
          activePlayers++;
        }
      });
    });

    if (activePlayers === 2) {
      const body = new URLSearchParams();
      body.set('session', this._activeSession.name);

      this.http.post(`${environment.server}/start-session`, body.toString(), environment.formHeader).toPromise();
    } else {
      alert('Bevor das Spiel losgeht, muss pro Team ein aktiver Spieler gewählt sein, der die Begriffe erklärt.');
    }
  }

  leaveSession(): void {
    // const body = new URLSearchParams();
    // body.set("session", this._activeSession.name);
    // // body.set('player', this._activeSession.name);

    // this.http.post(
    //   `${environment.server}/leave-session`,
    //   body.toString(),
    //   environment.formHeader
    // );

    this.iAmActivePlayer = false;
    this.activePlayer.emit(false);
    this.leave.emit();
  }
}
