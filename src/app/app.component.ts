import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  protected page: 'welcome' | 'new game' | 'join game' | 'play';
  protected horizontal: number;
  protected vertical: number;
  protected theme: string;

  constructor() {
    this.page = 'welcome';
    this.horizontal = 5;
    this.vertical = 5;
    this.theme = 'mixed';
  }

  updateBoardSize(horizontal: number, vertical: number) {
    this.horizontal = horizontal;
    this.vertical = vertical;
  }

  updateTheme(theme: string) {
    this.theme = theme;
  }

  createGame() {
    this.page = 'play';
  }
}
