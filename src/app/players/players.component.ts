import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { Team } from '../interfaces/team';
import { ActiveSession } from '../interfaces/active-session';

@Component({
  selector: 'app-players',
  templateUrl: './players.component.html',
  styleUrls: ['./players.component.scss']
})
export class PlayersComponent implements AfterViewInit {
  @Input() activeSession: ActiveSession;

  public teams: Team[];
  public activeTeam: string;

  constructor() {
    this.activeTeam = '';
  }


  ngAfterViewInit(): void {
    this.activeTeam = this.activeSession.teams.find((team: Team) => team.active).name;
  }
}
