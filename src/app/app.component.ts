import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Session } from './interfaces/session';
import { Theme } from './interfaces/theme';
import { JoinSession } from './interfaces/join-session';
import { Response } from './interfaces/response';
import { ActiveSession } from './interfaces/active-session';
import { Player } from './interfaces/player';
import { Team } from './interfaces/team';
import { SessionService } from 'src/services/session.service';
import { UserService } from 'src/services/user.service';
import { User } from './interfaces/user';
import { Card } from './interfaces/card';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  public page: 'welcome' | 'create session' | 'join session' | 'play';
  public horizontal: number;
  public vertical: number;

  public themes: Theme[];
  public selectedTheme: string;

  public session: Session;
  public boardSizes: string[];
  public selectedBoardSize: string;

  public selectedSession: any;
  public sessions2Join: JoinSession[];
  public selectedSession2Join: JoinSession;

  public participant: string;
  public selectedTeam2Join: string;
  public teamMembers: string;

  public activeSession: ActiveSession;
  public iAmActivePlayer: boolean;

  public user: User;

  public winnerTeam: string;

  public noActivePlayer: boolean;

  constructor(private http: HttpClient, private sessionService: SessionService, private userService: UserService) {}

  ngOnInit(): void {
    this.page = 'welcome';
    this.horizontal = 5;
    this.vertical = 5;

    this.boardSizes = ['3 x 3', '4 x 3', '4 x 4', '5 x 4', '5 x 5', '6 x 5', '6 x 6'];
    this.selectedBoardSize = this.boardSizes[4];

    // TODO: allow multiple themes?
    this.themes = [
      {
        name: 'Gemischte Begriffe',
        file: 'mixed'
      },
      {
        name: 'Ü 18',
        file: 'over-18'
      }
      // {
      //   name: 'Party',
      //   file: 'party'
      // },
      // {
      //   name: 'Jazzchor',
      //   file: 'jazzchor'
      // }
      // {
      //   name: 'Corona',
      //   file: 'corona'
      // }
    ];
    this.selectedTheme = this.themes[0].name;

    this.session = {
      creator: 'Mr(s). Anonymous',
      name: 'Friday Fun',
      horizontal: 5,
      vertical: 5,
      theme: 'mixed',
      teamOneName: 'A',
      teamOneColor: '#c22b0c',
      teamTwoName: 'B',
      teamTwoColor: '#0b6bca'
    };

    this.sessions2Join = [];
    this.selectedSession2Join = null;

    this.participant = 'Mr(s). Anonymous';
    this.selectedTeam2Join = '';

    this.iAmActivePlayer = false;

    this.winnerTeam = '';

    this.noActivePlayer = true;

    setInterval(() => this.fetchActiveSession(), 1000);
  }

  createSession(): void {
    this.session = {
      creator: this.session.creator,
      name: this.session.name,
      horizontal: Number(this.selectedBoardSize.substr(0, 1)),
      vertical: Number(this.selectedBoardSize.substr(4, 1)),
      theme: this.themes.find((theme: Theme) => theme.name === this.selectedTheme).file,
      teamOneName: this.session.teamOneName,
      teamOneColor: this.session.teamOneColor,
      teamTwoName: this.session.teamTwoName,
      teamTwoColor: this.session.teamTwoColor
    };

    this.participant = this.session.creator;

    const body = new URLSearchParams();
    body.set('creator', this.session.creator);
    body.set('name', this.session.name);
    body.set('horizontal', this.selectedBoardSize.substr(0, 1));
    body.set('vertical', this.selectedBoardSize.substr(4, 1));
    body.set('theme', this.themes.find((theme: Theme) => theme.name === this.selectedTheme).file);
    body.set('teamOneName', this.session.teamOneName);
    body.set('teamOneColor', this.session.teamOneColor);
    body.set('teamTwoName', this.session.teamTwoName);
    body.set('teamTwoColor', this.session.teamTwoColor);

    this.http
      .post(`${environment.server}/create-session`, body.toString(), environment.formHeader)
      .toPromise()
      .then((response: Response<ActiveSession>) => {
        this.activeSession = response.data;
        console.info('Active Session:', this.activeSession);
        this.page = 'play';
      })
      .catch((error: any) => {
        console.error('An error occurred:', error);
      });
  }

  getSessions(): void {
    this.http
      .get(`${environment.server}/get-sessions`)
      .toPromise()
      .then((response: Response<JoinSession[]>) => {
        this.sessions2Join = response.data;
        this.page = 'join session';
      });
  }

  selectSession2Join(joinSession: JoinSession): void {
    this.selectedSession2Join = joinSession;
  }

  updateTeamMembers(): void {
    const selectedTeam = this.selectedSession2Join.teams.find((team: Team) => team.name === this.selectedTeam2Join);

    if (selectedTeam.players.length === 0) {
      this.teamMembers = ' ist noch niemand';
    } else if (selectedTeam.players.length === 1) {
      this.teamMembers = ` ist ${selectedTeam.players[0].name}`;
      if (selectedTeam.players[0].active) {
        this.teamMembers += ' (aktiv)';
      }
    } else {
      this.teamMembers = ' sind ';
      selectedTeam.players.forEach((player: Player, index: number, array: Player[]) => {
        this.teamMembers += player.name;
        if (player.active) {
          this.teamMembers += ' (aktiv)';
        }
        if (index < array.length - 2) {
          this.teamMembers += ', ';
        } else if (index === array.length - 2) {
          this.teamMembers += ' und ';
        }
      });
    }
  }

  joinSession(): void {
    if (this.selectedSession2Join === null) return;

    const body = new URLSearchParams();
    body.set('participant', this.participant);
    body.set('session', this.selectedSession2Join.name);
    body.set('team', this.selectedTeam2Join);

    this.http
      .post(`${environment.server}/join-session`, body.toString(), environment.formHeader)
      .toPromise()
      .then((response: Response<ActiveSession>) => {
        this.activeSession = response.data;
        console.info('Active Session:', this.activeSession);

        this.userService.setUser({
          session: this.activeSession.name,
          team: this.selectedTeam2Join,
          player: this.participant
        });
        this.user = this.userService.getUser();

        this.page = 'play';
      })
      .catch((error: any) => {
        console.error('Could not join session:', error);
      });
  }

  fetchActiveSession(): void {
    if (this.page === 'play') {
      this.sessionService.fetchActiveSession(this.activeSession.name).then((response: Response<ActiveSession>) => {
        this.activeSession = response.data;
        this.activeSession.teams.forEach((team: Team) => {
          team.players.forEach((player: Player) => {
            if (player.active) {
              if(player.name === this.user.player) {
                this.iAmActivePlayer = true;
              } else if(team.name === this.user.team) {
                this.noActivePlayer = false;
              }
            }
          });

          if (team.remainingCards === 0) {
            this.winnerTeam = team.name;
          }
        });

        // black card: team loses immediately
        this.activeSession.cards.forEach((card: Card) => {
          if (card.color === '#222222' && card.uncovered === true) {
            this.winnerTeam = this.activeSession.teams.find((team: Team) => team.active === false).name;
          }
        });
      });
    }
  }

  requestActivePlayer(): void {
    const body = new URLSearchParams();
    body.set('session', this.user.session);
    body.set('team', this.user.team);
    body.set('player', this.user.player);

    this.http
      .post(`${environment.server}/request-active-player`, body.toString(), environment.formHeader)
      .toPromise()
      .then((response: Response<void>) => {
        // TODO: implement case that player could be denied
        this.iAmActivePlayer = true;
      });
  }

  resetSession(): void {
    const body = new URLSearchParams();
    body.set('session', this.user.session);

    this.http
      .post(`${environment.server}/reset-session`, body.toString(), environment.formHeader)
      .toPromise()
      .then(() => {
        this.iAmActivePlayer = false;
        this.winnerTeam = '';
      });
  }
}
