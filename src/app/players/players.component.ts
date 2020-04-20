import { Component, OnInit, Input } from '@angular/core';
import { Team } from '../interfaces/team';
import { ActiveSession } from '../interfaces/active-session';

@Component({
  selector: 'app-players',
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent implements OnInit {
  @Input() activeSession: ActiveSession;

  public teams: Team[];

  constructor() {}

  ngOnInit(): void {
    this.teams = [
      {
        name: 'A',
        color: 'peru',
        players: ['Andreas', 'Jenny', 'Leonie', 'Ferdinand', 'Franzi'],
        remainingCards: 8
      },
      {
        name: 'B',
        color: 'gray',
        players: ['Nancy', 'Stefan S.', 'Volker', 'Ulla', 'Maike'],
        remainingCards: 9
      }
    ];
  }
}
