import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Session } from './interfaces/session';
import { Theme } from './interfaces/theme';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  public page: 'welcome' | 'create game' | 'join game' | 'play';
  public horizontal: number;
  public vertical: number;

  public themes: Theme[];
  public selectedTheme: string;

  public session: Session;
  public boardSizes: string[];
  public selectedBoardSize: string;

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.page = 'create game';
    this.horizontal = 5;
    this.vertical = 5;

    this.boardSizes = ['3 x 3', '4 x 3', '4 x 4', '5 x 4', '5 x 5', '6 x 5', '6 x 6'];
    this.selectedBoardSize = this.boardSizes[4];

    this.themes = [
      {
        name: 'Gemischte Begriffe',
        file: 'mixed'
      },
      {
        name: 'Ü 18',
        file: 'over-18'
      },
      {
        name: 'Party',
        file: 'party'
      },
      {
        name: 'Jazzchor',
        file: 'jazzchor'
      },
      {
        name: 'Corona',
        file: 'corona'
      }
    ];
    this.selectedTheme = this.themes[0].name;

    this.session = {
      name: 'Friday Fun',
      horizontal: 5,
      vertical: 5,
      theme: 'mixed',
      teamOneName: 'Team A',
      teamOneColor: '#cd853f',
      teamTwoName: 'Team B',
      teamTwoColor: '#327ac0'
    };
  }

  createSession() {
    this.session = {
      name: this.session.name,
      horizontal: Number(this.selectedBoardSize.substr(0, 1)),
      vertical: Number(this.selectedBoardSize.substr(4, 1)),
      theme: this.themes.find((theme: Theme) => theme.name === this.selectedTheme).file,
      teamOneName: this.session.teamOneName,
      teamOneColor: this.session.teamOneColor,
      teamTwoName: this.session.teamTwoName,
      teamTwoColor: this.session.teamTwoColor
    };

    const options = {
      headers: new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
    };

    const body = new URLSearchParams();
    body.set('name', this.session.name);
    body.set('horizontal', this.selectedBoardSize.substr(0, 1));
    body.set('vertical', this.selectedBoardSize.substr(4, 1));
    body.set('theme', this.themes.find((theme: Theme) => theme.name === this.selectedTheme).file);
    body.set('teamOneName', this.session.teamOneName);
    body.set('teamOneColor', this.session.teamOneColor);
    body.set('teamTwoName', this.session.teamTwoName);
    body.set('teamTwoColor', this.session.teamTwoColor);

    this.http
      .post(`${environment.server}/create-session`, body.toString(), options)
      .toPromise()
      .then((response: any) => {
        console.info(`Session ${this.session.name} has been created:`, response);

        this.page = 'play';
      })
      .catch((error: any) => {
        console.error('An error occurred:', error);
      });
  }
}
