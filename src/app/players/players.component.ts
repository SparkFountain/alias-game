import { Component, OnInit } from '@angular/core';
import { Team } from '../interfaces/team';

@Component({
  selector: 'app-players',
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent implements OnInit {
  public teams: Team[];

  constructor() {}

  ngOnInit(): void {
    this.teams = [
      {
        name: 'Team A',
        color: 'peru',
        players: ['Andreas', 'Jenny', 'Leonie', 'Ferdinand', 'Franzi'],
        remainingCards: 8
      },
      {
        name: 'Team B',
        color: 'gray',
        players: ['Nancy', 'Stefan S.', 'Volker', 'Ulla', 'Maike'],
        remainingCards: 9
      }
    ];
  }
}
